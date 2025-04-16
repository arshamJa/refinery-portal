<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewUserRequest;
use App\Http\Requests\UpdateNewUserRequest;
use App\Models\Department;
use App\Models\Organization;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserInfo;
use App\Traits\UserSearchTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UsersTableController extends Controller
{
    use UserSearchTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        Gate::authorize('viewUserTable', UserInfo::class);

        $user = auth()->user();

        $query = UserInfo::with([
            'user:id,p_code',
            'department:id,department_name',
            'user.roles:id,name',
            'user.permissions:id,name',
            'user.roles.permissions:id,name',
        ])
            ->where('full_name', '!=', 'Arsham Jamali')
            ->select(['id', 'user_id', 'department_id', 'full_name', 'n_code', 'position']);


         $originalUsersCount = $query->count();
        // Get role list with filtering
        $roles = Role::select(['id', 'name'])
            ->when(!$user->hasRole('super_admin'), fn($q) => $q->where('name', '!=', 'super_admin'))
            ->get();

        $permissions = Permission::select(['id', 'name'])->get();


        $query = $this->applyUserSearch($request, $query); // Use the trait

        // Apply filtering and pagination
        $users = $this->applyUserSearch($request, $query)->paginate(5)->appends($request->except('page'));

        // Attach merged permissions to each UserInfo object
        foreach ($users as $userInfo) {
            $userModel = $userInfo->user;
            if ($userModel) {
                $rolePermissions = $userModel->roles->flatMap->permissions;
                $directPermissions = $userModel->permissions;
                $allPermissions = $rolePermissions->merge($directPermissions)->unique('id')->pluck('name')->values(); // reset keys
                $userInfo->all_permissions = $allPermissions; // Virtual property for Blade\

                $userInfo->display_permission = $allPermissions->first();
                $userInfo->more_permissions_count = max($allPermissions->count() - 1, 0);

            } else {
                $userInfo->all_permissions = collect(); // Empty collection fallback
                $userInfo->display_permission = null;
                $userInfo->more_permissions_count = 0;
            }
        }
        $filteredUsersCount = $users->total();


        return view('users.index', [
            'userInfos' => $users,
            'roles' => $roles,
            'permissions' => $permissions,
            'originalUsersCount' => $originalUsersCount,
            'filteredUsersCount' => $filteredUsersCount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('createNewUser', UserInfo::class);
        $user = auth()->user();
        $roles = Role::select(['id', 'name'])
            ->when(!$user->hasRole('super_admin'), fn($q) => $q->where('name', '!=', 'super_admin'))
            ->get();
        $permissions = Permission::select(['id', 'name'])->get();
        $departments = Department::select(['id','department_name'])->get();

        return view('users.create', [
            'departments' => $departments,
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewUserRequest $request)
    {
        // Authorization check
        Gate::authorize('createNewUser', UserInfo::class);

        $validatedData = $request->validated();
        $newUser = User::create([
            'password' => Hash::make($validatedData['password']),
            'p_code' => $validatedData['p_code'],
        ]);

        // Sync roles and permissions
        $newUser->syncRoles([$validatedData['role']]);
        $newUser->syncPermissions($validatedData['permissions']);

        // Create UserInfo record
        $userInfo = UserInfo::create([
            'user_id' => $newUser->id,
            'department_id' => $validatedData['departmentId'],
            'full_name' => $validatedData['full_name'],
            'work_phone' => $validatedData['work_phone'],
            'house_phone' => $validatedData['house_phone'],
            'phone' => $validatedData['phone'],
            'n_code' => $validatedData['n_code'],
            'position' => $validatedData['position'],
        ]);

        // Attach organizations to the new user based on the department
        $organizations = Organization::where('department_id', $validatedData['departmentId'])->get();
        foreach ($organizations as $organization) {
            $organization->users()->attach($newUser->id);
        }
        return to_route('users.index')->with('status', 'کاربر جدید ساخته شد');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userInfo = UserInfo::findOrFail($id);
        Gate::authorize('viewUsers', $userInfo);

        // Load user with related roles and direct permissions
        $user = User::with(['organizations:id,organization_name', 'permissions', 'roles.permissions', 'roles:id,name'])
            ->findOrFail($userInfo->user_id);

        // Get direct permissions
        $directPermissions = $user->permissions;

        // Get role-based permissions
        $rolePermissions = $user->roles->flatMap(function ($role) {
            return $role->permissions;
        });

        // Merge and remove duplicates
        $allPermissions = $directPermissions->merge($rolePermissions)->unique('id');

        return view('users.show', [
            'userInfo' => $userInfo,
            'user' => $user,
            'userRoles' => $user->roles,
            'allPermissions' => $allPermissions, // Pass merged permissions to the view
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $userInfo = UserInfo::with(['user:id,p_code', 'department:id,department_name'])
            ->findOrFail($id);

        Gate::authorize('updateUsers', $userInfo);

        // Fetch departments and permissions
        $departments = Department::select(['id', 'department_name'])->get();
        $permissions = Permission::select(['id', 'name'])->get();

        // Fetch the user with its related roles and permissions
        $user = User::with('roles:id,name', 'permissions:id,name')
            ->findOrFail($userInfo->user_id);

        $roles = Role::select(['id', 'name'])
            ->when(!$user->hasRole('super_admin'), fn($q) => $q->where('name', '!=', 'super_admin'))
            ->get();

        return view('users.edit', [
            'userInfo' => $userInfo,
            'departments' => $departments,
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewUserRequest $request, string $id)
    {
//        $request->validated();

        $userInfo = UserInfo::findOrFail($id);
//        Gate::authorize('updateUsers', $userInfo);

        $user = User::findOrFail($userInfo->user_id);

        // Update user basic info
        $user->update([
            'p_code' => $request->p_code,
            'password' => Hash::make($request->password),
        ]);

        $role = Role::find($request->role);
        // Sync role (only one role in your dropdown)
        if ($request->filled('role')) {
            $user->syncRoles([$role->id]);
        }

        // Sync permissions
        $user->syncPermissions($request->permissions);

        // Update user info
        $userInfo->update([
            'user_id' => $user->id,
            'department_id' => $request->departmentId,
            'full_name' => $request->full_name,
            'work_phone' => $request->work_phone,
            'house_phone' => $request->house_phone,
            'phone' => $request->phone,
            'n_code' => $request->n_code,
            'position' => $request->position,
        ]);

        // Sync organizations related to new department
        if ($request->filled('departmentId')) {
            $department = Department::find($request->departmentId);
            if ($department) {
                $organizations = Organization::where('department_id', $department->id)->get();

                foreach ($organizations as $organization) {
                    $organization->users()->syncWithoutDetaching([$user->id]);
                }
            }
        }

        return redirect()->route('users.index')->with('success', 'کاربر با موفقیت بروزرسانی شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
