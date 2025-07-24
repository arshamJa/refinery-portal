<?php

use App\Http\Controllers\PhoneListController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('phone/list', [PhoneListController::class, 'index'])
        ->name('phone-list.index');

    Route::get('phone/list/create',[PhoneListController::class,'create'])
        ->name('phone-list.create');

    Route::post('phone/list/store',[PhoneListController::class,'store'])
        ->name('phone-list.store');

    Route::get('phone/list/{source}/{id}/edit', [PhoneListController::class, 'edit'])
        ->name('phone-list.edit');

    Route::put('phone/list/{source}/{id}', [PhoneListController::class, 'update'])
        ->name('phone-list.update');

    Route::delete('phone/list/{source}/{id}', [PhoneListController::class, 'destroy'])
        ->name('phone-list.destroy');

});


