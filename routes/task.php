<?php


use App\Http\Controllers\MeetingReportTableController;
use App\Http\Controllers\ParticipantsTaskController;
use App\Http\Controllers\RefineryReportController;
use App\Http\Controllers\Reports\TasksReportController;
use App\Http\Controllers\TaskManagementController;
use App\Livewire\MyTasks;
use App\Livewire\TaskList;
use App\Livewire\ViewTaskPage;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {

    Route::post('/meeting/{meeting}/lock-tasks', [TaskManagementController::class, 'lockTasks'])
        ->name('meeting.lockTasks');


    Route::get('/view/tasks/{meeting}', ViewTaskPage::class)
        ->name('view.task.page');

    Route::get('/task/list/{meeting}', TaskList::class)->name('task.list');


    Route::post('tasks/{meeting}', [TaskManagementController::class, 'store'])->name('tasks.store');


    // this is my task table
    Route::get('my/task/table', MyTasks::class)->name('my.task.table');

    // Route to Export for all 3 Tasks
    Route::get('tasks/report/completed/download', [TasksReportController::class, 'downloadCompletedTasksExcel'])
        ->name('tasks.report.completed.download');

    Route::get('tasks/report/completed-with-delay/download',
        [TasksReportController::class, 'downloadCompletedTasksWithDelayExcel'])
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

    Route::get('incompleteTasksWithDelay', [TasksReportController::class, 'incompleteTasksWithDelay'])
        ->name('incompleteTasksWithDelay');

    Route::get('participant/task/report/{meeting_id}/{user_id}',
        [ParticipantsTaskController::class, 'index'])
        ->name('participant.task.report');

    // the details of each participant with their task
    Route::get('/meeting/{meeting}/details', [MeetingReportTableController::class, 'show'])
        ->name('meeting.details.show');

    // this is the table for all the meeting in refinery
    Route::get('meeting/report/table', [MeetingReportTableController::class, 'meetingTable'])
        ->name('meeting.report.table')
        ->can('refinery-report');

    Route::get('meeting/report/export', [MeetingReportTableController::class, 'downloadMeetingReport'])
        ->name('meeting.report.download');

    Route::get('task/report/table', [MeetingReportTableController::class, 'taskTable'])
        ->name('task.report.table')
        ->can('refinery-report');


    Route::get('/tasks/report/export', [MeetingReportTableController::class, 'exportTasks'])
        ->name('tasks.report.download');


    Route::get('refinery/reports', [RefineryReportController::class, 'index'])
        ->name('refinery.report')
        ->can('refinery-report');

});

