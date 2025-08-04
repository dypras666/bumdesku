<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MasterAccountController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API routes
Route::middleware(['web', 'auth'])->group(function () {
    // Transaction API routes
    Route::get('/transactions', [TransactionController::class, 'apiIndex'])->name('api.transactions.index');
    Route::get('/transactions/search', [TransactionController::class, 'apiSearch'])->name('api.transactions.search');
    Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'apiApprove'])->name('api.transactions.approve');
    Route::post('/transactions/{transaction}/reject', [TransactionController::class, 'apiReject'])->name('api.transactions.reject');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'apiDestroy'])->name('api.transactions.destroy');
    
    // Account API routes
    Route::get('/accounts/search', [MasterAccountController::class, 'apiSearch'])->name('api.accounts.search');
});