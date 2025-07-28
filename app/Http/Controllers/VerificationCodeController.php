<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class VerificationCodeController extends Controller
{
    public function showVerifyCodeLogin()
    {
        return view('auth.otp');
    }
    public function send(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^09\d{9}$/'],
        ]);

        $phone = $request->input('phone');
        $user = UserInfo::where('phone', $phone)->first();

        if (!$user) {
            return back()->withErrors(['phone' => 'شماره موبایل در سیستم یافت نشد.']);
        }

        $otpCode = rand(100000, 999999);

        // Save OTP hashed
        DB::table('verify_codes')->updateOrInsert(
            ['phone' => $phone],
            [
                'code_hash' => Hash::make($otpCode),
                'expires_at' => now()->addMinutes(10),
                'verified_at' => null,
            ]
        );

        // Send OTP (mocked here)
//        logger("OTP for $phone is: $otpCode");

        // Store phone temporarily
        Session::put('otp_phone', $phone);

        return back()->with('otp_sent', 'کد تایید ارسال شد.')->with('otp_timer_start', true);
    }

    /**
     * @throws ValidationException
     */
    public function verify(Request $request)
    {
        $this->ensureIsNotRateLimited($request);

        $validated = $request->validate([
            'otp' => 'required|digits:6',
        ]);
        $validated['otp'] = trim(strip_tags($validated['otp']));

        $phone = Session::get('otp_phone');

        $otp = DB::table('verify_codes')->where('phone', $phone)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp || !Hash::check($validated['otp'], $otp->code_hash)) {
            RateLimiter::hit($this->throttleKey($request));
            return back()->withErrors(['otp' => 'کد اشتباه است.']);
        }

        // Update the verified_at field
        DB::table('verify_codes')
            ->where('phone', $phone)
            ->whereNull('verified_at')
            ->update(['verified_at' => now()]);

        $userInfo = UserInfo::where('phone', $phone)->first();
        if (!$userInfo) {
            return back()->withErrors(['otp' => 'کاربر یافت نشد.']);
        }
        $user = User::where('id',$userInfo->user_id)->first();

        RateLimiter::clear($this->throttleKey($request));

        Auth::login($user);
        Session::forget('otp_phone');
        Session::flash('otp_verified', true);
        // Delete the used OTP
        DB::table('verify_codes')->where('phone', $phone)->delete();

        return redirect()->route('dashboard');
    }

    protected function throttleKey(Request $request): string
    {
        // Use phone + IP as unique key for rate limiting
        return Str::lower($request->input('phone') ?? Session::get('otp_phone', '')) . '|' . $request->ip();
    }

    /**
     * @throws ValidationException
     */
    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($this->throttleKey($request));
        throw ValidationException::withMessages([
            'otp' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
}
