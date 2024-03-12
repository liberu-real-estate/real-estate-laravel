<?php

/**
 * Defines web routes for the application, including home, property listings, and booking management.
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


// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');




// Displays the list of properties
Route::get('/properties', \App\Http\Livewire\PropertyList::class);
});
// Creates a new booking
Route::post('/bookings', [\App\Http\Controllers\BookingController::class, 'store']);
// Updates an existing booking
Route::put('/bookings/{booking}', [\App\Http\Controllers\BookingController::class, 'update']);
// Lists all bookings
Route::get('/bookings', [\App\Http\Controllers\BookingController::class, 'index']);
// Booking page for a specific property
Route::get('/properties/{property}/book', \App\Http\Livewire\PropertyBooking::class)->name('property.book');

