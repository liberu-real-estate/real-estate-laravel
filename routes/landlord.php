&lt;?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandlordAuthController;
use App\Http\Controllers\LandlordPasswordResetController;
use App\Http\Controllers\LandlordVerificationController;

// Landlord Registration
Route::get('/register', [LandlordAuthController::class, 'registerForm'])->name('landlord.register.form');
Route::post('/register', [LandlordAuthController::class, 'register'])->name('landlord.register');

// Landlord Login
Route::get('/login', [LandlordAuthController::class, 'loginForm'])->name('landlord.login.form');
Route::post('/login', [LandlordAuthController::class, 'login'])->name('landlord.login');

// Landlord Password Reset
Route::get('/password/reset', [LandlordPasswordResetController::class, 'showResetRequestForm'])->name('landlord.password.request');
Route::post('/password/email', [LandlordPasswordResetController::class, 'sendResetLinkEmail'])->name('landlord.password.email');
Route::get('/password/reset/{token}', [LandlordPasswordResetController::class, 'showResetForm'])->name('landlord.password.reset.form');
Route::post('/password/reset', [LandlordPasswordResetController::class, 'reset'])->name('landlord.password.update');

// Landlord Verification
Route::get('/email/verify', [LandlordVerificationController::class, 'show'])->name('landlord.verification.notice');
Route::get('/email/verify/{id}/{hash}', [LandlordVerificationController::class, 'verify'])->name('landlord.verification.verify')->middleware(['signed', 'throttle:6,1']);
