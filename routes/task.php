<?php


use App\Http\Controllers\Reports\TasksReportController;
use App\Http\Controllers\TaskManagementController;
use App\Livewire\TaskList;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {

    Route::post('/meeting/{meeting}/lock-tasks', [TaskManagementController::class, 'lockTasks'])
        ->name('meeting.lockTasks');


    Route::get('/view/tasks/{meeting}', \App\Livewire\ViewTaskPage::class)
        ->name('view.task.page');

    Route::get('/task/list/{meeting}', TaskList::class)->name('task.list');


    Route::post('tasks/{meeting}', [TaskManagementController::class, 'store'])->name('tasks.store');


    // this is my task table
    Route::get('my/task/table',\App\Livewire\MyTasks::class)->name('my.task.table');

    // Route to Export for all 3 Tasks
    Route::get('tasks/report/completed/download', [TasksReportController::class, 'downloadCompletedTasksExcel'])
        ->name('tasks.report.completed.download');

    Route::get('tasks/report/completed-with-delay/download',[TasksReportController::class, 'downloadCompletedTasksWithDelayExcel'])
        ->name('tasks.report.completed.withDelay.download');

    Route::get('tasks/report/incomplete/download', [TasksReportController::class, 'downloadIncompleteTasksExcel'])
        ->name('tasks.report.incomplete.download');
    // End of Route to Export for all 3 Tasks


    // tasks report on time
    Route::get('completedTasksOnTime', [TasksReportController::class, 'completedTasks'])
        ->name('completedTasks');

    // tasks report done with delay
    Route::get('completedTasksWithDelay',
        [TasksReportController::class, 'completedTasksWithDelay'])
        ->name('tasksWithDelay');

    // tasks report not done on time
    Route::get('incompleteTasksOnTime', [TasksReportController::class, 'incompleteTasks'])
        ->name('incompleteTasksOnTime');

    Route::get('incompleteTasksWithDelay',[TasksReportController::class,'incompleteTasksWithDelay'])
        ->name('incompleteTasksWithDelay');



});

