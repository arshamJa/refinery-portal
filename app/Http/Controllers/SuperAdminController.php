<?php

namespace App\Http\Controllers;


use App\Imports\UserImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class SuperAdminController extends Controller
{
    public function index()
    {
        Gate::authorize('super-admin-only');
        return view('super-admin.index');
    }
    public function importUsers(Request $request)
    {
        Gate::authorize('super-admin-only');
        $request->validate([
            'file' => ['required', 'mimes:xlsx,xls,csv']
        ]);
        Excel::import(new UserImport() , $request->file('file'));
        return redirect()->back()->with('status','Users Imported Successfully');
    }
}
