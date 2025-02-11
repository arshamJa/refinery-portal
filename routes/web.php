<?php

use App\Http\Controllers\ResetPasswordController;
use App\Livewire\admin\EmployeeAccess;
use App\Livewire\LoginPage;
use App\Livewire\TranslatePage;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('/', LoginPage::class)->name('login');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout',[\App\Http\Controllers\LogOutController::class,'logout'])
        ->name('logout');

    Route::view('dashboard', 'dashboard')
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

Route::get('employee/organization', \App\Livewire\employee\EmployeesOrganization::class)->name('employee.organization');



    // Employee-Access-Table Route
    Route::get('employee/access', EmployeeAccess::class)
        ->name('employeeAccess')
        ->middleware('signed');


//    Route::get('/chat', App\Livewire\Chat\Index::class)
//        ->name('chat.index');
//    Route::get('/chat/{query}', App\Livewire\Chat\Chat::class)
//        ->name('chat');
//    Route::get('/users', Users::class)
//        ->name('users');


    Route::get('/translate', TranslatePage::class)
        ->name('translate')
        ->can('view-any');

    Route::get('message',\App\Livewire\Message::class)->name('message');
    Route::get('meetings/request',\App\Livewire\MeetingsRequest::class)->name('meetings.request');
});

Route::get('/reset/password/{id}', [ResetPasswordController::class, 'index'])
    ->name('reset.password')
    ->middleware('guest');
Route::post('/resetPassword/{id}', [ResetPasswordController::class, 'reset'])
    ->name('resetPassword');




require __DIR__.'/department_organization.php';
require __DIR__.'/otp.php';
require __DIR__.'/importExport.php';
require __DIR__.'/blog.php';
require __DIR__.'/phoneList.php';
require __DIR__.'/newUser.php';
require __DIR__.'/task.php';
require __DIR__.'/meeting.php';
require __DIR__.'/profile.php';
