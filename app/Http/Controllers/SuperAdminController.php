<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Imports\UserInfoImport;
use App\Imports\UsersImport;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use function Laravel\Prompts\select;

class SuperAdminController extends Controller
{
    public function index()
    {
        $users = User::with(['user_info:id,user_id,full_name', 'roles:id,name'])
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', UserRole::SUPER_ADMIN->value);
            })->get();
        return view('super-admin.index',['users' => $users]);
    }
    public function importUsers(Request $request)
    {
        Gate::authorize('super-admin-only');
        $request->validate([
            'file' => ['required']
        ]);
        Excel::import(new UsersImport() , $request->file('file'));
        return redirect()->back()->with('status','Users Imported Successfully');
    }
    public function importUserInfos(Request $request)
    {
        Gate::authorize('super-admin-only');
        $request->validate([
            'file' => ['required']
        ]);
        Excel::import(new UserInfoImport() , $request->file('file'));
        return redirect()->back()->with('status','User Infos Imported Successfully');
    }
    public function assignRoles()
    {
        $users = User::all();
        foreach ($users as $user) {
            if ($user->id == 2) {
                $role = Role::where('name', UserRole::ADMIN->value)->first();
            } elseif (in_array($user->id, [3, 4])) {
                $role = Role::where('name', UserRole::OPERATOR->value)->first();
            } elseif ($user->id >= 5) {
                $role = Role::where('name', UserRole::USER->value)->first();
            } else {
                continue; // Skip if no role match
            }
            // Assign only one role per user
            $user->syncRoles([$role->id]);
        }
        return redirect()->back()->with('status', 'Roles assigned successfully.');
    }
}
