<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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

        $user = auth()->user();
        if ($user->id === 1 && $request->input('p_code') !== config('auth.super_admin_p_code')) {
            auth()->logout(); // Log the user out just in case
            abort(403, 'Unauthorized super admin login attempt.');
        }

        return redirect()->intended(route('dashboard'));
    }
    /**
     * Log the current user out of the application.
     */
    public function logout(): \Illuminate\Http\RedirectResponse
    {
        Auth::guard('web')->logout();
        Session::invalidate();
        Session::regenerateToken();
        return to_route('login');
    }
}
