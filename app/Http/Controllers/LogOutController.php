<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogOutController extends Controller
{
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
