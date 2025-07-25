<?php

use App\Http\Controllers\ProfilePageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth','sanitizeInputs'])->group(function () {

    Route::get('profile', [ProfilePageController::class, 'index'])
        ->name('profile')->can('profile-page')
        ->middleware('signed');

    Route::post('updateProfileInformation', [ProfilePageController::class, 'updateProfileInformation'])
        ->name('updateProfileInformation')
        ->can('profile-page');

    Route::post('updatePassword', [ProfilePageController::class, 'updatePassword'])
        ->name('updatePassword')
        ->can('profile-page');

    Route::post('updateProfilePhoto', [ProfilePageController::class, 'updateProfilePhoto'])
        ->name('updateProfilePhoto')
        ->can('profile-page');

    Route::delete('deleteProfilePhoto', [ProfilePageController::class, 'deleteProfilePhoto'])
        ->name('deleteProfilePhoto')
        ->can('profile-page');


});
