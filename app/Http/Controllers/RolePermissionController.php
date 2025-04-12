<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionStoreRequest;
use App\Http\Requests\RoleStoreRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    public function table()
    {
        $user = Auth::user();
        $rolesQuery = Role::with('permissions:id,name');
        // Check if the authenticated user is a super-admin
        if (!$user->hasRole('super-admin')) {
            // If not super-admin, filter out the super-admin role
            $rolesQuery->where('name', '!=', 'super_admin');
        }
        $roles = $rolesQuery->paginate(5);
//        $authorizedRoles = [];
//        foreach ($roles as $role) {
//            $authorizedRoles[$role->id] = Gate::allows('update', $role);
//        }
        $permissions = DB::table('permissions')->select('id', 'name')->paginate(5);
        return view('permission.role-permission-table', [
            'roles' => $roles,
            'permissions' => $permissions,
//            'authorizedRoles' => $authorizedRoles
        ]);
    }
    public function create_role()
    {
        $permissions = DB::table('permissions')->select('id','name')->paginate(5);
        return view('permission.create-role',[
            'permissions' => $permissions
        ]);
    }
    public function store_role(RoleStoreRequest $request)
    {
        $request->validated();
        $role = Role::create(['name' => $request->role]);
        $role->syncPermissions($request->permissions);
        return to_route('role.permission.table')->with('status','نقش جدید اضافه و سطح دسترسی اعمال شد');
    }
//    public function show_role(string $id)
//    {
//        $role = Role::with('permissions:id,name')->findOrFail($id);
//        return view('permission.show-role',['role'=>$role]);
//    }
    public function edit_role(string $id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::select('id', 'name')->get();
        return view('permission.edit-role',['role'=>$role , 'permissions'=> $permissions]);
    }
    public function update_role(RoleStoreRequest $request, string $id)
    {
        $request->validated();
        $role = Role::findOrFail($id);
        $role->name = $request->role;
        $role->save();
        $role->syncPermissions($request->permissions);
        return to_route('role.permission.table')->with('status','نقش با موفقیت آپدیت شد');
    }
    public function destroy_role(string $id)
    {
        $role = Role::findOrFail($id)->delete();
        DB::table('permission_role')->where('role_id',$id)->delete();
        return to_route('role.permission.table')->with('status','نقش با موفقیت حذف شد');
    }

    // these below are Permission
    public function create_permission()
    {
        return view('permission.create-permission');
    }
    public function store_permission(PermissionStoreRequest $request)
    {
        $request->validated();
        $permission = Str::squish($request->permission);
        Permission::create(['name' => $permission]);
        return to_route('role.permission.table')->with('status','سطح دسترسی جدید با موفقیت ایجاد شد');
    }
//    public function show_permission(string $id)
//    {
//        $permission = Permission::findOrFail($id);
//        return view('permission.show-permission',['permission'=>$permission]);
//    }
    public function edit_permission(string $id)
    {
        $permission = Permission::findOrFail($id);
        return view('permission.edit-permission',['permission'=> $permission]);
    }
    public function update_permission(PermissionStoreRequest $request, string $id)
    {
        $request->validated();
        $permissionName = Str::squish($request->permission);
        $permission = Permission::findOrFail($id);
        $permission->name = $permissionName;
        $permission->save();
        return to_route('role.permission.table')->with('status','سطح دسترسی با موفقیت آپدیت شد');
    }
    public function destroy_permission(string $id)
    {
        $permission = Permission::findOrFail($id)->delete();
        return to_route('role.permission.table')->with('status','سطح دسترسی با موفقیت حذف شد');
    }



}
