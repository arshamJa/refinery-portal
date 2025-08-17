<?php

use App\Http\Controllers\PhoneListController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'sanitizeInputs'])->group(function () {

    Route::get('phone/list', [PhoneListController::class, 'index'])
        ->name('phone-list.index');

    // ResidentPhones create form & store
    Route::get('/phone-list/resident/create', [PhoneListController::class, 'createResident'])
        ->name('phone-list.resident.create');
    Route::post('/phone-list/resident/store', [PhoneListController::class, 'storeResident'])
        ->name('phone-list.resident.store');
    // Resident Phones
    Route::prefix('resident-phones')->name('resident-phones.')->group(function () {
        Route::get('{id}/edit', [PhoneListController::class, 'editResident'])->name('edit');
        Route::put('{id}', [PhoneListController::class, 'updateResident'])->name('update');
    });


    // OperatorPhones create form & store
    Route::get('/phone-list/operator/create', [PhoneListController::class, 'createOperator'])
        ->name('phone-list.operator.create');
    Route::post('/phone-list/operator/store', [PhoneListController::class, 'storeOperator'])
        ->name('phone-list.operator.store');
    // Operator Phones
    Route::prefix('operator-phones')->name('operator-phones.')->group(function () {
        Route::get('{id}/edit', [PhoneListController::class, 'editOperator'])->name('edit');
        Route::put('{id}', [PhoneListController::class, 'updateOperator'])->name('update');
    });

    Route::delete('phone/list/{source}/{id}', [PhoneListController::class, 'destroy'])
        ->name('phone-list.destroy');

});


