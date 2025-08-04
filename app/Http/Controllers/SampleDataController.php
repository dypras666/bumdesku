<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\GeneralLedger;
use App\Models\MasterAccount;
use App\Models\MasterUnit;
use App\Models\MasterInventory;
use App\Models\FinancialReport;

class SampleDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display sample data import page
     */
    public function index()
    {
        // Check if user has super_admin role
        if (!Auth::user()->hasRole('super_admin')) {
            abort(403, 'Unauthorized access. Super admin role required.');
        }

        // Get current data counts
        $dataCounts = [
            'transactions' => Transaction::count(),
            'general_ledgers' => GeneralLedger::count(),
            'master_accounts' => MasterAccount::count(),
            'master_units' => MasterUnit::count(),
            'master_inventories' => MasterInventory::count(),
            'financial_reports' => FinancialReport::count(),
        ];

        // Available sample data sets
        $sampleDataSets = [
            'master_data' => [
                'name' => 'Data Master',
                'description' => 'Import akun, unit, dan inventori master',
                'seeders' => ['MasterAccountSeeder', 'MasterUnitSeeder', 'MasterInventorySeeder'],
                'estimated_records' => 50,
            ],
            'transaction_data' => [
                'name' => 'Data Transaksi',
                'description' => 'Import transaksi sampel untuk Juni-Juli 2025',
                'seeders' => ['TransactionSeeder'],
                'estimated_records' => 35,
                'requires' => ['master_data'],
            ],
            'ledger_data' => [
                'name' => 'Data Buku Besar',
                'description' => 'Import entri buku besar dari transaksi',
                'seeders' => ['GeneralLedgerSeeder'],
                'estimated_records' => 90,
                'requires' => ['transaction_data'],
            ],
            'report_data' => [
                'name' => 'Data Laporan',
                'description' => 'Import laporan keuangan sampel',
                'seeders' => ['FinancialReportSeeder', 'DaftarLaporanKeuanganSeeder'],
                'estimated_records' => 10,
                'requires' => ['ledger_data'],
            ],
            'complete_sample' => [
                'name' => 'Data Lengkap',
                'description' => 'Import semua data sampel (master, transaksi, buku besar, laporan)',
                'seeders' => [
                    'MasterAccountSeeder',
                    'MasterUnitSeeder', 
                    'MasterInventorySeeder',
                    'TransactionSeeder',
                    'GeneralLedgerSeeder',
                    'FinancialReportSeeder',
                    'DaftarLaporanKeuanganSeeder'
                ],
                'estimated_records' => 185,
            ],
        ];

        return view('sample-data.index', compact('dataCounts', 'sampleDataSets'));
    }

    /**
     * Import specific sample data set
     */
    public function import(Request $request)
    {
        $request->validate([
            'data_set' => 'required|string',
            'confirm_import' => 'required|accepted',
        ]);

        $dataSet = $request->input('data_set');
        
        // Available sample data sets configuration
        $sampleDataSets = [
            'master_data' => ['MasterAccountSeeder', 'MasterUnitSeeder', 'MasterInventorySeeder'],
            'transaction_data' => ['TransactionSeeder'],
            'ledger_data' => ['GeneralLedgerSeeder'],
            'report_data' => ['FinancialReportSeeder', 'DaftarLaporanKeuanganSeeder'],
            'complete_sample' => [
                'MasterAccountSeeder',
                'MasterUnitSeeder', 
                'MasterInventorySeeder',
                'TransactionSeeder',
                'GeneralLedgerSeeder',
                'FinancialReportSeeder',
                'DaftarLaporanKeuanganSeeder'
            ],
        ];

        if (!isset($sampleDataSets[$dataSet])) {
            return redirect()->back()->with('error', 'Set data sampel tidak valid.');
        }

        try {
            DB::beginTransaction();

            $importedSeeders = [];
            $totalRecords = 0;

            foreach ($sampleDataSets[$dataSet] as $seederClass) {
                // Get record count before seeding
                $beforeCount = $this->getTotalRecordCount();
                
                // Run the seeder
                Artisan::call('db:seed', [
                    '--class' => "Database\\Seeders\\{$seederClass}",
                    '--force' => true
                ]);

                // Get record count after seeding
                $afterCount = $this->getTotalRecordCount();
                $recordsAdded = $afterCount - $beforeCount;
                
                $importedSeeders[] = [
                    'seeder' => $seederClass,
                    'records_added' => $recordsAdded
                ];
                
                $totalRecords += $recordsAdded;
            }

            DB::commit();

            return redirect()->back()->with('success', 
                "Data sampel berhasil diimpor! Total {$totalRecords} record ditambahkan."
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->with('error', 
                'Gagal mengimpor data sampel: ' . $e->getMessage()
            );
        }
    }

    /**
     * Get total record count across all main tables
     */
    private function getTotalRecordCount()
    {
        return Transaction::count() + 
               GeneralLedger::count() + 
               MasterAccount::count() + 
               MasterUnit::count() + 
               MasterInventory::count() + 
               FinancialReport::count();
    }

    /**
     * Check data dependencies before import
     */
    public function checkDependencies(Request $request)
    {
        $dataSet = $request->input('data_set');
        
        $dependencies = [
            'transaction_data' => [
                'master_accounts' => MasterAccount::count() > 0,
            ],
            'ledger_data' => [
                'transactions' => Transaction::count() > 0,
            ],
            'report_data' => [
                'general_ledgers' => GeneralLedger::count() > 0,
            ],
        ];

        $result = [
            'can_import' => true,
            'missing_dependencies' => [],
        ];

        if (isset($dependencies[$dataSet])) {
            foreach ($dependencies[$dataSet] as $dependency => $exists) {
                if (!$exists) {
                    $result['can_import'] = false;
                    $result['missing_dependencies'][] = $dependency;
                }
            }
        }

        return response()->json($result);
    }

    /**
     * Preview sample data that will be imported
     */
    public function preview(Request $request)
    {
        $dataSet = $request->input('data_set');
        
        $previews = [
            'master_data' => [
                'description' => 'Data master akun, unit, dan inventori',
                'sample_data' => [
                    'Akun: Kas, Bank BRI, Bank Mandiri, Piutang Usaha',
                    'Unit: Unit Produksi, Unit Pemasaran, Unit Keuangan',
                    'Inventori: Bahan baku, produk jadi, peralatan'
                ]
            ],
            'transaction_data' => [
                'description' => 'Transaksi sampel untuk periode Juni-Juli 2025',
                'sample_data' => [
                    'Juni 2025: 16 transaksi (balance)',
                    'Juli 2025: 19 transaksi (minus)',
                    'Total: 35 transaksi dengan berbagai jenis'
                ]
            ],
            'ledger_data' => [
                'description' => 'Entri buku besar dari transaksi',
                'sample_data' => [
                    'Entri debit dan kredit untuk setiap transaksi',
                    'Posting otomatis ke akun terkait',
                    'Total: ~90 entri buku besar'
                ]
            ],
            'report_data' => [
                'description' => 'Laporan keuangan sampel',
                'sample_data' => [
                    'Laporan Laba Rugi',
                    'Laporan Neraca',
                    'Laporan Arus Kas'
                ]
            ],
            'complete_sample' => [
                'description' => 'Semua data sampel lengkap',
                'sample_data' => [
                    'Semua data master (50+ record)',
                    'Transaksi lengkap (35 record)',
                    'Buku besar (90+ record)',
                    'Laporan keuangan (10+ record)'
                ]
            ],
        ];

        return response()->json($previews[$dataSet] ?? [
            'description' => 'Data set tidak ditemukan',
            'sample_data' => []
        ]);
    }
}
