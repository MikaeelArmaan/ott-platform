<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\CatalogController;
use App\Http\Controllers\Api\V1\PlaybackController;
use App\Http\Controllers\Api\V1\WatchlistController;
use App\Http\Controllers\Api\V1\WatchHistoryController;
use App\Http\Controllers\Api\V1\Admin\ContentController;

/*
|--------------------------------------------------------------------------
| API v1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    /*
    |---------------- PUBLIC ----------------
    */

    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);

    Route::get('/catalog/movies', [CatalogController::class, 'movies']);
    Route::get('/catalog/series', [CatalogController::class, 'series']);
    Route::get('/catalog/content/{content:slug}', [CatalogController::class, 'show']);
    Route::get('/catalog/search', [CatalogController::class, 'search']);

    /*
    |---------------- AUTH ----------------
    */

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        Route::get('/home', [HomeController::class, 'index']);

        Route::post('/playback/start', [PlaybackController::class, 'start']);
        Route::post('/playback/progress', [PlaybackController::class, 'progress']);
        Route::post('/watch-history', [WatchHistoryController::class, 'update']);

        Route::apiResource('profiles', ProfileController::class);

        Route::prefix('watchlist')->group(function () {
            Route::get('/', [WatchlistController::class, 'index']);
            Route::post('/', [WatchlistController::class, 'store']);
            Route::delete('/{content}', [WatchlistController::class, 'destroy']);
        });

        Route::middleware('permission:upload_content')->group(function () {
            Route::post('/admin/contents', [ContentController::class, 'store']);
        });
    });
});
