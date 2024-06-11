<?php

/**
 * Web routes for the real estate application.
 * Includes routes for home, property listings, bookings, and payment processing.
 */

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/properties', [\App\Http\Livewire\PropertyList::class]);
Route::post('/bookings', [\App\Http\Controllers\BookingController::class, 'store']);
Route::put('/bookings/{booking}', [\App\Http\Controllers\BookingController::class, 'update']);
Route::get('/bookings', [\App\Http\Controllers\BookingController::class, 'index']);
Route::get('/properties/{property}/book', [\App\Http\Livewire\PropertyBooking::class])->name('property.book');
Route::post('/payments/session', [\App\Http\Controllers\PaymentController::class, 'createSession']);
Route::get('/payments/success', [\App\Http\Controllers\PaymentController::class, 'handlePaymentSuccess']);
Route::get('/booking-calendar', [\App\Http\Livewire\BookingCalendar::class])->middleware('auth')->name('booking.calendar');

