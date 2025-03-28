<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogOutController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SendInvitationToReplacementController;
use App\Livewire\admin\EmployeeAccess;
use App\Livewire\employee\EmployeesOrganization;
use App\Livewire\Message;
use App\Livewire\TranslatePage;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {

    Route::get('/', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
    Route::get('register', [AuthController::class, 'registrationPage'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.store')
        ->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');


    Route::post('sendInvitation/{meetingId}',
        [SendInvitationToReplacementController::class, '__invoke'])
        ->name('sendInvitation');



    Route::view('dashboard', 'dashboard')
        ->middleware(['auth', 'verified'])
        ->name('dashboard');




    // Employee-Access-Table Route
    Route::get('employee/access', EmployeeAccess::class)
        ->name('employeeAccess');


//    Route::get('/chat', App\Livewire\Chat\Index::class)
//        ->name('chat.index');
//    Route::get('/chat/{query}', App\Livewire\Chat\Chat::class)
//        ->name('chat');
//    Route::get('/users', Users::class)
//        ->name('users');


    Route::get('/translate', TranslatePage::class)
        ->name('translate')
        ->can('view-any');

    Route::get('message', Message::class)->name('message');
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
require __DIR__.'/rolePermission.php';
