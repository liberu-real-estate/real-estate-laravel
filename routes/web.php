<?php

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
Route::get('/properties/{property}/availability', [ViewingController::class, 'checkAvailability'])->name('properties.availability');
Route::post('/properties/{property}/book-viewing', [ViewingController::class, 'store'])->name('properties.book-viewing');

