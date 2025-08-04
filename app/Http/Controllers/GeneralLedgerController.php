<?php

namespace App\Http\Controllers;

use App\Models\GeneralLedger;
use App\Models\MasterAccount;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GeneralLedgerController extends Controller
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
        $query = GeneralLedger::with(['account', 'transaction', 'postedBy']);

        // Filter by account
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('posting_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('posting_date', '<=', $request->end_date);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('entry_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }

        $entries = $query->orderBy('posting_date', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->paginate(25);

        $accounts = MasterAccount::orderBy('nama_akun')->get();

        return view('general-ledger.index', compact('entries', 'accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = MasterAccount::orderBy('nama_akun')->get();
        $transactions = Transaction::where('status', 'approved')
                                 ->orderBy('transaction_date', 'desc')
                                 ->get();

        return view('general-ledger.create', compact('accounts', 'transactions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:master_accounts,id',
            'posting_date' => 'required|date',
            'debit' => 'nullable|numeric|min:0',
            'credit' => 'nullable|numeric|min:0',
            'description' => 'required|string|max:255',
            'reference_type' => 'required|string|max:50',
            'reference_number' => 'nullable|string|max:100',
            'transaction_id' => 'nullable|exists:transactions,id'
        ]);

        // Validate that either debit or credit is provided, but not both
        if (($request->debit > 0 && $request->credit > 0) || 
            ($request->debit == 0 && $request->credit == 0)) {
            return back()->withErrors(['amount' => 'Please enter either debit or credit amount, not both.']);
        }

        DB::transaction(function () use ($request) {
            $entry = GeneralLedger::create([
                'entry_code' => GeneralLedger::generateEntryCode(),
                'account_id' => $request->account_id,
                'transaction_id' => $request->transaction_id,
                'posting_date' => $request->posting_date,
                'debit' => $request->debit ?? 0,
                'credit' => $request->credit ?? 0,
                'description' => $request->description,
                'reference_type' => $request->reference_type,
                'reference_number' => $request->reference_number,
                'posted_by' => Auth::id(),
                'posted_at' => now(),
                'status' => 'posted'
            ]);
        });

        return redirect()->route('general-ledger.index')
                        ->with('success', 'General ledger entry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(GeneralLedger $generalLedger)
    {
        $generalLedger->load(['account', 'transaction', 'postedBy']);
        
        // Pass as $entry to match the view expectations
        $entry = $generalLedger;
        
        return view('general-ledger.show', compact('entry'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GeneralLedger $generalLedger)
    {
        if ($generalLedger->status === 'posted') {
            return redirect()->route('general-ledger.show', $generalLedger)
                           ->with('error', 'Posted entries cannot be edited.');
        }

        $accounts = MasterAccount::orderBy('nama_akun')->get();
        $transactions = Transaction::where('status', 'approved')
                                 ->orderBy('transaction_date', 'desc')
                                 ->get();

        // Pass as $entry to match the view expectations
        $entry = $generalLedger;

        return view('general-ledger.edit', compact('entry', 'accounts', 'transactions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GeneralLedger $generalLedger)
    {
        if ($generalLedger->status === 'posted') {
            return redirect()->route('general-ledger.show', $generalLedger)
                           ->with('error', 'Posted entries cannot be updated.');
        }

        $request->validate([
            'account_id' => 'required|exists:master_accounts,id',
            'posting_date' => 'required|date',
            'debit' => 'nullable|numeric|min:0',
            'credit' => 'nullable|numeric|min:0',
            'description' => 'required|string|max:255',
            'reference_type' => 'required|string|max:50',
            'reference_number' => 'nullable|string|max:100',
            'transaction_id' => 'nullable|exists:transactions,id'
        ]);

        // Validate that either debit or credit is provided, but not both
        if (($request->debit > 0 && $request->credit > 0) || 
            ($request->debit == 0 && $request->credit == 0)) {
            return back()->withErrors(['amount' => 'Please enter either debit or credit amount, not both.']);
        }

        $generalLedger->update([
            'account_id' => $request->account_id,
            'transaction_id' => $request->transaction_id,
            'posting_date' => $request->posting_date,
            'debit' => $request->debit ?? 0,
            'credit' => $request->credit ?? 0,
            'description' => $request->description,
            'reference_type' => $request->reference_type,
            'reference_number' => $request->reference_number
        ]);

        return redirect()->route('general-ledger.show', $generalLedger)
                        ->with('success', 'General ledger entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GeneralLedger $generalLedger)
    {
        if ($generalLedger->status === 'posted') {
            return redirect()->route('general-ledger.index')
                           ->with('error', 'Posted entries cannot be deleted.');
        }

        $generalLedger->delete();

        return redirect()->route('general-ledger.index')
                        ->with('success', 'General ledger entry deleted successfully.');
    }

    /**
     * Post a draft entry
     */
    public function post(GeneralLedger $generalLedger)
    {
        if ($generalLedger->status === 'posted') {
            return redirect()->route('general-ledger.show', $generalLedger)
                           ->with('error', 'Entry is already posted.');
        }

        $generalLedger->update([
            'status' => 'posted',
            'posted_by' => Auth::id(),
            'posted_at' => now()
        ]);

        return redirect()->route('general-ledger.show', $generalLedger)
                        ->with('success', 'Entry posted successfully.');
    }

    /**
     * Get account balance
     */
    public function accountBalance(Request $request)
    {
        $accountId = $request->account_id;
        $asOfDate = $request->as_of_date ?? Carbon::today();

        $balance = GeneralLedger::byAccount($accountId)
                              ->posted()
                              ->whereDate('posting_date', '<=', $asOfDate)
                              ->selectRaw('SUM(debit) - SUM(credit) as balance')
                              ->value('balance') ?? 0;

        return response()->json(['balance' => $balance]);
    }

    /**
     * Generate trial balance
     */
    public function trialBalance(Request $request)
    {
        // Get date parameters
        $startDate = $request->start_date ?? Carbon::today()->startOfMonth();
        $endDate = $request->end_date ?? Carbon::today()->endOfMonth();
        $asOfDate = $request->as_of_date ?? $endDate;

        // Convert to Carbon instances if they're strings
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }
        if (is_string($asOfDate)) {
            $asOfDate = Carbon::parse($asOfDate);
        }

        $accounts = MasterAccount::with(['generalLedgerEntries' => function($query) use ($asOfDate) {
            $query->posted()->whereDate('posting_date', '<=', $asOfDate);
        }])->get();

        $trialBalance = $accounts->map(function($account) {
            $totalDebit = $account->generalLedgerEntries->sum('debit');
            $totalCredit = $account->generalLedgerEntries->sum('credit');
            $balance = $totalDebit - $totalCredit;

            return [
                'account' => $account,
                'debit' => $totalDebit,
                'credit' => $totalCredit,
                'balance' => $balance
            ];
        })->filter(function($item) {
            return $item['debit'] > 0 || $item['credit'] > 0;
        });

        // Calculate totals
        $totalDebit = $trialBalance->sum('debit');
        $totalCredit = $trialBalance->sum('credit');

        return view('general-ledger.trial-balance', compact(
            'trialBalance', 
            'asOfDate', 
            'startDate', 
            'endDate', 
            'totalDebit', 
            'totalCredit'
        ));
    }
}
