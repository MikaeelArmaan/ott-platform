<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
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

Route::get('/', [BrowseController::class, 'home'])->name('home');

Route::get('/browse', [BrowseController::class, 'browse'])->name('browse');

Route::get('/title/{content}', [BrowseController::class, 'show'])->name('title.show');

Route::get('/search', [BrowseController::class, 'search'])->name('search');


/*
|--------------------------------------------------------------------------
| Authenticated Consumer Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'consumer'])->group(function () {

    Route::get('/watchlist', [WatchlistController::class, 'index'])
        ->name('watchlist.index');

    Route::post('/watchlist/toggle', [WatchlistController::class, 'toggle'])
        ->name('watchlist.toggle');

    Route::get('/watchlist/partial', [WatchlistController::class, 'partial'])
        ->name('watchlist.partial');

    Route::get('/watch/{content}', [BrowseController::class, 'watch'])
        ->name('title.watch');

    Route::get('/watch/{content}/episode/{episode}', [BrowseController::class, 'watchEpisode'])
        ->name('title.watch.episode');

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
| Admin RBAC Panel
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'permission:manage_users'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /* Dashboard */
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');


        /* Roles */
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::post('/roles/{id}/permissions', [RoleController::class, 'attachPermissions'])
            ->name('roles.permissions');


        /* Permissions */
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');


        /* Users */
        Route::get('/users', [UserRoleController::class, 'index'])->name('users.index');
        Route::post('/users/{id}/roles', [UserRoleController::class, 'assign'])->name('users.assign');


        /* Content Management */
        Route::resource('contents', ContentController::class);

        /* Inline publish toggle */
        Route::post('/contents/{content}/toggle-publish',
            [ContentController::class, 'togglePublish'])
            ->name('contents.togglePublish');

        /* Inline field update (for title, rating etc) */
        Route::post('/contents/{content}/inline-update',
            [ContentController::class, 'inlineUpdate'])
            ->name('contents.inlineUpdate');

        /* Seasons */
        Route::post('/contents/{content}/seasons',
            [ContentController::class, 'storeSeason'])
            ->name('seasons.store');

        /* Episodes */
        Route::post('/seasons/{season}/episodes',
            [ContentController::class, 'storeEpisode'])
            ->name('episodes.store');

});


/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';