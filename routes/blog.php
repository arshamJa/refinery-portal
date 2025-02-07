<?php

use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function (){

    Route::get('/blogs', \App\Livewire\operator\News::class)
        ->name('blogs.index');

    Route::get('/blogs/create',[BlogController::class,'create'])
        ->name('blogs.create')
        ->can('create-blog')
        ->middleware('signed');

    Route::post('/blogs',[BlogController::class,'store'])
        ->name('blogs.store')
        ->can('create-blog');

    Route::get('/blogs/{blog}',[BlogController::class,'show'])
        ->name('blogs.show')
        ->middleware('signed');

    Route::get('/blogs/{blog}/edit',[BlogController::class,'edit'])
        ->name('blogs.edit')
        ->can('update-blog')
        ->middleware('signed');

    Route::put('/blogs/{blog}',[BlogController::class,'update'])
        ->name('blogs.update')
        ->can('update-blog');


});
