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
    public function __construct()
    {
        $this->middleware('auth');
    }

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
    public function show(FinancialReport $financial_report)
    {
        $financial_report->load(['generatedBy', 'finalizedBy']);
        
        // Assign to $report for view consistency
        $report = $financial_report;
        
        return view('financial-reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinancialReport $financial_report)
    {
        if ($financial_report->isFinalized()) {
            return redirect()->route('financial-reports.show', $financial_report)
                           ->with('error', 'Finalized reports cannot be edited.');
        }

        // Assign to $report for view consistency
        $report = $financial_report;
        
        // Get accounts for the account selection dropdown
        $accounts = MasterAccount::orderBy('kode_akun')->get();

        return view('financial-reports.edit', compact('report', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinancialReport $financial_report)
    {
        if ($financial_report->isFinalized()) {
            return redirect()->route('financial-reports.show', $financial_report)
                           ->with('error', 'Finalized reports cannot be updated.');
        }

        $request->validate([
            'report_title' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $financial_report->update([
            'report_title' => $request->report_title,
            'notes' => $request->notes
        ]);

        return redirect()->route('financial-reports.show', $financial_report)
                        ->with('success', 'Financial report updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinancialReport $financial_report)
    {
        if ($financial_report->isFinalized()) {
            return redirect()->route('financial-reports.index')
                           ->with('error', 'Finalized reports cannot be deleted.');
        }

        $financial_report->delete();

        return redirect()->route('financial-reports.index')
                        ->with('success', 'Financial report deleted successfully.');
    }

    /**
     * Finalize a report
     */
    public function finalize(FinancialReport $financial_report)
    {
        if ($financial_report->isFinalized()) {
            return redirect()->route('financial-reports.show', $financial_report)
                           ->with('error', 'Report is already finalized.');
        }

        $financial_report->update([
            'status' => 'finalized',
            'finalized_by' => Auth::id(),
            'finalized_at' => now()
        ]);

        return redirect()->route('financial-reports.show', $financial_report)
                        ->with('success', 'Report finalized successfully.');
    }

    /**
     * Regenerate report data
     */
    public function regenerate(FinancialReport $financial_report)
    {
        if ($financial_report->isFinalized()) {
            return redirect()->route('financial-reports.show', $financial_report)
                           ->with('error', 'Finalized reports cannot be regenerated.');
        }

        $reportData = $this->generateReportData(
            $financial_report->report_type,
            $financial_report->period_start,
            $financial_report->period_end
        );

        $financial_report->update([
            'report_data' => $reportData,
            'generated_at' => now()
        ]);

        return redirect()->route('financial-reports.show', $financial_report)
                        ->with('success', 'Report data regenerated successfully.');
    }

    /**
     * Export report to PDF
     */
    public function exportPdf(FinancialReport $financial_report)
    {
        if (!$financial_report->report_data) {
            return redirect()->back()->with('error', 'Data laporan tidak tersedia untuk diekspor.');
        }

        // Ensure numeric values in report data
        $reportData = $this->sanitizeReportData($financial_report->report_data);

        $data = [
            'report' => $financial_report,
            'reportData' => $reportData,
            'title' => $financial_report->getReportTypeLabel(),
            'company_info' => [
                'name' => company_info('name') ?? 'BUMDES',
                'address' => company_info('address') ?? '',
                'phone' => company_info('phone') ?? '',
                'email' => company_info('email') ?? ''
            ]
        ];

        // Add specific date variables based on report type
        if (in_array($financial_report->report_type, ['balance_sheet', 'trial_balance'])) {
            // For balance sheet and trial balance, use asOfDate (end date)
            $data['asOfDate'] = $financial_report->period_end;
        } else {
            // For income statement and cash flow, use period range
            $data['periodStart'] = $financial_report->period_start;
            $data['periodEnd'] = $financial_report->period_end;
        }

        $pdf = app('dompdf.wrapper');
        
        // Choose the appropriate template based on report type
        $template = 'financial-reports.exports.' . str_replace('_', '-', $financial_report->report_type) . '-pdf';
        
        $pdf->loadView($template, $data);
        $pdf->setPaper('letter', 'portrait');
        
        $filename = $financial_report->report_code . '_' . $financial_report->getReportTypeLabel() . '_' . date('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export report to DOCX
     */
    public function exportDocx(FinancialReport $financial_report)
    {
        if (!$financial_report->report_data) {
            return redirect()->back()->with('error', 'Data laporan tidak tersedia untuk diekspor.');
        }

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();

        // Company header
        $companyName = company_info('name') ?? 'BUMDES';
        $section->addText($companyName, ['bold' => true, 'size' => 16], ['alignment' => 'center']);
        $section->addText(strtoupper($financial_report->getReportTypeLabel()), ['bold' => true, 'size' => 14], ['alignment' => 'center']);
        $section->addText($financial_report->getPeriodLabel(), ['size' => 12], ['alignment' => 'center']);
        $section->addTextBreak(2);

        // Add report content based on type
        $this->addReportContentToWord($section, $financial_report);

        $filename = $financial_report->report_code . '_' . $financial_report->getReportTypeLabel() . '_' . date('Y-m-d_H-i-s') . '.docx';
        
        $tempFile = tempnam(sys_get_temp_dir(), 'phpword');
        $phpWord->save($tempFile, 'Word2007');
        
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Export report to Excel
     */
    public function exportExcel(FinancialReport $financial_report)
    {
        if (!$financial_report->report_data) {
            return redirect()->back()->with('error', 'Data laporan tidak tersedia untuk diekspor.');
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Company header
        $companyName = company_info('name') ?? 'BUMDES';
        $sheet->setCellValue('A1', $companyName);
        $sheet->setCellValue('A2', strtoupper($financial_report->getReportTypeLabel()));
        $sheet->setCellValue('A3', $financial_report->getPeriodLabel());
        
        // Style headers
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(16);
        $sheet->getStyle('A2')->getFont()->setSize(14);

        // Add report content based on type
        $this->addReportContentToExcel($sheet, $financial_report);

        $filename = $financial_report->report_code . '_' . $financial_report->getReportTypeLabel() . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        $tempFile = tempnam(sys_get_temp_dir(), 'phpspreadsheet');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempFile);
        
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Add report content to Word document
     */
    private function addReportContentToWord($section, $financialReport)
    {
        $data = $financialReport->report_data;
        
        switch ($financialReport->report_type) {
            case 'income_statement':
                $this->addIncomeStatementToWord($section, $data);
                break;
            case 'balance_sheet':
                $this->addBalanceSheetToWord($section, $data);
                break;
            case 'cash_flow':
                $this->addCashFlowToWord($section, $data);
                break;
            case 'trial_balance':
                $this->addTrialBalanceToWord($section, $data);
                break;
            default:
                $section->addText('Tipe laporan tidak didukung untuk ekspor DOCX.');
        }
    }

    /**
     * Add report content to Excel sheet
     */
    private function addReportContentToExcel($sheet, $financialReport)
    {
        $data = $financialReport->report_data;
        
        switch ($financialReport->report_type) {
            case 'income_statement':
                $this->addIncomeStatementToExcel($sheet, $data);
                break;
            case 'balance_sheet':
                $this->addBalanceSheetToExcel($sheet, $data);
                break;
            case 'cash_flow':
                $this->addCashFlowToExcel($sheet, $data);
                break;
            case 'trial_balance':
                $this->addTrialBalanceToExcel($sheet, $data);
                break;
            default:
                $sheet->setCellValue('A5', 'Tipe laporan tidak didukung untuk ekspor Excel.');
        }
    }

    /**
     * Add Income Statement content to Word
     */
    private function addIncomeStatementToWord($section, $data)
    {
        $tableStyle = ['borderSize' => 6, 'borderColor' => '000000'];
        $table = $section->addTable($tableStyle);
        
        // Header
        $table->addRow();
        $table->addCell(4000)->addText('Keterangan', ['bold' => true]);
        $table->addCell(2000)->addText('Jumlah', ['bold' => true]);
        
        // Revenues
        if (isset($data['revenues']) && is_iterable($data['revenues'])) {
            $table->addRow();
            $table->addCell(4000)->addText('PENDAPATAN', ['bold' => true]);
            $table->addCell(2000)->addText('');
            
            foreach ($data['revenues'] as $account => $amount) {
                $table->addRow();
                $table->addCell(4000)->addText($account);
                $table->addCell(2000)->addText(number_format(is_numeric($amount) ? $amount : 0, 0, ',', '.'));
            }
            
            $table->addRow();
            $table->addCell(4000)->addText('Total Pendapatan', ['bold' => true]);
            $table->addCell(2000)->addText(number_format(is_numeric($data['total_revenue'] ?? 0) ? $data['total_revenue'] : 0, 0, ',', '.'), ['bold' => true]);
        }
        
        // Expenses
        if (isset($data['expenses']) && is_iterable($data['expenses'])) {
            $table->addRow();
            $table->addCell(4000)->addText('BEBAN', ['bold' => true]);
            $table->addCell(2000)->addText('');
            
            foreach ($data['expenses'] as $account => $amount) {
                $table->addRow();
                $table->addCell(4000)->addText($account);
                $table->addCell(2000)->addText(number_format(is_numeric($amount) ? $amount : 0, 0, ',', '.'));
            }
            
            $table->addRow();
            $table->addCell(4000)->addText('Total Beban', ['bold' => true]);
            $table->addCell(2000)->addText(number_format(is_numeric($data['total_expenses'] ?? 0) ? $data['total_expenses'] : 0, 0, ',', '.'), ['bold' => true]);
        }
        
        // Net Income
        $table->addRow();
        $table->addCell(4000)->addText('LABA BERSIH', ['bold' => true]);
        $table->addCell(2000)->addText(number_format(is_numeric($data['net_income'] ?? 0) ? $data['net_income'] : 0, 0, ',', '.'), ['bold' => true]);
    }

    /**
     * Add Income Statement content to Excel
     */
    private function addIncomeStatementToExcel($sheet, $data)
    {
        $row = 5;
        
        // Headers
        $sheet->setCellValue('A' . $row, 'Keterangan');
        $sheet->setCellValue('B' . $row, 'Jumlah');
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        $row++;
        
        // Revenues
        if (isset($data['revenues']) && is_iterable($data['revenues'])) {
            $sheet->setCellValue('A' . $row, 'PENDAPATAN');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;
            
            foreach ($data['revenues'] as $account => $amount) {
                $sheet->setCellValue('A' . $row, $account);
                $sheet->setCellValue('B' . $row, is_numeric($amount) ? $amount : 0);
                $row++;
            }
            
            $sheet->setCellValue('A' . $row, 'Total Pendapatan');
            $sheet->setCellValue('B' . $row, is_numeric($data['total_revenue'] ?? 0) ? $data['total_revenue'] : 0);
            $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
            $row += 2;
        }
        
        // Expenses
        if (isset($data['expenses']) && is_iterable($data['expenses'])) {
            $sheet->setCellValue('A' . $row, 'BEBAN');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;
            
            foreach ($data['expenses'] as $account => $amount) {
                $sheet->setCellValue('A' . $row, $account);
                $sheet->setCellValue('B' . $row, is_numeric($amount) ? $amount : 0);
                $row++;
            }
            
            $sheet->setCellValue('A' . $row, 'Total Beban');
            $sheet->setCellValue('B' . $row, is_numeric($data['total_expenses'] ?? 0) ? $data['total_expenses'] : 0);
            $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
            $row += 2;
        }
        
        // Net Income
        $sheet->setCellValue('A' . $row, 'LABA BERSIH');
        $sheet->setCellValue('B' . $row, is_numeric($data['net_income'] ?? 0) ? $data['net_income'] : 0);
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
    }

    /**
     * Add Balance Sheet content to Word
     */
    private function addBalanceSheetToWord($section, $data)
    {
        $tableStyle = ['borderSize' => 6, 'borderColor' => '000000'];
        $table = $section->addTable($tableStyle);
        
        // Header
        $table->addRow();
        $table->addCell(4000)->addText('Keterangan', ['bold' => true]);
        $table->addCell(2000)->addText('Jumlah', ['bold' => true]);
        
        // Assets
        if (isset($data['assets']) && is_iterable($data['assets'])) {
            $table->addRow();
            $table->addCell(4000)->addText('ASET', ['bold' => true]);
            $table->addCell(2000)->addText('');
            
            foreach ($data['assets'] as $account => $amount) {
                $table->addRow();
                $table->addCell(4000)->addText($account);
                $table->addCell(2000)->addText(number_format(is_numeric($amount) ? $amount : 0, 0, ',', '.'));
            }
            
            $table->addRow();
            $table->addCell(4000)->addText('Total Aset', ['bold' => true]);
            $table->addCell(2000)->addText(number_format(is_numeric($data['total_assets'] ?? 0) ? $data['total_assets'] : 0, 0, ',', '.'), ['bold' => true]);
        }
        
        // Liabilities
        if (isset($data['liabilities']) && is_iterable($data['liabilities'])) {
            $table->addRow();
            $table->addCell(4000)->addText('KEWAJIBAN', ['bold' => true]);
            $table->addCell(2000)->addText('');
            
            foreach ($data['liabilities'] as $account => $amount) {
                $table->addRow();
                $table->addCell(4000)->addText($account);
                $table->addCell(2000)->addText(number_format(is_numeric($amount) ? $amount : 0, 0, ',', '.'));
            }
            
            $table->addRow();
            $table->addCell(4000)->addText('Total Kewajiban', ['bold' => true]);
            $table->addCell(2000)->addText(number_format(is_numeric($data['total_liabilities'] ?? 0) ? $data['total_liabilities'] : 0, 0, ',', '.'), ['bold' => true]);
        }
        
        // Equity
        if (isset($data['equity']) && is_iterable($data['equity'])) {
            $table->addRow();
            $table->addCell(4000)->addText('MODAL', ['bold' => true]);
            $table->addCell(2000)->addText('');
            
            foreach ($data['equity'] as $account => $amount) {
                $table->addRow();
                $table->addCell(4000)->addText($account);
                $table->addCell(2000)->addText(number_format(is_numeric($amount) ? $amount : 0, 0, ',', '.'));
            }
            
            $table->addRow();
            $table->addCell(4000)->addText('Total Modal', ['bold' => true]);
            $table->addCell(2000)->addText(number_format(is_numeric($data['total_equity'] ?? 0) ? $data['total_equity'] : 0, 0, ',', '.'), ['bold' => true]);
        }
    }

    /**
     * Add Balance Sheet content to Excel
     */
    private function addBalanceSheetToExcel($sheet, $data)
    {
        $row = 5;
        
        // Headers
        $sheet->setCellValue('A' . $row, 'Keterangan');
        $sheet->setCellValue('B' . $row, 'Jumlah');
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        $row++;
        
        // Assets
        if (isset($data['assets']) && is_iterable($data['assets'])) {
            $sheet->setCellValue('A' . $row, 'ASET');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;
            
            foreach ($data['assets'] as $account => $amount) {
                $sheet->setCellValue('A' . $row, $account);
                $sheet->setCellValue('B' . $row, is_numeric($amount) ? $amount : 0);
                $row++;
            }
            
            $sheet->setCellValue('A' . $row, 'Total Aset');
            $sheet->setCellValue('B' . $row, is_numeric($data['total_assets'] ?? 0) ? $data['total_assets'] : 0);
            $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
            $row += 2;
        }
        
        // Liabilities
        if (isset($data['liabilities']) && is_iterable($data['liabilities'])) {
            $sheet->setCellValue('A' . $row, 'KEWAJIBAN');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;
            
            foreach ($data['liabilities'] as $account => $amount) {
                $sheet->setCellValue('A' . $row, $account);
                $sheet->setCellValue('B' . $row, is_numeric($amount) ? $amount : 0);
                $row++;
            }
            
            $sheet->setCellValue('A' . $row, 'Total Kewajiban');
            $sheet->setCellValue('B' . $row, is_numeric($data['total_liabilities'] ?? 0) ? $data['total_liabilities'] : 0);
            $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
            $row += 2;
        }
        
        // Equity
        if (isset($data['equity']) && is_iterable($data['equity'])) {
            $sheet->setCellValue('A' . $row, 'MODAL');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;
            
            foreach ($data['equity'] as $account => $amount) {
                $sheet->setCellValue('A' . $row, $account);
                $sheet->setCellValue('B' . $row, is_numeric($amount) ? $amount : 0);
                $row++;
            }
            
            $sheet->setCellValue('A' . $row, 'Total Modal');
            $sheet->setCellValue('B' . $row, is_numeric($data['total_equity'] ?? 0) ? $data['total_equity'] : 0);
            $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        }
    }

    /**
     * Add Cash Flow content to Word
     */
    private function addCashFlowToWord($section, $data)
    {
        $tableStyle = ['borderSize' => 6, 'borderColor' => '000000'];
        $table = $section->addTable($tableStyle);
        
        // Header
        $table->addRow();
        $table->addCell(4000)->addText('Keterangan', ['bold' => true]);
        $table->addCell(2000)->addText('Jumlah', ['bold' => true]);
        
        // Operating Activities
        if (isset($data['operating_activities'])) {
            $table->addRow();
            $table->addCell(4000)->addText('AKTIVITAS OPERASIONAL', ['bold' => true]);
            $table->addCell(2000)->addText('');
            
            foreach ($data['operating_activities'] as $activity) {
                $table->addRow();
                $table->addCell(4000)->addText($activity['description']);
                $table->addCell(2000)->addText(number_format($activity['amount'], 0, ',', '.'));
            }
            
            $table->addRow();
            $table->addCell(4000)->addText('Kas Bersih dari Aktivitas Operasional', ['bold' => true]);
            $table->addCell(2000)->addText(number_format($data['net_operating_cash'] ?? 0, 0, ',', '.'), ['bold' => true]);
        }
        
        // Add other cash flow sections similarly...
    }

    /**
     * Add Cash Flow content to Excel
     */
    private function addCashFlowToExcel($sheet, $data)
    {
        $row = 5;
        
        // Headers
        $sheet->setCellValue('A' . $row, 'Keterangan');
        $sheet->setCellValue('B' . $row, 'Jumlah');
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        $row++;
        
        // Operating Activities
        if (isset($data['operating_activities'])) {
            $sheet->setCellValue('A' . $row, 'AKTIVITAS OPERASIONAL');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;
            
            foreach ($data['operating_activities'] as $activity) {
                $sheet->setCellValue('A' . $row, $activity['description']);
                $sheet->setCellValue('B' . $row, $activity['amount']);
                $row++;
            }
            
            $sheet->setCellValue('A' . $row, 'Kas Bersih dari Aktivitas Operasional');
            $sheet->setCellValue('B' . $row, $data['net_operating_cash'] ?? 0);
            $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
            $row += 2;
        }
        
        // Add other cash flow sections similarly...
    }

    /**
     * Add Trial Balance content to Word
     */
    private function addTrialBalanceToWord($section, $data)
    {
        $tableStyle = ['borderSize' => 6, 'borderColor' => '000000'];
        $table = $section->addTable($tableStyle);
        
        // Header
        $table->addRow();
        $table->addCell(3000)->addText('Nama Akun', ['bold' => true]);
        $table->addCell(1500)->addText('Debit', ['bold' => true]);
        $table->addCell(1500)->addText('Kredit', ['bold' => true]);
        
        if (isset($data['accounts'])) {
            foreach ($data['accounts'] as $account) {
                $table->addRow();
                $table->addCell(3000)->addText($account['name']);
                $table->addCell(1500)->addText(number_format($account['debit'] ?? 0, 0, ',', '.'));
                $table->addCell(1500)->addText(number_format($account['credit'] ?? 0, 0, ',', '.'));
            }
        }
        
        // Totals
        $table->addRow();
        $table->addCell(3000)->addText('TOTAL', ['bold' => true]);
        $table->addCell(1500)->addText(number_format($data['total_debit'] ?? 0, 0, ',', '.'), ['bold' => true]);
        $table->addCell(1500)->addText(number_format($data['total_credit'] ?? 0, 0, ',', '.'), ['bold' => true]);
    }

    /**
     * Add Trial Balance content to Excel
     */
    private function addTrialBalanceToExcel($sheet, $data)
    {
        $row = 5;
        
        // Headers
        $sheet->setCellValue('A' . $row, 'Nama Akun');
        $sheet->setCellValue('B' . $row, 'Debit');
        $sheet->setCellValue('C' . $row, 'Kredit');
        $sheet->getStyle('A' . $row . ':C' . $row)->getFont()->setBold(true);
        $row++;
        
        if (isset($data['accounts'])) {
            foreach ($data['accounts'] as $account) {
                $sheet->setCellValue('A' . $row, $account['name']);
                $sheet->setCellValue('B' . $row, $account['debit'] ?? 0);
                $sheet->setCellValue('C' . $row, $account['credit'] ?? 0);
                $row++;
            }
        }
        
        // Totals
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->setCellValue('B' . $row, $data['total_debit'] ?? 0);
        $sheet->setCellValue('C' . $row, $data['total_credit'] ?? 0);
        $sheet->getStyle('A' . $row . ':C' . $row)->getFont()->setBold(true);
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

    /**
     * Show annual report form
     */
    public function annualReport()
    {
        return view('financial-reports.annual-report');
    }

    /**
     * Generate annual report
     */
    public function generateAnnualReport(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'cover_title' => 'nullable|string|max:255',
            'accountability_text' => 'nullable|string',
            'pages' => 'nullable|array',
            'pages.*.title' => 'required_with:pages.*.content|string|max:255',
            'pages.*.content' => 'nullable|string',
            'pages.*.type' => 'nullable|string|in:content,introduction,profile,summary,conclusion,appendix',
            'pages.*.show_in_toc' => 'nullable|boolean',
            'pages.*.new_page' => 'nullable|boolean'
        ]);

        $year = $request->year;
        $periodStart = $year . '-01-01';
        $periodEnd = $year . '-12-31';
        $asOfDate = $periodEnd;

        // Generate all financial reports data
        $incomeStatement = $this->generateIncomeStatementData($periodStart, $periodEnd);
        $balanceSheet = $this->generateBalanceSheetData($asOfDate);
        $cashFlow = $this->generateCashFlowData($periodStart, $periodEnd);
        $generalLedger = $this->generateGeneralLedgerData($periodStart, $periodEnd);
        $trialBalance = $this->generateTrialBalanceData($asOfDate);

        // Get company information
        $companyInfo = company_info();

        // Prepare data for the view
        $data = [
            'year' => $year,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'as_of_date' => $asOfDate,
            'company_info' => $companyInfo,
            'cover_title' => $request->cover_title ?: "Laporan Keuangan Tahunan {$year}",
            'accountability_text' => $request->accountability_text,
            'pages' => $request->pages ?: [],
            'income_statement' => $incomeStatement,
            'balance_sheet' => $balanceSheet,
            'cash_flow' => $cashFlow,
            'general_ledger' => $generalLedger,
            'trial_balance' => $trialBalance,
            'generated_at' => now(),
            'generated_by' => Auth::user()
        ];

        if ($request->has('preview')) {
            return view('financial-reports.annual-report-preview', $data);
        }

        if ($request->has('export_docx')) {
            return $this->generateAnnualReportDocx($data);
        }

        // Generate PDF
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('financial-reports.annual-report-pdf', $data);
        $pdf->setPaper('letter', 'portrait');
        
        $filename = "Laporan_Keuangan_Tahunan_{$year}_" . date('Y-m-d_H-i-s') . ".pdf";
        
        return $pdf->download($filename);
    }

    /**
     * Generate annual report in DOCX format
     */
    private function generateAnnualReportDocx($data)
    {
        // Import PhpWord classes
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        // Set document properties
        $properties = $phpWord->getDocInfo();
        $properties->setCreator($data['company_info']['name'] ?? 'BUMDES');
        $properties->setCompany($data['company_info']['name'] ?? 'BUMDES');
        $properties->setTitle($data['cover_title']);
        $properties->setDescription('Laporan Keuangan Tahunan ' . $data['year']);
        $properties->setCategory('Financial Report');
        $properties->setLastModifiedBy($data['generated_by']->name);
        $properties->setCreated(time());
        $properties->setModified(time());

        // Define styles
        $phpWord->addTitleStyle(1, ['size' => 16, 'bold' => true], ['alignment' => 'center']);
        $phpWord->addTitleStyle(2, ['size' => 14, 'bold' => true]);
        $phpWord->addTitleStyle(3, ['size' => 12, 'bold' => true]);
        
        $headerStyle = ['size' => 12, 'bold' => true];
        $normalStyle = ['size' => 11];
        $tableHeaderStyle = ['size' => 10, 'bold' => true];
        $tableDataStyle = ['size' => 10];

        // Create section
        $section = $phpWord->addSection([
            'marginTop' => 1440,    // 1 inch
            'marginBottom' => 1440, // 1 inch
            'marginLeft' => 1440,   // 1 inch
            'marginRight' => 1440,  // 1 inch
        ]);

        // Cover Page
        $section->addTitle($data['cover_title'], 1);
        $section->addTextBreak(2);
        
        $section->addText($data['company_info']['name'] ?? 'BUMDES', $headerStyle, ['alignment' => 'center']);
        if (!empty($data['company_info']['address'])) {
            $section->addText($data['company_info']['address'], $normalStyle, ['alignment' => 'center']);
        }
        $section->addTextBreak(2);
        
        $section->addText('Tahun ' . $data['year'], $headerStyle, ['alignment' => 'center']);
        $section->addPageBreak();

        // Table of Contents
        $section->addTitle('Daftar Isi', 2);
        $section->addTextBreak();
        
        $tocItems = [
            'Lembar Pertanggungjawaban',
        ];
        
        // Add pages to TOC
        foreach ($data['pages'] as $index => $page) {
            if (!empty($page['title']) && ($page['show_in_toc'] ?? true)) {
                $pageNumber = $index + 1;
                $pageTitle = $page['title'];
                
                // Add page type prefix if specified
                if (!empty($page['type']) && $page['type'] !== 'content') {
                    switch ($page['type']) {
                        case 'introduction':
                            $pageTitle = 'Pendahuluan: ' . $pageTitle;
                            break;
                        case 'profile':
                            $pageTitle = 'Profil: ' . $pageTitle;
                            break;
                        case 'summary':
                            $pageTitle = 'Ringkasan: ' . $pageTitle;
                            break;
                        case 'conclusion':
                            $pageTitle = 'Kesimpulan: ' . $pageTitle;
                            break;
                        case 'appendix':
                            $pageTitle = 'Lampiran: ' . $pageTitle;
                            break;
                    }
                }
                
                $tocItems[] = $pageNumber . '. ' . $pageTitle;
            }
        }
        
        $tocItems = array_merge($tocItems, [
            'Laporan Laba Rugi',
            'Neraca',
            'Laporan Arus Kas',
            'Buku Besar',
            'Neraca Saldo'
        ]);
        
        foreach ($tocItems as $item) {
            $section->addText($item, $normalStyle);
        }
        $section->addPageBreak();

        // Accountability Text
        $section->addTitle('Lembar Pertanggungjawaban', 2);
        $section->addTextBreak();
        
        if (!empty($data['accountability_text'])) {
            // Strip HTML tags and add as text
            $cleanText = strip_tags($data['accountability_text']);
            $section->addText($cleanText, $normalStyle, ['alignment' => 'justify']);
        }
        $section->addPageBreak();

        // Custom Pages
        foreach ($data['pages'] as $index => $page) {
            if (!empty($page['title'])) {
                // Add page break if specified
                if (($page['new_page'] ?? true) && $index > 0) {
                    $section->addPageBreak();
                }
                
                $pageNumber = $index + 1;
                $pageTitle = $page['title'];
                
                // Add page type prefix if specified
                if (!empty($page['type']) && $page['type'] !== 'content') {
                    switch ($page['type']) {
                        case 'introduction':
                            $pageTitle = 'Pendahuluan: ' . $pageTitle;
                            break;
                        case 'profile':
                            $pageTitle = 'Profil: ' . $pageTitle;
                            break;
                        case 'summary':
                            $pageTitle = 'Ringkasan: ' . $pageTitle;
                            break;
                        case 'conclusion':
                            $pageTitle = 'Kesimpulan: ' . $pageTitle;
                            break;
                        case 'appendix':
                            $pageTitle = 'Lampiran: ' . $pageTitle;
                            break;
                    }
                }
                
                $section->addTitle($pageNumber . '. ' . $pageTitle, 2);
                $section->addTextBreak();
                
                if (!empty($page['content'])) {
                    // Strip HTML tags and add as text
                    $cleanContent = strip_tags($page['content']);
                    $section->addText($cleanContent, $normalStyle, ['alignment' => 'justify']);
                }
                
                // Add page break after content if not the last page
                if ($index < count($data['pages']) - 1 && ($page['new_page'] ?? true)) {
                    $section->addPageBreak();
                }
            }
        }

        // Income Statement
        $section->addTitle('Laporan Laba Rugi', 2);
        $section->addText('Periode: ' . date('d F Y', strtotime($data['period_start'])) . ' s/d ' . date('d F Y', strtotime($data['period_end'])), $normalStyle);
        $section->addTextBreak();
        
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
        $table->addRow();
        $table->addCell(4000)->addText('Keterangan', $tableHeaderStyle);
        $table->addCell(2000)->addText('Jumlah (Rp)', $tableHeaderStyle);
        
        // Add income statement data
        if (isset($data['income_statement']['revenues'])) {
            $table->addRow();
            $table->addCell(4000)->addText('PENDAPATAN', $tableHeaderStyle);
            $table->addCell(2000)->addText('', $tableHeaderStyle);
            
            foreach ($data['income_statement']['revenues'] as $accountName => $amount) {
                $table->addRow();
                $table->addCell(4000)->addText($accountName, $tableDataStyle);
                $table->addCell(2000)->addText(number_format($amount, 0, ',', '.'), $tableDataStyle);
            }
            
            $table->addRow();
            $table->addCell(4000)->addText('Total Pendapatan', $tableHeaderStyle);
            $table->addCell(2000)->addText(number_format($data['income_statement']['total_revenue'], 0, ',', '.'), $tableHeaderStyle);
        }
        
        if (isset($data['income_statement']['expenses'])) {
            $table->addRow();
            $table->addCell(4000)->addText('BEBAN', $tableHeaderStyle);
            $table->addCell(2000)->addText('', $tableHeaderStyle);
            
            foreach ($data['income_statement']['expenses'] as $accountName => $amount) {
                $table->addRow();
                $table->addCell(4000)->addText($accountName, $tableDataStyle);
                $table->addCell(2000)->addText(number_format($amount, 0, ',', '.'), $tableDataStyle);
            }
            
            $table->addRow();
            $table->addCell(4000)->addText('Total Beban', $tableHeaderStyle);
            $table->addCell(2000)->addText(number_format($data['income_statement']['total_expenses'], 0, ',', '.'), $tableHeaderStyle);
        }
        
        if (isset($data['income_statement']['net_income'])) {
            $table->addRow();
            $table->addCell(4000)->addText('LABA BERSIH', $tableHeaderStyle);
            $table->addCell(2000)->addText(number_format($data['income_statement']['net_income'], 0, ',', '.'), $tableHeaderStyle);
        }
        
        $section->addPageBreak();

        // Balance Sheet
        $section->addTitle('Neraca', 2);
        $section->addText('Per: ' . date('d F Y', strtotime($data['as_of_date'])), $normalStyle);
        $section->addTextBreak();
        
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
        $table->addRow();
        $table->addCell(4000)->addText('Akun', $tableHeaderStyle);
        $table->addCell(2000)->addText('Jumlah (Rp)', $tableHeaderStyle);
        
        // Add balance sheet data
        if (isset($data['balance_sheet']['assets'])) {
            $table->addRow();
            $table->addCell(4000)->addText('ASET', $tableHeaderStyle);
            $table->addCell(2000)->addText('', $tableHeaderStyle);
            
            foreach ($data['balance_sheet']['assets'] as $accountName => $amount) {
                $table->addRow();
                $table->addCell(4000)->addText($accountName, $tableDataStyle);
                $table->addCell(2000)->addText(number_format($amount, 0, ',', '.'), $tableDataStyle);
            }
            
            $table->addRow();
            $table->addCell(4000)->addText('Total Aset', $tableHeaderStyle);
            $table->addCell(2000)->addText(number_format($data['balance_sheet']['total_assets'], 0, ',', '.'), $tableHeaderStyle);
        }
        
        if (isset($data['balance_sheet']['liabilities'])) {
            $table->addRow();
            $table->addCell(4000)->addText('KEWAJIBAN', $tableHeaderStyle);
            $table->addCell(2000)->addText('', $tableHeaderStyle);
            
            foreach ($data['balance_sheet']['liabilities'] as $accountName => $amount) {
                $table->addRow();
                $table->addCell(4000)->addText($accountName, $tableDataStyle);
                $table->addCell(2000)->addText(number_format($amount, 0, ',', '.'), $tableDataStyle);
            }
            
            $table->addRow();
            $table->addCell(4000)->addText('Total Kewajiban', $tableHeaderStyle);
            $table->addCell(2000)->addText(number_format($data['balance_sheet']['total_liabilities'], 0, ',', '.'), $tableHeaderStyle);
        }
        
        if (isset($data['balance_sheet']['equity'])) {
            $table->addRow();
            $table->addCell(4000)->addText('MODAL', $tableHeaderStyle);
            $table->addCell(2000)->addText('', $tableHeaderStyle);
            
            foreach ($data['balance_sheet']['equity'] as $accountName => $amount) {
                $table->addRow();
                $table->addCell(4000)->addText($accountName, $tableDataStyle);
                $table->addCell(2000)->addText(number_format($amount, 0, ',', '.'), $tableDataStyle);
            }
            
            $table->addRow();
            $table->addCell(4000)->addText('Total Modal', $tableHeaderStyle);
            $table->addCell(2000)->addText(number_format($data['balance_sheet']['total_equity'], 0, ',', '.'), $tableHeaderStyle);
        }
        
        $section->addPageBreak();

        // Cash Flow Statement
        $section->addTitle('Laporan Arus Kas', 2);
        $section->addText('Periode: ' . date('d F Y', strtotime($data['period_start'])) . ' s/d ' . date('d F Y', strtotime($data['period_end'])), $normalStyle);
        $section->addTextBreak();
        
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
        $table->addRow();
        $table->addCell(4000)->addText('Aktivitas', $tableHeaderStyle);
        $table->addCell(2000)->addText('Jumlah (Rp)', $tableHeaderStyle);
        
        // Add cash flow data
        if (isset($data['cash_flow']['operating_activities'])) {
            $table->addRow();
            $table->addCell(4000)->addText('Aktivitas Operasi', $tableHeaderStyle);
            $table->addCell(2000)->addText('', $tableHeaderStyle);
            
            foreach ($data['cash_flow']['operating_activities'] as $activity) {
                $table->addRow();
                $table->addCell(4000)->addText($activity['description'], $tableDataStyle);
                $table->addCell(2000)->addText(number_format($activity['amount'], 0, ',', '.'), $tableDataStyle);
            }
            
            $table->addRow();
            $table->addCell(4000)->addText('Kas Bersih dari Aktivitas Operasi', $tableHeaderStyle);
            $table->addCell(2000)->addText(number_format($data['cash_flow']['net_operating_cash'], 0, ',', '.'), $tableHeaderStyle);
        }
        
        if (isset($data['cash_flow']['investing_activities'])) {
            $table->addRow();
            $table->addCell(4000)->addText('Aktivitas Investasi', $tableHeaderStyle);
            $table->addCell(2000)->addText('', $tableHeaderStyle);
            
            foreach ($data['cash_flow']['investing_activities'] as $activity) {
                $table->addRow();
                $table->addCell(4000)->addText($activity['description'], $tableDataStyle);
                $table->addCell(2000)->addText(number_format($activity['amount'], 0, ',', '.'), $tableDataStyle);
            }
            
            $table->addRow();
            $table->addCell(4000)->addText('Kas Bersih dari Aktivitas Investasi', $tableHeaderStyle);
            $table->addCell(2000)->addText(number_format($data['cash_flow']['net_investing_cash'], 0, ',', '.'), $tableHeaderStyle);
        }
        
        if (isset($data['cash_flow']['financing_activities'])) {
            $table->addRow();
            $table->addCell(4000)->addText('Aktivitas Pendanaan', $tableHeaderStyle);
            $table->addCell(2000)->addText('', $tableHeaderStyle);
            
            foreach ($data['cash_flow']['financing_activities'] as $activity) {
                $table->addRow();
                $table->addCell(4000)->addText($activity['description'], $tableDataStyle);
                $table->addCell(2000)->addText(number_format($activity['amount'], 0, ',', '.'), $tableDataStyle);
            }
            
            $table->addRow();
            $table->addCell(4000)->addText('Kas Bersih dari Aktivitas Pendanaan', $tableHeaderStyle);
            $table->addCell(2000)->addText(number_format($data['cash_flow']['net_financing_cash'], 0, ',', '.'), $tableHeaderStyle);
        }
        
        if (isset($data['cash_flow']['net_cash_change'])) {
            $table->addRow();
            $table->addCell(4000)->addText('Kenaikan (Penurunan) Bersih Kas', $tableHeaderStyle);
            $table->addCell(2000)->addText(number_format($data['cash_flow']['net_cash_change'], 0, ',', '.'), $tableHeaderStyle);
            
            $table->addRow();
            $table->addCell(4000)->addText('Kas Awal Periode', $tableHeaderStyle);
            $table->addCell(2000)->addText(number_format($data['cash_flow']['beginning_cash'], 0, ',', '.'), $tableHeaderStyle);
            
            $table->addRow();
            $table->addCell(4000)->addText('Kas Akhir Periode', $tableHeaderStyle);
            $table->addCell(2000)->addText(number_format($data['cash_flow']['ending_cash'], 0, ',', '.'), $tableHeaderStyle);
        }

        $section->addPageBreak();

        // Trial Balance
        $section->addTitle('Neraca Saldo', 2);
        $section->addText('Per: ' . date('d F Y', strtotime($data['as_of_date'])), $normalStyle);
        $section->addTextBreak();
        
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
        $table->addRow();
        $table->addCell(3000)->addText('Nama Akun', $tableHeaderStyle);
        $table->addCell(1500)->addText('Debit (Rp)', $tableHeaderStyle);
        $table->addCell(1500)->addText('Kredit (Rp)', $tableHeaderStyle);
        
        $totalDebit = 0;
        $totalCredit = 0;
        
        if (isset($data['trial_balance']) && is_array($data['trial_balance'])) {
            foreach ($data['trial_balance'] as $account) {
                $table->addRow();
                $table->addCell(3000)->addText($account['account_name'], $tableDataStyle);
                $table->addCell(1500)->addText(number_format($account['debit'], 0, ',', '.'), $tableDataStyle);
                $table->addCell(1500)->addText(number_format($account['credit'], 0, ',', '.'), $tableDataStyle);
                
                $totalDebit += $account['debit'];
                $totalCredit += $account['credit'];
            }
        }
        
        // Add totals
        $table->addRow();
        $table->addCell(3000)->addText('TOTAL', $tableHeaderStyle);
        $table->addCell(1500)->addText(number_format($totalDebit, 0, ',', '.'), $tableHeaderStyle);
        $table->addCell(1500)->addText(number_format($totalCredit, 0, ',', '.'), $tableHeaderStyle);

        $section->addPageBreak();

        // General Ledger
        $section->addTitle('Buku Besar', 2);
        $section->addText('Periode: ' . date('d F Y', strtotime($data['period_start'])) . ' s/d ' . date('d F Y', strtotime($data['period_end'])), $normalStyle);
        $section->addTextBreak();
        
        if (isset($data['general_ledger']) && is_array($data['general_ledger'])) {
            foreach ($data['general_ledger'] as $accountName => $entries) {
                $section->addTitle($accountName, 3);
                
                $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
                $table->addRow();
                $table->addCell(1500)->addText('Tanggal', $tableHeaderStyle);
                $table->addCell(2000)->addText('Keterangan', $tableHeaderStyle);
                $table->addCell(1200)->addText('Debit (Rp)', $tableHeaderStyle);
                $table->addCell(1200)->addText('Kredit (Rp)', $tableHeaderStyle);
                $table->addCell(1200)->addText('Saldo (Rp)', $tableHeaderStyle);
                
                $runningBalance = 0;
                
                foreach ($entries as $entry) {
                    $table->addRow();
                    $table->addCell(1500)->addText(date('d/m/Y', strtotime($entry['tanggal_posting'])), $tableDataStyle);
                    $table->addCell(2000)->addText($entry['keterangan'], $tableDataStyle);
                    $table->addCell(1200)->addText(number_format($entry['debit'], 0, ',', '.'), $tableDataStyle);
                    $table->addCell(1200)->addText(number_format($entry['kredit'], 0, ',', '.'), $tableDataStyle);
                    
                    $runningBalance += $entry['debit'] - $entry['kredit'];
                    $table->addCell(1200)->addText(number_format($runningBalance, 0, ',', '.'), $tableDataStyle);
                }
                
                $section->addTextBreak();
            }
        }

        // Generate filename
        $filename = "Laporan_Keuangan_Tahunan_{$data['year']}_" . date('Y-m-d_H-i-s') . ".docx";
        
        // Save to temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'annual_report_');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);
        
        // Return download response
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    // ========== INCOME STATEMENT EXPORTS ==========

    /**
     * Export Income Statement to PDF
     */
    public function exportIncomeStatementPdf(Request $request)
    {
        $periodStart = $request->period_start ? Carbon::parse($request->period_start) : Carbon::now()->startOfMonth();
        $periodEnd = $request->period_end ? Carbon::parse($request->period_end) : Carbon::now()->endOfMonth();
        
        $reportData = $this->generateIncomeStatementData($periodStart, $periodEnd);
        $companyInfo = company_info();
        
        $data = [
            'reportData' => $reportData,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'company_info' => $companyInfo,
            'title' => 'Laporan Laba Rugi'
        ];
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('financial-reports.exports.income-statement-pdf', $data);
        $pdf->setPaper('a4', 'portrait');
        
        $filename = "Laporan_Laba_Rugi_" . $periodStart->format('Y-m-d') . "_to_" . $periodEnd->format('Y-m-d') . ".pdf";
        
        return $pdf->download($filename);
    }

    /**
     * Export Income Statement to DOCX
     */
    public function exportIncomeStatementDocx(Request $request)
    {
        $periodStart = $request->period_start ? Carbon::parse($request->period_start) : Carbon::now()->startOfMonth();
        $periodEnd = $request->period_end ? Carbon::parse($request->period_end) : Carbon::now()->endOfMonth();
        
        $reportData = $this->generateIncomeStatementData($periodStart, $periodEnd);
        $companyInfo = company_info();
        
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        // Set document properties
        $properties = $phpWord->getDocInfo();
        $properties->setCreator($companyInfo['name'] ?? 'BUMDES');
        $properties->setCompany($companyInfo['name'] ?? 'BUMDES');
        $properties->setTitle('Laporan Laba Rugi');
        $properties->setDescription('Laporan Laba Rugi periode ' . $periodStart->format('d F Y') . ' s/d ' . $periodEnd->format('d F Y'));
        
        // Define styles
        $phpWord->addTitleStyle(1, ['size' => 16, 'bold' => true], ['alignment' => 'center']);
        $phpWord->addTitleStyle(2, ['size' => 14, 'bold' => true]);
        
        $tableHeaderStyle = ['bold' => true, 'size' => 11];
        $tableDataStyle = ['size' => 10];
        $normalStyle = ['size' => 10];
        
        // Create section
        $section = $phpWord->addSection([
            'marginLeft' => 1134,
            'marginRight' => 1134,
            'marginTop' => 1134,
            'marginBottom' => 1134,
        ]);
        
        // Header
        $section->addTitle($companyInfo['name'] ?? 'BUMDES', 1);
        $section->addTitle('Laporan Laba Rugi', 2);
        $section->addText('Periode: ' . $periodStart->format('d F Y') . ' s/d ' . $periodEnd->format('d F Y'), $normalStyle);
        $section->addTextBreak();
        
        // Create table
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
        $table->addRow();
        $table->addCell(4000)->addText('Keterangan', $tableHeaderStyle);
        $table->addCell(2000)->addText('Jumlah (Rp)', $tableHeaderStyle);
        
        // Revenue section
        $table->addRow();
        $table->addCell(4000)->addText('PENDAPATAN', $tableHeaderStyle);
        $table->addCell(2000)->addText('', $tableHeaderStyle);
        
        foreach ($reportData['revenues'] as $accountName => $amount) {
            $table->addRow();
            $table->addCell(4000)->addText($accountName, $tableDataStyle);
            $table->addCell(2000)->addText(number_format($amount, 0, ',', '.'), $tableDataStyle);
        }
        
        $table->addRow();
        $table->addCell(4000)->addText('Total Pendapatan', $tableHeaderStyle);
        $table->addCell(2000)->addText(number_format($reportData['total_revenue'], 0, ',', '.'), $tableHeaderStyle);
        
        // Expense section
        $table->addRow();
        $table->addCell(4000)->addText('BEBAN', $tableHeaderStyle);
        $table->addCell(2000)->addText('', $tableHeaderStyle);
        
        foreach ($reportData['expenses'] as $accountName => $amount) {
            $table->addRow();
            $table->addCell(4000)->addText($accountName, $tableDataStyle);
            $table->addCell(2000)->addText(number_format($amount, 0, ',', '.'), $tableDataStyle);
        }
        
        $table->addRow();
        $table->addCell(4000)->addText('Total Beban', $tableHeaderStyle);
        $table->addCell(2000)->addText(number_format($reportData['total_expenses'], 0, ',', '.'), $tableHeaderStyle);
        
        // Net Income
        $table->addRow();
        $table->addCell(4000)->addText('LABA (RUGI) BERSIH', $tableHeaderStyle);
        $table->addCell(2000)->addText(number_format($reportData['net_income'], 0, ',', '.'), $tableHeaderStyle);
        
        // Generate filename and save
        $filename = "Laporan_Laba_Rugi_" . $periodStart->format('Y-m-d') . "_to_" . $periodEnd->format('Y-m-d') . ".docx";
        $tempFile = tempnam(sys_get_temp_dir(), 'income_statement_');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);
        
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Export Income Statement to Excel
     */
    public function exportIncomeStatementExcel(Request $request)
    {
        $periodStart = $request->period_start ? Carbon::parse($request->period_start) : Carbon::now()->startOfMonth();
        $periodEnd = $request->period_end ? Carbon::parse($request->period_end) : Carbon::now()->endOfMonth();
        
        $reportData = $this->generateIncomeStatementData($periodStart, $periodEnd);
        $companyInfo = company_info();
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set title
        $sheet->setTitle('Laporan Laba Rugi');
        
        // Header
        $sheet->setCellValue('A1', $companyInfo['name'] ?? 'BUMDES');
        $sheet->setCellValue('A2', 'Laporan Laba Rugi');
        $sheet->setCellValue('A3', 'Periode: ' . $periodStart->format('d F Y') . ' s/d ' . $periodEnd->format('d F Y'));
        
        // Style header
        $sheet->getStyle('A1:B1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2:B2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3:B3')->getFont()->setSize(10);
        
        // Table headers
        $row = 5;
        $sheet->setCellValue('A' . $row, 'Keterangan');
        $sheet->setCellValue('B' . $row, 'Jumlah (Rp)');
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        
        $row++;
        
        // Revenue section
        $sheet->setCellValue('A' . $row, 'PENDAPATAN');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        foreach ($reportData['revenues'] as $accountName => $amount) {
            $sheet->setCellValue('A' . $row, $accountName);
            $sheet->setCellValue('B' . $row, $amount);
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $row++;
        }
        
        $sheet->setCellValue('A' . $row, 'Total Pendapatan');
        $sheet->setCellValue('B' . $row, $reportData['total_revenue']);
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $row += 2;
        
        // Expense section
        $sheet->setCellValue('A' . $row, 'BEBAN');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        foreach ($reportData['expenses'] as $accountName => $amount) {
            $sheet->setCellValue('A' . $row, $accountName);
            $sheet->setCellValue('B' . $row, $amount);
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $row++;
        }
        
        $sheet->setCellValue('A' . $row, 'Total Beban');
        $sheet->setCellValue('B' . $row, $reportData['total_expenses']);
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $row += 2;
        
        // Net Income
        $sheet->setCellValue('A' . $row, 'LABA (RUGI) BERSIH');
        $sheet->setCellValue('B' . $row, $reportData['net_income']);
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
        
        // Auto-size columns
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        
        // Generate filename and save
        $filename = "Laporan_Laba_Rugi_" . $periodStart->format('Y-m-d') . "_to_" . $periodEnd->format('Y-m-d') . ".xlsx";
        $tempFile = tempnam(sys_get_temp_dir(), 'income_statement_');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempFile);
        
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    // ========== BALANCE SHEET EXPORTS ==========

    /**
     * Export Balance Sheet to PDF
     */
    public function exportBalanceSheetPdf(Request $request)
    {
        $asOfDate = $request->as_of_date ? Carbon::parse($request->as_of_date) : Carbon::now();
        
        $reportData = $this->generateBalanceSheetData($asOfDate);
        $companyInfo = company_info();
        
        $data = [
            'reportData' => $reportData,
            'asOfDate' => $asOfDate,
            'company_info' => $companyInfo,
            'title' => 'Neraca'
        ];
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('financial-reports.exports.balance-sheet-pdf', $data);
        $pdf->setPaper('a4', 'portrait');
        
        $filename = "Neraca_" . $asOfDate->format('Y-m-d') . ".pdf";
        
        return $pdf->download($filename);
    }

    /**
     * Export Balance Sheet to DOCX
     */
    public function exportBalanceSheetDocx(Request $request)
    {
        $asOfDate = $request->as_of_date ? Carbon::parse($request->as_of_date) : Carbon::now();
        
        $reportData = $this->generateBalanceSheetData($asOfDate);
        $companyInfo = company_info();
        
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        // Set document properties
        $properties = $phpWord->getDocInfo();
        $properties->setCreator($companyInfo['name'] ?? 'BUMDES');
        $properties->setCompany($companyInfo['name'] ?? 'BUMDES');
        $properties->setTitle('Neraca');
        $properties->setDescription('Neraca per ' . $asOfDate->format('d F Y'));
        
        // Define styles
        $phpWord->addTitleStyle(1, ['size' => 16, 'bold' => true], ['alignment' => 'center']);
        $phpWord->addTitleStyle(2, ['size' => 14, 'bold' => true]);
        
        $tableHeaderStyle = ['bold' => true, 'size' => 11];
        $tableDataStyle = ['size' => 10];
        $normalStyle = ['size' => 10];
        
        // Create section
        $section = $phpWord->addSection([
            'marginLeft' => 1134,
            'marginRight' => 1134,
            'marginTop' => 1134,
            'marginBottom' => 1134,
        ]);
        
        // Header
        $section->addTitle($companyInfo['name'] ?? 'BUMDES', 1);
        $section->addTitle('Neraca', 2);
        $section->addText('Per: ' . $asOfDate->format('d F Y'), $normalStyle);
        $section->addTextBreak();
        
        // Create table
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
        $table->addRow();
        $table->addCell(4000)->addText('Keterangan', $tableHeaderStyle);
        $table->addCell(2000)->addText('Jumlah (Rp)', $tableHeaderStyle);
        
        // Assets section
        $table->addRow();
        $table->addCell(4000)->addText('ASET', $tableHeaderStyle);
        $table->addCell(2000)->addText('', $tableHeaderStyle);
        
        foreach ($reportData['assets'] as $accountName => $amount) {
            $table->addRow();
            $table->addCell(4000)->addText($accountName, $tableDataStyle);
            $table->addCell(2000)->addText(number_format($amount, 0, ',', '.'), $tableDataStyle);
        }
        
        $table->addRow();
        $table->addCell(4000)->addText('Total Aset', $tableHeaderStyle);
        $table->addCell(2000)->addText(number_format($reportData['total_assets'], 0, ',', '.'), $tableHeaderStyle);
        
        // Liabilities section
        $table->addRow();
        $table->addCell(4000)->addText('KEWAJIBAN', $tableHeaderStyle);
        $table->addCell(2000)->addText('', $tableHeaderStyle);
        
        foreach ($reportData['liabilities'] as $accountName => $amount) {
            $table->addRow();
            $table->addCell(4000)->addText($accountName, $tableDataStyle);
            $table->addCell(2000)->addText(number_format($amount, 0, ',', '.'), $tableDataStyle);
        }
        
        $table->addRow();
        $table->addCell(4000)->addText('Total Kewajiban', $tableHeaderStyle);
        $table->addCell(2000)->addText(number_format($reportData['total_liabilities'], 0, ',', '.'), $tableHeaderStyle);
        
        // Equity section
        $table->addRow();
        $table->addCell(4000)->addText('MODAL', $tableHeaderStyle);
        $table->addCell(2000)->addText('', $tableHeaderStyle);
        
        foreach ($reportData['equity'] as $accountName => $amount) {
            $table->addRow();
            $table->addCell(4000)->addText($accountName, $tableDataStyle);
            $table->addCell(2000)->addText(number_format($amount, 0, ',', '.'), $tableDataStyle);
        }
        
        $table->addRow();
        $table->addCell(4000)->addText('Total Modal', $tableHeaderStyle);
        $table->addCell(2000)->addText(number_format($reportData['total_equity'], 0, ',', '.'), $tableHeaderStyle);
        
        // Total Liabilities + Equity
        $table->addRow();
        $table->addCell(4000)->addText('TOTAL KEWAJIBAN + MODAL', $tableHeaderStyle);
        $table->addCell(2000)->addText(number_format($reportData['total_liabilities'] + $reportData['total_equity'], 0, ',', '.'), $tableHeaderStyle);
        
        // Generate filename and save
        $filename = "Neraca_" . $asOfDate->format('Y-m-d') . ".docx";
        $tempFile = tempnam(sys_get_temp_dir(), 'balance_sheet_');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);
        
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Export Balance Sheet to Excel
     */
    public function exportBalanceSheetExcel(Request $request)
    {
        $asOfDate = $request->as_of_date ? Carbon::parse($request->as_of_date) : Carbon::now();
        
        $reportData = $this->generateBalanceSheetData($asOfDate);
        $companyInfo = company_info();
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set title
        $sheet->setTitle('Neraca');
        
        // Header
        $sheet->setCellValue('A1', $companyInfo['name'] ?? 'BUMDES');
        $sheet->setCellValue('A2', 'Neraca');
        $sheet->setCellValue('A3', 'Per: ' . $asOfDate->format('d F Y'));
        
        // Style header
        $sheet->getStyle('A1:B1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2:B2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3:B3')->getFont()->setSize(10);
        
        // Table headers
        $row = 5;
        $sheet->setCellValue('A' . $row, 'Keterangan');
        $sheet->setCellValue('B' . $row, 'Jumlah (Rp)');
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        
        $row++;
        
        // Assets section
        $sheet->setCellValue('A' . $row, 'ASET');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        foreach ($reportData['assets'] as $accountName => $amount) {
            $sheet->setCellValue('A' . $row, $accountName);
            $sheet->setCellValue('B' . $row, $amount);
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $row++;
        }
        
        $sheet->setCellValue('A' . $row, 'Total Aset');
        $sheet->setCellValue('B' . $row, $reportData['total_assets']);
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $row += 2;
        
        // Liabilities section
        $sheet->setCellValue('A' . $row, 'KEWAJIBAN');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        foreach ($reportData['liabilities'] as $accountName => $amount) {
            $sheet->setCellValue('A' . $row, $accountName);
            $sheet->setCellValue('B' . $row, $amount);
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $row++;
        }
        
        $sheet->setCellValue('A' . $row, 'Total Kewajiban');
        $sheet->setCellValue('B' . $row, $reportData['total_liabilities']);
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $row += 2;
        
        // Equity section
        $sheet->setCellValue('A' . $row, 'MODAL');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        foreach ($reportData['equity'] as $accountName => $amount) {
            $sheet->setCellValue('A' . $row, $accountName);
            $sheet->setCellValue('B' . $row, $amount);
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $row++;
        }
        
        $sheet->setCellValue('A' . $row, 'Total Modal');
        $sheet->setCellValue('B' . $row, $reportData['total_equity']);
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $row += 2;
        
        // Total Liabilities + Equity
        $sheet->setCellValue('A' . $row, 'TOTAL KEWAJIBAN + MODAL');
        $sheet->setCellValue('B' . $row, $reportData['total_liabilities'] + $reportData['total_equity']);
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
        
        // Auto-size columns
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        
        // Generate filename and save
        $filename = "Neraca_" . $asOfDate->format('Y-m-d') . ".xlsx";
        $tempFile = tempnam(sys_get_temp_dir(), 'balance_sheet_');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempFile);
        
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    // ========== CASH FLOW EXPORTS ==========

    /**
     * Export Cash Flow to PDF
     */
    public function exportCashFlowPdf(Request $request)
    {
        $periodStart = $request->period_start ? Carbon::parse($request->period_start) : Carbon::now()->startOfMonth();
        $periodEnd = $request->period_end ? Carbon::parse($request->period_end) : Carbon::now()->endOfMonth();
        
        $reportData = $this->generateCashFlowData($periodStart, $periodEnd);
        $companyInfo = company_info();
        
        $data = [
            'reportData' => $reportData,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'company_info' => $companyInfo,
            'title' => 'Laporan Arus Kas'
        ];
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('financial-reports.exports.cash-flow-pdf', $data);
        $pdf->setPaper('a4', 'portrait');
        
        $filename = "Laporan_Arus_Kas_" . $periodStart->format('Y-m-d') . "_to_" . $periodEnd->format('Y-m-d') . ".pdf";
        
        return $pdf->download($filename);
    }

    /**
     * Export Cash Flow to DOCX
     */
    public function exportCashFlowDocx(Request $request)
    {
        $periodStart = $request->period_start ? Carbon::parse($request->period_start) : Carbon::now()->startOfMonth();
        $periodEnd = $request->period_end ? Carbon::parse($request->period_end) : Carbon::now()->endOfMonth();
        
        $reportData = $this->generateCashFlowData($periodStart, $periodEnd);
        $companyInfo = company_info();
        
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        // Set document properties
        $properties = $phpWord->getDocInfo();
        $properties->setCreator($companyInfo['name'] ?? 'BUMDES');
        $properties->setCompany($companyInfo['name'] ?? 'BUMDES');
        $properties->setTitle('Laporan Arus Kas');
        $properties->setDescription('Laporan Arus Kas periode ' . $periodStart->format('d F Y') . ' s/d ' . $periodEnd->format('d F Y'));
        
        // Define styles
        $phpWord->addTitleStyle(1, ['size' => 16, 'bold' => true], ['alignment' => 'center']);
        $phpWord->addTitleStyle(2, ['size' => 14, 'bold' => true]);
        
        $tableHeaderStyle = ['bold' => true, 'size' => 11];
        $tableDataStyle = ['size' => 10];
        $normalStyle = ['size' => 10];
        
        // Create section
        $section = $phpWord->addSection([
            'marginLeft' => 1134,
            'marginRight' => 1134,
            'marginTop' => 1134,
            'marginBottom' => 1134,
        ]);
        
        // Header
        $section->addTitle($companyInfo['name'] ?? 'BUMDES', 1);
        $section->addTitle('Laporan Arus Kas', 2);
        $section->addText('Periode: ' . $periodStart->format('d F Y') . ' s/d ' . $periodEnd->format('d F Y'), $normalStyle);
        $section->addTextBreak();
        
        // Create table
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
        $table->addRow();
        $table->addCell(4000)->addText('Aktivitas', $tableHeaderStyle);
        $table->addCell(2000)->addText('Jumlah (Rp)', $tableHeaderStyle);
        
        // Operating Activities
        if (isset($reportData['operating_activities'])) {
            $table->addRow();
            $table->addCell(4000)->addText('Aktivitas Operasi', $tableHeaderStyle);
            $table->addCell(2000)->addText('', $tableHeaderStyle);
            
            foreach ($reportData['operating_activities'] as $activity) {
                $table->addRow();
                $table->addCell(4000)->addText($activity['description'], $tableDataStyle);
                $table->addCell(2000)->addText(number_format($activity['amount'], 0, ',', '.'), $tableDataStyle);
            }
            
            $table->addRow();
            $table->addCell(4000)->addText('Kas Bersih dari Aktivitas Operasi', $tableHeaderStyle);
            $table->addCell(2000)->addText(number_format($reportData['net_operating_cash'], 0, ',', '.'), $tableHeaderStyle);
        }
        
        // Investing Activities
        if (isset($reportData['investing_activities'])) {
            $table->addRow();
            $table->addCell(4000)->addText('Aktivitas Investasi', $tableHeaderStyle);
            $table->addCell(2000)->addText('', $tableHeaderStyle);
            
            foreach ($reportData['investing_activities'] as $activity) {
                $table->addRow();
                $table->addCell(4000)->addText($activity['description'], $tableDataStyle);
                $table->addCell(2000)->addText(number_format($activity['amount'], 0, ',', '.'), $tableDataStyle);
            }
            
            $table->addRow();
            $table->addCell(4000)->addText('Kas Bersih dari Aktivitas Investasi', $tableHeaderStyle);
            $table->addCell(2000)->addText(number_format($reportData['net_investing_cash'], 0, ',', '.'), $tableHeaderStyle);
        }
        
        // Financing Activities
        if (isset($reportData['financing_activities'])) {
            $table->addRow();
            $table->addCell(4000)->addText('Aktivitas Pendanaan', $tableHeaderStyle);
            $table->addCell(2000)->addText('', $tableHeaderStyle);
            
            foreach ($reportData['financing_activities'] as $activity) {
                $table->addRow();
                $table->addCell(4000)->addText($activity['description'], $tableDataStyle);
                $table->addCell(2000)->addText(number_format($activity['amount'], 0, ',', '.'), $tableDataStyle);
            }
            
            $table->addRow();
            $table->addCell(4000)->addText('Kas Bersih dari Aktivitas Pendanaan', $tableHeaderStyle);
            $table->addCell(2000)->addText(number_format($reportData['net_financing_cash'], 0, ',', '.'), $tableHeaderStyle);
        }
        
        // Net Cash Change
        if (isset($reportData['net_cash_change'])) {
            $table->addRow();
            $table->addCell(4000)->addText('Kenaikan (Penurunan) Bersih Kas', $tableHeaderStyle);
            $table->addCell(2000)->addText(number_format($reportData['net_cash_change'], 0, ',', '.'), $tableHeaderStyle);
            
            $table->addRow();
            $table->addCell(4000)->addText('Kas Awal Periode', $tableHeaderStyle);
            $table->addCell(2000)->addText(number_format($reportData['beginning_cash'], 0, ',', '.'), $tableHeaderStyle);
            
            $table->addRow();
            $table->addCell(4000)->addText('Kas Akhir Periode', $tableHeaderStyle);
            $table->addCell(2000)->addText(number_format($reportData['ending_cash'], 0, ',', '.'), $tableHeaderStyle);
        }
        
        // Generate filename and save
        $filename = "Laporan_Arus_Kas_" . $periodStart->format('Y-m-d') . "_to_" . $periodEnd->format('Y-m-d') . ".docx";
        $tempFile = tempnam(sys_get_temp_dir(), 'cash_flow_');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);
        
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Export Cash Flow to Excel
     */
    public function exportCashFlowExcel(Request $request)
    {
        $periodStart = $request->period_start ? Carbon::parse($request->period_start) : Carbon::now()->startOfMonth();
        $periodEnd = $request->period_end ? Carbon::parse($request->period_end) : Carbon::now()->endOfMonth();
        
        $reportData = $this->generateCashFlowData($periodStart, $periodEnd);
        $companyInfo = company_info();
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set title
        $sheet->setTitle('Laporan Arus Kas');
        
        // Header
        $sheet->setCellValue('A1', $companyInfo['name'] ?? 'BUMDES');
        $sheet->setCellValue('A2', 'Laporan Arus Kas');
        $sheet->setCellValue('A3', 'Periode: ' . $periodStart->format('d F Y') . ' s/d ' . $periodEnd->format('d F Y'));
        
        // Style header
        $sheet->getStyle('A1:B1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2:B2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3:B3')->getFont()->setSize(10);
        
        // Table headers
        $row = 5;
        $sheet->setCellValue('A' . $row, 'Aktivitas');
        $sheet->setCellValue('B' . $row, 'Jumlah (Rp)');
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        
        $row++;
        
        // Operating Activities
        if (isset($reportData['operating_activities'])) {
            $sheet->setCellValue('A' . $row, 'Aktivitas Operasi');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;
            
            foreach ($reportData['operating_activities'] as $activity) {
                $sheet->setCellValue('A' . $row, $activity['description']);
                $sheet->setCellValue('B' . $row, $activity['amount']);
                $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
                $row++;
            }
            
            $sheet->setCellValue('A' . $row, 'Kas Bersih dari Aktivitas Operasi');
            $sheet->setCellValue('B' . $row, $reportData['net_operating_cash']);
            $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $row += 2;
        }
        
        // Investing Activities
        if (isset($reportData['investing_activities'])) {
            $sheet->setCellValue('A' . $row, 'Aktivitas Investasi');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;
            
            foreach ($reportData['investing_activities'] as $activity) {
                $sheet->setCellValue('A' . $row, $activity['description']);
                $sheet->setCellValue('B' . $row, $activity['amount']);
                $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
                $row++;
            }
            
            $sheet->setCellValue('A' . $row, 'Kas Bersih dari Aktivitas Investasi');
            $sheet->setCellValue('B' . $row, $reportData['net_investing_cash']);
            $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $row += 2;
        }
        
        // Financing Activities
        if (isset($reportData['financing_activities'])) {
            $sheet->setCellValue('A' . $row, 'Aktivitas Pendanaan');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;
            
            foreach ($reportData['financing_activities'] as $activity) {
                $sheet->setCellValue('A' . $row, $activity['description']);
                $sheet->setCellValue('B' . $row, $activity['amount']);
                $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
                $row++;
            }
            
            $sheet->setCellValue('A' . $row, 'Kas Bersih dari Aktivitas Pendanaan');
            $sheet->setCellValue('B' . $row, $reportData['net_financing_cash']);
            $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $row += 2;
        }
        
        // Net Cash Change
        if (isset($reportData['net_cash_change'])) {
            $sheet->setCellValue('A' . $row, 'Kenaikan (Penurunan) Bersih Kas');
            $sheet->setCellValue('B' . $row, $reportData['net_cash_change']);
            $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Kas Awal Periode');
            $sheet->setCellValue('B' . $row, $reportData['beginning_cash']);
            $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Kas Akhir Periode');
            $sheet->setCellValue('B' . $row, $reportData['ending_cash']);
            $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
        }
        
        // Auto-size columns
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        
        // Generate filename and save
        $filename = "Laporan_Arus_Kas_" . $periodStart->format('Y-m-d') . "_to_" . $periodEnd->format('Y-m-d') . ".xlsx";
        $tempFile = tempnam(sys_get_temp_dir(), 'cash_flow_');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempFile);
        
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    // ========== TRIAL BALANCE EXPORTS ==========

    /**
     * Export Trial Balance to PDF
     */
    public function exportTrialBalancePdf(Request $request)
    {
        $asOfDate = $request->as_of_date ? Carbon::parse($request->as_of_date) : Carbon::now();
        
        $accounts = $this->generateTrialBalanceData($asOfDate);
        $companyInfo = company_info();
        
        // Calculate totals and check balance
        $totalDebit = $accounts->sum('debit');
        $totalCredit = $accounts->sum('credit');
        $isBalanced = abs($totalDebit - $totalCredit) < 0.01; // Allow for small rounding differences
        
        // Log balance check for debugging
         if (!$isBalanced) {
             \Illuminate\Support\Facades\Log::warning('Trial Balance is not balanced', [
                 'as_of_date' => $asOfDate->format('Y-m-d'),
                 'total_debit' => $totalDebit,
                 'total_credit' => $totalCredit,
                 'difference' => $totalDebit - $totalCredit
             ]);
         }
        
        $data = [
            'reportData' => [
                'accounts' => $accounts->toArray(),
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
                'is_balanced' => $isBalanced,
                'difference' => $totalDebit - $totalCredit
            ],
            'asOfDate' => $asOfDate,
            'company_info' => $companyInfo,
            'title' => 'Neraca Saldo'
        ];
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('financial-reports.exports.trial-balance-pdf', $data);
        $pdf->setPaper('a4', 'landscape');
        
        $filename = "Neraca_Saldo_" . $asOfDate->format('Y-m-d') . ".pdf";
        
        return $pdf->download($filename);
    }

    /**
     * Export Trial Balance to DOCX
     */
    public function exportTrialBalanceDocx(Request $request)
    {
        $asOfDate = $request->as_of_date ? Carbon::parse($request->as_of_date) : Carbon::now();
        
        $accounts = $this->generateTrialBalanceData($asOfDate);
        $companyInfo = company_info();
        
        // Calculate totals and check balance
        $totalDebit = $accounts->sum('debit');
        $totalCredit = $accounts->sum('credit');
        $isBalanced = abs($totalDebit - $totalCredit) < 0.01;
        
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        // Set document properties
        $properties = $phpWord->getDocInfo();
        $properties->setCreator($companyInfo['name'] ?? 'BUMDES');
        $properties->setCompany($companyInfo['name'] ?? 'BUMDES');
        $properties->setTitle('Neraca Saldo');
        $properties->setDescription('Neraca Saldo per ' . $asOfDate->format('d F Y'));
        
        // Define styles
        $phpWord->addTitleStyle(1, ['size' => 16, 'bold' => true], ['alignment' => 'center']);
        $phpWord->addTitleStyle(2, ['size' => 14, 'bold' => true]);
        
        $tableHeaderStyle = ['bold' => true, 'size' => 10];
        $tableDataStyle = ['size' => 9];
        $normalStyle = ['size' => 10];
        
        // Create section with landscape orientation
        $section = $phpWord->addSection([
            'marginLeft' => 567,
            'marginRight' => 567,
            'marginTop' => 567,
            'marginBottom' => 567,
            'orientation' => 'landscape',
        ]);
        
        // Header
        $section->addTitle($companyInfo['name'] ?? 'BUMDES', 1);
        $section->addTitle('Neraca Saldo', 2);
        $section->addText('Per: ' . $asOfDate->format('d F Y'), $normalStyle);
        $section->addTextBreak();
        
        // Create table
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
        $table->addRow();
        $table->addCell(1000)->addText('Kode', $tableHeaderStyle);
        $table->addCell(3000)->addText('Nama Akun', $tableHeaderStyle);
        $table->addCell(1500)->addText('Debit (Rp)', $tableHeaderStyle);
        $table->addCell(1500)->addText('Kredit (Rp)', $tableHeaderStyle);
        
        foreach ($accounts as $account) {
            $table->addRow();
            $table->addCell(1000)->addText($account['account_code'] ?? '', $tableDataStyle);
            $table->addCell(3000)->addText($account['account_name'], $tableDataStyle);
            $table->addCell(1500)->addText(number_format($account['debit'], 0, ',', '.'), $tableDataStyle);
            $table->addCell(1500)->addText(number_format($account['credit'], 0, ',', '.'), $tableDataStyle);
        }
        
        // Total row
        $table->addRow();
        $table->addCell(1000)->addText('', $tableHeaderStyle);
        $table->addCell(3000)->addText('TOTAL', $tableHeaderStyle);
        $table->addCell(1500)->addText(number_format($totalDebit, 0, ',', '.'), $tableHeaderStyle);
        $table->addCell(1500)->addText(number_format($totalCredit, 0, ',', '.'), $tableHeaderStyle);
        
        // Balance Check Section
        $section->addTextBreak(2);
        $section->addText('Status Neraca: ' . ($isBalanced ? 'SEIMBANG' : 'TIDAK SEIMBANG'), 
                         ['bold' => true, 'color' => $isBalanced ? '28a745' : 'dc3545']);
        
        if (!$isBalanced) {
            $difference = $totalDebit - $totalCredit;
            $section->addText('Selisih: Rp ' . number_format(abs($difference), 0, ',', '.') . 
                             ($difference > 0 ? ' (Debit lebih besar)' : ' (Kredit lebih besar)'), $normalStyle);
            $section->addTextBreak();
            $section->addText('Catatan: Neraca saldo harus seimbang (total debit = total kredit). ' .
                             'Jika tidak seimbang, periksa kembali pencatatan transaksi dan posting ke buku besar.', 
                             ['size' => 9, 'italic' => true]);
        } else {
            $section->addText('Neraca saldo telah seimbang dengan benar.', ['size' => 9, 'italic' => true]);
        }
        
        // Generate filename and save
        $filename = "Neraca_Saldo_" . $asOfDate->format('Y-m-d') . ".docx";
        $tempFile = tempnam(sys_get_temp_dir(), 'trial_balance_');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);
        
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Export Trial Balance to Excel
     */
    public function exportTrialBalanceExcel(Request $request)
    {
        $asOfDate = $request->as_of_date ? Carbon::parse($request->as_of_date) : Carbon::now();
        
        $accounts = $this->generateTrialBalanceData($asOfDate);
        $companyInfo = company_info();
        
        // Calculate totals and check balance
        $totalDebit = $accounts->sum('debit');
        $totalCredit = $accounts->sum('credit');
        $isBalanced = abs($totalDebit - $totalCredit) < 0.01;
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set title
        $sheet->setTitle('Neraca Saldo');
        
        // Header
        $sheet->setCellValue('A1', $companyInfo['name'] ?? 'BUMDES');
        $sheet->setCellValue('A2', 'Neraca Saldo');
        $sheet->setCellValue('A3', 'Per: ' . $asOfDate->format('d F Y'));
        
        // Style header
        $sheet->getStyle('A1:D1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2:D2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3:D3')->getFont()->setSize(10);
        
        // Table headers
        $row = 5;
        $sheet->setCellValue('A' . $row, 'Kode');
        $sheet->setCellValue('B' . $row, 'Nama Akun');
        $sheet->setCellValue('C' . $row, 'Debit (Rp)');
        $sheet->setCellValue('D' . $row, 'Kredit (Rp)');
        $sheet->getStyle('A' . $row . ':D' . $row)->getFont()->setBold(true);
        
        $row++;
        
        foreach ($accounts as $account) {
            $sheet->setCellValue('A' . $row, $account['account_code'] ?? '');
            $sheet->setCellValue('B' . $row, $account['account_name']);
            $sheet->setCellValue('C' . $row, $account['debit']);
            $sheet->setCellValue('D' . $row, $account['credit']);
            
            $sheet->getStyle('C' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('D' . $row)->getNumberFormat()->setFormatCode('#,##0');
            
            $row++;
        }
        
        // Total row
        $sheet->setCellValue('A' . $row, '');
        $sheet->setCellValue('B' . $row, 'TOTAL');
        $sheet->setCellValue('C' . $row, $totalDebit);
        $sheet->setCellValue('D' . $row, $totalCredit);
        $sheet->getStyle('A' . $row . ':D' . $row)->getFont()->setBold(true);
        $sheet->getStyle('C' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('D' . $row)->getNumberFormat()->setFormatCode('#,##0');
        
        // Balance Check Section
        $row += 2;
        $sheet->setCellValue('A' . $row, 'Status Neraca:');
        $sheet->setCellValue('B' . $row, $isBalanced ? 'SEIMBANG' : 'TIDAK SEIMBANG');
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        $sheet->getStyle('B' . $row)->getFont()->setColor(
            new \PhpOffice\PhpSpreadsheet\Style\Color($isBalanced ? '28a745' : 'dc3545')
        );
        
        if (!$isBalanced) {
            $row++;
            $difference = $totalDebit - $totalCredit;
            $sheet->setCellValue('A' . $row, 'Selisih:');
            $sheet->setCellValue('B' . $row, 'Rp ' . number_format(abs($difference), 0, ',', '.') . 
                                           ($difference > 0 ? ' (Debit lebih besar)' : ' (Kredit lebih besar)'));
            
            $row++;
            $sheet->setCellValue('A' . $row, 'Catatan:');
            $sheet->setCellValue('B' . $row, 'Neraca saldo harus seimbang (total debit = total kredit). ' .
                                           'Jika tidak seimbang, periksa kembali pencatatan transaksi dan posting ke buku besar.');
            $sheet->getStyle('A' . $row . ':D' . $row)->getFont()->setItalic(true)->setSize(9);
        } else {
            $row++;
            $sheet->setCellValue('A' . $row, 'Catatan:');
            $sheet->setCellValue('B' . $row, 'Neraca saldo telah seimbang dengan benar.');
            $sheet->getStyle('A' . $row . ':D' . $row)->getFont()->setItalic(true)->setSize(9);
        }
        
        // Auto-size columns
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        
        // Generate filename and save
        $filename = "Neraca_Saldo_" . $asOfDate->format('Y-m-d') . ".xlsx";
        $tempFile = tempnam(sys_get_temp_dir(), 'trial_balance_');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempFile);
        
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Sanitize report data to ensure all numeric values are properly formatted
     */
    private function sanitizeReportData($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // If it's an array, recursively sanitize it
                $sanitized[$key] = $this->sanitizeReportData($value);
            } elseif (is_numeric($value) || (is_string($value) && is_numeric($value))) {
                // Convert to float for proper number formatting
                $sanitized[$key] = (float) $value;
            } else {
                // Keep as is for non-numeric values
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }


}
