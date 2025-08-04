<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\GeneralLedger;
use App\Models\MasterAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DataManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('super_admin')) {
                abort(403, 'Akses ditolak. Hanya Super Administrator yang dapat mengakses fitur ini.');
            }
            return $next($request);
        });
    }

    /**
     * Show data management page
     */
    public function index()
    {
        // Get statistics for display
        $stats = [
            'total_transactions' => Transaction::count(),
            'total_general_ledger_entries' => GeneralLedger::count(),
            'total_accounts_with_balance' => MasterAccount::where(function($query) {
                $query->whereHas('transactions')
                      ->orWhere('saldo_awal', '>', 0);
            })->count(),
            'earliest_transaction' => Transaction::orderBy('transaction_date')->first()?->transaction_date,
            'latest_transaction' => Transaction::orderBy('transaction_date', 'desc')->first()?->transaction_date,
        ];

        return view('data-management.index', compact('stats'));
    }

    /**
     * Reset all transaction data
     */
    public function resetTransactionData(Request $request)
    {
        $request->validate([
            'confirmation_text' => 'required|in:RESET DATA TRANSAKSI',
        ], [
            'confirmation_text.required' => 'Konfirmasi diperlukan untuk melanjutkan.',
            'confirmation_text.in' => 'Konfirmasi tidak valid. Ketik "RESET DATA TRANSAKSI" untuk melanjutkan.',
        ]);

        try {
            DB::beginTransaction();

            // Log the action
            Log::warning('Data reset initiated by user: ' . Auth::user()->email, [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'timestamp' => now(),
                'ip_address' => $request->ip(),
            ]);

            // Get counts before deletion for logging
            $transactionCount = Transaction::count();
            $generalLedgerCount = GeneralLedger::count();

            Log::info('Starting data reset process', [
                'transactions_to_delete' => $transactionCount,
                'general_ledger_entries_to_delete' => $generalLedgerCount,
            ]);

            // Get database driver for database-specific operations
            $driver = DB::getDriverName();
            
            // Disable foreign key checks based on database driver
            if ($driver === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys=OFF;');
            } elseif ($driver === 'pgsql') {
                // PostgreSQL doesn't have a global foreign key disable, so we'll use a different approach
                // We'll delete in the correct order instead
            }

            // Delete all general ledger entries first (they reference transactions)
            $deletedGL = DB::table('general_ledgers')->delete();
            Log::info('Deleted general ledger entries', ['count' => $deletedGL]);

            // Delete all transactions
            $deletedTrans = DB::table('transactions')->delete();
            Log::info('Deleted transactions', ['count' => $deletedTrans]);

            // Reset auto increment counters based on database driver
            if ($driver === 'mysql') {
                DB::statement('ALTER TABLE general_ledgers AUTO_INCREMENT = 1;');
                DB::statement('ALTER TABLE transactions AUTO_INCREMENT = 1;');
            } elseif ($driver === 'sqlite') {
                DB::statement('DELETE FROM sqlite_sequence WHERE name IN ("general_ledgers", "transactions");');
            } elseif ($driver === 'pgsql') {
                DB::statement('ALTER SEQUENCE general_ledgers_id_seq RESTART WITH 1;');
                DB::statement('ALTER SEQUENCE transactions_id_seq RESTART WITH 1;');
            }

            // Re-enable foreign key checks
            if ($driver === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys=ON;');
            }

            // Verify deletion was successful
            $remainingTransactions = Transaction::count();
            $remainingGL = GeneralLedger::count();
            
            Log::info('Data reset verification', [
                'remaining_transactions' => $remainingTransactions,
                'remaining_general_ledger_entries' => $remainingGL,
            ]);

            if ($remainingTransactions > 0 || $remainingGL > 0) {
                throw new \Exception("Reset tidak lengkap. Masih ada {$remainingTransactions} transaksi dan {$remainingGL} entri buku besar.");
            }
            
            DB::commit();

            // Log successful completion
            Log::info('Data reset completed successfully', [
                'deleted_transactions' => $transactionCount,
                'deleted_general_ledger_entries' => $generalLedgerCount,
                'user_id' => Auth::id(),
                'timestamp' => now(),
            ]);

            return redirect()->route('data-management.index')
                ->with('success', "Data berhasil direset! Dihapus: {$transactionCount} transaksi dan {$generalLedgerCount} entri buku besar.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Make sure to re-enable foreign key checks even if there's an error
            try {
                $driver = DB::getDriverName();
                if ($driver === 'mysql') {
                    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                } elseif ($driver === 'sqlite') {
                    DB::statement('PRAGMA foreign_keys=ON;');
                }
            } catch (\Exception $fkException) {
                Log::error('Failed to re-enable foreign key checks', [
                    'error' => $fkException->getMessage(),
                ]);
            }
            
            Log::error('Data reset failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'timestamp' => now(),
            ]);

            return redirect()->route('data-management.index')
                ->with('error', 'Gagal mereset data: ' . $e->getMessage());
        }
    }

    /**
     * Show confirmation page for data reset
     */
    public function confirmReset()
    {
        $stats = [
            'total_transactions' => Transaction::count(),
            'total_general_ledger_entries' => GeneralLedger::count(),
            'earliest_transaction' => Transaction::orderBy('transaction_date')->first()?->transaction_date,
            'latest_transaction' => Transaction::orderBy('transaction_date', 'desc')->first()?->transaction_date,
        ];

        return view('data-management.confirm-reset', compact('stats'));
    }


}
