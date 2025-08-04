<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\User;
use Carbon\Carbon;

class LoanFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing loan data to avoid duplicates
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        LoanPayment::truncate();
        Loan::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Ambil user pertama sebagai default user
        $user = User::first();
        
        if (!$user) {
            $this->command->error('No users found. Please run UserSeeder first.');
            return;
        }

        // Ambil akun piutang untuk foreign key
        $piutangAccount = \App\Models\MasterAccount::where('kode_akun', 'LIKE', '1-1%')->first();
        if (!$piutangAccount) {
            $this->command->error('No piutang account found. Please run MasterAccountSeeder first.');
            return;
        }

        // 1. Pinjaman dengan Bunga (sudah jatuh tempo)
        $loanBunga = Loan::create([
            'loan_code' => 'PJM-' . date('Ymd') . '-001',
            'borrower_name' => 'Ahmad Santoso',
            'borrower_phone' => '081234567890',
            'borrower_address' => 'Jl. Merdeka No. 123, Desa Sukamaju',
            'borrower_id_number' => '3201234567890001',
            'loan_amount' => 5000000,
            'loan_type' => 'bunga',
            'interest_rate' => 12.0,
            'admin_fee' => 50000,
            'loan_term_months' => 12,
            'monthly_payment' => 450000,
            'loan_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
            'due_date' => Carbon::now()->addMonths(9)->format('Y-m-d'),
            'status' => 'active',
            'total_paid' => 450000,
            'remaining_balance' => 4550000,
            'notes' => 'Pinjaman untuk modal usaha warung',
            'account_id' => $piutangAccount->id,
            'created_by' => $user->id,
            'approved_at' => Carbon::now()->subMonths(3),
            'approved_by' => $user->id,
        ]);

        // Tambahkan beberapa pembayaran untuk pinjaman bunga (terlambat)
        LoanPayment::create([
            'payment_code' => 'PAY-' . date('Ymd') . '-001',
            'loan_id' => $loanBunga->id,
            'payment_date' => Carbon::now()->subMonths(2)->format('Y-m-d'),
            'payment_amount' => 450000,
            'principal_amount' => 400000,
            'interest_amount' => 50000,
            'penalty_amount' => 0,
            'installment_number' => 1,
            'payment_method' => 'cash',
            'status' => 'approved',
            'notes' => 'Pembayaran cicilan bulan ke-1',
            'created_by' => $user->id,
            'approved_at' => Carbon::now()->subMonths(2),
            'approved_by' => $user->id,
        ]);

        // 2. Pinjaman Bagi Hasil (aktif, belum jatuh tempo)
        $loanBagiHasil = Loan::create([
            'loan_code' => 'PJM-' . date('Ymd') . '-002',
            'borrower_name' => 'Siti Nurhaliza',
            'borrower_phone' => '081234567891',
            'borrower_address' => 'Jl. Raya No. 456, Desa Makmur',
            'borrower_id_number' => '3201234567890002',
            'loan_amount' => 10000000,
            'loan_type' => 'bagi_hasil',
            'interest_rate' => 0,
            'profit_sharing_percentage' => 25.0,
            'expected_profit' => 2500000,
            'admin_fee' => 100000,
            'loan_term_months' => 24,
            'monthly_payment' => 500000,
            'loan_date' => Carbon::now()->subMonths(1)->format('Y-m-d'),
            'due_date' => Carbon::now()->addMonths(23)->format('Y-m-d'),
            'status' => 'active',
            'total_paid' => 500000,
            'remaining_balance' => 9500000,
            'notes' => 'Pinjaman untuk usaha ternak ayam',
            'business_description' => 'Usaha ternak ayam petelur dengan kapasitas 500 ekor, target produksi 400 butir per hari',
            'account_id' => $piutangAccount->id,
            'created_by' => $user->id,
            'approved_at' => Carbon::now()->subMonths(1),
            'approved_by' => $user->id,
        ]);

        // Tambahkan pembayaran untuk pinjaman bagi hasil
        LoanPayment::create([
            'payment_code' => 'PAY-' . date('Ymd') . '-002',
            'loan_id' => $loanBagiHasil->id,
            'payment_date' => Carbon::now()->subDays(15)->format('Y-m-d'),
            'payment_amount' => 500000,
            'principal_amount' => 500000,
            'interest_amount' => 0,
            'penalty_amount' => 0,
            'installment_number' => 1,
            'payment_method' => 'transfer',
            'status' => 'approved',
            'notes' => 'Pembayaran bagi hasil bulan ke-1',
            'created_by' => $user->id,
            'approved_at' => Carbon::now()->subDays(15),
            'approved_by' => $user->id,
        ]);

        // 3. Pinjaman Tanpa Bunga (sudah jatuh tempo)
        $loanTanpaBunga = Loan::create([
            'loan_code' => 'PJM-' . date('Ymd') . '-003',
            'borrower_name' => 'Budi Prasetyo',
            'borrower_phone' => '081234567892',
            'borrower_address' => 'Jl. Sejahtera No. 789, Desa Bahagia',
            'borrower_id_number' => '3201234567890003',
            'loan_amount' => 3000000,
            'loan_type' => 'tanpa_bunga',
            'interest_rate' => 0,
            'admin_fee' => 30000,
            'loan_term_months' => 6,
            'monthly_payment' => 500000,
            'loan_date' => Carbon::now()->subMonths(4)->format('Y-m-d'),
            'due_date' => Carbon::now()->addMonths(2)->format('Y-m-d'),
            'status' => 'active',
            'total_paid' => 1000000,
            'remaining_balance' => 2000000,
            'notes' => 'Pinjaman untuk biaya pendidikan anak',
            'account_id' => $piutangAccount->id,
            'created_by' => $user->id,
            'approved_at' => Carbon::now()->subMonths(4),
            'approved_by' => $user->id,
        ]);

        // Tambahkan pembayaran untuk pinjaman tanpa bunga
        LoanPayment::create([
            'payment_code' => 'PAY-' . date('Ymd') . '-003',
            'loan_id' => $loanTanpaBunga->id,
            'payment_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
            'payment_amount' => 500000,
            'principal_amount' => 500000,
            'interest_amount' => 0,
            'penalty_amount' => 0,
            'installment_number' => 1,
            'payment_method' => 'cash',
            'status' => 'approved',
            'notes' => 'Pembayaran cicilan bulan ke-1',
            'created_by' => $user->id,
            'approved_at' => Carbon::now()->subMonths(3),
            'approved_by' => $user->id,
        ]);

        LoanPayment::create([
            'payment_code' => 'PAY-' . date('Ymd') . '-004',
            'loan_id' => $loanTanpaBunga->id,
            'payment_date' => Carbon::now()->subMonths(2)->format('Y-m-d'),
            'payment_amount' => 500000,
            'principal_amount' => 500000,
            'interest_amount' => 0,
            'penalty_amount' => 0,
            'installment_number' => 2,
            'payment_method' => 'transfer',
            'status' => 'approved',
            'notes' => 'Pembayaran cicilan bulan ke-2',
            'created_by' => $user->id,
            'approved_at' => Carbon::now()->subMonths(2),
            'approved_by' => $user->id,
        ]);

        // 4. Pinjaman Bunga Lainnya (baru, belum jatuh tempo)
        $loanBunga2 = Loan::create([
            'loan_code' => 'PJM-' . date('Ymd') . '-004',
            'borrower_name' => 'Dewi Sartika',
            'borrower_phone' => '081234567893',
            'borrower_address' => 'Jl. Pahlawan No. 321, Desa Merdeka',
            'borrower_id_number' => '3201234567890004',
            'loan_amount' => 7500000,
            'loan_type' => 'bunga',
            'interest_rate' => 15.0,
            'admin_fee' => 75000,
            'loan_term_months' => 18,
            'monthly_payment' => 520000,
            'loan_date' => Carbon::now()->subDays(20)->format('Y-m-d'),
            'due_date' => Carbon::now()->addMonths(17)->addDays(10)->format('Y-m-d'),
            'status' => 'active',
            'total_paid' => 0,
            'remaining_balance' => 7500000,
            'notes' => 'Pinjaman untuk modal usaha jahit',
            'account_id' => $piutangAccount->id,
            'created_by' => $user->id,
            'approved_at' => Carbon::now()->subDays(20),
            'approved_by' => $user->id,
        ]);

        // 5. Pinjaman yang sudah lunas
        $loanCompleted = Loan::create([
            'loan_code' => 'PJM-' . date('Ymd') . '-005',
            'borrower_name' => 'Eko Wijaya',
            'borrower_phone' => '081234567894',
            'borrower_address' => 'Jl. Mandiri No. 654, Desa Sejahtera',
            'borrower_id_number' => '3201234567890005',
            'loan_amount' => 2000000,
            'loan_type' => 'bunga',
            'interest_rate' => 10.0,
            'admin_fee' => 20000,
            'loan_term_months' => 6,
            'monthly_payment' => 350000,
            'loan_date' => Carbon::now()->subMonths(8)->format('Y-m-d'),
            'due_date' => Carbon::now()->subMonths(2)->format('Y-m-d'),
            'status' => 'completed',
            'total_paid' => 2100000,
            'remaining_balance' => 0,
            'notes' => 'Pinjaman untuk modal usaha kecil (sudah lunas)',
            'account_id' => $piutangAccount->id,
            'created_by' => $user->id,
            'approved_at' => Carbon::now()->subMonths(8),
            'approved_by' => $user->id,
        ]);

        // Tambahkan pembayaran lengkap untuk pinjaman yang sudah lunas
        for ($i = 1; $i <= 6; $i++) {
            LoanPayment::create([
                'payment_code' => 'PAY-' . date('Ymd') . '-' . str_pad(4 + $i, 3, '0', STR_PAD_LEFT),
                'loan_id' => $loanCompleted->id,
                'payment_date' => Carbon::now()->subMonths(8 - $i)->format('Y-m-d'),
                'payment_amount' => 350000, // Cicilan pokok + bunga
                'principal_amount' => 333333,
                'interest_amount' => 16667,
                'penalty_amount' => 0,
                'installment_number' => $i,
                'payment_method' => 'cash',
                'status' => 'approved',
                'notes' => "Pembayaran cicilan bulan ke-{$i}",
                'created_by' => $user->id,
                'approved_at' => Carbon::now()->subMonths(8 - $i),
                'approved_by' => $user->id,
            ]);
        }

        // 6. Pinjaman dengan status pending payment
        $loanPending = Loan::create([
            'loan_code' => 'PJM-' . date('Ymd') . '-006',
            'borrower_name' => 'Rina Melati',
            'borrower_phone' => '081234567895',
            'borrower_address' => 'Jl. Harapan No. 987, Desa Jaya',
            'borrower_id_number' => '3201234567890006',
            'loan_amount' => 4000000,
            'loan_type' => 'tanpa_bunga',
            'interest_rate' => 0,
            'admin_fee' => 40000,
            'loan_term_months' => 12,
            'monthly_payment' => 400000,
            'loan_date' => Carbon::now()->subMonths(2)->format('Y-m-d'),
            'due_date' => Carbon::now()->addMonths(10)->format('Y-m-d'),
            'status' => 'active',
            'total_paid' => 400000,
            'remaining_balance' => 3600000,
            'notes' => 'Pinjaman untuk renovasi rumah',
            'account_id' => $piutangAccount->id,
            'created_by' => $user->id,
            'approved_at' => Carbon::now()->subMonths(2),
            'approved_by' => $user->id,
        ]);

        // Tambahkan pembayaran pending
        LoanPayment::create([
            'payment_code' => 'PAY-' . date('Ymd') . '-011',
            'loan_id' => $loanPending->id,
            'payment_date' => Carbon::now()->subDays(5)->format('Y-m-d'),
            'payment_amount' => 400000,
            'principal_amount' => 400000,
            'interest_amount' => 0,
            'penalty_amount' => 0,
            'installment_number' => 1,
            'payment_method' => 'cash',
            'status' => 'pending',
            'notes' => 'Pembayaran cicilan bulan ke-1 (menunggu approval)',
            'created_by' => $user->id,
        ]);

        $this->command->info('Loan feature seeder completed successfully!');
        $this->command->info('Created:');
        $this->command->info('- 6 loans with different types and statuses');
        $this->command->info('- Multiple loan payments with various statuses');
        $this->command->info('- Some loans are overdue to test due receivables feature');
    }
}
