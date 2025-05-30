<?php

use App\Http\Controllers\meeting\AttendedMeetingsController;
use App\Http\Controllers\meeting\CreateNewMeetingController;
use App\Http\Controllers\meeting\MeetingListController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\Reports\ScriptoriumReportController;
use App\Livewire\AttendedMeetings;
use App\Livewire\InvitationsResult;
use App\Livewire\MeetingDashboard;
use App\Livewire\MeetingInvitation;
use App\Livewire\MeetingNotification;
use App\Livewire\Meetings;
use App\Livewire\MeetingsList;
use App\Livewire\MeetingTable;
use App\Livewire\PresentUsers;
use App\Livewire\Reports\MeetingDashboardReport;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'sanitizeInputs'])->group(function () {

    // Livewire Meeting-Dashboard
    Route::get('dashboard/meeting', MeetingDashboard::class)
        ->name('dashboard.meeting');


//    this is for creating new meeting and send invitation to participants
    Route::get('meeting/table', [CreateNewMeetingController::class, 'index'])
        ->name('meeting.table');
    Route::get('create/new/meeting', [CreateNewMeetingController::class, 'create'])
        ->name('meeting.create');
    Route::post('create/new/meeting', [CreateNewMeetingController::class, 'store'])
        ->name('meeting.store');
    Route::get('/meetings/{meeting}', [CreateNewMeetingController::class, 'show'])
        ->name('meeting.show');
    Route::get('/meetings/{meeting}/edit', [CreateNewMeetingController::class, 'edit'])
        ->name('meeting.edit');
    Route::patch('/meetings/{meeting}', [CreateNewMeetingController::class, 'update'])
        ->name('meeting.update');
    Route::delete('/meetings/{meeting}', [CreateNewMeetingController::class, 'destroy'])
        ->name('meeting.destroy');


    Route::delete('/meetings/{meetingId}/users/{userId}', [CreateNewMeetingController::class, 'deleteUser']);
    Route::delete('/guests/{guestId}/delete', [CreateNewMeetingController::class, 'deleteGuest']);
    Route::delete('/meetings/{meetingId}/guests/{guestIndex}/delete', [CreateNewMeetingController::class, 'deleteOuterGuest']);

    // Route for deleting an inner guest
//    Route::delete('/meetings/{meetingId}/guests/inner/{guestId}',
//        [CreateNewMeetingController::class, 'deleteInnerGuest'])->name('deleteInnerGuest');
//    // Route for deleting an outer guest
//    Route::delete('/meetings/{meetingId}/guests/outer/{guestIndex}',
//        [CreateNewMeetingController::class, 'deleteOuterGuest'])->name('deleteOuterGuest');


//    Route::get('/meetings/{meetingId}/edit', \App\Livewire\EditMeeting::class)
//        ->name('meeting.edit');
//    the end of creating new meeting


//    list of meetings that are going to hold
//    Route::get('meeting/list', [MeetingListController::class, 'index'])
//        ->name('meetingsList');

//    meetings that users participated in
//    Route::get('attended/meetings', [AttendedMeetingsController::class, 'index'])
//        ->name('attended.meetings');


    Route::get('meeting/notification', MeetingNotification::class)
        ->name('meeting.notification');

    Route::get('meeting/report', MeetingDashboardReport::class)->name('meeting.report');


//    Route::get('scriptorium/report', [ScriptoriumReportController::class, 'index'])
//        ->name('scriptorium.report');
//    Route::get('/scriptorium/report',\App\Livewire\ScriptoriumReport::class)
//        ->name('scriptorium.report');


    //    Route::get('/meetings', [TaskManagementController::class, 'index'])->name('meetings.index');
//    Route::get('/dashboard/meetings', MeetingTable::class)
//        ->name('meetings.index');

//    Route::get('/meetings', Meetings::class)->name('meetings');


    // send invitation to participants
//    Route::get('meeting/invitation', MeetingInvitation::class)
//        ->name('meeting.invitation');


    // receive invitations result
//    Route::get('invitations/result', InvitationsResult::class)
//        ->name('invitations.result');


    Route::get('/presentUsers/{meetingId}', PresentUsers::class)
        ->name('presentUsers');


    // this is the export for scriptorium report table
//    Route::get('/scriptorium-report/export-excel', [ScriptoriumReportController::class, 'exportExcel'])
//        ->name('scriptorium.report.export.excel');


});

