<?php

namespace App\Http\Controllers;

use App\Rules\farsi_chs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use function Symfony\Component\String\s;

class RolePermissionController extends Controller
{
    public function table()
    {
        $roles = Role::with('permissions:id,name')->paginate(5);
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
        $validated = $request->validate([
           'role' => ['required','string','max:40',new farsi_chs()],
            'permissions' => ['required']
        ]);
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
        $permissions = DB::table('permissions')->select('id','name')->get();
        return view('permission.edit-role',['role'=>$role , 'permissions'=> $permissions]);
    }
    public function update_role(Request $request, string $id)
    {
        $validated = $request->validate([
            'role' => ['required','string','max:40',new farsi_chs()],
            'permissions' => ['required']
        ]);
        $role = Role::findOrFail($id);
        $role->name = $request->role;
        $role->save();
        $role->syncPermissions($request->permissions);
        return to_route('role.permission.table')->with('status','نقش با موفقیت آپدیت شد');
    }
    public function destroy_role(string $id)
    {
        $role = Role::findOrFail($id)->delete();
        return to_route('role.permission.table')->with('status','نقش با موفقیت حذف شد');
    }

    // these below are Permission
    public function create_permission()
    {
        return view('permission.create-permission');
    }
    public function store_permission(Request $request)
    {
        $validated = $request->validate([
            'permission' => ['required','string','max:40',new farsi_chs()],
        ]);
        $permission = Permission::create(['name'=>$request->permission]);
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
    public function update_permission(Request $request, string $id)
    {
        $validated = $request->validate([
            'permission' => ['required','string','max:40',new farsi_chs()],
        ]);
        $permission = Permission::findOrFail($id);
        $permission->name = $request->permission;
        $permission->save();
        return to_route('role.permission.table')->with('status','سطح دسترسی با موفقیت آپدیت شد');
    }
    public function destroy_permission(string $id)
    {
        $permission = Permission::findOrFail($id)->delete();
        return to_route('role.permission.table')->with('status','سطح دسترسی با موفقیت حذف شد');
    }



}
