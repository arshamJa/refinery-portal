<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Sodium\increment;

class OtpController extends Controller
{
    public function index()
    {
        return view('otp.index');
    }
    public function generate(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|digits:11'
        ]);
        $user = UserInfo::where('phone',$request->phone)->first();
        if (UserInfo::where('phone',$request->phone)->exists()){
            $verify = VerificationCode::updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'user_id' => $user->id,
                    'two_factor_code' => rand(100000, 999999)
                ]
            );
            return  redirect()->route('otp.verification',['id' => $user->id]);
        }else{
            return redirect()->back()->with('guest','شماره تلفن شما ثبت نشده');
        }

    }

    public function verification($id)
    {
        return view('otp.otp-verification')->with(['id' => $id]);
    }

    public function resendOTP($id)
    {
        $verificationCode = VerificationCode::where('user_id',$id)->first();
        if ($verificationCode->two_factor_expires_at < now()){
            return redirect('/otp')->with('time','Your verification code expired');
        }
            return redirect()->back()->with('send','otp has send');
    }

    public function loginWithOtp(Request $request ,$id)
    {
        $request->validate([
            'two_factor_code' => 'required|numeric|digits:6|exists:verification_codes'
        ]);
        $verificationCode = VerificationCode::where('user_id',$id)->first();
        $verificationCode = VerificationCode::where('user_id',$id)->first();
        if ($verificationCode->two_factor_expires_at < now()){
            return redirect()->route('otp.verification',['id'=>$id])->with('time','Your verification code expired');
        }else{
            return redirect()->route('reset.password',['id'=>$id]);
        }
    }
}
