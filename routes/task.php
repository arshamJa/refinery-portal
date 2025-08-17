<?php

use App\Http\Controllers\MeetingReportTableController;
use App\Http\Controllers\ParticipantsTaskController;
use App\Http\Controllers\TaskManagementController;
use App\Livewire\MeetingReport;
use App\Livewire\MyTasks;
use App\Livewire\TaskReport;
use App\Livewire\ViewTaskPage;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {


    // report table of all meetings with chart and export
    Route::get('meeting/report/table', MeetingReport::class)
        ->name('meeting.report.table');

    // detail of each meeting with print
    Route::get('/meeting/{meeting}/details', [MeetingReportTableController::class, 'show'])
        ->name('meeting.details.show');

    // report table of all tasks with chart and export
    Route::get('task/report/table', TaskReport::class)
        ->name('task.report.table');

    // detail of each task's participant with print
    Route::get('participant/task/report/{taskUser_id}', [ParticipantsTaskController::class, 'index'])
        ->name('participant.task.report');


    Route::post('/meeting/{meeting}/lock-tasks', [TaskManagementController::class, 'lockTasks'])
        ->name('meeting.lockTasks');

    Route::get('/view/tasks/{meeting}', ViewTaskPage::class)
        ->name('view.task.page');

    Route::post('tasks/{meeting}', [TaskManagementController::class, 'store'])->name('tasks.store');

    // this is my task table
    Route::get('my/task/table', MyTasks::class)->name('my.task.table');


});

