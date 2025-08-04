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

        // Data transaksi untuk 3 bulan terakhir (lebih lengkap dan realistis)
        $transactions = [
            // Bulan 1 (3 bulan lalu)
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::now()->subMonths(3)->addDays(5),
                'amount' => 5000000,
                'description' => 'Modal awal dari dana desa',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subMonths(3)->addDays(6),
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::now()->subMonths(3)->addDays(10),
                'amount' => 2000000,
                'description' => 'Pembelian peralatan produksi kerajinan',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subMonths(3)->addDays(11),
            ],
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::now()->subMonths(3)->addDays(15),
                'amount' => 1500000,
                'description' => 'Penjualan kerajinan bambu batch pertama',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subMonths(3)->addDays(16),
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::now()->subMonths(3)->addDays(20),
                'amount' => 800000,
                'description' => 'Pembelian bahan baku bambu dan rotan',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subMonths(3)->addDays(21),
            ],
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::now()->subMonths(3)->addDays(25),
                'amount' => 2200000,
                'description' => 'Penjualan hasil pertanian organik',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subMonths(3)->addDays(26),
            ],
            
            // Bulan 2 (2 bulan lalu)
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::now()->subMonths(2)->addDays(3),
                'amount' => 1200000,
                'description' => 'Biaya operasional dan gaji karyawan',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subMonths(2)->addDays(4),
            ],
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::now()->subMonths(2)->addDays(8),
                'amount' => 3500000,
                'description' => 'Penjualan produk olahan makanan',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subMonths(2)->addDays(9),
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::now()->subMonths(2)->addDays(12),
                'amount' => 900000,
                'description' => 'Pembelian bahan baku makanan olahan',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subMonths(2)->addDays(13),
            ],
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::now()->subMonths(2)->addDays(18),
                'amount' => 1800000,
                'description' => 'Pendapatan dari wisata desa',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subMonths(2)->addDays(19),
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::now()->subMonths(2)->addDays(22),
                'amount' => 600000,
                'description' => 'Biaya promosi dan marketing',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subMonths(2)->addDays(23),
            ],
            
            // Bulan 3 (1 bulan lalu)
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::now()->subMonths(1)->addDays(5),
                'amount' => 4200000,
                'description' => 'Penjualan produk kerajinan ke kota',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subMonths(1)->addDays(6),
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::now()->subMonths(1)->addDays(10),
                'amount' => 1500000,
                'description' => 'Investasi peralatan baru',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subMonths(1)->addDays(11),
            ],
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::now()->subMonths(1)->addDays(15),
                'amount' => 2800000,
                'description' => 'Kontrak jasa konsultasi UMKM',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subMonths(1)->addDays(16),
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::now()->subMonths(1)->addDays(20),
                'amount' => 1100000,
                'description' => 'Biaya operasional dan maintenance',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subMonths(1)->addDays(21),
            ],
            
            // Bulan ini (transaksi terbaru)
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
                'transaction_date' => Carbon::now()->subDays(12),
                'amount' => 800000,
                'description' => 'Pembelian bahan baku bulanan',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subDays(11),
            ],
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::now()->subDays(8),
                'amount' => 1800000,
                'description' => 'Penjualan hasil pertanian organik',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subDays(7),
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::now()->subDays(5),
                'amount' => 500000,
                'description' => 'Biaya operasional kantor',
                'status' => 'pending',
            ],
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::now()->subDays(3),
                'amount' => 3200000,
                'description' => 'Penjualan produk olahan - Keripik singkong',
                'status' => 'approved',
                'approved_at' => Carbon::now()->subDays(2),
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::now()->subDays(1),
                'amount' => 300000,
                'description' => 'Biaya transportasi distribusi',
                'status' => 'pending',
            ],
        ];

        // Get specific accounts for proper categorization
        $kasAccount = MasterAccount::where('nama_akun', 'like', '%Kas%')->first();
        $pendapatanAccount = MasterAccount::where('kategori_akun', 'Pendapatan')->first();
        $bebanAccount = MasterAccount::where('kategori_akun', 'Beban')->first();
        $peralatanAccount = MasterAccount::where('nama_akun', 'like', '%Peralatan%')->first();
        $persediaanAccount = MasterAccount::where('nama_akun', 'like', '%Persediaan%')->first();

        foreach ($transactions as $index => $transactionData) {
            $user = $users->random();
            $approver = $users->where('id', '!=', $user->id)->random();

            // Select appropriate account based on transaction type and description
            $account = $kasAccount; // Default to cash account
            
            if ($transactionData['transaction_type'] === 'income') {
                if (str_contains($transactionData['description'], 'Modal') || 
                    str_contains($transactionData['description'], 'dana desa')) {
                    $account = MasterAccount::where('kategori_akun', 'Modal')->first() ?: $kasAccount;
                } else {
                    $account = $pendapatanAccount ?: $kasAccount;
                }
            } elseif ($transactionData['transaction_type'] === 'expense') {
                if (str_contains($transactionData['description'], 'peralatan') || 
                    str_contains($transactionData['description'], 'Investasi')) {
                    $account = $peralatanAccount ?: $kasAccount;
                } elseif (str_contains($transactionData['description'], 'bahan baku') || 
                          str_contains($transactionData['description'], 'Pembelian bahan')) {
                    $account = $persediaanAccount ?: $kasAccount;
                } else {
                    $account = $bebanAccount ?: $kasAccount;
                }
            }

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
