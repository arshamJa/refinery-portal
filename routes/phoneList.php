<?php

use App\Http\Controllers\PhoneListController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('phone-list', [PhoneListController::class, 'index'])
        ->name('phone-list.index');

    Route::post('phone-list/{id}', [PhoneListController::class, 'update'])
        ->name('phone-list.update');

});


