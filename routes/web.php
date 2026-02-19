<?php

/**
 * Web routes for the real estate application.
 * Includes routes for home, property listings, bookings, and payment processing.
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomReportController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TenancyApplicationController;
use App\Http\Livewire\PropertyList;
use App\Http\Livewire\PropertyBooking;
use App\Http\Livewire\BookingCalendar;
use App\Http\Livewire\PropertyComparison;
use App\Http\Livewire\PropertyDetail;
use App\Http\Livewire\RentalApplicationForm;
use App\Http\Livewire\ServicesComponent;
use App\Http\Livewire\CalculatorsComponent;
use App\Http\Livewire\About;
use App\Http\Livewire\TermsAndConditions;
use App\Http\Livewire\PrivacyPolicy;
use App\Http\Livewire\WishlistManager;
use App\Http\Livewire\NewsList;
use App\Http\Livewire\NewsDetail;
use App\Http\Livewire\PropertyValuationComponent;
use App\Http\Controllers\PropertyValuationController;
use App\Http\Livewire\HolographicViewer;


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
Route::get('/booking-calendar', BookingCalendar::class)->middleware('auth')->name('booking.calendar');
Route::get('/properties', PropertyList::class)->name('property.list');
Route::get('/properties/search', [App\Http\Controllers\PropertyController::class, 'search'])->name('property.search');
Route::get('/properties/{propertyId}', PropertyDetail::class)->name('property.detail');
Route::get('/properties/{propertyId}/holographic-tour', HolographicViewer::class)->name('property.holographic-tour');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/apply/{property}', RentalApplicationForm::class)->name('rental.apply');
    Route::get('/applications', [App\Http\Controllers\TenantController::class, 'applications'])->name('tenant.applications');

    // Wishlist
    Route::get('/wishlist', WishlistManager::class)->name('wishlist');

    // Custom Reports
    Route::get('/custom-reports', [CustomReportController::class, 'index'])->name('custom-reports.index');
    Route::post('/custom-reports/generate', [CustomReportController::class, 'generateReport'])->name('custom-reports.generate');
    Route::post('/custom-reports/export-pdf', [CustomReportController::class, 'exportReportToPdf'])->name('custom-reports.export-pdf');
    Route::post('/custom-reports/export-excel', [CustomReportController::class, 'exportReportToExcel'])->name('custom-reports.export-excel');
});

Route::get('/properties/{property}/apply', [TenancyApplicationController::class, 'create'])->name('tenancy.apply');
Route::post('/properties/{property}/apply', [TenancyApplicationController::class, 'store'])->name('tenancy.store');

Route::get('/properties/compare/{propertyIds}', PropertyComparison::class)->name('property.compare');

Route::controller(ContactController::class)->group(function () {
    Route::get('/contact', 'show')->name('contact.show');
    Route::post('/contact', 'submit')->name('contact.submit');
});

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/terms-and-conditions', [PageController::class, 'terms'])->name('termsandconditions');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacypolicy');

Route::get('/services', [PageController::class, 'services'])->name('services');

Route::get('/calculators', CalculatorsComponent::class)->name('calculators');

// News Routes
Route::get('/news', NewsList::class)->name('news.list');
Route::get('/news/{slug}', NewsDetail::class)->name('news.detail');

// Property Valuation Routes
Route::get('/properties/{propertyId}/valuation', PropertyValuationComponent::class)->name('property.valuation');
Route::middleware(['auth'])->group(function () {
    Route::post('/properties/{property}/valuation/generate', [PropertyValuationController::class, 'generateValuation'])->name('property.valuation.generate');
    Route::get('/properties/{property}/valuation/history', [PropertyValuationController::class, 'getValuationHistory'])->name('property.valuation.history');
    Route::get('/properties/{property}/valuation/report', [PropertyValuationController::class, 'getDetailedReport'])->name('property.valuation.report');
    Route::post('/valuation/train-model', [PropertyValuationController::class, 'trainModel'])->name('valuation.train');
});

// AR Tour Routes
Route::get('/properties/{property}/ar-tour/config', [\App\Http\Controllers\ARTourController::class, 'getConfig'])->name('property.ar-tour.config');
Route::get('/properties/{property}/ar-tour/availability', [\App\Http\Controllers\ARTourController::class, 'checkAvailability'])->name('property.ar-tour.availability');

Route::middleware(['auth'])->group(function () {
    Route::post('/properties/{property}/ar-tour/enable', [\App\Http\Controllers\ARTourController::class, 'enable'])->name('property.ar-tour.enable');
    Route::post('/properties/{property}/ar-tour/disable', [\App\Http\Controllers\ARTourController::class, 'disable'])->name('property.ar-tour.disable');
    Route::put('/properties/{property}/ar-tour/settings', [\App\Http\Controllers\ARTourController::class, 'updateSettings'])->name('property.ar-tour.update-settings');
});

require __DIR__.'/socialstream.php';

