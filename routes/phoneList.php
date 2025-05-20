<?php

use App\Http\Controllers\PhoneListController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('phone-list', [PhoneListController::class, 'index'])
        ->name('phone-list.index');

    Route::get('phone-list/{id}/edit', [PhoneListController::class, 'edit'])
        ->name('phone-list.edit');

    Route::put('phone-list/{id}', [PhoneListController::class, 'update'])
        ->name('phone-list.update');

    Route::post('phone-list/{id}', [PhoneListController::class, 'update'])
        ->name('phone-list.update');

});


