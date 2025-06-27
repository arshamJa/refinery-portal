<?php

use App\Http\Controllers\Admin\UsersTableController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('users/table', [UsersTableController::class, 'index'])
        ->name('users.index');

    Route::get('users/create', [UsersTableController::class, 'create'])
        ->name('users.create');

    Route::post('users/store', [UsersTableController::class, 'store'])
        ->name('users.store');

    Route::get('/users/{user}', [UsersTableController::class, 'show'])
        ->name('users.show');

    Route::get('users/{user}/edit', [UsersTableController::class, 'edit'])
        ->name('users.edit');

    Route::put('users/{user}', [UsersTableController::class, 'update'])
        ->name('users.update');

    Route::delete('uses/{user}', [UsersTableController::class, 'destroy'])
        ->name('users.destroy');


    // Route for resting users password by admin
    Route::get('users/reset-password/{user}',[UsersTableController::class,'resetPasswordPage'])
        ->name('reset.password');
    Route::put('admin/users/{user}/reset-password', [UsersTableController::class, 'resetPassword'])
        ->name('admin.users.reset-password');

});






