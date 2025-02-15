<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['auth','sanitizeInputs'])->group( function () {

    Route::get('/meeting/notification',\App\Livewire\MeetingNotification::class)
        ->name('meeting.notification');

    Route::get('/meeting/report', \App\Livewire\Reports\MeetingDashboardReport::class)->name('meeting.report');

    Route::get('attended/meetings',\App\Livewire\AttendedMeetings::class)->name('attended.meetings');


//    Route::get('/scriptorium/report',[\App\Http\Controllers\ScriptoriumReportController::class,'index'])
//            ->name('scriptorium.report');
    Route::get('/scriptorium/report',\App\Livewire\ScriptoriumReport::class)->name('scriptorium.report');


    //    Route::get('/meetings', [TaskManagementController::class, 'index'])->name('meetings.index');
    Route::get('/dashboard/meetings',\App\Livewire\MeetingTable::class)
        ->name('meetings.index');

    Route::get('/meetings',\App\Livewire\Meetings::class)->name('meetings');

    Route::get('/meetings/list',\App\Livewire\MeetingsList::class)->name('meetingsList');


    // send invitation to participants
    Route::get('meeting/invitation',\App\Livewire\MeetingInvitation::class)
        ->name('meeting.invitation');


    // receive invitations result
    Route::get('invitations/result',\App\Livewire\InvitationsResult::class)
        ->name('invitations.result');




    Route::get('/meetings/create', [\App\Http\Controllers\MeetingController::class, 'create'])
        ->name('meetings.create');

    Route::post('/meetings', [\App\Http\Controllers\MeetingController::class, 'store'])
        ->name('meetings.store');

    Route::get('/meetings/{meeting}', [\App\Http\Controllers\MeetingController::class, 'show'])
        ->name('meetings.show');

    Route::get('/meetings/{meeting}/edit', [\App\Http\Controllers\MeetingController::class, 'edit'])
        ->name('meetings.edit');

    Route::patch('/meetings/{meeting}', [\App\Http\Controllers\MeetingController::class, 'update'])
        ->name('meetings.update');

    Route::get('/presentUsers/{meetingId}',\App\Livewire\PresentUsers::class)
        ->name('presentUsers');

    Route::delete('/meetings/{meeting}', [\App\Http\Controllers\MeetingController::class, 'destroy'])
        ->name('meetings.destroy');


});


