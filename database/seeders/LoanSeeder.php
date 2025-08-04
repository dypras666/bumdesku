<?php

namespace Database\Seeders;

use App\Models\Loan;
use App\Models\User;
use App\Models\MasterAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users for created_by and approved_by
        $admin = User::where('email', 'admin@bumdes.com')->first();
        $financeManager = User::where('email', 'finance@bumdes.com')->first();
        $accountant = User::where('email', 'accountant@bumdes.com')->first();

        // Get piutang account
        $piutangAccount = MasterAccount::where('nama_akun', 'Piutang Usaha')->first();

        $loans = [
            [
                'loan_code' => 'PJM202501040001',
                'borrower_name' => 'Budi Santoso',
                'borrower_phone' => '081234567890',
                'borrower_address' => 'Jl. Merdeka No. 15, Desa Sejahtera',
                'borrower_id_number' => '3201234567890001',
                'loan_type' => 'bunga',
                'loan_amount' => 50000000, // 50 juta
                'interest_rate' => 12.0, // 12% per tahun
                'admin_fee' => 500000, // 500 ribu
                'loan_term_months' => 24, // 2 tahun
                'monthly_payment' => 2353207.79,
                'loan_date' => Carbon::now()->subMonths(6),
                'due_date' => Carbon::now()->addMonths(18),
                'status' => 'active',
                'total_paid' => 14119246.74, // 6 bulan pembayaran
                'remaining_balance' => 35880753.26,
                'notes' => 'Pinjaman untuk modal usaha warung sembako',
                'account_id' => $piutangAccount?->id,
                'created_by' => $admin?->id,
                'approved_at' => Carbon::now()->subMonths(6)->addDays(1),
                'approved_by' => $financeManager?->id,
                'created_at' => Carbon::now()->subMonths(6),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'loan_code' => 'PJM202501040002',
                'borrower_name' => 'Siti Aminah',
                'borrower_phone' => '081234567891',
                'borrower_address' => 'Jl. Raya Desa No. 22, Desa Sejahtera',
                'borrower_id_number' => '3201234567890002',
                'loan_type' => 'bagi_hasil',
                'loan_amount' => 30000000, // 30 juta
                'profit_sharing_percentage' => 30.0, // 30% untuk BUMDes
                'expected_profit' => 12000000, // Keuntungan yang diharapkan 12 juta
                'admin_fee' => 300000, // 300 ribu
                'business_description' => 'Usaha jahit dan konveksi pakaian dengan target keuntungan 12 juta per tahun',
                'loan_term_months' => 18, // 1.5 tahun
                'monthly_payment' => 1897160.49,
                'loan_date' => Carbon::now()->subMonths(4),
                'due_date' => Carbon::now()->addMonths(14),
                'status' => 'active',
                'total_paid' => 7588641.96, // 4 bulan pembayaran
                'remaining_balance' => 22411358.04,
                'notes' => 'Pinjaman bagi hasil untuk pengembangan usaha jahit',
                'account_id' => $piutangAccount?->id,
                'created_by' => $accountant?->id,
                'approved_at' => Carbon::now()->subMonths(4)->addDays(2),
                'approved_by' => $financeManager?->id,
                'created_at' => Carbon::now()->subMonths(4),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'loan_code' => 'PJM202501040003',
                'borrower_name' => 'Ahmad Wijaya',
                'borrower_phone' => '081234567892',
                'borrower_address' => 'Jl. Mawar No. 8, Desa Sejahtera',
                'borrower_id_number' => '3201234567890003',
                'loan_type' => 'tanpa_bunga',
                'loan_amount' => 75000000, // 75 juta
                'admin_fee' => 750000, // 750 ribu
                'loan_term_months' => 36, // 3 tahun
                'monthly_payment' => 2598076.21,
                'loan_date' => Carbon::now()->subMonths(8),
                'due_date' => Carbon::now()->addMonths(28),
                'status' => 'active',
                'total_paid' => 20784609.68, // 8 bulan pembayaran
                'remaining_balance' => 54215390.32,
                'notes' => 'Pinjaman tanpa bunga untuk modal usaha ternak ayam (program sosial)',
                'account_id' => $piutangAccount?->id,
                'created_by' => $admin?->id,
                'approved_at' => Carbon::now()->subMonths(8)->addDays(1),
                'approved_by' => $financeManager?->id,
                'created_at' => Carbon::now()->subMonths(8),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'loan_code' => 'PJM202501040004',
                'borrower_name' => 'Dewi Sartika',
                'borrower_phone' => '081234567893',
                'borrower_address' => 'Jl. Kenanga No. 12, Desa Sejahtera',
                'borrower_id_number' => '3201234567890004',
                'loan_type' => 'bunga',
                'loan_amount' => 25000000, // 25 juta
                'interest_rate' => 8.0, // 8% per tahun
                'admin_fee' => 250000, // 250 ribu
                'loan_term_months' => 12, // 1 tahun
                'monthly_payment' => 2174649.35,
                'loan_date' => Carbon::now()->subMonths(10),
                'due_date' => Carbon::now()->addMonths(2),
                'status' => 'active',
                'total_paid' => 21746493.50, // 10 bulan pembayaran
                'remaining_balance' => 3253506.50,
                'notes' => 'Pinjaman untuk modal usaha catering',
                'account_id' => $piutangAccount?->id,
                'created_by' => $accountant?->id,
                'approved_at' => Carbon::now()->subMonths(10)->addDays(1),
                'approved_by' => $financeManager?->id,
                'created_at' => Carbon::now()->subMonths(10),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            [
                'loan_code' => 'PJM202501040005',
                'borrower_name' => 'Joko Susilo',
                'borrower_phone' => '081234567894',
                'borrower_address' => 'Jl. Melati No. 5, Desa Sejahtera',
                'borrower_id_number' => '3201234567890005',
                'loan_amount' => 40000000, // 40 juta
                'interest_rate' => 11.0, // 11% per tahun
                'loan_term_months' => 30, // 2.5 tahun
                'monthly_payment' => 1551515.15,
                'loan_date' => Carbon::now()->subMonths(3),
                'due_date' => Carbon::now()->addMonths(27),
                'status' => 'active',
                'total_paid' => 4654545.45, // 3 bulan pembayaran
                'remaining_balance' => 35345454.55,
                'notes' => 'Pinjaman untuk modal usaha bengkel motor',
                'account_id' => $piutangAccount?->id,
                'created_by' => $admin?->id,
                'approved_at' => Carbon::now()->subMonths(3)->addDays(2),
                'approved_by' => $financeManager?->id,
                'created_at' => Carbon::now()->subMonths(3),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'loan_code' => 'PJM202501040006',
                'borrower_name' => 'Rina Marlina',
                'borrower_phone' => '081234567895',
                'borrower_address' => 'Jl. Anggrek No. 18, Desa Sejahtera',
                'borrower_id_number' => '3201234567890006',
                'loan_amount' => 60000000, // 60 juta
                'interest_rate' => 13.0, // 13% per tahun
                'loan_term_months' => 24, // 2 tahun
                'monthly_payment' => 2823848.47,
                'loan_date' => Carbon::now()->subMonths(12),
                'due_date' => Carbon::now()->addMonths(12),
                'status' => 'active',
                'total_paid' => 33886181.64, // 12 bulan pembayaran
                'remaining_balance' => 26113818.36,
                'notes' => 'Pinjaman untuk modal usaha toko kelontong',
                'account_id' => $piutangAccount?->id,
                'created_by' => $accountant?->id,
                'approved_at' => Carbon::now()->subMonths(12)->addDays(1),
                'approved_by' => $financeManager?->id,
                'created_at' => Carbon::now()->subMonths(12),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'loan_code' => 'PJM202501040007',
                'borrower_name' => 'Bambang Sutrisno',
                'borrower_phone' => '081234567896',
                'borrower_address' => 'Jl. Dahlia No. 25, Desa Sejahtera',
                'borrower_id_number' => '3201234567890007',
                'loan_amount' => 20000000, // 20 juta
                'interest_rate' => 9.0, // 9% per tahun
                'loan_term_months' => 15, // 1.25 tahun
                'monthly_payment' => 1426984.13,
                'loan_date' => Carbon::now()->subMonths(15),
                'due_date' => Carbon::now(),
                'status' => 'completed',
                'total_paid' => 21404761.95, // Lunas
                'remaining_balance' => 0,
                'notes' => 'Pinjaman untuk modal usaha warung makan - LUNAS',
                'account_id' => $piutangAccount?->id,
                'created_by' => $admin?->id,
                'approved_at' => Carbon::now()->subMonths(15)->addDays(1),
                'approved_by' => $financeManager?->id,
                'created_at' => Carbon::now()->subMonths(15),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'loan_code' => 'PJM202501040008',
                'borrower_name' => 'Sri Wahyuni',
                'borrower_phone' => '081234567897',
                'borrower_address' => 'Jl. Cempaka No. 7, Desa Sejahtera',
                'borrower_id_number' => '3201234567890008',
                'loan_amount' => 35000000, // 35 juta
                'interest_rate' => 14.0, // 14% per tahun
                'loan_term_months' => 20, // 1.67 tahun
                'monthly_payment' => 2020202.02,
                'loan_date' => Carbon::now()->subMonths(22),
                'due_date' => Carbon::now()->subMonths(2),
                'status' => 'overdue',
                'total_paid' => 38383838.38, // Terlambat 2 bulan
                'remaining_balance' => -3383838.38, // Overpaid karena denda
                'notes' => 'Pinjaman untuk modal usaha salon - TERLAMBAT',
                'account_id' => $piutangAccount?->id,
                'created_by' => $accountant?->id,
                'approved_at' => Carbon::now()->subMonths(22)->addDays(2),
                'approved_by' => $financeManager?->id,
                'created_at' => Carbon::now()->subMonths(22),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            [
                'loan_code' => 'PJM202501040009',
                'borrower_name' => 'Hendra Gunawan',
                'borrower_phone' => '081234567898',
                'borrower_address' => 'Jl. Flamboyan No. 30, Desa Sejahtera',
                'borrower_id_number' => '3201234567890009',
                'loan_amount' => 45000000, // 45 juta
                'interest_rate' => 12.5, // 12.5% per tahun
                'loan_term_months' => 18, // 1.5 tahun
                'monthly_payment' => 2777777.78,
                'loan_date' => Carbon::now()->subDays(15),
                'due_date' => Carbon::now()->addMonths(18)->subDays(15),
                'status' => 'pending',
                'total_paid' => 0,
                'remaining_balance' => 45000000,
                'notes' => 'Pinjaman untuk modal usaha fotocopy - MENUNGGU PERSETUJUAN',
                'account_id' => $piutangAccount?->id,
                'created_by' => $admin?->id,
                'approved_at' => null,
                'approved_by' => null,
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            [
                'loan_code' => 'PJM202501040010',
                'borrower_name' => 'Lestari Indah',
                'borrower_phone' => '081234567899',
                'borrower_address' => 'Jl. Bougenville No. 14, Desa Sejahtera',
                'borrower_id_number' => '3201234567890010',
                'loan_amount' => 55000000, // 55 juta
                'interest_rate' => 10.5, // 10.5% per tahun
                'loan_term_months' => 36, // 3 tahun
                'monthly_payment' => 1777777.78,
                'loan_date' => Carbon::now()->subDays(5),
                'due_date' => Carbon::now()->addMonths(36)->subDays(5),
                'status' => 'pending',
                'total_paid' => 0,
                'remaining_balance' => 55000000,
                'notes' => 'Pinjaman untuk modal usaha laundry - MENUNGGU PERSETUJUAN',
                'account_id' => $piutangAccount?->id,
                'created_by' => $accountant?->id,
                'approved_at' => null,
                'approved_by' => null,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
        ];

        foreach ($loans as $loanData) {
            Loan::create($loanData);
        }

        $this->command->info('Loan seeder completed successfully!');
        $this->command->info('Created 10 loan records with various statuses:');
        $this->command->info('- 6 Active loans');
        $this->command->info('- 1 Completed loan');
        $this->command->info('- 1 Overdue loan');
        $this->command->info('- 2 Pending loans');
        $this->command->info('Total loan amount: Rp ' . number_format(array_sum(array_column($loans, 'loan_amount')), 0, ',', '.'));
    }
}
