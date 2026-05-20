<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CommunityEventController;
use App\Http\Controllers\ChatbotController;

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

// Public Community Events API Routes
Route::prefix('community-events')->group(function () {
    Route::get('/', [CommunityEventController::class, 'index']);
    Route::get('/{id}', [CommunityEventController::class, 'show']);
});

// Property-specific community events route
Route::get('/properties/{propertyId}/community-events', [CommunityEventController::class, 'propertyEvents']);

// Chatbot API Routes
Route::prefix('chatbot')->group(function () {
    Route::post('/start', [ChatbotController::class, 'startConversation']);
    Route::post('/message', [ChatbotController::class, 'sendMessage']);
    Route::get('/history/{sessionId}', [ChatbotController::class, 'getHistory']);
    Route::post('/escalate', [ChatbotController::class, 'escalate']);
    Route::post('/close', [ChatbotController::class, 'closeConversation']);
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
    
    // VR Property Design API Routes
    Route::prefix('vr-design')->group(function () {
        Route::get('styles', [App\Http\Controllers\API\VRPropertyDesignController::class, 'getStyles']);
        Route::get('furniture-categories', [App\Http\Controllers\API\VRPropertyDesignController::class, 'getFurnitureCategories']);
        Route::get('room-types', [App\Http\Controllers\API\VRPropertyDesignController::class, 'getRoomTypes']);
        Route::get('devices', [App\Http\Controllers\API\VRPropertyDesignController::class, 'getSupportedDevices']);
        Route::get('templates', [App\Http\Controllers\API\VRPropertyDesignController::class, 'getTemplates']);
    });
    
    Route::prefix('properties/{property}')->group(function () {
        Route::post('vr-designs', [App\Http\Controllers\API\VRPropertyDesignController::class, 'createDesign']);
        Route::get('vr-designs', [App\Http\Controllers\API\VRPropertyDesignController::class, 'getPropertyDesigns']);
    });
    
    Route::prefix('vr-designs')->group(function () {
        Route::get('{design}', [App\Http\Controllers\API\VRPropertyDesignController::class, 'getDesign']);
        Route::put('{design}', [App\Http\Controllers\API\VRPropertyDesignController::class, 'updateDesign']);
        Route::delete('{design}', [App\Http\Controllers\API\VRPropertyDesignController::class, 'deleteDesign']);
        Route::post('{design}/furniture', [App\Http\Controllers\API\VRPropertyDesignController::class, 'addFurniture']);
        Route::delete('{design}/furniture/{furnitureId}', [App\Http\Controllers\API\VRPropertyDesignController::class, 'removeFurniture']);
        Route::post('{design}/clone', [App\Http\Controllers\API\VRPropertyDesignController::class, 'cloneDesign']);
        Route::post('{design}/thumbnail', [App\Http\Controllers\API\VRPropertyDesignController::class, 'uploadThumbnail']);
        Route::get('{design}/export', [App\Http\Controllers\API\VRPropertyDesignController::class, 'exportDesign']);
    });
    
    // Wishlist/Favorites routes
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{propertyId}', [FavoriteController::class, 'destroy']);
    Route::get('/favorites/check/{propertyId}', [FavoriteController::class, 'check']);
});
