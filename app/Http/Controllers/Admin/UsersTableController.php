<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserPermission;
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
use App\Rules\PasswordRule;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;


class UsersTableController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin-role');
        $user = auth()->user();
        $query = UserInfo::with([
            'user:id,p_code',
            'department:id,department_name',
            'user.roles:id,name',
//            'user.permissions:id,name',
//            'user.roles.permissions:id,name',
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


        $roles = Role::select(['id', 'name'])
            ->when(!$user->hasRole('super_admin'), fn($q) => $q->where('name', '!=', 'super_admin'))
            ->get();

//        $permissions = Permission::select(['id', 'name'])->get();
        $users = $query->paginate(10)->appends($request->except('page'));
//        foreach ($users as $userInfo) {
//            $userModel = $userInfo->user;
//            if ($userModel) {
//                $rolePermissions = $userModel->roles->flatMap->permissions;
//                $directPermissions = $userModel->permissions;
//                $allPermissions = $rolePermissions->merge($directPermissions)
//                    ->unique('id')->pluck('name')->values();
//                $userInfo->all_permissions = $allPermissions;
//                $userInfo->display_permission = $allPermissions->first();
//                $userInfo->more_permissions_count = max($allPermissions->count() - 1, 0);
//            } else {
//                $userInfo->all_permissions = collect();
//                $userInfo->display_permission = null;
//                $userInfo->more_permissions_count = 0;
//            }
//        }
        return view('users.index', [
            'userInfos' => $users,
            'roles' => $roles,
//            'permissions' => $permissions,
        ]);
    }

    public function export(Request $request)
    {
        Gate::authorize('admin-role');
        $searchTerm = $request->input('search');
        return Excel::download(new UsersExport($searchTerm), 'users.xlsx');
    }

    public function create()
    {
        Gate::authorize('admin-role');
        $roles = Role::where('name', '!=', UserRole::SUPER_ADMIN->value)
            ->select(['id', 'name'])->get();
        $departments = Department::select(['id','department_name'])->get();
        $organizations = Organization::select(['id','organization_name'])->get();
        // Fetch all permissions once
        $allPermissions = Permission::select(['id', 'name'])->get();
        $rolePermissionsMap = $this->mapRolePermissions($roles, $allPermissions);
        return view('users.create', [
            'departments' => $departments,
            'organization' => $organizations,
            'roles' => $roles,
            'permissions' => $allPermissions,
            'rolePermissionsMap' => $rolePermissionsMap,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(StoreNewUserRequest $request)
    {
        Gate::authorize('admin-role');

        $validatedData = $request->validated();

        // Create the user
        $newUser = User::create([
            'password' => $validatedData['password'],
            'p_code' => $validatedData['p_code'],
        ]);

        // Assign role & permissions
        $this->assignRoleAndPermissions(
            $newUser,
            $validatedData['role'] ?? null,
            $validatedData['permissions'] ?? [],
            false // new user → never self-editing
        );

        // Handle signature
        $signature_path = null;
        if (isset($validatedData['signature'])) {
            $signature_path = $validatedData['signature']->store('signatures', 'public');
        }

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

        // Attach organizations
        $organizationIds = explode(',', $validatedData['organization']);
        $organizationIds = array_filter(array_map('trim', $organizationIds), fn($id) => $id !== '');
        $newUser->organizations()->attach($organizationIds);

        return to_route('users.index')->with('status', 'کاربر جدید ساخته شد');
    }

    public function show(string $id)
    {
        Gate::authorize('admin-role');
        $userInfo = UserInfo::findOrFail($id);

        // Load user with related roles and direct permissions
        $user = User::with([
            'organizations:id,organization_name',
            'permissions:id,name',
            'roles.permissions',
            'roles:id,name'
        ])
            ->findOrFail($userInfo->user_id);

        // Get direct permissions
        $directPermissions = $user->permissions;

//        // Get role-based permissions
//        $rolePermissions = $user->roles->flatMap(function ($role) {
//            return $role->permissions;
//        });
//
//        // Merge and remove duplicates
//        $allPermissions = $directPermissions->merge($rolePermissions)->unique('id');

        return view('users.show', [
            'userInfo' => $userInfo,
            'user' => $user,
            'userRoles' => $user->roles,
            'allPermissions' => $directPermissions, // Pass merged permissions to the view
        ]);
    }

    public function edit(string $id)
    {
        Gate::authorize('admin-role');
        $userInfo = UserInfo::with(['user:id,p_code', 'department:id,department_name','user.roles'])
            ->findOrFail($id);

        // Security check: admin can edit only own record or non-admins
        Gate::authorize('edit-user', $userInfo->user);

        // Fetch departments and permissions
        $departments = Department::select(['id', 'department_name'])->get();

        // Fetch the user with its related roles and permissions
        $user = User::with('roles:id,name', 'permissions:id,name')
            ->findOrFail($userInfo->user_id);

        $roles = Role::where('name', '!=', UserRole::SUPER_ADMIN->value)
            ->select(['id', 'name'])->get();

        $organizations = Organization::select(['id','organization_name'])->get();
        $relatedOrganizations = $user->organizations()->select('organizations.id', 'organization_name')->get();


        // Fetch all permissions
        $allPermissions = Permission::select(['id', 'name'])->get();

        // Build role-permission mapping
        $rolePermissionsMap = $this->mapRolePermissions($roles, $allPermissions);

        return view('users.edit', [
            'userInfo' => $userInfo,
            'organization' => $organizations,
            'relatedOrganizations' => $relatedOrganizations,
            'departments' => $departments,
            'user' => $user,
            'roles' => $roles,
            'permissions' => $allPermissions,
            'rolePermissionsMap' => $rolePermissionsMap
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(UpdateNewUserRequest $request, string $id)
    {
        Gate::authorize('admin-role');

        $validated = $request->validated();

        // Load user & related info
        $userInfo = UserInfo::with(['user:id,p_code', 'department:id,department_name','user.roles'])
            ->findOrFail($id);
        $user = User::findOrFail($userInfo->user_id);

        // Admin-specific restriction: can edit only self or non-admins
        Gate::authorize('edit-user', $user);

        // Update user basic info
        $user->update([
            'p_code' => $validated['p_code'],
        ]);

        // Assign role & permissions
        $this->assignRoleAndPermissions(
            $user,
            $validated['role'] ?? null,
            $validated['permissions'] ?? [],
            auth()->id() === $user->id // self-editing case
        );


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

        // Update operator phone record if exists
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

    public function destroy(string $id)
    {
        Gate::authorize('has-permission-and-role',UserRole::SUPER_ADMIN->value);
        $user = User::with('user_info')->findOrFail($id);
        // Prevent deletion of super admin
        if ($user->hasRole(UserRole::SUPER_ADMIN->value)) {
            abort(403, 'Cannot delete super admin.');
        }

        // Admin-specific restriction: can delete only self or non-admins
        Gate::authorize('edit-user', $user);

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
        // Admin-specific restriction: can edit only self or non-admins
        Gate::authorize('edit-user', $user);
        return view('users.reset-password',['user'=>$user]);
    }

    public function resetPassword(Request $request, string $id)
    {
        Gate::authorize('admin-role');
        // Fetch the user
        $user = User::findOrFail($id);
        $roleName = $user->roles()->first()?->name;
        // Default password rules
        $rules = [
            'password' => ['required', 'confirmed',
                \Illuminate\Validation\Rules\Password::min(6)->max(10)->letters()->numbers()],
        ];
        if ($roleName === UserRole::ADMIN->value) {
            $rules['password'] = ['required', 'confirmed', 'string', 'min:6', 'max:30', new PasswordRule()];
        }
        // Validate request
        $validated = $request->validate($rules);
        // Update password (auto-hashed via cast)
        $user->password = $validated['password'];
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


    /**
     * Get allowed permissions for each role
     *
     * @param Collection $roles
     * @param Collection $allPermissions
     * @return array
     */
    private function mapRolePermissions($roles, $allPermissions): array
    {
        // Define permissions for each role
        $permissionsByRole = [
            UserRole::USER->value => [
                UserPermission::VIEW_BLOG->value,
                UserPermission::VIEW_ORGANIZATIONS->value,
                UserPermission::VIEW_MEETING_DASHBOARD->value,
                UserPermission::VIEW_PHONE_LISTS->value,
            ],
            UserRole::OPERATOR->value => [
                UserPermission::TASK_REPORT_TABLE->value,
                UserPermission::PHONE_PERMISSIONS->value,
                UserPermission::NEWS_PERMISSIONS->value,
                UserPermission::SCRIPTORIUM_PERMISSIONS->value,
                UserPermission::VIEW_ORGANIZATIONS->value,
                UserPermission::VIEW_MEETING_DASHBOARD->value,
            ],
            UserRole::ADMIN->value => [
                UserPermission::ORGANIZATION_TABLE->value,
                UserPermission::DEPARTMENT_TABLE->value,
                UserPermission::CREATE_NEW_USER->value,
                UserPermission::USERS_TABLE->value,
                UserPermission::ORGANIZATION_DEPARTMENT_MANAGE->value
            ],
            UserRole::SUPER_ADMIN->value => 'all',
        ];

        $rolePermissionsMap = [];

        foreach ($roles as $role) {
            if (isset($permissionsByRole[$role->name])) {
                if ($permissionsByRole[$role->name] === 'all') {
                    $allowed = $allPermissions;
                } else {
                    $allowed = $allPermissions->whereIn('name', $permissionsByRole[$role->name]);
                }
            } else {
                $allowed = collect();
            }

            $rolePermissionsMap[$role->id] = $allowed->pluck('name')->toArray();
        }

        return $rolePermissionsMap;
    }


    /**
     * Validate and apply role + permissions to a user.
     *
     * @param  int|null  $roleId
     * @param  array  $submittedPermissions
     * @param  bool  $isSelfEditing
     * @return void
     * @throws ValidationException
     */
    private function assignRoleAndPermissions(User $user, ?int $roleId, array $submittedPermissions, bool $isSelfEditing = false): void
    {
        // Fetch roles and permissions
        $roles = Role::where('name', '!=', UserRole::SUPER_ADMIN->value)
            ->select(['id', 'name'])->get();

        $allPermissions = Permission::select(['id', 'name'])->get();
        $rolePermissionsMap = $this->mapRolePermissions($roles, $allPermissions);

        // Normalize permissions input
        if (array_keys($submittedPermissions) !== range(0, count($submittedPermissions) - 1)) {
            $submittedPermissions = array_keys($submittedPermissions);
        }

        // Case 1: Self-editing Admin → role locked
        if ($isSelfEditing && $user->hasRole(UserRole::ADMIN->value)) {
            $currentRoleId = $user->roles()->first()?->id;
            $allowedPermissionsForRole = $rolePermissionsMap[$currentRoleId] ?? [];
            $filteredPermissions = array_values(array_intersect($submittedPermissions, $allowedPermissionsForRole));
            $permissionIds = Permission::whereIn('name', $filteredPermissions)->pluck('id')->toArray();
            $user->permissions()->sync($permissionIds);
            return;
        }

        // Case 2: Admin editing another user → role + permissions editable
        if ($roleId) {
            $role = Role::find($roleId);

            if (!$role) {
                throw ValidationException::withMessages(['role' => 'نقش انتخاب شده معتبر نیست.']);
            }

            // Prevent assigning SUPER_ADMIN unless current user is SUPER_ADMIN
            if ($role->name === UserRole::SUPER_ADMIN->value && !auth()->user()->hasRole(UserRole::SUPER_ADMIN->value)) {
                throw ValidationException::withMessages(['role' => 'شما اجازه انتخاب نقش مدیر کل (SUPER_ADMIN) را ندارید.']);
            }

            $user->syncRoles([$role->id]);
        }

        // Get effective role after sync
        $effectiveRoleId = $user->roles()->first()?->id;
        $allowedPermissionsForRole = $rolePermissionsMap[$effectiveRoleId] ?? [];

        $filteredPermissions = array_values(array_intersect($submittedPermissions, $allowedPermissionsForRole));
        $permissionIds = Permission::whereIn('name', $filteredPermissions)->pluck('id')->toArray();

        $user->permissions()->sync($permissionIds);
    }

}
