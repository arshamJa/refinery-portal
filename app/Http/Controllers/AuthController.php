<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\UserInfo;
use App\Rules\farsi_chs;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }
    /**
     * Handle an incoming authentication request.
     * @throws ValidationException
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }
    public function registrationPage()
    {
        return view('auth.register');
    }
    public function register(RegisterRequest $request)
    {
        $request->validated();
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
    /**
     * Log the current user out of the application.
     */
    public function logout(): \Illuminate\Http\RedirectResponse
    {
        \Illuminate\Support\Facades\Auth::guard('web')->logout();
        Session::invalidate();
        Session::regenerateToken();
        return to_route('login');
    }

}
