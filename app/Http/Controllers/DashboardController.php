<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\MasterAccount;
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

        // Transaksi terbaru untuk periode yang dipilih
        $recentTransactions = Transaction::with(['account', 'user'])
            ->whereYear('transaction_date', $selectedYear)
            ->whereMonth('transaction_date', $selectedMonth)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'monthlyData', 'recentTransactions'));
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
            $date = $startDate->copy()->addMonths($i);
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
}
