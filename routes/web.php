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
