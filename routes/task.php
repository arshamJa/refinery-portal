<?php


use App\Http\Controllers\TaskManagementController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {

    Route::get('/task/list/{meeting}',\App\Livewire\TaskList::class)->name('task.list');



    // route to participantsTask
    Route::get('participants/task',[\App\Http\Controllers\ParticipantsTaskController::class,'index'])
        ->name('participants.task');

    // sent tasks by participants to their scriptorium
    Route::get('/tasks/sent',\App\Livewire\TaskSent::class)->name('task.sent');


    // tasks report on time
//    Route::get('/tasks/onTime', \App\Livewire\Reports\ReportTasksDone::class)->name('tasksFinishedOnTime');
    Route::get('completedTasks',[\App\Http\Controllers\Reports\TasksReportController::class,'completedTasks'])
        ->name('completedTasks');

    // tasks report not done on time
    Route::get('incompleteTasks',[\App\Http\Controllers\Reports\TasksReportController::class,'incompleteTasks'])
        ->name('incompleteTasks');

    // tasks report done with delay
    Route::get('tasksWithDelay', [\App\Http\Controllers\Reports\TasksReportController::class,'completedTasksWithDelay'])
        ->name('tasksWithDelay');


    // tasks report not done on time
//    Route::get('/tasks/notDone/onTime', \App\Livewire\Reports\ReportTaskNotDoneOnTime::class)
//        ->name('tasksNotFinishedOnTime');

    // tasks report done with delay
//    Route::get('/tasks/doneWithDelay', \App\Livewire\Reports\ReportTaskDoneWithDelay::class)
//        ->name('tasksDoneWithDelay');


    Route::get('/dashboard/tasks', \App\Livewire\TaskManagementTable::class)
        ->name('tasks.index');

    Route::get('/tasks/create/{meeting}',[TaskManagementController::class,'create'])
        ->name('tasks.create');

    Route::post('/tasks/{meetingId}',[TaskManagementController::class,'store'])->name('tasks.store');

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

