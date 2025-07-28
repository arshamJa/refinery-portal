<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewUserRequest;
use App\Http\Requests\UpdateNewUserRequest;
use App\Models\Department;
use App\Models\OperatorPhones;
use App\Models\Organization;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;


class UsersTableController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('admin-role');
        $user = auth()->user();
        $query = UserInfo::with([
            'user:id,p_code',
            'department:id,department_name',
            'user.roles:id,name',
            'user.permissions:id,name',
            'user.roles.permissions:id,name',
        ])
            ->where('full_name', '!=', 'Arsham Jamali')
            ->select(['id', 'user_id', 'department_id', 'full_name', 'n_code', 'position'])
            ->oldest();

        if ($request->filled('search')) {
            $searchTerm = trim(strip_tags($request->input('search')));

            $query->where(function ($q) use ($searchTerm) {
                $q->where('full_name', 'like', "%{$searchTerm}%")
                    ->orWhere('n_code', 'like', "%{$searchTerm}%")
                    ->orWhere('position', 'like', "%{$searchTerm}%")
                    ->orWhereHas('user', fn($q) =>
                    $q->where('p_code', 'like', "%{$searchTerm}%"))
                    ->orWhereHas('department', fn($q) =>
                    $q->where('department_name', 'like', "%{$searchTerm}%"))
                    ->orWhereHas('user.roles', fn($q) =>
                    $q->where('name', 'like', "%{$searchTerm}%"))
                    ->orWhereHas('user.permissions', fn($q) =>
                    $q->where('name', 'like', "%{$searchTerm}%"));
            });
        }

        // Count original before pagination
        $originalUsersCount = UserInfo::where('full_name', '!=', 'Arsham Jamali')->count();

        $roles = Role::select(['id', 'name'])
            ->when(!$user->hasRole('super_admin'), fn($q) => $q->where('name', '!=', 'super_admin'))
            ->get();

        $permissions = Permission::select(['id', 'name'])->get();

        $users = $query->paginate(10)->appends($request->except('page'));

        foreach ($users as $userInfo) {
            $userModel = $userInfo->user;
            if ($userModel) {
                $rolePermissions = $userModel->roles->flatMap->permissions;
                $directPermissions = $userModel->permissions;
                $allPermissions = $rolePermissions->merge($directPermissions)->unique('id')->pluck('name')->values();
                $userInfo->all_permissions = $allPermissions;
                $userInfo->display_permission = $allPermissions->first();
                $userInfo->more_permissions_count = max($allPermissions->count() - 1, 0);
            } else {
                $userInfo->all_permissions = collect();
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
    public function export(Request $request)
    {
        Gate::authorize('admin-role');
        $searchTerm = $request->input('search');
        return Excel::download(new UsersExport($searchTerm), 'users.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('admin-role');
        $user = auth()->user();
        $roles = Role::select(['id', 'name'])
            ->when(!$user->hasRole(UserRole::SUPER_ADMIN->value),
                fn($q) => $q->where('name', '!=', UserRole::SUPER_ADMIN->value))
            ->get();
        $permissions = Permission::select(['id', 'name'])->get();
        $departments = Department::select(['id','department_name'])->get();
        $organizations = Organization::select(['id','organization_name'])->get();
        return view('users.create', [
            'departments' => $departments,
            'organization' => $organizations,
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewUserRequest $request)
    {
        Gate::authorize('admin-role');
        $validatedData = $request->validated();
        $newUser = User::create([
            'password' => Hash::make($validatedData['password']),
            'p_code' => $validatedData['p_code'],
        ]);

        $permissions = isset($validatedData['permissions'])
            ? Permission::whereIn('name', array_keys($validatedData['permissions']))->pluck('id')->toArray()
            : [];

        $newUser->roles()->sync([$validatedData['role']]);
        $newUser->permissions()->sync($permissions);
        $signature_path = $validatedData['signature']->store('signatures','public');

        // Create UserInfo record
        UserInfo::create([
            'user_id' => $newUser->id,
            'department_id' => $validatedData['departmentId'],
            'full_name' => $validatedData['full_name'],
            'work_phone' => $validatedData['work_phone'],
            'house_phone' => $validatedData['house_phone'],
            'phone' => $validatedData['phone'],
            'n_code' => $validatedData['n_code'],
            'position' => $validatedData['position'],
            'signature' => $signature_path
        ]);
        $organizationIds = explode(',', $validatedData['organization']);
        $organizationIds = array_filter(array_map('trim', $organizationIds), fn($id) => $id !== '');
        $newUser->organizations()->attach($organizationIds);
        return to_route('users.index')->with('status', 'کاربر جدید ساخته شد');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('admin-role');
        $userInfo = UserInfo::findOrFail($id);

        // Load user with related roles and direct permissions
        $user = User::with([
            'organizations:id,organization_name',
            'permissions',
            'roles.permissions',
            'roles:id,name'
        ])
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
        Gate::authorize('admin-role');
        $userInfo = UserInfo::with(['user:id,p_code', 'department:id,department_name'])
            ->findOrFail($id);

        // Fetch departments and permissions
        $departments = Department::select(['id', 'department_name'])->get();
        $permissions = Permission::select(['id', 'name'])->get();

        // Fetch the user with its related roles and permissions
        $user = User::with('roles:id,name', 'permissions:id,name')
            ->findOrFail($userInfo->user_id);

        $roles = Role::select(['id', 'name'])
            ->when(!$user->hasRole('super_admin'), fn($q) => $q->where('name', '!=', 'super_admin'))
            ->get();
        $organizations = Organization::select(['id','organization_name'])->get();
        $relatedOrganizations = $user->organizations()->select('organizations.id', 'organization_name')->get();
        return view('users.edit', [
            'userInfo' => $userInfo,
            'organization' => $organizations,
            'relatedOrganizations' => $relatedOrganizations,
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
        Gate::authorize('admin-role');

        $validated = $request->validated();

        $userInfo = UserInfo::findOrFail($id);

        $user = User::findOrFail($userInfo->user_id);

        // Update user basic info
        $user->update([
            'p_code' => $validated['p_code'],
        ]);
        $role = Role::find($validated['role']);
        // Sync role (only one role in your dropdown)
        if ($request->filled('role')) {
            $user->syncRoles([$role->id]);
        }

        // Sync permissions
        $user->syncPermissions(is_array($request->permissions) ? $request->permissions : []);

        // Update user info
        $userInfo->update([
            'user_id' => $user->id,
            'department_id' => $validated['department'],
            'full_name' => $validated['full_name'],
            'work_phone' => $validated['work_phone'],
            'house_phone' => $validated['house_phone'],
            'phone' => $validated['phone'],
            'n_code' => $validated['n_code'],
            'position' => $validated['position'],
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

        $operatorPhone = OperatorPhones::where('full_name', $validated['full_name'])
            ->where('position', $validated['position'])
            ->first();
        if ($operatorPhone) {
            $operatorPhone->update([
                'department_id' => $validated['department'],
            ]);
        }

        return redirect()->route('users.index')->with('status', 'کاربر با موفقیت بروزرسانی شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('has-permission-and-role',UserRole::SUPER_ADMIN->value);
        $user = User::with('user_info')->findOrFail($id);
        if ($user->hasRole(UserRole::SUPER_ADMIN->value)) {
            abort(403, 'Cannot delete super admin.');
        }
        // Delete related info first
        if ($user->user_info) {
            $user->user_info->delete();
        }
        // Then delete the user
        $user->delete();
    }

    public function resetPasswordPage(string $id)
    {
        Gate::authorize('admin-role');
        $user = User::with('user_info:id,user_id,full_name')->findOrFail($id);
        return view('users.reset-password',['user'=>$user]);
    }

    public function resetPassword(Request $request,string $id)
    {
        Gate::authorize('admin-role');
        $validated = $request->validate([
            'password' => ['required', 'confirmed',
                \Illuminate\Validation\Rules\Password::min(6)->max(8)->letters()->numbers()],
        ]);
        $user = User::findOrFail($id);
        $user->password = $validated['password']; // auto-hashed via cast
        $user->save();

        return redirect()->back()->with('status', 'رمز ورود با موفقیت آپدیت شد');
    }

    public function deleteOrganization($userId, $organizationId)
    {
        Gate::authorize('admin-role');
        DB::table('organization_user')
            ->where('user_id', $userId)
            ->where('organization_id', $organizationId)
            ->delete();

        return redirect()->back()->with('status', 'سازمان با موفقیت از کاربر حذف شد.');
    }


}
