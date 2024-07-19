<?php

/*
 * Web routes for the real estate application.
 * Includes routes for home, property listings, bookings, and payment processing.
 */

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\PropertyComparison;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/properties/compare/{propertyIds}', PropertyComparison::class)->name('property.compare');