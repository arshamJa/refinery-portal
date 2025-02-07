<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;


Route::get('/otp', [OtpController::class, 'index'])->name('otp');

Route::post('/otp/generate', [OtpController::class, 'generate'])
    ->name('otp.generate');

Route::get('/otp/verification/{id}', [OtpController::class, 'verification'])
    ->name('otp.verification');

Route::post('/otp/check/{id}', [OtpController::class, 'loginWithOtp'])->name('otp.getLogin');

Route::post('/resend/{id}',[OtpController::class,'resendOTP'])->name('resend');
