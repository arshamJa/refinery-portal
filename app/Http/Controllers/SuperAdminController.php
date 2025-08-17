<?php

namespace App\Http\Controllers;


use App\Enums\UserRole;
use App\Imports\UserImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class SuperAdminController extends Controller
{
    public function index()
    {
        Gate::authorize('has-role',UserRole::SUPER_ADMIN);
        return view('super-admin.index');
    }
    public function importUsers(Request $request)
    {
        Gate::authorize('has-role',UserRole::SUPER_ADMIN);
        $request->validate([
            'file' => ['required', 'mimes:xlsx,xls,csv']
        ]);
        Excel::import(new UserImport() , $request->file('file'));
        return redirect()->back()->with('status','Users Imported Successfully');
    }
}
