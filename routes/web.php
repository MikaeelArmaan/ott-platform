<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserRoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return 'OTT Platform Running';
});

/*
|--------------------------------------------------------------------------
| Admin RBAC UI
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->group(function(){

    Route::get('/roles', [RoleController::class,'index']);
    Route::post('/roles', [RoleController::class,'store']);
    Route::post('/roles/{id}/permissions', [RoleController::class,'attachPermissions']);

    Route::get('/permissions', [PermissionController::class,'index']);
    Route::post('/permissions', [PermissionController::class,'store']);

    Route::get('/users', [UserRoleController::class,'index']);
    Route::post('/users/{id}/roles', [UserRoleController::class,'assign']);

});
