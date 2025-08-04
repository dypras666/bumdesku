<?php

namespace App\Http\Controllers;

use App\Models\FinancialReport;
use App\Models\GeneralLedger;
use App\Models\MasterAccount;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FinancialReport::with(['generatedBy', 'finalizedBy']);

        // Filter by report type
        if ($request->filled('report_type')) {
            $query->where('report_type', $request->report_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('period_start', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('period_end', '<=', $request->end_date);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('report_code', 'like', "%{$search}%")
                  ->orWhere('report_title', 'like', "%{$search}%");
            });
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(25);

        return view('financial-reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = MasterAccount::orderBy('nama_akun')->get();
        return view('financial-reports.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:income_statement,balance_sheet,cash_flow,trial_balance,general_ledger',
            'report_title' => 'required|string|max:255',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'notes' => 'nullable|string'
        ]);

        $reportData = $this->generateReportData($request->report_type, $request->period_start, $request->period_end);

        $report = FinancialReport::create([
            'report_code' => FinancialReport::generateReportCode($request->report_type),
            'report_type' => $request->report_type,
            'report_title' => $request->report_title,
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'report_data' => $reportData,
            'report_parameters' => $request->only(['period_start', 'period_end']),
            'status' => 'generated',
            'generated_by' => Auth::id(),
            'generated_at' => now(),
            'notes' => $request->notes
        ]);

        return redirect()->route('financial-reports.show', $report)
                        ->with('success', 'Financial report generated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FinancialReport $financialReport)
    {
        $financialReport->load(['generatedBy', 'finalizedBy']);
        
        // Assign to $report for view consistency
        $report = $financialReport;
        
        return view('financial-reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinancialReport $financialReport)
    {
        if ($financialReport->isFinalized()) {
            return redirect()->route('financial-reports.show', $financialReport)
                           ->with('error', 'Finalized reports cannot be edited.');
        }

        // Assign to $report for view consistency
        $report = $financialReport;
        
        // Get accounts for the account selection dropdown
        $accounts = MasterAccount::orderBy('kode_akun')->get();

        return view('financial-reports.edit', compact('report', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinancialReport $financialReport)
    {
        if ($financialReport->isFinalized()) {
            return redirect()->route('financial-reports.show', $financialReport)
                           ->with('error', 'Finalized reports cannot be updated.');
        }

        $request->validate([
            'report_title' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $financialReport->update([
            'report_title' => $request->report_title,
            'notes' => $request->notes
        ]);

        return redirect()->route('financial-reports.show', $financialReport)
                        ->with('success', 'Financial report updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinancialReport $financialReport)
    {
        if ($financialReport->isFinalized()) {
            return redirect()->route('financial-reports.index')
                           ->with('error', 'Finalized reports cannot be deleted.');
        }

        $financialReport->delete();

        return redirect()->route('financial-reports.index')
                        ->with('success', 'Financial report deleted successfully.');
    }

    /**
     * Finalize a report
     */
    public function finalize(FinancialReport $financialReport)
    {
        if ($financialReport->isFinalized()) {
            return redirect()->route('financial-reports.show', $financialReport)
                           ->with('error', 'Report is already finalized.');
        }

        $financialReport->update([
            'status' => 'finalized',
            'finalized_by' => Auth::id(),
            'finalized_at' => now()
        ]);

        return redirect()->route('financial-reports.show', $financialReport)
                        ->with('success', 'Report finalized successfully.');
    }

    /**
     * Regenerate report data
     */
    public function regenerate(FinancialReport $financialReport)
    {
        if ($financialReport->isFinalized()) {
            return redirect()->route('financial-reports.show', $financialReport)
                           ->with('error', 'Finalized reports cannot be regenerated.');
        }

        $reportData = $this->generateReportData(
            $financialReport->report_type,
            $financialReport->period_start,
            $financialReport->period_end
        );

        $financialReport->update([
            'report_data' => $reportData,
            'generated_at' => now()
        ]);

        return redirect()->route('financial-reports.show', $financialReport)
                        ->with('success', 'Report data regenerated successfully.');
    }

    /**
     * Export report to PDF
     */
    public function exportPdf(FinancialReport $financialReport)
    {
        // TODO: Implement PDF export functionality
        // Requires PDF library installation (e.g., dompdf or tcpdf)
        return redirect()->back()->with('info', 'PDF export functionality will be implemented later.');
    }

    /**
     * Income Statement
     */
    public function incomeStatement(Request $request)
    {
        $periodStart = $request->period_start ? Carbon::parse($request->period_start) : Carbon::now()->startOfMonth();
        $periodEnd = $request->period_end ? Carbon::parse($request->period_end) : Carbon::now()->endOfMonth();

        $reportData = $this->generateReportData('income_statement', $periodStart, $periodEnd);

        return view('financial-reports.income-statement', compact('reportData', 'periodStart', 'periodEnd'));
    }

    /**
     * Balance Sheet
     */
    public function balanceSheet(Request $request)
    {
        $asOfDate = $request->as_of_date ? Carbon::parse($request->as_of_date) : Carbon::now();

        $reportData = $this->generateReportData('balance_sheet', null, $asOfDate);

        return view('financial-reports.balance-sheet', compact('reportData', 'asOfDate'));
    }

    /**
     * Cash Flow Statement
     */
    public function cashFlow(Request $request)
    {
        $periodStart = $request->period_start ? Carbon::parse($request->period_start) : Carbon::now()->startOfMonth();
        $periodEnd = $request->period_end ? Carbon::parse($request->period_end) : Carbon::now()->endOfMonth();

        $reportData = $this->generateReportData('cash_flow', $periodStart, $periodEnd);

        return view('financial-reports.cash-flow', compact('reportData', 'periodStart', 'periodEnd'));
    }

    /**
     * Generate report data based on type
     */
    private function generateReportData($reportType, $periodStart, $periodEnd)
    {
        switch ($reportType) {
            case 'income_statement':
                return $this->generateIncomeStatementData($periodStart, $periodEnd);
            case 'balance_sheet':
                return $this->generateBalanceSheetData($periodEnd);
            case 'cash_flow':
                return $this->generateCashFlowData($periodStart, $periodEnd);
            case 'trial_balance':
                return $this->generateTrialBalanceData($periodEnd);
            case 'general_ledger':
                return $this->generateGeneralLedgerData($periodStart, $periodEnd);
            default:
                return [];
        }
    }

    /**
     * Generate Income Statement data
     */
    private function generateIncomeStatementData($periodStart, $periodEnd)
    {
        // Get revenue accounts with detailed breakdown
        $revenueAccounts = MasterAccount::where('kategori_akun', 'Pendapatan')
            ->with(['generalLedgerEntries' => function($query) use ($periodStart, $periodEnd) {
                $query->posted()->whereBetween('posting_date', [$periodStart, $periodEnd]);
            }])
            ->get();

        $revenues = collect();
        $totalRevenue = 0;
        foreach ($revenueAccounts as $account) {
            $amount = $account->generalLedgerEntries->sum('credit') - $account->generalLedgerEntries->sum('debit');
            if ($amount > 0) {
                $revenues->put($account->nama_akun, $amount);
                $totalRevenue += $amount;
            }
        }

        // Get expense accounts with detailed breakdown
        $expenseAccounts = MasterAccount::where('kategori_akun', 'Beban')
            ->with(['generalLedgerEntries' => function($query) use ($periodStart, $periodEnd) {
                $query->posted()->whereBetween('posting_date', [$periodStart, $periodEnd]);
            }])
            ->get();

        $expenses = collect();
        $totalExpenses = 0;
        foreach ($expenseAccounts as $account) {
            $amount = $account->generalLedgerEntries->sum('debit') - $account->generalLedgerEntries->sum('credit');
            if ($amount > 0) {
                $expenses->put($account->nama_akun, $amount);
                $totalExpenses += $amount;
            }
        }
        $netIncome = $totalRevenue - $totalExpenses;

        return [
            'revenues' => $revenues,
            'expenses' => $expenses,
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'net_income' => $netIncome,
            'period_start' => $periodStart,
            'period_end' => $periodEnd
        ];
    }

    /**
     * Generate Balance Sheet data
     */
    private function generateBalanceSheetData($asOfDate)
    {
        // Assets
        $assetAccounts = MasterAccount::where('kategori_akun', 'Aset')
            ->with(['generalLedgerEntries' => function($query) use ($asOfDate) {
                $query->posted()->where('posting_date', '<=', $asOfDate);
            }])
            ->get();

        $assets = collect();
        $totalAssets = 0;
        foreach ($assetAccounts as $account) {
            $balance = $account->saldo_awal + $account->generalLedgerEntries->sum('debit') - $account->generalLedgerEntries->sum('credit');
            if ($balance != 0) {
                $assets->put($account->nama_akun, $balance);
                $totalAssets += $balance;
            }
        }

        // Liabilities
        $liabilityAccounts = MasterAccount::where('kategori_akun', 'Kewajiban')
            ->with(['generalLedgerEntries' => function($query) use ($asOfDate) {
                $query->posted()->where('posting_date', '<=', $asOfDate);
            }])
            ->get();

        $liabilities = collect();
        $totalLiabilities = 0;
        foreach ($liabilityAccounts as $account) {
            $balance = $account->saldo_awal + $account->generalLedgerEntries->sum('credit') - $account->generalLedgerEntries->sum('debit');
            if ($balance != 0) {
                $liabilities->put($account->nama_akun, $balance);
                $totalLiabilities += $balance;
            }
        }

        // Equity
        $equityAccounts = MasterAccount::where('kategori_akun', 'Modal')
            ->with(['generalLedgerEntries' => function($query) use ($asOfDate) {
                $query->posted()->where('posting_date', '<=', $asOfDate);
            }])
            ->get();

        $equity = collect();
        $totalEquity = 0;
        foreach ($equityAccounts as $account) {
            $balance = $account->saldo_awal + $account->generalLedgerEntries->sum('credit') - $account->generalLedgerEntries->sum('debit');
            if ($balance != 0) {
                $equity->put($account->nama_akun, $balance);
                $totalEquity += $balance;
            }
        }

        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'total_assets' => $totalAssets,
            'total_liabilities' => $totalLiabilities,
            'total_equity' => $totalEquity,
            'as_of_date' => $asOfDate
        ];
    }

    /**
     * Generate Cash Flow data
     */
    private function generateCashFlowData($periodStart, $periodEnd)
    {
        // Get cash and bank accounts
        $cashAccounts = MasterAccount::where(function($query) {
                $query->where('nama_akun', 'like', '%Kas%')
                      ->orWhere('nama_akun', 'like', '%Bank%')
                      ->orWhere('nama_akun', 'like', '%Cash%')
                      ->orWhere('kode_akun', 'like', '1-1%'); // Cash accounts typically start with 1-1
            })
            ->pluck('id');

        // Get all cash transactions for the period
        $cashTransactions = GeneralLedger::whereIn('account_id', $cashAccounts)
            ->posted()
            ->whereBetween('posting_date', [$periodStart, $periodEnd])
            ->with(['account', 'transaction'])
            ->get();

        // Operating Activities - based on transaction types and account categories
        $operatingActivities = collect();
        $investingActivities = collect();
        $financingActivities = collect();

        foreach ($cashTransactions as $entry) {
            $transaction = $entry->transaction;
            $amount = $entry->debit - $entry->credit; // Positive = cash in, Negative = cash out
            
            if ($transaction) {
                // Categorize based on transaction type
                switch ($transaction->jenis_transaksi) {
                    case 'income':
                    case 'revenue':
                    case 'penjualan':
                        $operatingActivities->push([
                            'description' => $entry->description ?: $transaction->keterangan,
                            'amount' => $amount,
                            'date' => $entry->posting_date,
                            'type' => 'Revenue'
                        ]);
                        break;
                    
                    case 'expense':
                    case 'beban':
                    case 'operasional':
                        $operatingActivities->push([
                            'description' => $entry->description ?: $transaction->keterangan,
                            'amount' => $amount,
                            'date' => $entry->posting_date,
                            'type' => 'Operating Expense'
                        ]);
                        break;
                    
                    case 'investment':
                    case 'asset':
                    case 'peralatan':
                        $investingActivities->push([
                            'description' => $entry->description ?: $transaction->keterangan,
                            'amount' => $amount,
                            'date' => $entry->posting_date,
                            'type' => 'Asset Purchase'
                        ]);
                        break;
                    
                    case 'loan':
                    case 'modal':
                    case 'pinjaman':
                        $financingActivities->push([
                            'description' => $entry->description ?: $transaction->keterangan,
                            'amount' => $amount,
                            'date' => $entry->posting_date,
                            'type' => 'Financing'
                        ]);
                        break;
                    
                    default:
                        // Default to operating activities
                        $operatingActivities->push([
                            'description' => $entry->description ?: $transaction->keterangan,
                            'amount' => $amount,
                            'date' => $entry->posting_date,
                            'type' => 'Other Operating'
                        ]);
                        break;
                }
            } else {
                // If no transaction linked, categorize as operating
                $operatingActivities->push([
                    'description' => $entry->description,
                    'amount' => $amount,
                    'date' => $entry->posting_date,
                    'type' => 'General'
                ]);
            }
        }

        // Calculate beginning and ending cash balances
        $beginningCash = 0;
        foreach ($cashAccounts as $accountId) {
            $account = MasterAccount::find($accountId);
            if ($account) {
                $beginningCash += $account->saldo_awal;
                
                // Add transactions before period start
                $priorEntries = GeneralLedger::where('account_id', $accountId)
                    ->posted()
                    ->where('posting_date', '<', $periodStart)
                    ->get();
                
                $beginningCash += $priorEntries->sum('debit') - $priorEntries->sum('credit');
            }
        }

        $netOperatingCash = $operatingActivities->sum('amount');
        $netInvestingCash = $investingActivities->sum('amount');
        $netFinancingCash = $financingActivities->sum('amount');
        $netCashChange = $netOperatingCash + $netInvestingCash + $netFinancingCash;
        $endingCash = $beginningCash + $netCashChange;

        return [
            'operating_activities' => $operatingActivities,
            'investing_activities' => $investingActivities,
            'financing_activities' => $financingActivities,
            'net_operating_cash' => $netOperatingCash,
            'net_investing_cash' => $netInvestingCash,
            'net_financing_cash' => $netFinancingCash,
            'net_cash_change' => $netCashChange,
            'beginning_cash' => $beginningCash,
            'ending_cash' => $endingCash,
            'period_start' => $periodStart,
            'period_end' => $periodEnd
        ];
    }

    /**
     * Generate Trial Balance data
     */
    private function generateTrialBalanceData($asOfDate)
    {
        $accounts = MasterAccount::with(['generalLedgerEntries' => function($query) use ($asOfDate) {
            $query->posted()->whereDate('posting_date', '<=', $asOfDate);
        }])->get();

        return $accounts->map(function($account) {
            $totalDebit = $account->generalLedgerEntries->sum('debit');
            $totalCredit = $account->generalLedgerEntries->sum('credit');

            return [
                'account_name' => $account->nama_akun,
                'account_code' => $account->kode_akun,
                'debit' => $totalDebit,
                'credit' => $totalCredit,
                'balance' => $totalDebit - $totalCredit
            ];
        })->filter(function($item) {
            return $item['debit'] > 0 || $item['credit'] > 0;
        });
    }

    /**
     * Generate General Ledger data
     */
    private function generateGeneralLedgerData($periodStart, $periodEnd)
    {
        return GeneralLedger::with(['account', 'transaction', 'postedBy'])
            ->posted()
            ->whereBetween('posting_date', [$periodStart, $periodEnd])
            ->orderBy('posting_date')
            ->orderBy('entry_code')
            ->get()
            ->groupBy('account.nama_akun');
    }
}
