<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Imports\UserInfoImport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class SuperAdminController extends Controller
{
    public function index()
    {
        return view('super-admin.index');
    }
    public function importUsers(Request $request)
    {
        Gate::authorize('users-info');

        $request->validate([
            'file' => ['required']
        ]);
        Excel::import(new UsersImport() , $request->file('file'));
        return redirect()->back()->with('status','Users Imported Successfully');
    }
    public function importUserInfos(Request $request)
    {
        Gate::authorize('users-info');

        $request->validate([
            'file' => ['required']
        ]);
        Excel::import(new UserInfoImport() , $request->file('file'));
        return redirect()->back()->with('status','User Infos Imported Successfully');
    }
}
