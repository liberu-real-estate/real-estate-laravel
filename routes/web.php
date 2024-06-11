<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Livewire\PropertyList;
use App\Http\Livewire\PropertyBooking;
use App\Http\Livewire\BookingCalendar;

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

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Property routes
Route::get('/properties', PropertyList::class);
Route::get('/properties/{property}/book', PropertyBooking::class)->name('property.book');

// Booking routes
Route::post('/bookings', [BookingController::class, 'store']);
Route::put('/bookings/{booking}', [BookingController::class, 'update']);
Route::get('/bookings', [BookingController::class, 'index']);

// Payment routes
Route::post('/payments/session', [PaymentController::class, 'createSession']);
Route::get('/payments/success', [PaymentController::class, 'handlePaymentSuccess']);

// Booking calendar route with authentication middleware
Route::get('/booking-calendar', BookingCalendar::class)->middleware('auth')->name('booking.calendar');