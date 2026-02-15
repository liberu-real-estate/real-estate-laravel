<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
    // Define your protected API routes here
});
