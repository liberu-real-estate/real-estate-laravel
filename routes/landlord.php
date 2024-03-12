<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Landlord\AuthController;

Route::prefix('landlord')->name('landlord.')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');
});
