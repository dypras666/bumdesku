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
        return view('financial-reports.create');
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
        
        return view('financial-reports.show', compact('financialReport'));
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

        return view('financial-reports.edit', compact('financialReport'));
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
        $periodStart = $request->period_start ?? Carbon::now()->startOfMonth();
        $periodEnd = $request->period_end ?? Carbon::now()->endOfMonth();

        $reportData = $this->generateReportData('income_statement', $periodStart, $periodEnd);

        return view('financial-reports.income-statement', compact('reportData', 'periodStart', 'periodEnd'));
    }

    /**
     * Balance Sheet
     */
    public function balanceSheet(Request $request)
    {
        $asOfDate = $request->as_of_date ?? Carbon::now();

        $reportData = $this->generateReportData('balance_sheet', null, $asOfDate);

        return view('financial-reports.balance-sheet', compact('reportData', 'asOfDate'));
    }

    /**
     * Cash Flow Statement
     */
    public function cashFlow(Request $request)
    {
        $periodStart = $request->period_start ?? Carbon::now()->startOfMonth();
        $periodEnd = $request->period_end ?? Carbon::now()->endOfMonth();

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
        $revenues = GeneralLedger::whereHas('account', function($query) {
                $query->where('account_category', 'Revenue');
            })
            ->posted()
            ->whereBetween('posting_date', [$periodStart, $periodEnd])
            ->with('account')
            ->get()
            ->groupBy('account.account_name')
            ->map(function($entries) {
                return $entries->sum('credit') - $entries->sum('debit');
            });

        $expenses = GeneralLedger::whereHas('account', function($query) {
                $query->where('account_category', 'Expense');
            })
            ->posted()
            ->whereBetween('posting_date', [$periodStart, $periodEnd])
            ->with('account')
            ->get()
            ->groupBy('account.account_name')
            ->map(function($entries) {
                return $entries->sum('debit') - $entries->sum('credit');
            });

        $totalRevenue = $revenues->sum();
        $totalExpenses = $expenses->sum();
        $netIncome = $totalRevenue - $totalExpenses;

        return [
            'revenues' => $revenues,
            'expenses' => $expenses,
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'net_income' => $netIncome
        ];
    }

    /**
     * Generate Balance Sheet data
     */
    private function generateBalanceSheetData($asOfDate)
    {
        $assets = MasterAccount::where('account_category', 'Asset')
            ->with(['generalLedgerEntries' => function($query) use ($asOfDate) {
                $query->posted()->whereDate('posting_date', '<=', $asOfDate);
            }])
            ->get()
            ->mapWithKeys(function($account) {
                $balance = $account->generalLedgerEntries->sum('debit') - $account->generalLedgerEntries->sum('credit');
                return [$account->account_name => $balance];
            });

        $liabilities = MasterAccount::where('account_category', 'Liability')
            ->with(['generalLedgerEntries' => function($query) use ($asOfDate) {
                $query->posted()->whereDate('posting_date', '<=', $asOfDate);
            }])
            ->get()
            ->mapWithKeys(function($account) {
                $balance = $account->generalLedgerEntries->sum('credit') - $account->generalLedgerEntries->sum('debit');
                return [$account->account_name => $balance];
            });

        $equity = MasterAccount::where('account_category', 'Equity')
            ->with(['generalLedgerEntries' => function($query) use ($asOfDate) {
                $query->posted()->whereDate('posting_date', '<=', $asOfDate);
            }])
            ->get()
            ->mapWithKeys(function($account) {
                $balance = $account->generalLedgerEntries->sum('credit') - $account->generalLedgerEntries->sum('debit');
                return [$account->account_name => $balance];
            });

        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'total_assets' => $assets->sum(),
            'total_liabilities' => $liabilities->sum(),
            'total_equity' => $equity->sum()
        ];
    }

    /**
     * Generate Cash Flow data
     */
    private function generateCashFlowData($periodStart, $periodEnd)
    {
        $cashAccounts = MasterAccount::where('account_name', 'like', '%Cash%')
            ->orWhere('account_name', 'like', '%Bank%')
            ->pluck('id');

        $cashTransactions = GeneralLedger::whereIn('account_id', $cashAccounts)
            ->posted()
            ->whereBetween('posting_date', [$periodStart, $periodEnd])
            ->with(['account', 'transaction'])
            ->get();

        $operatingActivities = $cashTransactions->filter(function($entry) {
            return in_array($entry->reference_type, ['Revenue', 'Expense', 'Operating']);
        });

        $investingActivities = $cashTransactions->filter(function($entry) {
            return in_array($entry->reference_type, ['Investment', 'Asset Purchase', 'Asset Sale']);
        });

        $financingActivities = $cashTransactions->filter(function($entry) {
            return in_array($entry->reference_type, ['Loan', 'Capital', 'Dividend']);
        });

        return [
            'operating_activities' => $operatingActivities,
            'investing_activities' => $investingActivities,
            'financing_activities' => $financingActivities,
            'net_operating_cash' => $operatingActivities->sum('debit') - $operatingActivities->sum('credit'),
            'net_investing_cash' => $investingActivities->sum('debit') - $investingActivities->sum('credit'),
            'net_financing_cash' => $financingActivities->sum('debit') - $financingActivities->sum('credit')
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
                'account_name' => $account->account_name,
                'account_code' => $account->account_code,
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
            ->groupBy('account.account_name');
    }
}
