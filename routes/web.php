<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MasterAccountController;
use App\Http\Controllers\MasterUnitController;
use App\Http\Controllers\MasterInventoryController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\GeneralLedgerController;
use App\Http\Controllers\FinancialReportController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Auth::routes();

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// User management routes
Route::resource('users', UserController::class);

// Profile routes
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

// Master Data routes
Route::resource('master-accounts', MasterAccountController::class);
Route::resource('master-units', MasterUnitController::class);
Route::resource('master-inventories', MasterInventoryController::class);

// System Settings routes
Route::resource('system-settings', SystemSettingController::class);
Route::put('system-settings-batch', [SystemSettingController::class, 'updateBatch'])->name('system-settings.update-batch');
Route::post('system-settings/clear-cache', [SystemSettingController::class, 'clearCache'])->name('system-settings.clear-cache');

// Transaction routes
Route::resource('transactions', TransactionController::class);
Route::post('transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
Route::post('transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');

// General Ledger routes
Route::resource('general-ledger', GeneralLedgerController::class);
Route::post('general-ledger/{generalLedger}/post', [GeneralLedgerController::class, 'post'])->name('general-ledger.post');
Route::get('general-ledger/account-balance', [GeneralLedgerController::class, 'accountBalance'])->name('general-ledger.account-balance');
Route::get('trial-balance', [GeneralLedgerController::class, 'trialBalance'])->name('trial-balance');

// Financial Report routes
Route::resource('financial-reports', FinancialReportController::class);
Route::post('financial-reports/{financialReport}/finalize', [FinancialReportController::class, 'finalize'])->name('financial-reports.finalize');
Route::post('financial-reports/{financialReport}/regenerate', [FinancialReportController::class, 'regenerate'])->name('financial-reports.regenerate');
Route::get('financial-reports/{financialReport}/export-pdf', [FinancialReportController::class, 'exportPdf'])->name('financial-reports.export-pdf');

// Financial Report Views
Route::get('reports/income-statement', [FinancialReportController::class, 'incomeStatement'])->name('reports.income-statement');
Route::get('reports/balance-sheet', [FinancialReportController::class, 'balanceSheet'])->name('reports.balance-sheet');
Route::get('reports/cash-flow', [FinancialReportController::class, 'cashFlow'])->name('reports.cash-flow');
