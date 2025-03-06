<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use App\Rules\farsi_chs;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function registrationPage()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name' => ['bail', 'required', 'string', 'max:255', new farsi_chs()],
            'p_code' => ['bail', 'required', 'numeric', 'digits:6'],
            'password' => ['required', 'numeric', 'digits:8']
        ]);

        $full_name = Str::squish($request->full_name);


        $userInfo = UserInfo::where('full_name', $full_name)->first();
        $userId = $userInfo->user_id;
        if ($userInfo) {
            $user = User::where('id',$userId)->where('p_code',$request->p_code)->exists();
            return to_route('login')->with('status', 'شما با این نام و کدپرسنلی قبلا ثبت نام کرده اید');
        } else {
            $user = User::create([
                'p_code' => $request->p_code,
                'password' => Hash::make($request->password)
            ]);
            $user->user_info()->create([
                'user_id' => $user->id,
                'department_id' => 1,
                'full_name' => $request->full_name,
                'work_phone' => '',
                'house_phone' => '',
                'phone' => '',
                'n_code' => '',
                'position' => '',
                'created_at' => '',
                'updated_at' => '',
            ]);
            \Illuminate\Support\Facades\Auth::login($user);
            return to_route('dashboard');
        }
    }

}
