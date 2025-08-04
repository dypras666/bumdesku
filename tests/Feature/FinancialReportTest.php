<?php

use App\Models\User;
use App\Models\MasterAccount;
use App\Models\Transaction;
use App\Models\GeneralLedger;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create test user with admin role
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    // Create test accounts
    $this->kasAccount = MasterAccount::create([
        'kode_akun' => '1-1001',
        'nama_akun' => 'Kas',
        'kategori_akun' => 'Aset',
        'saldo_awal' => 1000000,
        'is_active' => true
    ]);
    
    $this->pendapatanAccount = MasterAccount::create([
        'kode_akun' => '4-1001',
        'nama_akun' => 'Pendapatan Penjualan',
        'kategori_akun' => 'Pendapatan',
        'saldo_awal' => 0,
        'is_active' => true
    ]);
    
    $this->bebanAccount = MasterAccount::create([
        'kode_akun' => '5-1001',
        'nama_akun' => 'Beban Operasional',
        'kategori_akun' => 'Beban',
        'saldo_awal' => 0,
        'is_active' => true
    ]);
});

test('income statement page loads successfully', function () {
    $response = $this->get('/reports/income-statement');
    
    $response->assertStatus(200);
    $response->assertViewIs('financial-reports.income-statement');
});

test('balance sheet page loads successfully', function () {
    $response = $this->get('/reports/balance-sheet');
    
    $response->assertStatus(200);
    $response->assertViewIs('financial-reports.balance-sheet');
});

test('cash flow page loads successfully', function () {
    $response = $this->get('/reports/cash-flow');
    
    $response->assertStatus(200);
    $response->assertViewIs('financial-reports.cash-flow');
});

test('income statement shows correct revenue data', function () {
    // Create test transaction
    $transaction = Transaction::create([
        'transaction_code' => 'TRX001',
        'transaction_type' => 'income',
        'transaction_date' => Carbon::now(),
        'amount' => 1000000,
        'description' => 'Test Revenue',
        'account_id' => $this->pendapatanAccount->id,
        'user_id' => $this->user->id,
        'status' => 'approved',
        'approved_at' => Carbon::now(),
        'approved_by' => $this->user->id
    ]);
    
    // Create corresponding general ledger entries
    GeneralLedger::create([
        'entry_code' => 'GL001A',
        'account_id' => $this->kasAccount->id,
        'transaction_id' => $transaction->id,
        'posting_date' => Carbon::now(),
        'debit' => 1000000,
        'credit' => 0,
        'description' => 'Cash receipt',
        'posted_by' => $this->user->id,
        'posted_at' => Carbon::now(),
        'status' => 'posted'
    ]);
    
    GeneralLedger::create([
        'entry_code' => 'GL001B',
        'account_id' => $this->pendapatanAccount->id,
        'transaction_id' => $transaction->id,
        'posting_date' => Carbon::now(),
        'debit' => 0,
        'credit' => 1000000,
        'description' => 'Revenue recognition',
        'posted_by' => $this->user->id,
        'posted_at' => Carbon::now(),
        'status' => 'posted'
    ]);
    
    $response = $this->get('/reports/income-statement');
    
    $response->assertStatus(200);
    $response->assertViewHas('reportData');
    
    $reportData = $response->viewData('reportData');
    expect($reportData['total_revenue'])->toBe(1000000.0);
});

test('balance sheet shows correct asset data', function () {
    // Create general ledger entry for cash
    GeneralLedger::create([
        'entry_code' => 'GL002',
        'account_id' => $this->kasAccount->id,
        'transaction_id' => null,
        'posting_date' => Carbon::now(),
        'debit' => 500000,
        'credit' => 0,
        'description' => 'Additional cash',
        'posted_by' => $this->user->id,
        'posted_at' => Carbon::now(),
        'status' => 'posted'
    ]);
    
    $response = $this->get('/reports/balance-sheet');
    
    $response->assertStatus(200);
    $response->assertViewHas('reportData');
    
    $reportData = $response->viewData('reportData');
    expect($reportData['total_assets'])->toBeGreaterThan(0);
});

