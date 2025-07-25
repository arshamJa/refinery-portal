<?php

use App\Enums\UserPermission;
use App\Enums\UserRole;
//use App\Http\Controllers\meeting\AttendedMeetingsController;
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
use App\Livewire\PresentUsers;
use App\Livewire\Reports\MeetingDashboardReport;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'sanitizeInputs'])->group(function () {

    // Livewire Meeting-Dashboard
    Route::get('meeting/dashboard', MeetingDashboard::class)
        ->name('dashboard.meeting');

    Route::middleware('permission.role:'.UserPermission::SCRIPTORIUM_PERMISSIONS->value.','.UserRole::ADMIN->value)->group(function (){
        Route::get('create/new/meeting', [CreateNewMeetingController::class, 'create'])
            ->name('meeting.create');
        Route::post('create/new/meeting', [CreateNewMeetingController::class, 'store'])
            ->name('meeting.store');
        Route::get('/meetings/{meeting}/edit', [CreateNewMeetingController::class, 'edit'])
            ->name('meeting.edit');
        Route::patch('/meetings/{meeting}', [CreateNewMeetingController::class, 'update'])
            ->name('meeting.update');
        Route::delete('/meetings/{meeting}', [CreateNewMeetingController::class, 'destroy'])
            ->name('meeting.destroy');

        Route::delete('/meetings/{meetingId}/users/{userId}', [CreateNewMeetingController::class, 'deleteUser']);
        Route::delete('/guests/{guestId}/delete', [CreateNewMeetingController::class, 'deleteGuest']);
        Route::delete('/meetings/{meetingId}/guests/{guestIndex}/delete',
            [CreateNewMeetingController::class, 'deleteOuterGuest']);
    });





//    list of meetings that are going to hold
//    Route::get('meeting/list', [MeetingListController::class, 'index'])
//        ->name('meetingsList');

//    meetings that users participated in
//    Route::get('attended/meetings', [AttendedMeetingsController::class, 'index'])
//        ->name('attended.meetings');


//    Route::get('meeting/notification', MeetingNotification::class)
//        ->name('meeting.notification');

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


    // this is the export for scriptorium report table
//    Route::get('/scriptorium-report/export-excel', [ScriptoriumReportController::class, 'exportExcel'])
//        ->name('scriptorium.report.export.excel');


});

