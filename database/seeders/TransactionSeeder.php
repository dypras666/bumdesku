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
     * Data transaksi untuk Juni-Juli 2025
     * Juni: Balance (pendapatan = pengeluaran)
     * Juli: Minus (pengeluaran > pendapatan)
     */
    public function run(): void
    {
        $users = User::all();
        $accounts = MasterAccount::all();

        if ($users->isEmpty() || $accounts->isEmpty()) {
            $this->command->warn('Users atau Master Accounts belum ada. Jalankan seeder tersebut terlebih dahulu.');
            return;
        }

        // Data transaksi untuk Juni-Juli 2025
        $transactions = [
            // ===== JUNI 2025 - BALANCE (Pendapatan = Pengeluaran) =====
            
            // Minggu 1 Juni 2025
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 6, 2),
                'amount' => 15000000,
                'description' => 'Penjualan produk kerajinan bambu ke Jakarta',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 6, 3),
                'account_code' => '4-1001',
            ],
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 6, 4),
                'amount' => 8000000,
                'description' => 'Pendapatan wisata desa - paket tour',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 6, 5),
                'account_code' => '4-1003',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 6, 5),
                'amount' => 5000000,
                'description' => 'Pembelian bahan baku bambu dan rotan',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 6, 6),
                'account_code' => '5-1002',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 6, 6),
                'amount' => 3000000,
                'description' => 'Gaji karyawan produksi bulan Juni',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 6, 7),
                'account_code' => '5-1001',
            ],

            // Minggu 2 Juni 2025
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 6, 9),
                'amount' => 12000000,
                'description' => 'Penjualan produk olahan makanan ke supermarket',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 6, 10),
                'account_code' => '4-1001',
            ],
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 6, 11),
                'amount' => 6000000,
                'description' => 'Jasa konsultasi UMKM untuk desa tetangga',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 6, 12),
                'account_code' => '4-1002',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 6, 12),
                'amount' => 4000000,
                'description' => 'Pembelian peralatan produksi baru',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 6, 13),
                'account_code' => '1-2003',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 6, 13),
                'amount' => 2500000,
                'description' => 'Biaya pemasaran dan promosi',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 6, 14),
                'account_code' => '5-1005',
            ],

            // Minggu 3 Juni 2025
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 6, 16),
                'amount' => 10000000,
                'description' => 'Penjualan hasil pertanian organik',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 6, 17),
                'account_code' => '4-1001',
            ],
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 6, 18),
                'amount' => 7000000,
                'description' => 'Pendapatan dari event wisata desa',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 6, 19),
                'account_code' => '4-1003',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 6, 19),
                'amount' => 3500000,
                'description' => 'Biaya listrik dan air bulan Juni',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 6, 20),
                'account_code' => '5-1003',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 6, 20),
                'amount' => 2000000,
                'description' => 'Biaya transportasi dan distribusi',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 6, 21),
                'account_code' => '5-1004',
            ],

            // Minggu 4 Juni 2025
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 6, 23),
                'amount' => 9000000,
                'description' => 'Penjualan kerajinan tangan ke toko souvenir',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 6, 24),
                'account_code' => '4-1001',
            ],
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 6, 25),
                'amount' => 5000000,
                'description' => 'Pendapatan lain-lain dari sewa tempat',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 6, 26),
                'account_code' => '4-1004',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 6, 26),
                'amount' => 4000000,
                'description' => 'Biaya administrasi dan operasional',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 6, 27),
                'account_code' => '5-1006',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 6, 28),
                'amount' => 3000000,
                'description' => 'Biaya penyusutan peralatan',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 6, 29),
                'account_code' => '5-1007',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 6, 30),
                'amount' => 2500000,
                'description' => 'Biaya bunga pinjaman bank',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 6, 30),
                'account_code' => '5-1008',
            ],

            // ===== JULI 2025 - MINUS (Pengeluaran > Pendapatan) =====
            
            // Minggu 1 Juli 2025
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 7, 2),
                'amount' => 8000000,
                'description' => 'Penjualan produk kerajinan - menurun karena musim sepi',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 7, 3),
                'account_code' => '4-1001',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 7, 3),
                'amount' => 15000000,
                'description' => 'Investasi besar - pembelian mesin produksi baru',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 7, 4),
                'account_code' => '1-2003',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 7, 4),
                'amount' => 8000000,
                'description' => 'Renovasi bangunan produksi',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 7, 5),
                'account_code' => '1-2002',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 7, 5),
                'amount' => 5000000,
                'description' => 'Gaji karyawan bulan Juli + bonus',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 7, 6),
                'account_code' => '5-1001',
            ],

            // Minggu 2 Juli 2025
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 7, 9),
                'amount' => 6000000,
                'description' => 'Penjualan produk olahan - turun karena kompetisi',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 7, 10),
                'account_code' => '4-1001',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 7, 10),
                'amount' => 12000000,
                'description' => 'Pembelian kendaraan baru untuk distribusi',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 7, 11),
                'account_code' => '1-2004',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 7, 11),
                'amount' => 6000000,
                'description' => 'Biaya pelatihan karyawan dan sertifikasi',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 7, 12),
                'account_code' => '5-1006',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 7, 12),
                'amount' => 4000000,
                'description' => 'Biaya konsultan untuk pengembangan produk',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 7, 13),
                'account_code' => '5-1009',
            ],

            // Minggu 3 Juli 2025
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 7, 16),
                'amount' => 4000000,
                'description' => 'Pendapatan wisata desa - sangat menurun',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 7, 17),
                'account_code' => '4-1003',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 7, 17),
                'amount' => 10000000,
                'description' => 'Pembelian bahan baku dalam jumlah besar',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 7, 18),
                'account_code' => '5-1002',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 7, 18),
                'amount' => 7000000,
                'description' => 'Biaya pemasaran digital dan iklan online',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 7, 19),
                'account_code' => '5-1005',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 7, 19),
                'amount' => 5000000,
                'description' => 'Biaya listrik tinggi karena mesin baru',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 7, 20),
                'account_code' => '5-1003',
            ],

            // Minggu 4 Juli 2025
            [
                'transaction_type' => 'income',
                'transaction_date' => Carbon::create(2025, 7, 23),
                'amount' => 3000000,
                'description' => 'Penjualan sisa produk dengan diskon',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 7, 24),
                'account_code' => '4-1001',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 7, 24),
                'amount' => 8000000,
                'description' => 'Pembayaran hutang supplier yang jatuh tempo',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 7, 25),
                'account_code' => '2-1001',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 7, 25),
                'amount' => 6000000,
                'description' => 'Biaya maintenance peralatan dan kendaraan',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 7, 26),
                'account_code' => '5-1009',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 7, 26),
                'amount' => 4000000,
                'description' => 'Biaya transportasi ekspor ke luar negeri',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 7, 27),
                'account_code' => '5-1004',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 7, 28),
                'amount' => 3000000,
                'description' => 'Biaya bunga pinjaman dan administrasi bank',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 7, 29),
                'account_code' => '5-1008',
            ],
            [
                'transaction_type' => 'expense',
                'transaction_date' => Carbon::create(2025, 7, 30),
                'amount' => 5000000,
                'description' => 'Biaya tak terduga - perbaikan kerusakan mesin',
                'status' => 'approved',
                'approved_at' => Carbon::create(2025, 7, 31),
                'account_code' => '5-1009',
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
        $juniIncome = collect($transactions)->where('transaction_date', '>=', Carbon::create(2025, 6, 1))
                                          ->where('transaction_date', '<=', Carbon::create(2025, 6, 30))
                                          ->where('transaction_type', 'income')
                                          ->sum('amount');
        
        $juniExpense = collect($transactions)->where('transaction_date', '>=', Carbon::create(2025, 6, 1))
                                           ->where('transaction_date', '<=', Carbon::create(2025, 6, 30))
                                           ->where('transaction_type', 'expense')
                                           ->sum('amount');

        $juliIncome = collect($transactions)->where('transaction_date', '>=', Carbon::create(2025, 7, 1))
                                          ->where('transaction_date', '<=', Carbon::create(2025, 7, 31))
                                          ->where('transaction_type', 'income')
                                          ->sum('amount');
        
        $juliExpense = collect($transactions)->where('transaction_date', '>=', Carbon::create(2025, 7, 1))
                                           ->where('transaction_date', '<=', Carbon::create(2025, 7, 31))
                                           ->where('transaction_type', 'expense')
                                           ->sum('amount');

        $this->command->info('Transaction seeder completed successfully!');
        $this->command->info('=== JUNI 2025 ===');
        $this->command->info('Pendapatan: Rp ' . number_format($juniIncome));
        $this->command->info('Pengeluaran: Rp ' . number_format($juniExpense));
        $this->command->info('Selisih: Rp ' . number_format($juniIncome - $juniExpense) . ' (Balance)');
        $this->command->info('=== JULI 2025 ===');
        $this->command->info('Pendapatan: Rp ' . number_format($juliIncome));
        $this->command->info('Pengeluaran: Rp ' . number_format($juliExpense));
        $this->command->info('Selisih: Rp ' . number_format($juliIncome - $juliExpense) . ' (Minus)');
    }
}
