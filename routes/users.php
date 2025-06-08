<?php

use App\Http\Controllers\Admin\UsersTableController;
use App\Models\UserInfo;
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

});






