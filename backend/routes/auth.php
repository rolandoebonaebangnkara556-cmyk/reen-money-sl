<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::post('/auth/register', [AuthController::class, 'register'])->name('register');
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');
Route::post('/auth/verify-2fa', [AuthController::class, 'verify2FA'])->name('verify2fa');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
