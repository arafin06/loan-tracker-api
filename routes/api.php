<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LoanApplicationController;
use Illuminate\Support\Facades\Route;

// Public auth routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me',      [AuthController::class, 'me']);

    Route::get('loans/stats',                      [LoanApplicationController::class, 'stats']);
    Route::get('loans/{loanApplication}/history',  [LoanApplicationController::class, 'history']);
    Route::patch('loans/{loanApplication}/status', [LoanApplicationController::class, 'updateStatus']);

    Route::apiResource('loans', LoanApplicationController::class)
        ->parameters(['loans' => 'loanApplication']);
});
