<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserInfo;
use App\UserRole;
use Illuminate\Support\Facades\Auth;
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

        $user = auth()->user();

        return redirect()->intended(route('dashboard'));

//        return match(true) {
//            $user->hasRole(UserRole::ADMIN->value),
//            $user->hasRole(UserRole::SUPER_ADMIN->value) => redirect()->route('admin.dashboard'),
//
//            $user->hasRole(UserRole::OPERATOR->value) => redirect()->route('operator.dashboard'),
//
//            $user->hasRole(UserRole::USER->value) => redirect()->route('employee.dashboard'),
//
//            default => abort(403), // Or redirect to a fallback page
//        };
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

        if ($userInfo) {
            $userId = $userInfo->user_id;
            $userExists = User::where('id', $userId)
                ->where('p_code', $request->p_code)
                ->exists();

            if ($userExists) {
                return to_route('login')->with('status', 'شما با این نام و کدپرسنلی قبلا ثبت نام کرده اید');
            }
        }

        // Create the user
        $user = User::create([
            'p_code' => $request->p_code,
            'password' => Hash::make($request->password),
        ]);

        // Create user_info record
        $user->user_info()->create([
            'user_id' => $user->id,
            'department_id' => 1,
            'full_name' => $request->full_name,
            'work_phone' => '',
            'house_phone' => '',
            'phone' => '',
            'n_code' => '',
            'position' => '',
        ]);
        // Assign "employee" role
        $employeeRole = Role::where('name',UserRole::USER->value)->first();
        if ($employeeRole) {
            $user->assignRole($employeeRole);
        }

        // Assign default permissions (optional)
        $defaultPermissions = ['view_dashboard', 'submit_request']; // Replace with actual permission names
        $permissions = Permission::whereIn('name', $defaultPermissions)->get();

        foreach ($permissions as $permission) {
            $user->assignPermission($permission);
        }
        Auth::login($user);
        return to_route('dashboard');
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
