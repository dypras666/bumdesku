<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\MasterAccount;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $accounts = MasterAccount::all();

        if ($users->isEmpty() || $accounts->isEmpty()) {
            $this->command->warn('Users atau Master Accounts belum ada. Jalankan seeder tersebut terlebih dahulu.');
            return;
        }

        $transactions = [
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::now()->subDays(15),
                'amount' => 2500000,
                'description' => 'Penjualan produk desa - Kerajinan bambu',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subDays(14),
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::now()->subDays(14),
                'amount' => 1200000,
                'description' => 'Pembelian bahan baku - Bambu dan rotan',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subDays(13),
            ],
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::now()->subDays(13),
                'amount' => 800000,
                'description' => 'Jasa konsultasi pengembangan UMKM',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subDays(12),
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::now()->subDays(12),
                'amount' => 500000,
                'description' => 'Biaya operasional kantor bulan ini',
                'status' => 'pending',
            ],
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::now()->subDays(10),
                'amount' => 1800000,
                'description' => 'Penjualan hasil pertanian - Padi organik',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subDays(9),
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::now()->subDays(8),
                'amount' => 750000,
                'description' => 'Pembelian pupuk organik',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subDays(7),
            ],
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::now()->subDays(5),
                'amount' => 3200000,
                'description' => 'Penjualan produk olahan - Keripik singkong',
                'status' => 'pending',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::now()->subDays(3),
                'amount' => 450000,
                'description' => 'Biaya transportasi distribusi produk',
                'status' => 'pending',
            ],
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::now()->subDays(2),
                'amount' => 1500000,
                'description' => 'Pendapatan dari wisata desa',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subDays(1),
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::now()->subDays(1),
                'amount' => 300000,
                'description' => 'Biaya maintenance peralatan',
                'status' => 'rejected',
                'approved_at' => Carbon::now(),
                'notes' => 'Perlu approval dari kepala desa terlebih dahulu',
            ],
        ];

        foreach ($transactions as $index => $transactionData) {
            $user = $users->random();
            $account = $accounts->random();
            $approver = $users->where('id', '!=', $user->id)->random();

            $transactionCode = Transaction::generateTransactionCode($transactionData['transaction_type']);

            Transaction::create([
                'transaction_code' => $transactionCode,
                'transaction_type' => $transactionData['transaction_type'],
                'transaction_date' => $transactionData['transaction_date'],
                'amount' => $transactionData['amount'],
                'description' => $transactionData['description'],
                'account_id' => $account->id,
                'user_id' => $user->id,
                'status' => $transactionData['status'],
                'approved_at' => $transactionData['approved_at'] ?? null,
                'approved_by' => isset($transactionData['approved_at']) ? $approver->id : null,
                'notes' => $transactionData['notes'] ?? null,
            ]);
        }

        $this->command->info('Transaction seeder completed successfully!');
    }
}
