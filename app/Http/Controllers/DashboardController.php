<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\MasterAccount;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // Ambil periode dari request, default ke bulan dan tahun saat ini
        $selectedMonth = $request->get('month', Carbon::now()->month);
        $selectedYear = $request->get('year', Carbon::now()->year);
        
        // Validasi input
        $selectedMonth = max(1, min(12, (int)$selectedMonth));
        $selectedYear = max(2020, min(2030, (int)$selectedYear));
        
        // Data statistik untuk dashboard BUMDES berdasarkan periode
        $totalTransactions = Transaction::whereYear('transaction_date', $selectedYear)
            ->whereMonth('transaction_date', $selectedMonth)
            ->count();
            
        $totalIncome = Transaction::approved()
            ->income()
            ->whereYear('transaction_date', $selectedYear)
            ->whereMonth('transaction_date', $selectedMonth)
            ->sum('amount');
            
        $totalExpenses = Transaction::approved()
            ->expense()
            ->whereYear('transaction_date', $selectedYear)
            ->whereMonth('transaction_date', $selectedMonth)
            ->sum('amount');
        
        // Saldo awal kas untuk periode yang dipilih = saldo kas pada akhir bulan sebelumnya
        $initialCashBalance = $this->getCashBalanceUntilDate(
            Carbon::create($selectedYear, $selectedMonth, 1)->subDay()
        );

        // Saldo akhir kas untuk periode ini = Saldo awal periode + Pemasukan periode ini - Pengeluaran periode ini
        $finalCashBalance = $initialCashBalance + $totalIncome - $totalExpenses;

        // Total saldo awal semua akun aset pada awal periode
        $totalInitialBalance = $this->getTotalAssetBalanceUntilDate(Carbon::create($selectedYear, $selectedMonth, 1)->subDay());

        // Modal BUMDES (Akun 3-1001) - berdasarkan periode
        $modalAccount = MasterAccount::where('kode_akun', '3-1001')->first();
        
        // Hitung modal awal pada awal periode yang dipilih
        $initialModalBalance = $modalAccount ? $this->getModalBalanceUntilDate($modalAccount, Carbon::create($selectedYear, $selectedMonth, 1)->subDay()) : 0;
        
        // Hitung modal saat ini pada akhir periode yang dipilih
        $currentModalBalance = $modalAccount ? $this->getModalBalanceUntilDate($modalAccount, Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth()) : 0;

        $stats = [
            'total_transactions' => $totalTransactions,
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'final_cash_balance' => $finalCashBalance,
            'initial_cash_balance' => $initialCashBalance,
            'total_initial_balance' => $totalInitialBalance,
            'initial_modal_balance' => $initialModalBalance,
            'current_modal_balance' => $currentModalBalance,
            'selected_month' => $selectedMonth,
            'selected_year' => $selectedYear,
            'period_name' => Carbon::create($selectedYear, $selectedMonth, 1)->format('F Y'),
        ];

        // Data untuk chart bulanan (6 bulan terakhir dari periode yang dipilih)
        $monthlyData = $this->getMonthlyChartData($selectedYear, $selectedMonth);

        // Piutang jatuh tempo
        $dueReceivables = $this->getDueReceivables();

        // Total telat bayar dan total piutang
        $loanStats = $this->getLoanStatistics();

        // Transaksi terbaru untuk periode yang dipilih
        $recentTransactions = Transaction::with(['account', 'user'])
            ->whereYear('transaction_date', $selectedYear)
            ->whereMonth('transaction_date', $selectedMonth)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'monthlyData', 'recentTransactions', 'dueReceivables', 'loanStats'));
    }

    /**
     * Get monthly chart data for the last 6 months from selected period
     */
    private function getMonthlyChartData($selectedYear, $selectedMonth)
    {
        $months = [];
        $incomeData = [];
        $expenseData = [];

        // Mulai dari 5 bulan sebelum periode yang dipilih
        $startDate = Carbon::create($selectedYear, $selectedMonth, 1)->subMonths(5);

        for ($i = 0; $i < 6; $i++) {
            $date = $startDate->copy()->addMonths((int) $i);
            $monthName = $date->format('M Y');
            $months[] = $monthName;

            // Income untuk bulan ini
            $monthlyIncome = Transaction::approved()
                ->income()
                ->whereYear('transaction_date', $date->year)
                ->whereMonth('transaction_date', $date->month)
                ->sum('amount');

            // Expense untuk bulan ini
            $monthlyExpense = Transaction::approved()
                ->expense()
                ->whereYear('transaction_date', $date->year)
                ->whereMonth('transaction_date', $date->month)
                ->sum('amount');

            $incomeData[] = (float) $monthlyIncome;
            $expenseData[] = (float) $monthlyExpense;
        }

        return [
            'labels' => $months,
            'income' => $incomeData,
            'expenses' => $expenseData,
        ];
    }

    /**
     * Calculate cash balance until specific date
     */
    private function getCashBalanceUntilDate($endDate)
    {
        // Cari tanggal transaksi pertama
        $firstTransaction = Transaction::orderBy('transaction_date')->first();
        
        // Jika tidak ada transaksi atau tanggal yang diminta sebelum transaksi pertama
        if (!$firstTransaction || $endDate < $firstTransaction->transaction_date) {
            return 0; // Saldo awal = 0 untuk periode sebelum ada transaksi
        }

        // Saldo awal kas dari sistem (hanya digunakan jika ada transaksi)
        $kasAccount = MasterAccount::where('kode_akun', '1-1001')->first();
        $initialCashBalance = $kasAccount ? $kasAccount->saldo_awal : 0;

        // Total pemasukan sampai tanggal tersebut
        $totalIncomeUntilDate = Transaction::approved()
            ->income()
            ->where('transaction_date', '<=', $endDate)
            ->sum('amount');

        // Total pengeluaran sampai tanggal tersebut
        $totalExpensesUntilDate = Transaction::approved()
            ->expense()
            ->where('transaction_date', '<=', $endDate)
            ->sum('amount');

        return $initialCashBalance + $totalIncomeUntilDate - $totalExpensesUntilDate;
    }

    /**
     * Calculate modal balance until specific date
     */
    private function getModalBalanceUntilDate($modalAccount, $endDate)
    {
        // Cari tanggal transaksi pertama
        $firstTransaction = Transaction::orderBy('transaction_date')->first();
        
        // Jika tidak ada transaksi atau tanggal yang diminta sebelum transaksi pertama
        if (!$firstTransaction || $endDate < $firstTransaction->transaction_date) {
            return 0; // Modal awal = 0 untuk periode sebelum ada transaksi
        }

        // Saldo awal modal dari sistem (hanya digunakan jika ada transaksi)
        $initialModalBalance = $modalAccount->saldo_awal;

        // Untuk modal, biasanya tidak berubah kecuali ada transaksi khusus modal
        // Dalam sistem BUMDES sederhana, modal biasanya tetap
        // Namun jika ada transaksi yang mempengaruhi modal, bisa ditambahkan di sini
        
        return $initialModalBalance;
     }

    /**
     * Calculate total asset balance until specific date
     */
    private function getTotalAssetBalanceUntilDate($endDate)
    {
        // Cari tanggal transaksi pertama
        $firstTransaction = Transaction::orderBy('transaction_date')->first();
        
        // Jika tidak ada transaksi atau tanggal yang diminta sebelum transaksi pertama
        if (!$firstTransaction || $endDate < $firstTransaction->transaction_date) {
            return 0; // Total aset awal = 0 untuk periode sebelum ada transaksi
        }

        // Total saldo awal semua akun aset dari sistem (hanya digunakan jika ada transaksi)
        $totalInitialAssets = MasterAccount::where('kategori_akun', 'Aset')->sum('saldo_awal');
        
        return $totalInitialAssets;
    }

    /**
     * Get due receivables from loans
     */
    private function getDueReceivables()
    {
        $today = Carbon::today();
        
        // Ambil pinjaman yang masih aktif
        $activeLoans = Loan::where('status', 'active')->get();
        
        $dueReceivables = [];
        $totalDueAmount = 0;
        
        foreach ($activeLoans as $loan) {
            // Hitung total yang sudah dibayar
            $totalPaid = $loan->payments()
                ->where('status', 'approved')
                ->sum('payment_amount');
            
            // Hitung sisa pinjaman
            $remainingAmount = $loan->loan_amount - $totalPaid;
            
            if ($remainingAmount > 0) {
                // Hitung pembayaran yang jatuh tempo
                $monthlyPayment = $this->calculateMonthlyPayment($loan);
                $daysSinceLastPayment = $this->getDaysSinceLastPayment($loan);
                
                // Jika sudah lebih dari 30 hari sejak pembayaran terakhir, dianggap jatuh tempo
                if ($daysSinceLastPayment >= 30) {
                    $dueReceivables[] = [
                        'loan' => $loan,
                        'remaining_amount' => $remainingAmount,
                        'monthly_payment' => $monthlyPayment,
                        'days_overdue' => $daysSinceLastPayment - 30,
                        'borrower_name' => $loan->borrower_name,
                        'loan_type' => $loan->loan_type
                    ];
                    
                    $totalDueAmount += $monthlyPayment;
                }
            }
        }
        
        return [
            'items' => collect($dueReceivables)->sortByDesc('days_overdue'),
            'total_amount' => $totalDueAmount,
            'count' => count($dueReceivables)
        ];
    }

    /**
     * Calculate monthly payment for a loan
     */
    private function calculateMonthlyPayment($loan)
    {
        if ($loan->loan_type === 'bunga') {
            // Untuk pinjaman berbunga, hitung cicilan bulanan
            $monthlyInterest = ($loan->loan_amount * $loan->interest_rate / 100) / 12;
            $principal = $loan->loan_amount / $loan->loan_term_months;
            return $principal + $monthlyInterest;
        } elseif ($loan->loan_type === 'bagi_hasil') {
            // Untuk bagi hasil, gunakan expected profit dibagi term
            return ($loan->loan_amount + $loan->expected_profit) / $loan->loan_term_months;
        } else {
            // Untuk tanpa bunga, hanya pokok dibagi term
            return $loan->loan_amount / $loan->loan_term_months;
        }
    }

    /**
     * Get days since last payment
     */
    private function getDaysSinceLastPayment($loan)
    {
        $lastPayment = $loan->payments()
            ->where('status', 'approved')
            ->orderBy('payment_date', 'desc')
            ->first();
        
        if ($lastPayment) {
            return Carbon::today()->diffInDays(Carbon::parse($lastPayment->payment_date));
        } else {
            // Jika belum ada pembayaran, hitung dari tanggal pinjaman
            return Carbon::today()->diffInDays(Carbon::parse($loan->loan_date));
        }
    }

    /**
     * Get loan statistics for dashboard
     */
    private function getLoanStatistics()
    {
        // Total piutang (semua pinjaman aktif)
        $activeLoans = Loan::whereIn('status', ['active', 'overdue'])->get();
        $totalReceivables = 0;
        $totalOverduePayments = 0;

        foreach ($activeLoans as $loan) {
            // Hitung sisa pinjaman
            $totalPaid = $loan->approvedPayments()->sum('payment_amount');
            $totalPayable = $loan->getTotalPayableAmount();
            $remainingBalance = $totalPayable - $totalPaid;
            
            if ($remainingBalance > 0) {
                $totalReceivables += $remainingBalance;
                
                // Jika status overdue, tambahkan ke total telat bayar
                if ($loan->status === 'overdue') {
                    $totalOverduePayments += $remainingBalance;
                }
            }
        }

        return [
            'total_receivables' => $totalReceivables,
            'total_overdue_payments' => $totalOverduePayments,
            'formatted_total_receivables' => 'Rp ' . number_format($totalReceivables, 0, ',', '.'),
            'formatted_total_overdue_payments' => 'Rp ' . number_format($totalOverduePayments, 0, ',', '.'),
        ];
    }
}
