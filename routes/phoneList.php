<?php

use App\Http\Controllers\PhoneListController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('/phones', \App\Livewire\operator\PhoneList::class)
        ->name('phones.index');

    Route::get('/phones/create', [PhoneListController::class, 'create'])
        ->name('phones.create')
        ->can('create-phone-list');

    Route::post('/phones', [PhoneListController::class, 'store'])
        ->name('phones.store')
        ->can('create-phone-list');

    Route::get('/phones/{phone}', [PhoneListController::class, 'show'])
        ->name('phones.show')
        ->can('view-phone-list');

    Route::get('/phones/{phone}/edit', [PhoneListController::class, 'edit'])
        ->name('phones.edit')
        ->can('update-phone-list');

    Route::patch('/phones/{phone}', [PhoneListController::class, 'update'])
        ->name('phones.update')
        ->can('update-phone-list');

});


