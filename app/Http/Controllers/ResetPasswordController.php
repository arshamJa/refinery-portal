<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    public function index($id
    ): Factory|View|Application {
        return view('resetPassword.index', ['id' => $id]);
    }

    /**
     * @throws ValidationException
     */
    public function reset(Request $request, $id): RedirectResponse
    {

        $validated = $request->validate([
            'password' => 'required|numeric|digits:8|confirmed'
        ]);
        $user = User::where('id', $id)->update([
            'password' => Hash::make($request->password)
        ]);
        return redirect()->route('login')->with('newPassword', 'رمز جدید تایید شد');
    }
}
