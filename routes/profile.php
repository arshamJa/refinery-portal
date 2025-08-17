<?php

use App\Http\Controllers\ProfilePageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth','sanitizeInputs'])->group(function () {

    Route::get('profile', [ProfilePageController::class, 'index'])
        ->name('profile');

    Route::post('updateProfileInformation', [ProfilePageController::class, 'updateProfileInformation'])
        ->name('updateProfileInformation');

    Route::post('updatePassword', [ProfilePageController::class, 'updatePassword'])
        ->name('updatePassword');

    Route::post('updateProfilePhoto', [ProfilePageController::class, 'updateProfilePhoto'])
        ->name('updateProfilePhoto');

    Route::delete('deleteProfilePhoto', [ProfilePageController::class, 'deleteProfilePhoto'])
        ->name('deleteProfilePhoto');


});
