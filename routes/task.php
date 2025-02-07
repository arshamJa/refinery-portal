<?php


use App\Http\Controllers\TaskManagementController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard/tasks', \App\Livewire\TaskManagementTable::class)
        ->name('tasks.index');

    Route::get('/tasks/create/{meeting}',[TaskManagementController::class,'create'])
        ->name('tasks.create');

    Route::post('tasks/{meeting}',[TaskManagementController::class,'store'])->name('tasks.store');

    Route::post('/editTask/{task}',[TaskManagementController::class,'edit'])
        ->name('editTask');

    Route::get('/editUserTask/{task}',[TaskManagementController::class,'editUserTask'])
        ->name('editUserTask');

    Route::put('/editUserTask/{task}',[TaskManagementController::class,'updateUserTask'])
        ->name('updateUserTask');

//    Route::get('/tasks/create',[TaskManagementController::class,'create'])->name('tasks.create');

    Route::get('/tasks/{task}', [TaskManagementController::class, 'show'])->name('tasks.show');

    Route::post('/tasks/{task}',[TaskManagementController::class,'complete'])->name('tasks.complete');

//    Route::get('/tasks/{task}/edit', [TaskManagementController::class, 'edit'])->name('tasks.edit');

    Route::patch('/tasks/{task}', [TaskManagementController::class, 'update'])->name('tasks.update');

    Route::delete('/tasks/{task}', [TaskManagementController::class, 'destroy'])->name('tasks.destroy');

});

