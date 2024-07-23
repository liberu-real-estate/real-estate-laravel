<?php

/**
 * Web routes for the real estate application.
 * Includes routes for home, property listings, bookings, and payment processing.
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PaymentController;
use App\Http\Livewire\PropertyList;
use App\Http\Livewire\PropertyBooking;
use App\Http\Livewire\BookingCalendar;
use App\Http\Livewire\PropertyComparison;
use App\Http\Livewire\PropertyDetail;

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

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/properties', PropertyList::class)->name('property.list');
Route::get('/properties/{propertyId}', PropertyDetail::class)->name('property.detail');
Route::get('/properties/compare/{propertyIds}', PropertyComparison::class)->name('property.compare');
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Add routes for about, privacy, and terms
Route::get('/about', 'AboutController@index')->name('about');
Route::get('/privacy', 'PrivacyController@index')->name('privacy');
Route::get('/terms', 'TermsController@index')->name('terms');

// Protected routes
Route::middleware(['auth', 'role.redirect'])->group(function () {
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::put('/bookings/{booking}', [BookingController::class, 'update']);
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/properties/{property}/book', PropertyBooking::class)->name('property.book');
    Route::post('/payments/session', [PaymentController::class, 'createSession']);
    Route::get('/payments/success', [PaymentController::class, 'handlePaymentSuccess']);
    Route::get('/booking-calendar', BookingCalendar::class)->name('booking.calendar');
});

require __DIR__.'/socialstream.php';
