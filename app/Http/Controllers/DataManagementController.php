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

            // Delete all general ledger entries first (due to foreign key constraints)
            GeneralLedger::truncate();

            // Delete all transactions
            Transaction::truncate();

            // Reset account balances to initial state (optional - keep saldo_awal but reset current calculations)
            // This doesn't delete accounts, just resets their transaction-based balances
            
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
            
            Log::error('Data reset failed', [
                'error' => $e->getMessage(),
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
