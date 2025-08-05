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
     * Data transaksi untuk Agustus 2025 (tanggal 5 Agustus 2025 sebagai base)
     * Agustus: Plus (pendapatan > pengeluaran)
     */
    public function run(): void
    {
        $users = User::all();
        $accounts = MasterAccount::all();

        if ($users->isEmpty() || $accounts->isEmpty()) {
            $this->command->warn('Users atau Master Accounts belum ada. Jalankan seeder tersebut terlebih dahulu.');
            return;
        }

        // Data transaksi untuk Agustus 2025 - dimulai dari tanggal 5 Agustus 2025
        $transactions = [
            // ===== AGUSTUS 2025 - PLUS (Pendapatan > Pengeluaran) =====
            
            // Tanggal 5 Agustus 2025
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 8, 5),
                'amount' => 20000000,
                'description' => 'Kontrak besar - penjualan produk ke perusahaan multinasional',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 8, 5),
                'account_code' => '4-1001',
            ],
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 8, 5),
                'amount' => 15000000,
                'description' => 'Pendapatan dari festival wisata desa yang sukses',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 8, 5),
                'account_code' => '4-1003',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 8, 5),
                'amount' => 3000000,
                'description' => 'Biaya operasional festival',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 8, 5),
                'account_code' => '5-1006',
            ],

            // Tanggal 6 Agustus 2025
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 8, 6),
                'amount' => 12000000,
                'description' => 'Penjualan produk kerajinan bambu ke Jakarta',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 8, 6),
                'account_code' => '4-1001',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 8, 6),
                'amount' => 5000000,
                'description' => 'Pembelian bahan baku bambu dan rotan',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 8, 6),
                'account_code' => '5-1002',
            ],

            // Tanggal 7 Agustus 2025
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 8, 7),
                'amount' => 18000000,
                'description' => 'Penjualan produk makanan olahan ke distributor',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 8, 7),
                'account_code' => '4-1001',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 8, 7),
                'amount' => 4000000,
                'description' => 'Gaji karyawan produksi bulan Agustus',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 8, 7),
                'account_code' => '5-1001',
            ],

            // Tanggal 8 Agustus 2025
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 8, 8),
                'amount' => 10000000,
                'description' => 'Pendapatan wisata desa - paket tour',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 8, 8),
                'account_code' => '4-1003',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 8, 8),
                'amount' => 2500000,
                'description' => 'Biaya pemasaran dan promosi',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 8, 8),
                'account_code' => '5-1005',
            ],

            // Tanggal 9 Agustus 2025
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 8, 9),
                'amount' => 14000000,
                'description' => 'Penjualan hasil pertanian organik',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 8, 9),
                'account_code' => '4-1001',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 8, 9),
                'amount' => 3500000,
                'description' => 'Biaya listrik dan air bulan Agustus',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 8, 9),
                'account_code' => '5-1003',
            ],

            // Tanggal 10 Agustus 2025
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 8, 10),
                'amount' => 16000000,
                'description' => 'Penjualan produk makanan olahan ke distributor nasional',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 8, 10),
                'account_code' => '4-1001',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 8, 10),
                'amount' => 2000000,
                'description' => 'Biaya transportasi dan distribusi',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 8, 10),
                'account_code' => '5-1004',
            ],

            // Tanggal 11 Agustus 2025
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 8, 11),
                'amount' => 9000000,
                'description' => 'Pendapatan dari kemitraan dengan hotel resort',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 8, 11),
                'account_code' => '4-1003',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 8, 11),
                'amount' => 1500000,
                'description' => 'Biaya administrasi dan operasional',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 8, 11),
                'account_code' => '5-1006',
            ],

            // Tanggal 12 Agustus 2025
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 8, 12),
                'amount' => 11000000,
                'description' => 'Penjualan kerajinan tangan ke toko souvenir',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 8, 12),
                'account_code' => '4-1001',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 8, 12),
                'amount' => 2200000,
                'description' => 'Biaya maintenance dan perawatan rutin',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 8, 12),
                'account_code' => '5-1009',
            ],

            // Beberapa transaksi pending untuk testing
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 8, 13),
                'amount' => 5000000,
                'description' => 'Penjualan produk - menunggu konfirmasi',
                'status' => 'pending',
                'account_code' => '4-1001',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 8, 13),
                'amount' => 1000000,
                'description' => 'Biaya operasional - menunggu approval',
                'status' => 'pending',
                'account_code' => '5-1006',
            ],
        ];

        foreach ($transactions as $index => $transactionData) {
            $user = $users->random();
            $approver = $users->where('id', '!=', $user->id)->random();

            // Get account by code
            $account = MasterAccount::where('kode_akun', $transactionData['account_code'])->first();
            if (!$account) {
                // Fallback to cash account if specific account not found
                $account = MasterAccount::where('kode_akun', '1-1001')->first();
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

        // Hitung total untuk laporan
        $agustusIncome = collect($transactions)->where('transaction_date', '>=', Carbon::create(2025, 8, 5))
                                             ->where('transaction_date', '<=', Carbon::create(2025, 8, 31))
                                             ->where('transaction_type', 'income')
                                             ->where('status', 'approved') // Hanya yang approved
                                             ->sum('amount');
        
        $agustusExpense = collect($transactions)->where('transaction_date', '>=', Carbon::create(2025, 8, 5))
                                              ->where('transaction_date', '<=', Carbon::create(2025, 8, 31))
                                              ->where('transaction_type', 'expense')
                                              ->where('status', 'approved') // Hanya yang approved
                                              ->sum('amount');

        $this->command->info('Transaction seeder completed successfully!');
        $this->command->info('=== AGUSTUS 2025 (mulai 5 Agustus) ===');
        $this->command->info('Pendapatan: Rp ' . number_format($agustusIncome));
        $this->command->info('Pengeluaran: Rp ' . number_format($agustusExpense));
        $this->command->info('Selisih: Rp ' . number_format($agustusIncome - $agustusExpense) . ' (Plus)');
        $this->command->info('Total transaksi: ' . count($transactions));
    }
}
