<?php

/**
 * Defines web routes for the application.
 * 
 * This file is responsible for mapping URLs to their corresponding actions or views within the application.
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




Route::get('/properties', \App\Http\Livewire\PropertyList::class);
});
Route::post('/bookings', [\App\Http\Controllers\BookingController::class, 'store']);
Route::put('/bookings/{booking}', [\App\Http\Controllers\BookingController::class, 'update']);
Route::get('/bookings', [\App\Http\Controllers\BookingController::class, 'index']);
Route::get('/properties/{property}/book', \App\Http\Livewire\PropertyBooking::class)->name('property.book');

