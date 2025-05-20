<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExportPDFController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SendInvitationToReplacementController;
use App\Livewire\admin\AdminDashboard;
use App\Livewire\admin\EmployeeAccess;
use App\Livewire\employee\EmployeeDashboard;
use App\Livewire\employee\EmployeesOrganization;
use App\Livewire\Message;
use App\Livewire\operator\OperatorDashboard;
use App\Livewire\TranslatePage;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {

    Route::get('/', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
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


    Route::get('received/message',\App\Livewire\ReceivedMessage::class)->name('received.message');
    Route::get('sent/message',\App\Livewire\SentMessage::class)->name('sent.message');

    Route::get('employee/organization', EmployeesOrganization::class)
        ->name('employee.organization');
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
