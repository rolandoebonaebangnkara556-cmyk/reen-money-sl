<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Accounts\AccountController;

Route::middleware('auth:sanctum')->group(function () {
    // Account routes
    Route::get('/accounts/me', [AccountController::class, 'show'])->name('account.show');
    Route::get('/accounts/balance', [AccountController::class, 'getBalance'])->name('account.balance');
    Route::get('/accounts/transactions', [AccountController::class, 'getTransactions'])->name('account.transactions');
});
