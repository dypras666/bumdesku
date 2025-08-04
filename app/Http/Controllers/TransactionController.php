<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\MasterAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Jika request AJAX, return JSON
        if ($request->ajax()) {
            return $this->getTransactionsData($request);
        }

        return view('transactions.index');
    }

    /**
     * Get transactions data for AJAX requests
     */
    public function getTransactionsData(Request $request)
    {
        $query = Transaction::with(['account', 'user', 'approver']);

        // Filter berdasarkan jenis transaksi
        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('account', function($q) use ($search) {
                      $q->where('nama_akun', 'like', "%{$search}%");
                  });
            });
        }

        $perPage = $request->get('per_page', 25);
        $transactions = $query->orderBy('transaction_date', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate($perPage);

        return response()->json([
            'data' => $transactions->items(),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
                'from' => $transactions->firstItem(),
                'to' => $transactions->lastItem(),
                'has_more_pages' => $transactions->hasMorePages(),
                'prev_page_url' => $transactions->previousPageUrl(),
                'next_page_url' => $transactions->nextPageUrl(),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = MasterAccount::orderBy('nama_akun')->get();
        return view('transactions.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'transaction_type' => 'required|in:income,expense',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:1000',
            'account_id' => 'required|exists:master_accounts,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $transactionCode = Transaction::generateTransactionCode($request->transaction_type);

            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'transaction_type' => $request->transaction_type,
                'transaction_date' => $request->transaction_date,
                'amount' => $request->amount,
                'description' => $request->description,
                'account_id' => $request->account_id,
                'user_id' => Auth::id(),
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('transactions.index')
                           ->with('success', 'Transaksi berhasil dibuat dengan kode: ' . $transactionCode);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                        ->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['account', 'user', 'approver']);
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        // Hanya bisa edit jika status masih pending
        if ($transaction->status !== 'pending') {
            return redirect()->route('transactions.index')
                           ->with('error', 'Transaksi yang sudah disetujui/ditolak tidak dapat diedit.');
        }

        $accounts = MasterAccount::orderBy('nama_akun')->get();
        return view('transactions.edit', compact('transaction', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        // Hanya bisa update jika status masih pending
        if ($transaction->status !== 'pending') {
            return redirect()->route('transactions.index')
                           ->with('error', 'Transaksi yang sudah disetujui/ditolak tidak dapat diedit.');
        }

        $request->validate([
            'transaction_type' => 'required|in:income,expense',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:1000',
            'account_id' => 'required|exists:master_accounts,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Jika jenis transaksi berubah, generate kode baru
            if ($transaction->transaction_type !== $request->transaction_type) {
                $transactionCode = Transaction::generateTransactionCode($request->transaction_type);
                $transaction->transaction_code = $transactionCode;
            }

            $transaction->update([
                'transaction_type' => $request->transaction_type,
                'transaction_date' => $request->transaction_date,
                'amount' => $request->amount,
                'description' => $request->description,
                'account_id' => $request->account_id,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('transactions.index')
                           ->with('success', 'Transaksi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                        ->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        // Hanya bisa hapus jika status masih pending
        if ($transaction->status !== 'pending') {
            return redirect()->route('transactions.index')
                           ->with('error', 'Transaksi yang sudah disetujui/ditolak tidak dapat dihapus.');
        }

        try {
            $transaction->delete();
            return redirect()->route('transactions.index')
                           ->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('transactions.index')
                           ->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Print cash receipt for approved transactions
     */
    public function printReceipt(Transaction $transaction)
    {
        if ($transaction->status !== 'approved') {
            return redirect()->back()->with('error', 'Hanya transaksi yang sudah disetujui yang dapat dicetak.');
        }

        $companyInfo = [
            'name' => company_info('name') ?? 'BUMDES',
            'address' => company_info('address') ?? '',
            'phone' => company_info('phone') ?? '',
            'email' => company_info('email') ?? ''
        ];

        return view('transactions.print-receipt', compact('transaction', 'companyInfo'));
    }

    /**
     * Approve transaction
     */
    public function approve(Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return redirect()->route('transactions.index')
                           ->with('error', 'Transaksi sudah diproses sebelumnya.');
        }

        try {
            $transaction->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);

            return redirect()->route('transactions.index')
                           ->with('success', 'Transaksi berhasil disetujui.');
        } catch (\Exception $e) {
            return redirect()->route('transactions.index')
                           ->with('error', 'Gagal menyetujui transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Reject transaction
     */
    public function reject(Request $request, Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return redirect()->route('transactions.index')
                           ->with('error', 'Transaksi sudah diproses sebelumnya.');
        }

        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        try {
            $transaction->update([
                'status' => 'rejected',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
                'notes' => $request->notes,
            ]);

            return redirect()->route('transactions.index')
                           ->with('success', 'Transaksi berhasil ditolak.');
        } catch (\Exception $e) {
            return redirect()->route('transactions.index')
                           ->with('error', 'Gagal menolak transaksi: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint untuk mendapatkan data transaksi dengan pagination dan filter
     */
    public function apiIndex(Request $request)
    {
        $query = Transaction::with(['account', 'user', 'approver']);

        // Filter berdasarkan jenis transaksi
        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('account', function($q) use ($search) {
                      $q->where('nama_akun', 'like', "%{$search}%");
                  });
            });
        }

        $perPage = $request->get('per_page', 25);
        $transactions = $query->orderBy('transaction_date', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $transactions->items(),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
                'from' => $transactions->firstItem(),
                'to' => $transactions->lastItem(),
                'has_more_pages' => $transactions->hasMorePages(),
                'prev_page_url' => $transactions->previousPageUrl(),
                'next_page_url' => $transactions->nextPageUrl(),
            ]
        ]);
    }

    /**
     * API endpoint untuk pencarian transaksi (untuk dropdown dengan search)
     */
    public function apiSearch(Request $request)
    {
        $query = Transaction::with(['account'])
                           ->where('status', 'approved'); // Hanya transaksi yang sudah approved

        // Search berdasarkan kode transaksi atau deskripsi
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $limit = $request->get('limit', 10); // Default 10 hasil
        $transactions = $query->orderBy('transaction_date', 'desc')
                             ->limit($limit)
                             ->get();

        return response()->json([
            'success' => true,
            'data' => $transactions->map(function($transaction) {
                return [
                    'id' => $transaction->id,
                    'text' => $transaction->transaction_code . ' - ' . $transaction->description,
                    'transaction_code' => $transaction->transaction_code,
                    'description' => $transaction->description,
                    'amount' => $transaction->amount,
                    'transaction_date' => $transaction->transaction_date,
                    'account_name' => $transaction->account->nama_akun ?? ''
                ];
            })
        ]);
    }

    /**
     * API endpoint untuk approve transaksi
     */
    public function apiApprove(Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi sudah diproses sebelumnya.'
            ], 400);
        }

        try {
            $transaction->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disetujui.',
                'data' => $transaction->load(['account', 'user', 'approver'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint untuk reject transaksi
     */
    public function apiReject(Request $request, Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi sudah diproses sebelumnya.'
            ], 400);
        }

        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        try {
            $transaction->update([
                'status' => 'rejected',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
                'notes' => $request->notes,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil ditolak.',
                'data' => $transaction->load(['account', 'user', 'approver'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint untuk delete transaksi
     */
    public function apiDestroy(Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi yang sudah disetujui/ditolak tidak dapat dihapus.'
            ], 400);
        }

        try {
            $transaction->delete();
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}
