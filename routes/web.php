<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Admin\ContentController; 
use App\Http\Controllers\Web\BrowseController;
use App\Http\Controllers\Web\WatchlistController;      

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [BrowseController::class, 'home'])->name('home');
Route::get('/browse', [BrowseController::class, 'browse'])->name('browse');
Route::get('/title/{content}', [BrowseController::class, 'show'])->name('title.show');
#Route::get('/title/{content}/watch', [BrowseController::class, 'watch'])->name('title.watch');
#Route::get('/title/{content}/episode/{episode}/watch', [BrowseController::class, 'watchEpisode'])->name('episode.watch');

Route::get('/search', [BrowseController::class, 'search'])->name('search');
Route::get('/watch/{content}', [BrowseController::class, 'watch'])
    ->name('title.watch');

Route::middleware('auth')->group(function () {

    Route::get('/watchlist', [WatchlistController::class,'index'])
        ->name('watchlist.index');

    Route::post('/watchlist/toggle', [WatchlistController::class,'toggle'])
        ->name('watchlist.toggle');
    
        // Watch movie OR series main (will auto pick first ep) OR explicit episode
    Route::get('/watch/{content}', [BrowseController::class, 'watch'])->name('title.watch');
    Route::get('/watch/{content}/episode/{episode}', [BrowseController::class, 'watchEpisode'])->name('title.watch.episode');
});    
/*
|--------------------------------------------------------------------------
| Breeze Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin RBAC UI (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','permission:manage_users'])
    ->prefix('admin')
    ->group(function(){

        // Dashboard
        Route::get('/dashboard', function(){
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // Roles
        Route::get('/roles', [RoleController::class,'index']);
        Route::post('/roles', [RoleController::class,'store']);
        Route::post('/roles/{id}/permissions', [RoleController::class,'attachPermissions']);

        // Permissions
        Route::get('/permissions', [PermissionController::class,'index']);
        Route::post('/permissions', [PermissionController::class,'store']);

        // Users
        Route::get('/users', [UserRoleController::class,'index']);
        Route::post('/users/{id}/roles', [UserRoleController::class,'assign']);

        // Contents
        Route::get('/contents', [ContentController::class,'index']);
        Route::post('/contents', [ContentController::class,'store'])->name('admin.contents.store');
        Route::delete('/contents/{content}', [ContentController::class,'destroy'])->name('admin.contents.destroy');

        Route::post('/contents/{content}/seasons', [ContentController::class,'storeSeason'])->name('admin.seasons.store');
        Route::post('/seasons/{season}/episodes', [ContentController::class,'storeEpisode'])->name('admin.episodes.store');

});

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
