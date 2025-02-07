<?php

use App\Http\Controllers\ProfilePageController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('profile', [ProfilePageController::class, 'index'])
        ->name('profile')
        ->can('view-profile-page')
        ->middleware('signed');

    Route::post('/updateProfileInformation', [ProfilePageController::class, 'updateProfileInformation'])
        ->name('updateProfileInformation')
        ->can('update-profile-page');

    Route::post('/updatePassword', [ProfilePageController::class, 'updatePassword'])
        ->name('updatePassword')
        ->can('update-profile-page');

    Route::post('/updateProfilePhoto', [ProfilePageController::class, 'updateProfilePhoto'])
        ->name('updateProfilePhoto')
        ->can('update-profile-page');

    Route::post('/deleteProfilePhoto', [ProfilePageController::class, 'deleteProfilePhoto'])
        ->name('deleteProfilePhoto')
        ->can('update-profile-page');


});
