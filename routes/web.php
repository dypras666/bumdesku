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
use App\Http\Controllers\GuideController;
use App\Http\Controllers\UploadController;

// Public routes (before authentication)
Route::get('/', [GuideController::class, 'publicIndex'])->name('guides.public.index');
Route::get('/panduan/{slug}', [GuideController::class, 'publicShow'])->name('guides.public.show');

Auth::routes();

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// User management routes
Route::resource('users', UserController::class);

// Guide management routes (superadmin only)
Route::resource('guides', GuideController::class);

// Upload routes
Route::post('/upload/image', [UploadController::class, 'uploadImage'])->name('upload.image');

// Profile routes
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

// Master Data routes
Route::resource('master-accounts', MasterAccountController::class);
Route::resource('master-units', MasterUnitController::class);
Route::resource('master-inventories', MasterInventoryController::class);

// Route alias for /akun to redirect to master-accounts
Route::get('/akun', function () {
    return redirect('/master-accounts');
});

// System Settings routes
Route::resource('system-settings', SystemSettingController::class);
Route::put('system-settings-batch', [SystemSettingController::class, 'updateBatch'])->name('system-settings.update-batch');
Route::post('system-settings/clear-cache', [SystemSettingController::class, 'clearCache'])->name('system-settings.clear-cache');

// Transactions
Route::resource('transactions', TransactionController::class);
Route::post('transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
Route::post('transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
Route::get('transactions/{transaction}/print-receipt', [TransactionController::class, 'printReceipt'])->name('transactions.print-receipt');

// General Ledger routes
Route::resource('general-ledger', GeneralLedgerController::class);
Route::post('general-ledger/{generalLedger}/post', [GeneralLedgerController::class, 'post'])->name('general-ledger.post');
Route::get('general-ledger/account-balance', [GeneralLedgerController::class, 'accountBalance'])->name('general-ledger.account-balance');
Route::get('trial-balance', [GeneralLedgerController::class, 'trialBalance'])->name('trial-balance');

// Financial Report routes
Route::resource('financial-reports', FinancialReportController::class);
Route::post('financial-reports/{financial_report}/finalize', [FinancialReportController::class, 'finalize'])->name('financial-reports.finalize');
Route::post('financial-reports/{financial_report}/regenerate', [FinancialReportController::class, 'regenerate'])->name('financial-reports.regenerate');
Route::get('financial-reports/{financial_report}/export-pdf', [FinancialReportController::class, 'exportPdf'])->name('financial-reports.export-pdf');
Route::get('financial-reports/{financial_report}/export-docx', [FinancialReportController::class, 'exportDocx'])->name('financial-reports.export-docx');
Route::get('financial-reports/{financial_report}/export-excel', [FinancialReportController::class, 'exportExcel'])->name('financial-reports.export-excel');

// Financial Report Views
Route::get('reports/income-statement', [FinancialReportController::class, 'incomeStatement'])->name('reports.income-statement');
Route::get('reports/balance-sheet', [FinancialReportController::class, 'balanceSheet'])->name('reports.balance-sheet');
Route::get('reports/cash-flow', [FinancialReportController::class, 'cashFlow'])->name('reports.cash-flow');

// Export routes for individual reports
Route::get('reports/income-statement/export-pdf', [FinancialReportController::class, 'exportIncomeStatementPdf'])->name('reports.income-statement.export-pdf');
Route::get('reports/income-statement/export-docx', [FinancialReportController::class, 'exportIncomeStatementDocx'])->name('reports.income-statement.export-docx');
Route::get('reports/income-statement/export-excel', [FinancialReportController::class, 'exportIncomeStatementExcel'])->name('reports.income-statement.export-excel');

Route::get('reports/balance-sheet/export-pdf', [FinancialReportController::class, 'exportBalanceSheetPdf'])->name('reports.balance-sheet.export-pdf');
Route::get('reports/balance-sheet/export-docx', [FinancialReportController::class, 'exportBalanceSheetDocx'])->name('reports.balance-sheet.export-docx');
Route::get('reports/balance-sheet/export-excel', [FinancialReportController::class, 'exportBalanceSheetExcel'])->name('reports.balance-sheet.export-excel');

Route::get('reports/cash-flow/export-pdf', [FinancialReportController::class, 'exportCashFlowPdf'])->name('reports.cash-flow.export-pdf');
Route::get('reports/cash-flow/export-docx', [FinancialReportController::class, 'exportCashFlowDocx'])->name('reports.cash-flow.export-docx');
Route::get('reports/cash-flow/export-excel', [FinancialReportController::class, 'exportCashFlowExcel'])->name('reports.cash-flow.export-excel');

Route::get('trial-balance/export-pdf', [FinancialReportController::class, 'exportTrialBalancePdf'])->name('trial-balance.export-pdf');
Route::get('trial-balance/export-docx', [FinancialReportController::class, 'exportTrialBalanceDocx'])->name('trial-balance.export-docx');
Route::get('trial-balance/export-excel', [FinancialReportController::class, 'exportTrialBalanceExcel'])->name('trial-balance.export-excel');

// Annual Report routes
Route::get('financial-reports/annual/create', [FinancialReportController::class, 'annualReport'])->name('financial-reports.annual');
Route::post('financial-reports/annual/generate', [FinancialReportController::class, 'generateAnnualReport'])->name('financial-reports.annual.generate');
