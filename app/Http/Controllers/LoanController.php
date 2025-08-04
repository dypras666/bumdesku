<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\MasterAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Loan::with(['account', 'creator', 'approver']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by loan type
        if ($request->filled('loan_type')) {
            $query->where('loan_type', $request->loan_type);
        }

        // Filter by borrower name
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('borrower_name', 'like', '%' . $request->search . '%')
                  ->orWhere('loan_code', 'like', '%' . $request->search . '%')
                  ->orWhere('borrower_phone', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('loan_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('loan_date', '<=', $request->end_date);
        }

        $loans = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $statistics = [
            'total_loans' => Loan::count(),
            'active_loans' => Loan::active()->count(),
            'overdue_loans' => Loan::overdue()->count(),
            'completed_loans' => Loan::completed()->count(),
            'total_amount' => Loan::sum('loan_amount'),
            'total_outstanding' => Loan::active()->sum('remaining_balance'),
            // Statistics by loan type
            'bunga_loans' => Loan::where('loan_type', 'bunga')->count(),
            'bagi_hasil_loans' => Loan::where('loan_type', 'bagi_hasil')->count(),
            'tanpa_bunga_loans' => Loan::where('loan_type', 'tanpa_bunga')->count(),
            'bunga_amount' => Loan::where('loan_type', 'bunga')->sum('loan_amount'),
            'bagi_hasil_amount' => Loan::where('loan_type', 'bagi_hasil')->sum('loan_amount'),
            'tanpa_bunga_amount' => Loan::where('loan_type', 'tanpa_bunga')->sum('loan_amount')
        ];

        return view('loans.index', compact('loans', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get receivable accounts for loan
        $accounts = MasterAccount::where('kategori_akun', 'Aset')
                                ->where('nama_akun', 'like', '%piutang%')
                                ->orWhere('nama_akun', 'like', '%pinjaman%')
                                ->get();

        return view('loans.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Base validation rules
        $rules = [
            'borrower_name' => 'required|string|max:255',
            'borrower_phone' => 'required|string|max:20',
            'borrower_address' => 'nullable|string',
            'borrower_id_number' => 'nullable|string|max:20',
            'loan_type' => 'required|in:bunga,bagi_hasil,tanpa_bunga',
            'loan_amount' => 'required|numeric|min:1',
            'loan_term_months' => 'required|integer|min:1|max:120',
            'loan_date' => 'required|date',
            'account_id' => 'required|exists:master_accounts,id',
            'admin_fee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ];

        // Add conditional validation based on loan type
        if ($request->loan_type === 'bunga') {
            $rules['interest_rate'] = 'required|numeric|min:0|max:100';
        } elseif ($request->loan_type === 'bagi_hasil') {
            $rules['profit_sharing_percentage'] = 'required|numeric|min:0|max:100';
            $rules['expected_profit'] = 'required|numeric|min:0';
            $rules['business_description'] = 'required|string|max:1000';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            $loanCode = Loan::generateLoanCode();
            
            // Calculate monthly payment based on loan type
            $loanAmount = $request->loan_amount;
            $termMonths = $request->loan_term_months;
            $adminFee = $request->admin_fee ?? 0;
            
            $monthlyPayment = 0;
            $totalPayableAmount = $loanAmount;

            if ($request->loan_type === 'bunga') {
                // Interest-based loan
                $interestRate = $request->interest_rate;
                if ($interestRate > 0) {
                    $monthlyRate = ($interestRate / 100) / 12;
                    $monthlyPayment = $loanAmount * ($monthlyRate * pow(1 + $monthlyRate, $termMonths)) / 
                                    (pow(1 + $monthlyRate, $termMonths) - 1);
                    $totalPayableAmount = $monthlyPayment * $termMonths;
                } else {
                    $monthlyPayment = $loanAmount / $termMonths;
                }
            } elseif ($request->loan_type === 'bagi_hasil') {
                // Profit-sharing loan
                $expectedProfit = $request->expected_profit;
                $profitSharingPercentage = $request->profit_sharing_percentage;
                $bumdesShare = $expectedProfit * ($profitSharingPercentage / 100);
                $totalPayableAmount = $loanAmount + $bumdesShare;
                $monthlyPayment = $totalPayableAmount / $termMonths;
            } else {
                // Interest-free loan
                $monthlyPayment = $loanAmount / $termMonths;
            }

            // Add admin fee to total payable amount
            $totalPayableAmount += $adminFee;

            // Calculate due date
            $loanDate = Carbon::parse($request->loan_date);
            $dueDate = $loanDate->copy()->addMonths((int) $termMonths);

            $loan = Loan::create([
                'loan_code' => $loanCode,
                'borrower_name' => $request->borrower_name,
                'borrower_phone' => $request->borrower_phone,
                'borrower_address' => $request->borrower_address,
                'borrower_id_number' => $request->borrower_id_number,
                'loan_type' => $request->loan_type,
                'loan_amount' => $loanAmount,
                'interest_rate' => $request->interest_rate ?? 0,
                'profit_sharing_percentage' => $request->profit_sharing_percentage ?? null,
                'expected_profit' => $request->expected_profit ?? null,
                'admin_fee' => $adminFee,
                'business_description' => $request->business_description ?? null,
                'loan_term_months' => $termMonths,
                'monthly_payment' => $monthlyPayment,
                'loan_date' => $request->loan_date,
                'due_date' => $dueDate,
                'remaining_balance' => $totalPayableAmount,
                'notes' => $request->notes,
                'account_id' => $request->account_id,
                'created_by' => Auth::id()
            ]);

            DB::commit();

            return redirect()->route('loans.show', $loan)
                           ->with('success', 'Pinjaman modal berhasil dibuat dengan kode: ' . $loanCode);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                        ->with('error', 'Gagal membuat pinjaman: ' . $e->getMessage());
        }
    }

    /**
     * Print loan agreement
     */
    public function printAgreement(Loan $loan)
    {
        $loan->load(['account', 'creator', 'approver']);
        
        // Generate payment schedule for agreement
        $paymentSchedule = $this->generatePaymentSchedule($loan);
        
        return view('loans.print-agreement', compact('loan', 'paymentSchedule'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        $loan->load(['account', 'creator', 'approver', 'payments.creator', 'payments.approver']);
        
        // Payment schedule calculation
        $paymentSchedule = $this->generatePaymentSchedule($loan);
        
        return view('loans.show', compact('loan', 'paymentSchedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loan $loan)
    {
        // Only allow editing if not approved yet
        if ($loan->approved_at) {
            return back()->with('error', 'Pinjaman yang sudah disetujui tidak dapat diedit.');
        }

        $accounts = MasterAccount::where('kategori_akun', 'Aset')
                                ->where('nama_akun', 'like', '%piutang%')
                                ->orWhere('nama_akun', 'like', '%pinjaman%')
                                ->get();

        return view('loans.edit', compact('loan', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan)
    {
        // Only allow editing if not approved yet
        if ($loan->approved_at) {
            return back()->with('error', 'Pinjaman yang sudah disetujui tidak dapat diedit.');
        }

        // Base validation rules
        $rules = [
            'borrower_name' => 'required|string|max:255',
            'borrower_phone' => 'required|string|max:20',
            'borrower_address' => 'nullable|string',
            'borrower_id_number' => 'nullable|string|max:20',
            'loan_type' => 'required|in:bunga,bagi_hasil,tanpa_bunga',
            'loan_amount' => 'required|numeric|min:1',
            'loan_term_months' => 'required|integer|min:1|max:120',
            'loan_date' => 'required|date',
            'account_id' => 'required|exists:master_accounts,id',
            'admin_fee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ];

        // Add conditional validation based on loan type
        if ($request->loan_type === 'bunga') {
            $rules['interest_rate'] = 'required|numeric|min:0|max:100';
        } elseif ($request->loan_type === 'bagi_hasil') {
            $rules['profit_sharing_percentage'] = 'required|numeric|min:0|max:100';
            $rules['expected_profit'] = 'required|numeric|min:0';
            $rules['business_description'] = 'required|string|max:1000';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            // Recalculate monthly payment based on loan type
            $loanAmount = $request->loan_amount;
            $termMonths = $request->loan_term_months;
            $adminFee = $request->admin_fee ?? 0;
            
            $monthlyPayment = 0;
            $totalPayableAmount = $loanAmount;

            if ($request->loan_type === 'bunga') {
                // Interest-based loan
                $interestRate = $request->interest_rate;
                if ($interestRate > 0) {
                    $monthlyRate = ($interestRate / 100) / 12;
                    $monthlyPayment = $loanAmount * ($monthlyRate * pow(1 + $monthlyRate, $termMonths)) / 
                                    (pow(1 + $monthlyRate, $termMonths) - 1);
                    $totalPayableAmount = $monthlyPayment * $termMonths;
                } else {
                    $monthlyPayment = $loanAmount / $termMonths;
                }
            } elseif ($request->loan_type === 'bagi_hasil') {
                // Profit-sharing loan
                $expectedProfit = $request->expected_profit;
                $profitSharingPercentage = $request->profit_sharing_percentage;
                $bumdesShare = $expectedProfit * ($profitSharingPercentage / 100);
                $totalPayableAmount = $loanAmount + $bumdesShare;
                $monthlyPayment = $totalPayableAmount / $termMonths;
            } else {
                // Interest-free loan
                $monthlyPayment = $loanAmount / $termMonths;
            }

            // Add admin fee to total payable amount
            $totalPayableAmount += $adminFee;

            // Recalculate due date
            $loanDate = Carbon::parse($request->loan_date);
            $dueDate = $loanDate->copy()->addMonths((int) $termMonths);

            $loan->update([
                'borrower_name' => $request->borrower_name,
                'borrower_phone' => $request->borrower_phone,
                'borrower_address' => $request->borrower_address,
                'borrower_id_number' => $request->borrower_id_number,
                'loan_type' => $request->loan_type,
                'loan_amount' => $loanAmount,
                'interest_rate' => $request->interest_rate ?? 0,
                'profit_sharing_percentage' => $request->profit_sharing_percentage ?? null,
                'expected_profit' => $request->expected_profit ?? null,
                'admin_fee' => $adminFee,
                'business_description' => $request->business_description ?? null,
                'loan_term_months' => $termMonths,
                'monthly_payment' => $monthlyPayment,
                'loan_date' => $request->loan_date,
                'due_date' => $dueDate,
                'remaining_balance' => $totalPayableAmount,
                'notes' => $request->notes,
                'account_id' => $request->account_id
            ]);

            DB::commit();

            return redirect()->route('loans.show', $loan)
                           ->with('success', 'Data pinjaman berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                        ->with('error', 'Gagal memperbarui pinjaman: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        // Only allow deletion if not approved and no payments
        if ($loan->approved_at) {
            return back()->with('error', 'Pinjaman yang sudah disetujui tidak dapat dihapus.');
        }

        if ($loan->payments()->count() > 0) {
            return back()->with('error', 'Pinjaman yang sudah memiliki pembayaran tidak dapat dihapus.');
        }

        $loan->delete();

        return redirect()->route('loans.index')
                       ->with('success', 'Pinjaman berhasil dihapus.');
    }

    /**
     * Approve loan
     */
    public function approve(Loan $loan)
    {
        if ($loan->approved_at) {
            return back()->with('error', 'Pinjaman sudah disetujui sebelumnya.');
        }

        DB::beginTransaction();
        try {
            $loan->approve(Auth::id());
            DB::commit();

            return back()->with('success', 'Pinjaman berhasil disetujui dan transaksi jurnal telah dibuat.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menyetujui pinjaman: ' . $e->getMessage());
        }
    }

    /**
     * Generate payment schedule
     */
    private function generatePaymentSchedule(Loan $loan)
    {
        $schedule = [];
        $startDate = Carbon::parse($loan->loan_date);
        $remainingBalance = $loan->loan_amount;
        
        for ($i = 1; $i <= $loan->loan_term_months; $i++) {
            $paymentDate = $startDate->copy()->addMonths((int) $i);
            
            // Calculate interest for this period
            $interestAmount = 0;
            if ($loan->interest_rate > 0) {
                $interestAmount = $remainingBalance * ($loan->interest_rate / 100);
            }
            
            // Principal payment
            $principalAmount = $loan->monthly_payment - $interestAmount;
            
            // Adjust last payment to clear remaining balance
            if ($i == $loan->loan_term_months) {
                $principalAmount = $remainingBalance;
                $totalPayment = $principalAmount + $interestAmount;
            } else {
                $totalPayment = $loan->monthly_payment;
            }
            
            $schedule[] = [
                'installment' => $i,
                'payment_date' => $paymentDate,
                'principal_amount' => $principalAmount,
                'interest_amount' => $interestAmount,
                'total_payment' => $totalPayment,
                'remaining_balance' => $remainingBalance - $principalAmount,
                'status' => $this->getInstallmentStatus($loan, $i)
            ];
            
            $remainingBalance -= $principalAmount;
        }
        
        return $schedule;
    }

    /**
     * Get installment payment status
     */
    private function getInstallmentStatus(Loan $loan, $installmentNumber)
    {
        $payment = $loan->payments()
                       ->where('installment_number', $installmentNumber)
                       ->where('status', 'approved')
                       ->first();
        
        if ($payment) {
            return 'paid';
        }
        
        $pendingPayment = $loan->payments()
                             ->where('installment_number', $installmentNumber)
                             ->where('status', 'pending')
                             ->first();
        
        if ($pendingPayment) {
            return 'pending';
        }
        
        return 'unpaid';
    }
}
