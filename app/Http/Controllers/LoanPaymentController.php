<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LoanPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LoanPayment::with(['loan', 'creator', 'approver', 'transaction']);

        // Filter by loan
        if ($request->filled('loan_id')) {
            $query->where('loan_id', $request->loan_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment date range
        if ($request->filled('start_date')) {
            $query->where('payment_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('payment_date', '<=', $request->end_date);
        }

        // Search by payment code or loan code
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('payment_code', 'like', '%' . $request->search . '%')
                  ->orWhereHas('loan', function($lq) use ($request) {
                      $lq->where('loan_code', 'like', '%' . $request->search . '%')
                        ->orWhere('borrower_name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total_payments' => LoanPayment::count(),
            'pending_payments' => LoanPayment::pending()->count(),
            'approved_payments' => LoanPayment::approved()->count(),
            'rejected_payments' => LoanPayment::rejected()->count(),
            'total_amount' => LoanPayment::approved()->sum('payment_amount'),
            'today_payments' => LoanPayment::whereDate('payment_date', today())->count()
        ];

        // Get loans for filter dropdown
        $loans = Loan::select('id', 'loan_code', 'borrower_name')->get();

        return view('loan-payments.index', compact('payments', 'loans'))->with('statistics', $stats);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $loanId = $request->get('loan_id');
        $installmentNumber = $request->get('installment');
        
        $loan = null;
        $suggestedPayment = null;
        
        if ($loanId) {
            $loan = Loan::findOrFail($loanId);
            
            // Calculate suggested payment amount for the installment
            if ($installmentNumber) {
                $suggestedPayment = $this->calculateInstallmentPayment($loan, $installmentNumber);
            }
        }

        // Get active loans for dropdown
        $loans = Loan::active()->select('id', 'loan_code', 'borrower_name', 'monthly_payment', 'remaining_balance')->get();

        return view('loan-payments.create', compact('loans', 'loan', 'installmentNumber', 'suggestedPayment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'payment_date' => 'required|date',
            'payment_amount' => 'required|numeric|min:1',
            'installment_number' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,transfer,check,other',
            'notes' => 'nullable|string'
        ]);

        // Validate loan is active
        $loan = Loan::findOrFail($request->loan_id);
        if ($loan->status !== 'active') {
            return back()->withInput()
                        ->with('error', 'Pinjaman tidak aktif atau sudah lunas.');
        }

        // Check if installment already paid
        $existingPayment = LoanPayment::where('loan_id', $request->loan_id)
                                    ->where('installment_number', $request->installment_number)
                                    ->where('status', 'approved')
                                    ->first();

        if ($existingPayment) {
            return back()->withInput()
                        ->with('error', 'Cicilan ke-' . $request->installment_number . ' sudah dibayar.');
        }

        DB::beginTransaction();
        try {
            $paymentCode = LoanPayment::generatePaymentCode();
            
            // Calculate payment breakdown
            $breakdown = $this->calculatePaymentBreakdown($loan, $request->payment_amount, $request->installment_number);

            $payment = LoanPayment::create([
                'payment_code' => $paymentCode,
                'loan_id' => $request->loan_id,
                'payment_date' => $request->payment_date,
                'payment_amount' => $request->payment_amount,
                'principal_amount' => $breakdown['principal'],
                'interest_amount' => $breakdown['interest'],
                'penalty_amount' => $breakdown['penalty'],
                'installment_number' => $request->installment_number,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'created_by' => Auth::id()
            ]);

            DB::commit();

            return redirect()->route('loan-payments.show', $payment)
                           ->with('success', 'Pembayaran cicilan berhasil dicatat dengan kode: ' . $paymentCode);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                        ->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LoanPayment $loanPayment)
    {
        $loanPayment->load(['loan', 'creator', 'approver', 'transaction']);
        
        // Pass as $payment to match the view expectation
        $payment = $loanPayment;
        
        return view('loan-payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoanPayment $loanPayment)
    {
        // Only allow editing if not approved yet
        if ($loanPayment->status === 'approved') {
            return back()->with('error', 'Pembayaran yang sudah disetujui tidak dapat diedit.');
        }

        $loans = Loan::active()->select('id', 'loan_code', 'borrower_name', 'monthly_payment')->get();
        
        // Pass as $payment to match the view expectation
        $payment = $loanPayment;

        return view('loan-payments.edit', compact('payment', 'loans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoanPayment $loanPayment)
    {
        // Only allow editing if not approved yet
        if ($loanPayment->status === 'approved') {
            return back()->with('error', 'Pembayaran yang sudah disetujui tidak dapat diedit.');
        }

        $request->validate([
            'payment_date' => 'required|date',
            'payment_amount' => 'required|numeric|min:1',
            'installment_number' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,transfer,check,other',
            'notes' => 'nullable|string'
        ]);

        // Check if installment already paid by another payment
        $existingPayment = LoanPayment::where('loan_id', $loanPayment->loan_id)
                                    ->where('installment_number', $request->installment_number)
                                    ->where('status', 'approved')
                                    ->where('id', '!=', $loanPayment->id)
                                    ->first();

        if ($existingPayment) {
            return back()->withInput()
                        ->with('error', 'Cicilan ke-' . $request->installment_number . ' sudah dibayar.');
        }

        DB::beginTransaction();
        try {
            // Recalculate payment breakdown
            $breakdown = $this->calculatePaymentBreakdown($loanPayment->loan, $request->payment_amount, $request->installment_number);

            $loanPayment->update([
                'payment_date' => $request->payment_date,
                'payment_amount' => $request->payment_amount,
                'principal_amount' => $breakdown['principal'],
                'interest_amount' => $breakdown['interest'],
                'penalty_amount' => $breakdown['penalty'],
                'installment_number' => $request->installment_number,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes
            ]);

            DB::commit();

            return redirect()->route('loan-payments.show', $loanPayment)
                           ->with('success', 'Data pembayaran berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                        ->with('error', 'Gagal memperbarui pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoanPayment $loanPayment)
    {
        // Only allow deletion if not approved
        if ($loanPayment->status === 'approved') {
            return back()->with('error', 'Pembayaran yang sudah disetujui tidak dapat dihapus.');
        }

        $loanPayment->delete();

        return redirect()->route('loan-payments.index')
                       ->with('success', 'Pembayaran berhasil dihapus.');
    }

    /**
     * Approve payment
     */
    public function approve(LoanPayment $loanPayment)
    {
        if ($loanPayment->status === 'approved') {
            return back()->with('error', 'Pembayaran sudah disetujui sebelumnya.');
        }

        DB::beginTransaction();
        try {
            $loanPayment->approve(Auth::id());
            DB::commit();

            return back()->with('success', 'Pembayaran berhasil disetujui dan transaksi jurnal telah dibuat.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menyetujui pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Reject payment
     */
    public function reject(Request $request, LoanPayment $loanPayment)
    {
        if ($loanPayment->status === 'approved') {
            return back()->with('error', 'Pembayaran yang sudah disetujui tidak dapat ditolak.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $loanPayment->reject(Auth::id(), $request->rejection_reason);
            DB::commit();

            return back()->with('success', 'Pembayaran berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Print payment receipt
     */
    public function printReceipt(LoanPayment $loanPayment)
    {
        // Load relationships
        $loanPayment->load(['loan', 'creator', 'approver']);
        
        // Only allow printing for approved payments
        if ($loanPayment->status !== 'approved') {
            return back()->with('error', 'Hanya pembayaran yang sudah disetujui yang dapat dicetak bukti pembayarannya.');
        }

        return view('loan-payments.print-receipt', compact('loanPayment'));
    }

    /**
     * Get loan installment details for AJAX
     */
    public function getLoanInstallment(Request $request)
    {
        try {
            $loanId = $request->get('loan_id');
            $paymentAmount = $request->get('payment_amount');
            $paymentDate = $request->get('payment_date');

            if (!$loanId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter loan_id diperlukan'
                ], 400);
            }

            $loan = Loan::find($loanId);
            if (!$loan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pinjaman tidak ditemukan'
                ], 404);
            }

            // Calculate next installment number
            $nextInstallmentNumber = LoanPayment::where('loan_id', $loan->id)
                                              ->where('status', 'approved')
                                              ->max('installment_number') + 1;

            if ($nextInstallmentNumber > $loan->loan_term_months) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pinjaman sudah lunas'
                ], 400);
            }

            // Calculate payment breakdown
            $breakdown = $this->calculatePaymentBreakdown($loan, $paymentAmount ?: $loan->monthly_payment, $nextInstallmentNumber);

            return response()->json([
                'success' => true,
                'data' => [
                    'principal_amount' => $breakdown['principal'],
                    'interest_amount' => $breakdown['interest'],
                    'penalty_amount' => $breakdown['penalty'],
                    'installment_number' => $nextInstallmentNumber,
                    'loan_code' => $loan->loan_code,
                    'borrower_name' => $loan->borrower_name,
                    'monthly_payment' => $loan->monthly_payment,
                    'remaining_balance' => $loan->remaining_balance
                ]
            ]);
        } catch (\Exception $e) {
             Log::error('Error in getLoanInstallment: ' . $e->getMessage());
             Log::error('Stack trace: ' . $e->getTraceAsString());
             
             return response()->json([
                 'success' => false,
                 'message' => 'Terjadi kesalahan: ' . $e->getMessage()
             ], 500);
         }
    }

    /**
     * Calculate installment payment details
     */
    private function calculateInstallmentPayment(Loan $loan, $installmentNumber)
    {
        // Calculate remaining balance up to this installment
        $paidInstallments = LoanPayment::where('loan_id', $loan->id)
                                     ->where('installment_number', '<', $installmentNumber)
                                     ->where('status', 'approved')
                                     ->sum('principal_amount');

        $remainingBalance = $loan->loan_amount - $paidInstallments;

        // Calculate interest for this period
        $interestAmount = 0;
        if ($loan->interest_rate > 0) {
            $interestAmount = $remainingBalance * ($loan->interest_rate / 100);
        }

        // Principal payment
        $principalAmount = $loan->monthly_payment - $interestAmount;

        // Adjust last payment to clear remaining balance
        if ($installmentNumber == $loan->loan_term_months) {
            $principalAmount = $remainingBalance;
            $totalPayment = $principalAmount + $interestAmount;
        } else {
            $totalPayment = $loan->monthly_payment;
        }

        // Check for late payment penalty
        $expectedDate = Carbon::parse($loan->loan_date)->addMonths((int) $installmentNumber);
        $penaltyAmount = 0;
        if (now()->gt($expectedDate)) {
            $daysLate = now()->diffInDays($expectedDate);
            // 1% penalty per month late (simplified calculation)
            $penaltyAmount = $totalPayment * 0.01 * ceil($daysLate / 30);
        }

        return [
            'principal_amount' => $principalAmount,
            'interest_amount' => $interestAmount,
            'penalty_amount' => $penaltyAmount,
            'total_payment' => $totalPayment + $penaltyAmount,
            'remaining_balance' => $remainingBalance - $principalAmount,
            'expected_date' => $expectedDate->format('Y-m-d'),
            'is_late' => now()->gt($expectedDate)
        ];
    }

    /**
     * Calculate payment breakdown
     */
    private function calculatePaymentBreakdown(Loan $loan, $paymentAmount, $installmentNumber)
    {
        $suggested = $this->calculateInstallmentPayment($loan, $installmentNumber);
        
        // If payment amount matches suggested, use calculated breakdown
        if (abs($paymentAmount - $suggested['total_payment']) < 0.01) {
            return [
                'principal' => $suggested['principal_amount'],
                'interest' => $suggested['interest_amount'],
                'penalty' => $suggested['penalty_amount']
            ];
        }

        // For partial or different payments, allocate in order: penalty, interest, principal
        $remaining = $paymentAmount;
        
        $penalty = min($remaining, $suggested['penalty_amount']);
        $remaining -= $penalty;
        
        $interest = min($remaining, $suggested['interest_amount']);
        $remaining -= $interest;
        
        $principal = $remaining;

        return [
            'principal' => $principal,
            'interest' => $interest,
            'penalty' => $penalty
        ];
    }
}
