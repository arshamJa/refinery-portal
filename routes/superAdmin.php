<?php

use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'can:super-admin-only','sanitizeInputs'])->group(function () {

    Route::get('super/admin', [SuperAdminController::class, 'index'])
        ->name('super.admin.page');

    Route::post('import/users', [SuperAdminController::class, 'importUsers'])
        ->name('import.users');

    Route::post('import/user/infos', [SuperAdminController::class, 'importUserInfos'])
        ->name('import.user.infos');

    Route::post('/assign-roles', [SuperAdminController::class, 'assignRoles'])->name('roles.assign');


    Route::post('/import/employees', [SuperAdminController::class, 'importEmployees'])->name('import.employees');


});
