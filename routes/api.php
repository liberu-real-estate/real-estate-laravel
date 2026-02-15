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
});
