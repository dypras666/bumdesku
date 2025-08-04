<?php

namespace Database\Seeders;

use App\Models\GeneralLedger;
use App\Models\Transaction;
use App\Models\MasterAccount;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class GeneralLedgerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat jurnal umum untuk periode Juni-Juli 2025
     */
    public function run(): void
    {
        // Get all approved transactions
        $approvedTransactions = Transaction::where('status', 'approved')->get();

        if ($approvedTransactions->isEmpty()) {
            $this->command->warn('Tidak ada transaksi yang disetujui. Jalankan TransactionSeeder terlebih dahulu.');
            return;
        }

        // Get cash account for contra entries
        $kasAccount = MasterAccount::where('kode_akun', '1-1001')->first();

        if (!$kasAccount) {
            $this->command->error('Akun Kas tidak ditemukan. Pastikan MasterAccountSeeder sudah dijalankan.');
            return;
        }

        // Get admin user for posted_by
        $adminUser = User::first();
        if (!$adminUser) {
            $this->command->error('Tidak ada user ditemukan. Pastikan UserSeeder sudah dijalankan.');
            return;
        }

        $entryCounter = 1;
        foreach ($approvedTransactions as $transaction) {
            $transactionAccount = MasterAccount::find($transaction->account_id);

            if (!$transactionAccount) {
                $this->command->warn("Account tidak ditemukan untuk transaksi: {$transaction->transaction_code}");
                continue;
            }

            if ($transaction->transaction_type === 'income') {
                // For income: Debit Cash, Credit Revenue Account
                
                // Debit Cash
                $entryCodeDebit = 'GL-' . $transaction->transaction_date->format('Ymd') . '-' . str_pad($entryCounter, 4, '0', STR_PAD_LEFT);
                $entryCounter++;
                
                GeneralLedger::create([
                    'entry_code' => $entryCodeDebit,
                    'posting_date' => $transaction->transaction_date,
                    'account_id' => $kasAccount->id,
                    'debit' => $transaction->amount,
                    'credit' => 0,
                    'description' => $transaction->description,
                    'transaction_id' => $transaction->id,
                    'reference_number' => $transaction->transaction_code,
                    'posted_by' => $adminUser->id,
                    'posted_at' => $transaction->approved_at,
                    'status' => 'posted',
                ]);

                // Credit Revenue Account
                $entryCodeCredit = 'GL-' . $transaction->transaction_date->format('Ymd') . '-' . str_pad($entryCounter, 4, '0', STR_PAD_LEFT);
                $entryCounter++;
                
                GeneralLedger::create([
                    'entry_code' => $entryCodeCredit,
                    'posting_date' => $transaction->transaction_date,
                    'account_id' => $transactionAccount->id,
                    'debit' => 0,
                    'credit' => $transaction->amount,
                    'description' => $transaction->description,
                    'transaction_id' => $transaction->id,
                    'reference_number' => $transaction->transaction_code,
                    'posted_by' => $adminUser->id,
                    'posted_at' => $transaction->approved_at,
                    'status' => 'posted',
                ]);

            } elseif ($transaction->transaction_type === 'expense') {
                // For expense: Debit Expense/Asset Account, Credit Cash
                
                // Debit Expense/Asset Account
                $entryCodeDebit = 'GL-' . $transaction->transaction_date->format('Ymd') . '-' . str_pad($entryCounter, 4, '0', STR_PAD_LEFT);
                $entryCounter++;
                
                GeneralLedger::create([
                    'entry_code' => $entryCodeDebit,
                    'posting_date' => $transaction->transaction_date,
                    'account_id' => $transactionAccount->id,
                    'debit' => $transaction->amount,
                    'credit' => 0,
                    'description' => $transaction->description,
                    'transaction_id' => $transaction->id,
                    'reference_number' => $transaction->transaction_code,
                    'posted_by' => $adminUser->id,
                    'posted_at' => $transaction->approved_at,
                    'status' => 'posted',
                ]);

                // Credit Cash (except for liability payments)
                $entryCodeCredit = 'GL-' . $transaction->transaction_date->format('Ymd') . '-' . str_pad($entryCounter, 4, '0', STR_PAD_LEFT);
                $entryCounter++;
                
                if ($transactionAccount->kategori_akun !== 'Kewajiban') {
                    GeneralLedger::create([
                        'entry_code' => $entryCodeCredit,
                        'posting_date' => $transaction->transaction_date,
                        'account_id' => $kasAccount->id,
                        'debit' => 0,
                        'credit' => $transaction->amount,
                        'description' => $transaction->description,
                        'transaction_id' => $transaction->id,
                        'reference_number' => $transaction->transaction_code,
                        'posted_by' => $adminUser->id,
                        'posted_at' => $transaction->approved_at,
                        'status' => 'posted',
                    ]);
                } else {
                    // For liability payments: Debit Liability, Credit Cash
                    // The debit entry above is correct, just need to credit cash
                    GeneralLedger::create([
                        'entry_code' => $entryCodeCredit,
                        'posting_date' => $transaction->transaction_date,
                        'account_id' => $kasAccount->id,
                        'debit' => 0,
                        'credit' => $transaction->amount,
                        'description' => $transaction->description,
                        'transaction_id' => $transaction->id,
                        'reference_number' => $transaction->transaction_code,
                        'posted_by' => $adminUser->id,
                        'posted_at' => $transaction->approved_at,
                        'status' => 'posted',
                    ]);
                }
            }
        }

        // Add initial balance entries for all accounts with saldo_awal > 0
        $accountsWithInitialBalance = MasterAccount::where('saldo_awal', '>', 0)->get();
        
        foreach ($accountsWithInitialBalance as $account) {
            $initialEntryCode = 'GL-20250601-' . str_pad($entryCounter, 4, '0', STR_PAD_LEFT);
            $entryCounter++;
            
            // Determine debit/credit based on account category
            $isDebitAccount = in_array($account->kategori_akun, ['Aset', 'Beban']);
            
            GeneralLedger::create([
                'entry_code' => $initialEntryCode,
                'posting_date' => Carbon::create(2025, 6, 1), // Start of June 2025
                'account_id' => $account->id,
                'debit' => $isDebitAccount ? $account->saldo_awal : 0,
                'credit' => $isDebitAccount ? 0 : $account->saldo_awal,
                'description' => "Saldo awal {$account->nama_akun} per 1 Juni 2025",
                'transaction_id' => null,
                'reference_number' => 'INIT-' . str_pad($account->id, 3, '0', STR_PAD_LEFT),
                'posted_by' => $adminUser->id,
                'posted_at' => Carbon::create(2025, 6, 1),
                'status' => 'posted',
            ]);
        }

        // Add balancing entry for initial balances (Modal/Equity)
        $totalInitialAssets = MasterAccount::where('kategori_akun', 'Aset')->sum('saldo_awal');
        $totalInitialLiabilities = MasterAccount::where('kategori_akun', 'Kewajiban')->sum('saldo_awal');
        $totalInitialEquity = MasterAccount::where('kategori_akun', 'Modal')->sum('saldo_awal');
        
        $balancingAmount = $totalInitialAssets - $totalInitialLiabilities - $totalInitialEquity;
        
        if ($balancingAmount != 0) {
            $modalAccount = MasterAccount::where('kategori_akun', 'Modal')->first();
            if ($modalAccount) {
                $balancingEntryCode = 'GL-20250601-' . str_pad($entryCounter, 4, '0', STR_PAD_LEFT);
                $entryCounter++;
                
                GeneralLedger::create([
                    'entry_code' => $balancingEntryCode,
                    'posting_date' => Carbon::create(2025, 6, 1),
                    'account_id' => $modalAccount->id,
                    'debit' => $balancingAmount < 0 ? abs($balancingAmount) : 0,
                    'credit' => $balancingAmount > 0 ? $balancingAmount : 0,
                    'description' => 'Penyesuaian saldo awal untuk balance sheet',
                    'transaction_id' => null,
                    'reference_number' => 'INIT-BAL',
                    'posted_by' => $adminUser->id,
                    'posted_at' => Carbon::create(2025, 6, 1),
                    'status' => 'posted',
                ]);
            }
        }

        $totalEntries = GeneralLedger::count();
        $totalDebit = GeneralLedger::sum('debit');
        $totalCredit = GeneralLedger::sum('credit');

        $this->command->info('General Ledger seeder completed successfully!');
        $this->command->info("Total entries created: {$totalEntries}");
        $this->command->info("Total Debit: Rp " . number_format($totalDebit));
        $this->command->info("Total Credit: Rp " . number_format($totalCredit));
        $this->command->info("Balance Check: " . ($totalDebit == $totalCredit ? 'BALANCED ✓' : 'NOT BALANCED ✗'));
    }
}
