<?php

use App\Http\Controllers\Admin\NewUserController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {

//    Route::get('newUser',\App\Livewire\NewUser::class)->name('newUser');

//    Route::get('/newUsers', [NewUserController::class, 'index']);
    Route::get('/users/table', \App\Livewire\admin\NewUserTable::class)
        ->name('newUser.index');

    Route::get('/newUsers/create', [NewUserController::class, 'create'])
        ->name('newUser.create')
//        ->can('create-user')
        ->middleware('signed');

    Route::post('/newUsers', [NewUserController::class, 'store'])
        ->name('newUser.store');

    Route::get('/newUsers/{newUser}', [NewUserController::class, 'show'])
        ->name('newUser.show')
//        ->can('view-user')
        ->middleware('signed');

    Route::get('/newUsers/{newUser}/edit', [NewUserController::class, 'edit'])
        ->name('newUser.edit')
//        ->can('update-user')
        ->middleware('signed');

    Route::put('/newUsers/{newUser}', [NewUserController::class, 'update'])
        ->name('newUser.update');

});






