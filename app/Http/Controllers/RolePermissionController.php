<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function table()
    {
        $roles = Role::with('permissions')->paginate(5);
        $permissions = DB::table('permissions')->select('id','name')->paginate(5);
        return view('permission.role-permission-table',[
           'roles' => $roles,
           'permissions' => $permissions
        ]);

    }
    public function create_role()
    {
        $permissions = DB::table('permissions')->select('id','name')->paginate(5);
        return view('permission.create-role',[
            'permissions' => $permissions
        ]);
    }
    public function store_role(Request $request)
    {
        dd($request->all());
    }





    public function create_permission()
    {
        return view('permission.create-permission');
    }
    public function store_permission(Request $request)
    {

    }



}
