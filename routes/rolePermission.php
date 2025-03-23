<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {

    // this is the table for role and permission
    Route::get('roles/permissions',[\App\Http\Controllers\RolePermissionController::class,'table'])
        ->name('role.permission.table');

    // Role Controller
    Route::get('roles/create',[\App\Http\Controllers\RolePermissionController::class,'create_role'])
        ->name('role.create');
    Route::post('roles',[\App\Http\Controllers\RolePermissionController::class,'store_role'])
        ->name('role.store');



    // Permission Controller
    Route::get('permissions/create',[\App\Http\Controllers\RolePermissionController::class,'create_permission'])
        ->name('permission.create');
    Route::post('permissions',[\App\Http\Controllers\RolePermissionController::class,'store_permission'])
        ->name('permission.store');







});