test('cash flow shows correct operating activities', function () {
    // Create income transaction
    $incomeTransaction = Transaction::create([
        'transaction_code' => 'TRX002',
        'transaction_type' => 'income',
        'transaction_date' => Carbon::now(),
        'amount' => 2000000,
        'description' => 'Sales Revenue',
        'account_id' => $this->pendapatanAccount->id,
        'user_id' => $this->user->id,
        'status' => 'approved',
        'approved_at' => Carbon::now(),
        'approved_by' => $this->user->id
    ]);
    
    // Create cash receipt entry
    GeneralLedger::create([
        'entry_code' => 'GL003A',
        'account_id' => $this->kasAccount->id,
        'transaction_id' => $incomeTransaction->id,
        'posting_date' => Carbon::now(),
        'debit' => 2000000,
        'credit' => 0,
        'description' => 'Cash from sales',
        'posted_by' => $this->user->id,
        'posted_at' => Carbon::now(),
        'status' => 'posted'
    ]);
    
    $response = $this->get('/reports/cash-flow');
    
    $response->assertStatus(200);
    $response->assertViewHas('reportData');
    
    $reportData = $response->viewData('reportData');
    expect($reportData['operating_activities'])->not->toBeEmpty();
});

test('financial reports filter by date range', function () {
    $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
    $endDate = Carbon::now()->format('Y-m-d');
    
    $response = $this->get("/reports/income-statement?period_start={$startDate}&period_end={$endDate}");
    
    $response->assertStatus(200);
    $response->assertViewHas(['periodStart', 'periodEnd']);
});

test('general ledger entries are properly balanced', function () {
    // Create a transaction with corresponding GL entries
    $transaction = Transaction::create([
        'transaction_code' => 'TRX003',
        'transaction_type' => 'expense',
        'transaction_date' => Carbon::now(),
        'amount' => 500000,
        'description' => 'Office Supplies',
        'account_id' => $this->bebanAccount->id,
        'user_id' => $this->user->id,
        'status' => 'approved',
        'approved_at' => Carbon::now(),
        'approved_by' => $this->user->id
    ]);
    
    // Debit expense account
    GeneralLedger::create([
        'entry_code' => 'GL004A',
        'account_id' => $this->bebanAccount->id,
        'transaction_id' => $transaction->id,
        'posting_date' => Carbon::now(),
        'debit' => 500000,
        'credit' => 0,
        'description' => 'Office supplies expense',
        'posted_by' => $this->user->id,
        'posted_at' => Carbon::now(),
        'status' => 'posted'
    ]);
    
    // Credit cash account
    GeneralLedger::create([
        'entry_code' => 'GL004B',
        'account_id' => $this->kasAccount->id,
        'transaction_id' => $transaction->id,
        'posting_date' => Carbon::now(),
        'debit' => 0,
        'credit' => 500000,
        'description' => 'Cash payment',
        'posted_by' => $this->user->id,
        'posted_at' => Carbon::now(),
        'status' => 'posted'
    ]);
    
    // Check that debits equal credits for this transaction
    $totalDebits = GeneralLedger::where('transaction_id', $transaction->id)->sum('debit');
    $totalCredits = GeneralLedger::where('transaction_id', $transaction->id)->sum('credit');
    
    expect($totalDebits)->toBe($totalCredits);
});

test('trial balance is balanced', function () {
    // Create some test entries
    GeneralLedger::create([
        'entry_code' => 'GL005A',
        'account_id' => $this->kasAccount->id,
        'transaction_id' => null,
        'posting_date' => Carbon::now(),
        'debit' => 1000000,
        'credit' => 0,
        'description' => 'Test debit',
        'posted_by' => $this->user->id,
        'posted_at' => Carbon::now(),
        'status' => 'posted'
    ]);
    
    GeneralLedger::create([
        'entry_code' => 'GL005B',
        'account_id' => $this->pendapatanAccount->id,
        'transaction_id' => null,
        'posting_date' => Carbon::now(),
        'debit' => 0,
        'credit' => 1000000,
        'description' => 'Test credit',
        'posted_by' => $this->user->id,
        'posted_at' => Carbon::now(),
        'status' => 'posted'
    ]);
    
    // Check that total debits equal total credits
    $totalDebits = GeneralLedger::posted()->sum('debit');
    $totalCredits = GeneralLedger::posted()->sum('credit');
    
    expect($totalDebits)->toBe($totalCredits);
});

test('financial reports handle empty data gracefully', function () {
    // Test with no data
    $response = $this->get('/reports/income-statement');
    $response->assertStatus(200);
    
    $response = $this->get('/reports/balance-sheet');
    $response->assertStatus(200);
    
    $response = $this->get('/reports/cash-flow');
    $response->assertStatus(200);
});