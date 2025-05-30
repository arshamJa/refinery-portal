<?php

namespace App\Http\Controllers;

use App\Enums\UserPermission;
use App\Enums\UserRole;
use App\Http\Requests\PermissionStoreRequest;
use App\Http\Requests\RoleStoreRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    public function connect()
    {
        $users = User::whereNotIn('id', [1, 2])->get();
        // Create the permission if it doesn't exist
        $permissionName = UserPermission::SCRIPTORIUM_PERMISSIONS->value;
        $permission = Permission::firstOrCreate(
            ['name' => $permissionName]
        );
        // Assign the permission to each user
        foreach ($users as $user) {
            $user->assignPermission($permission);
        }
    }

    public function table(Request $request)
    {
        $user = Auth::user();

        // Role query builder
        $roleQuery = Role::with('permissions:id,name');

        if (!$user->hasRole(UserRole::SUPER_ADMIN->value)) {
            $roleQuery->where('name', '!=', UserRole::SUPER_ADMIN->value);
        }

        // Filter roles by 'role' input
        if ($request->filled('role')) {
            $search = $request->get('role');
            $roleQuery->where('name', 'like', "%{$search}%");
        }

        // Filter roles by permission (if needed)
        if ($request->filled('permission')) {
            $search = $request->get('permission');
            $roleQuery->whereHas('permissions', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });
        }

        $roles = $roleQuery->paginate(5);

        // Permissions query builder
        $permissionQuery = Permission::query();

        if ($request->filled('permission')) {
            $search = $request->get('permission');
            $permissionQuery->where('name', 'like', "%{$search}%");
        }

        $permissions = $permissionQuery->paginate(5);

        return view('permission.role-permission-table', [
            'roles' => $roles,
            'permissions' => $permissions,
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
        $permissionIds = array_values($request->permissions ?? []);
        $role->syncPermissions($permissionIds);

        return to_route('role.permission.table')->with('status','نقش جدید اضافه و سطح دسترسی اعمال شد');
    }
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
