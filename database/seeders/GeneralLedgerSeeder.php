<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GeneralLedger;
use App\Models\Transaction;
use App\Models\MasterAccount;
use App\Models\User;
use Carbon\Carbon;

class GeneralLedgerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua transaksi yang sudah approved
        $transactions = Transaction::where('status', 'approved')->get();
        
        // Ambil akun-akun yang diperlukan
        $kasAccount = MasterAccount::where('kode_akun', '1-1001')->first(); // Kas
        $pendapatanAccount = MasterAccount::where('kode_akun', '4-1001')->first(); // Pendapatan Penjualan
        $bebanAccount = MasterAccount::where('kode_akun', '5-1001')->first(); // Beban Operasional
        $persediaanAccount = MasterAccount::where('kode_akun', '1-1004')->first(); // Persediaan
        $peralatanAccount = MasterAccount::where('kode_akun', '1-2001')->first(); // Peralatan
        
        // Ambil user admin untuk posted_by
        $adminUser = User::whereHas('role', function($query) {
            $query->where('name', 'admin');
        })->first();
        
        if (!$adminUser) {
            $adminUser = User::first(); // Fallback ke user pertama jika tidak ada admin
        }
        
        $generalLedgerEntries = [];
        $entryCounter = 1;
        
        foreach ($transactions as $transaction) {
            $entryCodeA = 'GL' . str_pad($entryCounter, 6, '0', STR_PAD_LEFT) . 'A';
            $entryCodeB = 'GL' . str_pad($entryCounter, 6, '0', STR_PAD_LEFT) . 'B';
            
            if ($transaction->transaction_type === 'income') {
                // Debit Kas, Kredit Pendapatan
                $generalLedgerEntries[] = [
                    'entry_code' => $entryCodeA,
                    'account_id' => $kasAccount->id,
                    'transaction_id' => $transaction->id,
                    'posting_date' => $transaction->transaction_date,
                    'debit' => $transaction->amount,
                    'credit' => 0,
                    'description' => 'Penerimaan: ' . $transaction->description,
                    'posted_by' => $adminUser->id,
                    'posted_at' => now(),
                    'status' => 'posted',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $generalLedgerEntries[] = [
                    'entry_code' => $entryCodeB,
                    'account_id' => $pendapatanAccount->id,
                    'transaction_id' => $transaction->id,
                    'posting_date' => $transaction->transaction_date,
                    'debit' => 0,
                    'credit' => $transaction->amount,
                    'description' => 'Pendapatan: ' . $transaction->description,
                    'posted_by' => $adminUser->id,
                    'posted_at' => now(),
                    'status' => 'posted',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            } else {
                // Expense - tentukan akun berdasarkan jenis pengeluaran
                $expenseAccountId = $bebanAccount->id; // Default ke beban operasional
                
                // Kategorisasi berdasarkan deskripsi
                if (str_contains(strtolower($transaction->description), 'peralatan') || 
                    str_contains(strtolower($transaction->description), 'investasi')) {
                    $expenseAccountId = $peralatanAccount->id;
                } elseif (str_contains(strtolower($transaction->description), 'bahan baku') || 
                         str_contains(strtolower($transaction->description), 'pembelian')) {
                    $expenseAccountId = $persediaanAccount->id;
                }
                
                // Debit Beban/Aset, Kredit Kas
                $generalLedgerEntries[] = [
                    'entry_code' => $entryCodeA,
                    'account_id' => $expenseAccountId,
                    'transaction_id' => $transaction->id,
                    'posting_date' => $transaction->transaction_date,
                    'debit' => $transaction->amount,
                    'credit' => 0,
                    'description' => 'Pengeluaran: ' . $transaction->description,
                    'posted_by' => $adminUser->id,
                    'posted_at' => now(),
                    'status' => 'posted',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $generalLedgerEntries[] = [
                    'entry_code' => $entryCodeB,
                    'account_id' => $kasAccount->id,
                    'transaction_id' => $transaction->id,
                    'posting_date' => $transaction->transaction_date,
                    'debit' => 0,
                    'credit' => $transaction->amount,
                    'description' => 'Pembayaran: ' . $transaction->description,
                    'posted_by' => $adminUser->id,
                    'posted_at' => now(),
                    'status' => 'posted',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            $entryCounter++;
        }
        
        // Insert semua entri buku besar
        foreach (array_chunk($generalLedgerEntries, 100) as $chunk) {
            GeneralLedger::insert($chunk);
        }
        
        // Tambahkan saldo awal untuk akun-akun utama
        $initialBalances = [
            [
                'entry_code' => 'GL000001',
                'account_id' => $kasAccount->id,
                'transaction_id' => null, // Saldo awal tidak terkait transaksi
                'posting_date' => Carbon::now()->subMonths(4)->startOfMonth(),
                'debit' => 1000000, // Saldo awal kas 1 juta
                'credit' => 0,
                'description' => 'Saldo awal kas',
                'posted_by' => $adminUser->id,
                'posted_at' => now(),
                'status' => 'posted',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        GeneralLedger::insert($initialBalances);
        
        $this->command->info('General Ledger entries created successfully!');
    }
}
