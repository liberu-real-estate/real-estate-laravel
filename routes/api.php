<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\NewsController;

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

// Public News API Routes
Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index']);
    Route::get('/latest', [NewsController::class, 'latest']);
    Route::get('/featured', [NewsController::class, 'featured']);
    Route::get('/{slug}', [NewsController::class, 'show']);
});

Route::middleware('auth:sanctum')->group(function () {
    // Virtual Staging API Routes
    Route::prefix('properties/{property}')->group(function () {
        Route::post('images/upload', [App\Http\Controllers\API\VirtualStagingController::class, 'uploadImage']);
        Route::get('images', [App\Http\Controllers\API\VirtualStagingController::class, 'getPropertyImages']);
    });
    
    Route::prefix('images')->group(function () {
        Route::post('{image}/stage', [App\Http\Controllers\API\VirtualStagingController::class, 'stageImage']);
        Route::delete('{image}', [App\Http\Controllers\API\VirtualStagingController::class, 'deleteImage']);
    });
    
    Route::get('staging/styles', [App\Http\Controllers\API\VirtualStagingController::class, 'getStagingStyles']);
    // Wishlist/Favorites routes
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{propertyId}', [FavoriteController::class, 'destroy']);
    Route::get('/favorites/check/{propertyId}', [FavoriteController::class, 'check']);
});
