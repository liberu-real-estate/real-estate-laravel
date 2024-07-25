<?php

/**
 * Web routes for the real estate application.
 * Includes routes for home, property listings, bookings, and payment processing.
 */

use Illuminate\Support\Facades\Route;
use App\Filament\Tenant\Resources\PaymentResource;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PaymentController;
use App\Http\Livewire\PropertyList;
use App\Http\Livewire\PropertyBooking;
use App\Http\Livewire\BookingCalendar;
use App\Http\Livewire\PropertyComparison;
use App\Http\Livewire\PropertyDetail;
use App\Http\Livewire\RentalApplicationForm;
use App\Http\Livewire\About;
use App\Http\Livewire\TermsAndConditions;
use App\Http\Livewire\PrivacyPolicy;


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

Route::post('/bookings', [BookingController::class, 'store']);
Route::put('/bookings/{booking}', [BookingController::class, 'update']);
Route::get('/bookings', [BookingController::class, 'index']);
Route::get('/properties/{property}/book', PropertyBooking::class)->name('property.book');
Route::post('/payments/session', [PaymentController::class, 'createSession']);
Route::get('/payments/success', [PaymentController::class, 'handlePaymentSuccess']);
Route::post('/stripe/process-payment/{payment}', [PaymentResource::class, 'handleStripePayment'])->name('stripe.process-payment');
Route::get('/booking-calendar', BookingCalendar::class)->middleware('auth')->name('booking.calendar');
Route::get('/properties', PropertyList::class)->name('property.list');
Route::get('/properties/{propertyId}', PropertyDetail::class)->name('property.detail');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/apply/{property}', RentalApplicationForm::class)->name('rental.apply');
    Route::get('/applications', [App\Http\Controllers\TenantController::class, 'applications'])->name('tenant.applications');
});

Route::get('/properties/{property}/apply', [TenancyApplicationController::class, 'create'])->name('tenancy.apply');
Route::post('/properties/{property}/apply', [TenancyApplicationController::class, 'store'])->name('tenancy.store');

Route::get('/properties/compare/{propertyIds}', PropertyComparison::class)->name('property.compare');

Route::controller(ContactController::class)->group(function () {
    Route::get('/contact', 'show')->name('contact.show');
    Route::post('/contact', 'submit')->name('contact.submit');
});

Route::get('/about', About::class)->name('about');
Route::get('/terms-and-conditions', TermsAndConditions::class)->name('termsandconditions');
Route::get('/privacy', PrivacyPolicy::class)->name('privacypolicy');

require __DIR__.'/socialstream.php';

