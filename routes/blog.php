<?php

use App\Http\Controllers\BlogController;
use App\Livewire\employee\News;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'sanitizeInputs'])->group(function () {

    Route::get('blogs', News::class)
        ->name('blogs.index');


    Route::get('blogs/create', [BlogController::class, 'create'])
        ->name('blogs.create');

    Route::post('upload-image', [App\Http\Controllers\BlogController::class, 'uploadImage'])
        ->name('upload.image');

    Route::post('blogs', [BlogController::class, 'store'])
        ->name('blogs.store');

    Route::get('blogs/{blog}/edit', [BlogController::class, 'edit'])
        ->name('blogs.edit');

    Route::put('blogs/{blog}', [BlogController::class, 'update'])
        ->name('blogs.update');

    Route::delete('/blogs/images/{id}', [BlogController::class, 'deleteImage'])
        ->name('blogs.images.destroy');

    Route::delete('blogs/{blog}', [BlogController::class, 'destroy'])
        ->name('blogs.destroy');

});
