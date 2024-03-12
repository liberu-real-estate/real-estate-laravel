<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('rightmove')->group(function () {
        Route::get('/properties', [\App\Http\Controllers\RightmoveApiController::class, 'fetchProperties']);
        Route::post('/listings', [\App\Http\Controllers\RightmoveApiController::class, 'createListing']);
        Route::put('/listings/{listingId}', [\App\Http\Controllers\RightmoveApiController::class, 'updateListing']);
    });
});
