<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
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
    public function index()
    {
        // Data statistik untuk dashboard BUMDES
        $totalTransactions = Transaction::count();
        $totalIncome = Transaction::approved()->income()->sum('amount');
        $totalExpenses = Transaction::approved()->expense()->sum('amount');
        $cashBalance = $totalIncome - $totalExpenses;

        $stats = [
            'total_transactions' => $totalTransactions,
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'cash_balance' => $cashBalance,
        ];

        // Data untuk chart bulanan (6 bulan terakhir)
        $monthlyData = $this->getMonthlyChartData();

        // Transaksi terbaru (5 transaksi terakhir)
        $recentTransactions = Transaction::with(['account', 'user'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'monthlyData', 'recentTransactions'));
    }

    /**
     * Get monthly chart data for the last 6 months
     */
    private function getMonthlyChartData()
    {
        $months = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
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
}
