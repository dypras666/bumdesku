<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed dalam urutan yang benar untuk menghasilkan data lengkap
        $this->call([
            // 1. Master Data (Foundation)
            RoleSeeder::class,
            UserSeeder::class,
            SystemSettingSeeder::class,
            MasterAccountSeeder::class,
            MasterUnitSeeder::class,
            MasterInventorySeeder::class,
            
            // 2. Transactional Data
            TransactionSeeder::class,
            GeneralLedgerSeeder::class,
            LoanFeatureSeeder::class,
            
            // 3. Reports (Final Output)
            FinancialReportSeeder::class,
            DaftarLaporanKeuanganSeeder::class,
        ]);
    }
}
