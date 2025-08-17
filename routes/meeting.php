<?php

use App\Http\Controllers\meeting\CreateNewMeetingController;
use App\Livewire\MeetingDashboard;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'sanitizeInputs'])->group(function () {

    // Livewire Meeting-Dashboard
    Route::get('meeting/dashboard', MeetingDashboard::class)
        ->name('dashboard.meeting');

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

