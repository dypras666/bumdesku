<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FinancialReport;
use App\Models\User;
use Carbon\Carbon;

class DaftarLaporanKeuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (superadmin) to assign as report generator
        $superadmin = User::where('email', 'superadmin@bumdes.com')->first();
        $userId = $superadmin ? $superadmin->id : 1;

        // Clear existing financial reports
        FinancialReport::truncate();

        // Generate comprehensive financial reports for different periods
        $this->createQuarterlyReports($userId);
        $this->createMonthlyReports($userId);
        $this->createYearlyReports($userId);
        $this->createSpecialReports($userId);
    }

    /**
     * Create quarterly financial reports
     */
    private function createQuarterlyReports($userId)
    {
        $quarters = [
            ['Q1 2024', '2024-01-01', '2024-03-31'],
            ['Q2 2024', '2024-04-01', '2024-06-30'],
            ['Q3 2024', '2024-07-01', '2024-09-30'],
            ['Q4 2024', '2024-10-01', '2024-12-31'],
        ];

        foreach ($quarters as $quarter) {
            // Income Statement for each quarter
            FinancialReport::create([
                'report_code' => FinancialReport::generateReportCode('income_statement'),
                'report_type' => 'income_statement',
                'report_title' => "Laporan Laba Rugi {$quarter[0]} - BUMDES Maju Bersama",
                'period_start' => $quarter[1],
                'period_end' => $quarter[2],
                'report_data' => $this->generateIncomeStatementData($quarter[1], $quarter[2]),
                'report_parameters' => [
                    'period_start' => $quarter[1],
                    'period_end' => $quarter[2],
                    'include_details' => true,
                    'show_comparisons' => true
                ],
                'status' => 'finalized',
                'generated_by' => $userId,
                'generated_at' => Carbon::parse($quarter[2])->addDays(5),
                'finalized_by' => $userId,
                'finalized_at' => Carbon::parse($quarter[2])->addDays(7),
                'notes' => "Laporan keuangan triwulanan {$quarter[0]} telah diaudit dan disetujui oleh manajemen BUMDES."
            ]);

            // Balance Sheet for each quarter
            FinancialReport::create([
                'report_code' => FinancialReport::generateReportCode('balance_sheet'),
                'report_type' => 'balance_sheet',
                'report_title' => "Neraca {$quarter[0]} - BUMDES Maju Bersama",
                'period_start' => $quarter[1],
                'period_end' => $quarter[2],
                'report_data' => $this->generateBalanceSheetData($quarter[1], $quarter[2]),
                'report_parameters' => [
                    'period_start' => $quarter[1],
                    'period_end' => $quarter[2],
                    'include_notes' => true,
                    'show_percentages' => true
                ],
                'status' => 'finalized',
                'generated_by' => $userId,
                'generated_at' => Carbon::parse($quarter[2])->addDays(5),
                'finalized_by' => $userId,
                'finalized_at' => Carbon::parse($quarter[2])->addDays(7),
                'notes' => "Neraca posisi keuangan per akhir {$quarter[0]} menunjukkan kondisi keuangan yang sehat."
            ]);

            // Cash Flow for each quarter
            FinancialReport::create([
                'report_code' => FinancialReport::generateReportCode('cash_flow'),
                'report_type' => 'cash_flow',
                'report_title' => "Laporan Arus Kas {$quarter[0]} - BUMDES Maju Bersama",
                'period_start' => $quarter[1],
                'period_end' => $quarter[2],
                'report_data' => $this->generateCashFlowData($quarter[1], $quarter[2]),
                'report_parameters' => [
                    'period_start' => $quarter[1],
                    'period_end' => $quarter[2],
                    'method' => 'indirect',
                    'include_reconciliation' => true
                ],
                'status' => 'finalized',
                'generated_by' => $userId,
                'generated_at' => Carbon::parse($quarter[2])->addDays(5),
                'finalized_by' => $userId,
                'finalized_at' => Carbon::parse($quarter[2])->addDays(7),
                'notes' => "Laporan arus kas {$quarter[0]} menunjukkan aliran kas operasional yang positif."
            ]);
        }
    }

    /**
     * Create monthly financial reports for recent months
     */
    private function createMonthlyReports($userId)
    {
        $months = [
            ['Oktober 2024', '2024-10-01', '2024-10-31'],
            ['November 2024', '2024-11-01', '2024-11-30'],
            ['Desember 2024', '2024-12-01', '2024-12-31'],
        ];

        foreach ($months as $month) {
            // Monthly Income Statement
            FinancialReport::create([
                'report_code' => FinancialReport::generateReportCode('income_statement'),
                'report_type' => 'income_statement',
                'report_title' => "Laporan Laba Rugi Bulanan {$month[0]} - BUMDES Maju Bersama",
                'period_start' => $month[1],
                'period_end' => $month[2],
                'report_data' => $this->generateIncomeStatementData($month[1], $month[2]),
                'report_parameters' => [
                    'period_start' => $month[1],
                    'period_end' => $month[2],
                    'frequency' => 'monthly',
                    'include_budget_comparison' => true
                ],
                'status' => 'generated',
                'generated_by' => $userId,
                'generated_at' => Carbon::parse($month[2])->addDays(3),
                'notes' => "Laporan bulanan {$month[0]} untuk monitoring kinerja keuangan bulanan."
            ]);

            // Monthly Trial Balance
            FinancialReport::create([
                'report_code' => FinancialReport::generateReportCode('trial_balance'),
                'report_type' => 'trial_balance',
                'report_title' => "Neraca Saldo {$month[0]} - BUMDES Maju Bersama",
                'period_start' => $month[1],
                'period_end' => $month[2],
                'report_data' => $this->generateTrialBalanceData($month[1], $month[2]),
                'report_parameters' => [
                    'period_start' => $month[1],
                    'period_end' => $month[2],
                    'include_zero_balances' => false,
                    'sort_by' => 'account_code'
                ],
                'status' => 'generated',
                'generated_by' => $userId,
                'generated_at' => Carbon::parse($month[2])->addDays(2),
                'notes' => "Neraca saldo bulanan untuk verifikasi keseimbangan pembukuan."
            ]);
        }
    }

    /**
     * Create yearly financial reports
     */
    private function createYearlyReports($userId)
    {
        $years = [
            ['2023', '2023-01-01', '2023-12-31'],
            ['2024', '2024-01-01', '2024-12-31'],
        ];

        foreach ($years as $year) {
            // Annual Comprehensive Report
            FinancialReport::create([
                'report_code' => FinancialReport::generateReportCode('income_statement'),
                'report_type' => 'income_statement',
                'report_title' => "Laporan Keuangan Tahunan {$year[0]} - BUMDES Maju Bersama",
                'period_start' => $year[1],
                'period_end' => $year[2],
                'report_data' => $this->generateAnnualReportData($year[1], $year[2]),
                'report_parameters' => [
                    'period_start' => $year[1],
                    'period_end' => $year[2],
                    'report_type' => 'comprehensive',
                    'include_analysis' => true,
                    'include_ratios' => true,
                    'include_charts' => true
                ],
                'status' => $year[0] == '2023' ? 'finalized' : 'generated',
                'generated_by' => $userId,
                'generated_at' => Carbon::parse($year[2])->addDays(15),
                'finalized_by' => $year[0] == '2023' ? $userId : null,
                'finalized_at' => $year[0] == '2023' ? Carbon::parse($year[2])->addDays(30) : null,
                'notes' => "Laporan keuangan komprehensif tahunan {$year[0]} untuk evaluasi kinerja dan perencanaan strategis."
            ]);
        }
    }

    /**
     * Create special purpose reports
     */
    private function createSpecialReports($userId)
    {
        // Audit Report
        FinancialReport::create([
            'report_code' => FinancialReport::generateReportCode('general_ledger'),
            'report_type' => 'general_ledger',
            'report_title' => 'Laporan Audit Internal 2024 - BUMDES Maju Bersama',
            'period_start' => '2024-01-01',
            'period_end' => '2024-12-31',
            'report_data' => $this->generateAuditReportData(),
            'report_parameters' => [
                'audit_type' => 'internal',
                'scope' => 'full',
                'include_recommendations' => true,
                'auditor' => 'Tim Audit Internal BUMDES'
            ],
            'status' => 'finalized',
            'generated_by' => $userId,
            'generated_at' => Carbon::now()->subDays(10),
            'finalized_by' => $userId,
            'finalized_at' => Carbon::now()->subDays(5),
            'notes' => 'Laporan audit internal tahunan untuk memastikan kepatuhan dan efektivitas sistem pengendalian internal.'
        ]);

        // Performance Analysis Report
        FinancialReport::create([
            'report_code' => FinancialReport::generateReportCode('income_statement'),
            'report_type' => 'income_statement',
            'report_title' => 'Analisis Kinerja Keuangan Q1-Q3 2024 - BUMDES Maju Bersama',
            'period_start' => '2024-01-01',
            'period_end' => '2024-09-30',
            'report_data' => $this->generatePerformanceAnalysisData(),
            'report_parameters' => [
                'analysis_type' => 'performance',
                'include_trends' => true,
                'include_benchmarks' => true,
                'comparison_period' => '2023-01-01 to 2023-09-30'
            ],
            'status' => 'generated',
            'generated_by' => $userId,
            'generated_at' => Carbon::now()->subDays(7),
            'notes' => 'Analisis mendalam kinerja keuangan untuk mendukung pengambilan keputusan strategis.'
        ]);

        // Budget vs Actual Report
        FinancialReport::create([
            'report_code' => FinancialReport::generateReportCode('income_statement'),
            'report_type' => 'income_statement',
            'report_title' => 'Laporan Realisasi Anggaran vs Aktual 2024 - BUMDES Maju Bersama',
            'period_start' => '2024-01-01',
            'period_end' => '2024-12-31',
            'report_data' => $this->generateBudgetVsActualData(),
            'report_parameters' => [
                'report_type' => 'budget_comparison',
                'include_variances' => true,
                'variance_threshold' => 10,
                'include_explanations' => true
            ],
            'status' => 'generated',
            'generated_by' => $userId,
            'generated_at' => Carbon::now()->subDays(3),
            'notes' => 'Perbandingan realisasi anggaran dengan aktual untuk evaluasi efektivitas perencanaan keuangan.'
        ]);
    }

    /**
     * Generate sample income statement data
     */
    private function generateIncomeStatementData($startDate, $endDate)
    {
        return [
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
                'description' => 'Periode ' . Carbon::parse($startDate)->format('d M Y') . ' - ' . Carbon::parse($endDate)->format('d M Y')
            ],
            'revenues' => [
                ['account' => 'Pendapatan Usaha Toko', 'amount' => 45000000],
                ['account' => 'Pendapatan Simpan Pinjam', 'amount' => 12000000],
                ['account' => 'Pendapatan Jasa Konsultasi', 'amount' => 8500000],
                ['account' => 'Pendapatan Lain-lain', 'amount' => 2500000],
            ],
            'total_revenue' => 68000000,
            'expenses' => [
                ['account' => 'Beban Pokok Penjualan', 'amount' => 32000000],
                ['account' => 'Beban Gaji dan Tunjangan', 'amount' => 15000000],
                ['account' => 'Beban Operasional', 'amount' => 8000000],
                ['account' => 'Beban Administrasi', 'amount' => 3500000],
                ['account' => 'Beban Penyusutan', 'amount' => 2500000],
                ['account' => 'Beban Lain-lain', 'amount' => 1500000],
            ],
            'total_expenses' => 62500000,
            'net_income' => 5500000,
            'gross_profit' => 36000000,
            'operating_income' => 7000000
        ];
    }

    /**
     * Generate sample balance sheet data
     */
    private function generateBalanceSheetData($startDate, $endDate)
    {
        return [
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
                'as_of' => Carbon::parse($endDate)->format('d M Y')
            ],
            'assets' => [
                'current_assets' => [
                    ['account' => 'Kas dan Bank', 'amount' => 125000000],
                    ['account' => 'Piutang Usaha', 'amount' => 35000000],
                    ['account' => 'Persediaan', 'amount' => 45000000],
                    ['account' => 'Biaya Dibayar Dimuka', 'amount' => 5000000],
                ],
                'fixed_assets' => [
                    ['account' => 'Tanah', 'amount' => 200000000],
                    ['account' => 'Bangunan', 'amount' => 150000000],
                    ['account' => 'Kendaraan', 'amount' => 75000000],
                    ['account' => 'Peralatan', 'amount' => 25000000],
                    ['account' => 'Akumulasi Penyusutan', 'amount' => -50000000],
                ]
            ],
            'total_assets' => 610000000,
            'liabilities' => [
                'current_liabilities' => [
                    ['account' => 'Hutang Usaha', 'amount' => 25000000],
                    ['account' => 'Hutang Bank Jangka Pendek', 'amount' => 40000000],
                    ['account' => 'Biaya yang Masih Harus Dibayar', 'amount' => 8000000],
                ],
                'long_term_liabilities' => [
                    ['account' => 'Hutang Bank Jangka Panjang', 'amount' => 150000000],
                    ['account' => 'Hutang Lain-lain', 'amount' => 12000000],
                ]
            ],
            'total_liabilities' => 235000000,
            'equity' => [
                ['account' => 'Modal Disetor', 'amount' => 300000000],
                ['account' => 'Laba Ditahan', 'amount' => 75000000],
            ],
            'total_equity' => 375000000
        ];
    }

    /**
     * Generate sample cash flow data
     */
    private function generateCashFlowData($startDate, $endDate)
    {
        return [
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
                'description' => 'Periode ' . Carbon::parse($startDate)->format('d M Y') . ' - ' . Carbon::parse($endDate)->format('d M Y')
            ],
            'operating_activities' => [
                ['description' => 'Penerimaan dari Pelanggan', 'amount' => 65000000],
                ['description' => 'Pembayaran kepada Pemasok', 'amount' => -35000000],
                ['description' => 'Pembayaran Gaji Karyawan', 'amount' => -15000000],
                ['description' => 'Pembayaran Beban Operasional', 'amount' => -8000000],
            ],
            'investing_activities' => [
                ['description' => 'Pembelian Peralatan', 'amount' => -15000000],
                ['description' => 'Penjualan Aset Tetap', 'amount' => 5000000],
            ],
            'financing_activities' => [
                ['description' => 'Penerimaan Pinjaman Bank', 'amount' => 25000000],
                ['description' => 'Pembayaran Cicilan Pinjaman', 'amount' => -12000000],
                ['description' => 'Pembayaran Dividen', 'amount' => -5000000],
            ],
            'net_cash_flow' => 5000000,
            'beginning_cash' => 45000000,
            'ending_cash' => 50000000
        ];
    }

    /**
     * Generate trial balance data
     */
    private function generateTrialBalanceData($startDate, $endDate)
    {
        return [
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
                'as_of' => Carbon::parse($endDate)->format('d M Y')
            ],
            'accounts' => [
                ['code' => '1101', 'name' => 'Kas', 'debit' => 25000000, 'credit' => 0],
                ['code' => '1102', 'name' => 'Bank', 'debit' => 100000000, 'credit' => 0],
                ['code' => '1201', 'name' => 'Piutang Usaha', 'debit' => 35000000, 'credit' => 0],
                ['code' => '1301', 'name' => 'Persediaan', 'debit' => 45000000, 'credit' => 0],
                ['code' => '1401', 'name' => 'Tanah', 'debit' => 200000000, 'credit' => 0],
                ['code' => '1402', 'name' => 'Bangunan', 'debit' => 150000000, 'credit' => 0],
                ['code' => '2101', 'name' => 'Hutang Usaha', 'debit' => 0, 'credit' => 25000000],
                ['code' => '2201', 'name' => 'Hutang Bank', 'debit' => 0, 'credit' => 190000000],
                ['code' => '3101', 'name' => 'Modal Disetor', 'debit' => 0, 'credit' => 300000000],
                ['code' => '3201', 'name' => 'Laba Ditahan', 'debit' => 0, 'credit' => 75000000],
                ['code' => '4101', 'name' => 'Pendapatan Usaha', 'debit' => 0, 'credit' => 68000000],
                ['code' => '5101', 'name' => 'Beban Pokok Penjualan', 'debit' => 32000000, 'credit' => 0],
                ['code' => '5201', 'name' => 'Beban Gaji', 'debit' => 15000000, 'credit' => 0],
                ['code' => '5301', 'name' => 'Beban Operasional', 'debit' => 8000000, 'credit' => 0],
            ],
            'total_debit' => 610000000,
            'total_credit' => 610000000,
            'is_balanced' => true
        ];
    }

    /**
     * Generate annual comprehensive report data
     */
    private function generateAnnualReportData($startDate, $endDate)
    {
        return [
            'executive_summary' => [
                'total_revenue' => 280000000,
                'total_expenses' => 245000000,
                'net_income' => 35000000,
                'total_assets' => 610000000,
                'total_equity' => 375000000,
                'roi' => 9.3,
                'growth_rate' => 15.2
            ],
            'financial_highlights' => [
                'revenue_growth' => '15.2% dari tahun sebelumnya',
                'profit_margin' => '12.5%',
                'asset_turnover' => '0.46',
                'debt_to_equity' => '0.63',
                'current_ratio' => '2.85'
            ],
            'quarterly_performance' => [
                'Q1' => ['revenue' => 65000000, 'profit' => 8000000],
                'Q2' => ['revenue' => 70000000, 'profit' => 9500000],
                'Q3' => ['revenue' => 72000000, 'profit' => 9000000],
                'Q4' => ['revenue' => 73000000, 'profit' => 8500000],
            ],
            'key_achievements' => [
                'Peningkatan pendapatan 15.2% dari tahun sebelumnya',
                'Diversifikasi produk dan layanan berhasil meningkatkan margin',
                'Implementasi sistem digital meningkatkan efisiensi operasional',
                'Ekspansi ke 3 desa baru dalam wilayah kecamatan'
            ]
        ];
    }

    /**
     * Generate audit report data
     */
    private function generateAuditReportData()
    {
        return [
            'audit_scope' => 'Audit menyeluruh terhadap laporan keuangan dan sistem pengendalian internal',
            'audit_period' => '1 Januari 2024 - 31 Desember 2024',
            'audit_findings' => [
                'Sistem pencatatan keuangan sudah sesuai dengan standar akuntansi',
                'Pengendalian internal berfungsi dengan baik',
                'Dokumentasi transaksi lengkap dan tertib',
                'Rekonsiliasi bank dilakukan secara rutin'
            ],
            'recommendations' => [
                'Implementasi sistem backup data otomatis',
                'Peningkatan pelatihan SDM di bidang akuntansi',
                'Digitalisasi dokumen untuk efisiensi penyimpanan',
                'Review berkala terhadap kebijakan kredit'
            ],
            'audit_opinion' => 'Wajar Tanpa Pengecualian',
            'auditor_signature' => 'Tim Audit Internal BUMDES Maju Bersama',
            'audit_date' => Carbon::now()->subDays(5)->format('d M Y')
        ];
    }

    /**
     * Generate performance analysis data
     */
    private function generatePerformanceAnalysisData()
    {
        return [
            'performance_metrics' => [
                'revenue_growth' => 18.5,
                'profit_growth' => 22.3,
                'asset_growth' => 12.1,
                'efficiency_ratio' => 85.2,
                'customer_satisfaction' => 92.5
            ],
            'trend_analysis' => [
                'revenue_trend' => 'Meningkat konsisten selama 9 bulan terakhir',
                'cost_trend' => 'Terkendali dengan baik, efisiensi meningkat',
                'profit_trend' => 'Margin keuntungan stabil di atas 12%'
            ],
            'benchmark_comparison' => [
                'industry_average_roi' => 7.5,
                'bumdes_roi' => 9.3,
                'performance_vs_industry' => 'Di atas rata-rata industri sebesar 24%'
            ],
            'strategic_recommendations' => [
                'Fokus pada digitalisasi untuk meningkatkan efisiensi',
                'Ekspansi ke segmen pasar baru',
                'Investasi dalam pelatihan SDM',
                'Pengembangan produk inovatif'
            ]
        ];
    }

    /**
     * Generate budget vs actual comparison data
     */
    private function generateBudgetVsActualData()
    {
        return [
            'budget_summary' => [
                'total_budget_revenue' => 250000000,
                'actual_revenue' => 280000000,
                'revenue_variance' => 30000000,
                'revenue_variance_percent' => 12.0
            ],
            'expense_analysis' => [
                'total_budget_expense' => 220000000,
                'actual_expense' => 245000000,
                'expense_variance' => 25000000,
                'expense_variance_percent' => 11.4
            ],
            'detailed_variances' => [
                ['category' => 'Pendapatan Usaha', 'budget' => 180000000, 'actual' => 200000000, 'variance' => 20000000],
                ['category' => 'Pendapatan Jasa', 'budget' => 70000000, 'actual' => 80000000, 'variance' => 10000000],
                ['category' => 'Beban Operasional', 'budget' => 150000000, 'actual' => 165000000, 'variance' => -15000000],
                ['category' => 'Beban Administrasi', 'budget' => 70000000, 'actual' => 80000000, 'variance' => -10000000],
            ],
            'variance_explanations' => [
                'Pendapatan melebihi target karena peningkatan permintaan pasar',
                'Beban operasional meningkat karena ekspansi usaha',
                'Investasi dalam teknologi meningkatkan beban administrasi',
                'Secara keseluruhan kinerja masih dalam batas yang dapat diterima'
            ]
        ];
    }
}
