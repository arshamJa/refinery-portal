<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewUserRequest;
use App\Models\Department;
use App\Models\Organization;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserInfo;
use App\Traits\UserSearchTrait;
use App\UserRole;
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
            'user.roles:id,name'
        ])
            ->where('full_name', '!=', 'Arsham Jamali')
            ->select(['id', 'user_id', 'department_id', 'full_name', 'n_code', 'position']);

        // Get role list with filtering
        $roles = Role::select(['id', 'name'])
            ->when(!$user->hasRole('super_admin'), fn($q) => $q->where('name', '!=', 'super_admin'))
            ->get();


        $query = $this->applyUserSearch($request, $query); // Use the trait

        // Apply filtering and pagination
        $users = $this->applyUserSearch($request, $query)->paginate(5)->appends($request->except('page'));

        return view('users.index',[
           'userInfos' => $users , 'roles' => $roles ,
            'originalUsersCount' => $query->count(),
            'filteredUsersCount' => $users->total(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('createNewUser', UserInfo::class);
        $roles = Role::all();
        $permissions = Permission::all();
        return view('users.create' , [
            'departments' => Department::all(),
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
        return to_route('users.index')->with('status','کاربر جدید ساخته شد');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userInfo = UserInfo::findOrFail($id);
        Gate::authorize('viewUsers', $userInfo);

        $user = User::with('organizations:id,organization_name', 'permissions', 'roles:id,name')
            ->findOrFail($userInfo->user_id);

        return view('users.show', [
            'userInfo' => $userInfo,
            'user' => $user,
            'userRoles' => $user->roles,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $userInfo = UserInfo::with(['user:id,p_code', 'department:id,department_name'])
            ->findOrFail($id);

        Gate::authorize('updateUsers',$userInfo);

        // Fetch departments and permissions
        $departments = Department::select(['id', 'department_name'])->get();
        $permissions = Permission::select(['id', 'name'])->get();

        // Fetch the user with its related roles and permissions
        $user = User::with('roles:id,name', 'permissions:id,name')
            ->findOrFail($userInfo->user_id);

        return view('users.edit', [
            'userInfo' => $userInfo,
            'departments' => $departments,
            'user' => $user,
            'userRoles' => $user->roles,
            'permissions' => $permissions,
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(StoreNewUserRequest $request, string $id)
    {
        $request->validated();
        $userInfo = UserInfo::findOrFail($id);
        Gate::authorize('updateUsers', $userInfo);
        $user = User::find($userInfo->user_id);

        $user->update([
            'role' => $request->role, // Make sure roles are synced, not just set
            'password' => Hash::make($request->password),
            'p_code' => $request->p_code
        ]);

        // Sync roles and permissions properly (if needed)
        $user->syncRoles([$request->role]);
        $user->syncPermissions($request->permissions);

        $userInfo->update([
            'user_id' => $user->id,
            'department_id' => $request->departmentId,
            'full_name' => $request->full_name,
            'work_phone' => $request->work_phone,
            'house_phone' => $request->house_phone,
            'phone' => $request->phone,
            'n_code' => $request->n_code,
            'position' => $request->position
        ]);

        $department = Department::find($request->departmentId);
        $organizations = Organization::where('department_id', $department->id)->get();

        // Sync user with the organizations
        foreach ($organizations as $organization) {
            // Use `syncWithoutDetaching` to ensure the pivot is updated correctly
            $organization->users()->syncWithoutDetaching([$user->id]);
        }

//        $request->validated();
//        $userInfo = UserInfo::findOrFail($id);
//        Gate::authorize('updateUsers',$userInfo);
//        $user = User::find($userInfo->user_id);
//        $user->role = $request->role;
//        $user->password = Hash::make($request->password);
//        $user->p_code = $request->p_code;
//        $user->save();
//        $userInfo->user_id = $user->id;
//        $userInfo->department_id = $request->departmentId;
//        $userInfo->full_name = $request->full_name;
//        $userInfo->work_phone = $request->work_phone;
//        $userInfo->house_phone = $request->house_phone;
//        $userInfo->phone = $request->phone;
//        $userInfo->n_code = $request->n_code;
//        $userInfo->position = $request->position;
//        $userInfo->save();
//        $departments = Department::find($request->departmentId);
//        $organizations = Organization::where('department_id',$departments->id)->get();
//        foreach ($organizations as $organization){
//            if (DB::table('organization_user')
//                ->where('organization_id',$organization->id)
//                ->where('user_id',$user->id)
//                ->exists())
//            {
//                $organization->users()->updateExistingPivot($user->id, [
//                    'updated_at' => now(),
//                ]);
//            }else{
//                $organization->users()->attach($user->id);
//            }
//        }
        return to_route('users.index')->with('status','کاربر با موفقیت بروز شد');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
