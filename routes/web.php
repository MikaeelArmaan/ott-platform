<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserRoleController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
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
        });

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
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
