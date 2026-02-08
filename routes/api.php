<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\CatalogController;
use App\Http\Controllers\Api\V1\PlaybackController;
use App\Http\Controllers\Api\V1\WatchlistController;
use App\Http\Controllers\Admin\ContentController;

/*
|--------------------------------------------------------------------------
| API v1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    /* ---------------- AUTH ---------------- */

    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        /* ---------------- PROFILES ---------------- */

        Route::get('/profiles', [ProfileController::class, 'index']);
        Route::post('/profiles', [ProfileController::class, 'store']);
        Route::put('/profiles/{profile}', [ProfileController::class, 'update']);
        Route::delete('/profiles/{profile}', [ProfileController::class, 'destroy']);

        /* ---------------- HOME ---------------- */

        Route::get('/home', [HomeController::class, 'index']);

        /* ---------------- CATALOG ---------------- */

        Route::get('/movies', [CatalogController::class, 'movies']);
        Route::get('/series', [CatalogController::class, 'series']);
        Route::get('/content/{id}', [CatalogController::class, 'show']);
        Route::get('/search', [CatalogController::class, 'search']);

        /* ---------------- WATCHLIST ---------------- */

        Route::get('/watchlist', [WatchlistController::class, 'index']);
        Route::post('/watchlist', [WatchlistController::class, 'store']);
        Route::delete('/watchlist/{contentId}', [WatchlistController::class, 'destroy']);

        /* ---------------- PLAYBACK ---------------- */

        Route::post('/playback/start', [PlaybackController::class, 'start']);
        Route::post('/playback/progress', [PlaybackController::class, 'progress']);

        /* ---------------- ADMIN (RBAC PROTECTED) ---------------- */

        Route::middleware('permission:upload_content')->group(function () {
            Route::post('/admin/contents', [ContentController::class, 'store']);
        });

    });

});
