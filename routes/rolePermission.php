<?php

use App\Http\Controllers\RolePermissionController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {

    // this is the table for role and permission
    Route::get('roles/permissions', [RolePermissionController::class, 'table'])
        ->name('role.permission.table');

    // Role Controller
    Route::get('roles/create', [RolePermissionController::class, 'create_role'])
        ->name('role.create');

    Route::post('roles', [RolePermissionController::class, 'store_role'])
        ->name('role.store');

    Route::get('roles/{role}', [RolePermissionController::class, 'show_role'])
        ->name('role.show');

    Route::get('roles/{role}/edit', [RolePermissionController::class, 'edit_role'])
        ->name('role.edit');

    Route::put('roles/{role}', [RolePermissionController::class, 'update_role'])
        ->name('role.update');

    Route::delete('roles/{role}', [RolePermissionController::class, 'destroy_role'])
        ->name('role.destroy');


    // Permission Controller
    Route::get('permissions/create', [RolePermissionController::class, 'create_permission'])
        ->name('permission.create');

    Route::post('permissions', [RolePermissionController::class, 'store_permission'])
        ->name('permission.store');

    Route::get('permissions/{permission}', [RolePermissionController::class, 'show_permission'])
        ->name('permission.show');

    Route::get('permissions/{permission}/edit', [RolePermissionController::class, 'edit_permission'])
        ->name('permission.edit');

    Route::put('permissions/{permission}', [RolePermissionController::class, 'update_permission'])
        ->name('permission.update');

    Route::delete('permissions/{permission}', [RolePermissionController::class, 'destroy_permission'])
        ->name('permission.destroy');




});

