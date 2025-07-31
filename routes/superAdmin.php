<?php

use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'superAdminMiddleware','sanitizeInputs'])->group(function () {

    Route::get('super/admin', [SuperAdminController::class, 'index'])
        ->name('super.admin.page');

    Route::post('import/users', [SuperAdminController::class, 'importUsers'])
        ->name('import.users');

    Route::post('import/user/infos', [SuperAdminController::class, 'importUserInfos'])
        ->name('import.user.infos');

    Route::post('/assign-roles', [SuperAdminController::class, 'assignRoles'])->name('roles.assign');

    Route::post('/import/employees', [SuperAdminController::class, 'importEmployees'])->name('import.employees');

    // this is the table for role and permission
    Route::get('roles/permissions', [RolePermissionController::class, 'table'])
        ->name('role.permission.table');

    // Role Controller
    Route::get('roles/create', [RolePermissionController::class, 'create_role'])
        ->name('roles.create');

    Route::post('roles', [RolePermissionController::class, 'store_role'])
        ->name('roles.store');

    Route::get('roles/{role}/edit', [RolePermissionController::class, 'edit_role'])
        ->name('roles.edit');

    Route::put('roles/{role}', [RolePermissionController::class, 'update_role'])
        ->name('roles.update');

    Route::delete('roles/{role}', [RolePermissionController::class, 'destroy_role'])
        ->name('roles.destroy');


    // Permission Controller
    Route::get('permissions/create', [RolePermissionController::class, 'create_permission'])
        ->name('permissions.create');

    Route::post('permissions', [RolePermissionController::class, 'store_permission'])
        ->name('permissions.store');

    Route::get('permissions/{permission}/edit', [RolePermissionController::class, 'edit_permission'])
        ->name('permissions.edit');

    Route::put('permissions/{permission}', [RolePermissionController::class, 'update_permission'])
        ->name('permissions.update');

    Route::delete('permissions/{permission}', [RolePermissionController::class, 'destroy_permission'])
        ->name('permissions.destroy');

    Route::get('create-permissions', [RolePermissionController::class, 'connect'])
        ->name('connect-permissions');

});
