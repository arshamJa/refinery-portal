<?php

use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function (){

    Route::get('blogs', \App\Livewire\operator\News::class)
        ->name('blogs.index');

    Route::get('blogs/create',[BlogController::class,'create'])
        ->name('blogs.create');

    Route::post('upload-image', [App\Http\Controllers\BlogController::class, 'uploadImage'])->name('upload.image');


    Route::post('blogs',[BlogController::class,'store'])
        ->name('blogs.store');

    Route::get('blogs/{blog}',[BlogController::class,'show']);

    Route::get('blogs/{blog}/edit',[BlogController::class,'edit'])
        ->name('blogs.edit');

    Route::put('blogs/{blog}',[BlogController::class,'update'])
        ->name('blogs.update');


});
