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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableController extends Controller
{
    use UserSearchTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = UserInfo::with('user:id,p_code', 'department:id,department_name', 'user.roles')
            ->where('full_name','!=','Arsham Jamali')
            ->select(['id','user_id','department_id','full_name','n_code','position']);
        if (auth()->user()->hasRole('super-admin')){
            $roles = \App\Models\Role::get(['id','name']); // Fetch all roles for the dropdown
        }else{
            $roles = Role::where('name','!=','super-admin')->get(['id','name']);
        }

        $originalUsersCount = $query->count(); // Count before any filtering

        $query = $this->applyUserSearch($request, $query); // Use the trait

        $users = $query->paginate(5)->appends($request->except('page')); // Preserve all search parameters

        $filteredUsersCount = $users->total();

        return view('users.index',[
           'userInfos' => $users , 'roles' => $roles ,
            'originalUsersCount' => $originalUsersCount, 'filteredUsersCount' => $filteredUsersCount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
        $request->validated();
        $newUser = User::create([
            'password' => Hash::make($request->password),
            'p_code' => $request->p_code,
        ]);

        $newUser->syncRoles([$request->role]);

        $newUser->syncPermissions($request->permissions);

        $departments = Department::find($request->departmentId);
        UserInfo::create([
            'user_id' => $newUser->id,
            'department_id' => $request->departmentId,
            'full_name' => $request->full_name,
            'work_phone' => $request->work_phone,
            'house_phone' => $request->house_phone,
            'phone' => $request->phone,
            'n_code' => $request->n_code,
            'position' => $request->position,
        ]);

        $organizations = Organization::where('department_id',$departments->id)->get();
        foreach ($organizations as $organization){
            $organization->users()->attach($newUser->id);
        }

        return to_route('users.index')->with('status','کاربر جدید ساخته شد');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userInfo = UserInfo::find($id);
        $user = User::with('organizations:id,organization_name', 'permissions', 'roles:id,name')
            ->where('id', $userInfo->user_id)
            ->first(); // Use first() instead of get()

        if (!$user) {
            // Handle the case where the user is not found
            abort(404, 'User not found');
        }
        return view('users.show', [
            'userInfo' => $userInfo,
            'user' => $user, // Use user instead of users.
            'userRoles' => $user->roles, // Access roles from the eager-loaded relationship
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $userInfo = UserInfo::with(['user:id,p_code', 'department:id,department_name'])
            ->find($id);
        if (!$userInfo) {
            abort(404, 'Users Information not found');
        }
        $departments = Department::select(['id', 'department_name'])->get(); // Select only needed columns
        $user = User::with('permissions', 'roles:id,name')
            ->where('id', $userInfo->user_id)
            ->first();
        if (!$user) {
            abort(404, 'User not found');
        }
        $permissions = Permission::select(['id', 'name'])->get(); // Select only needed columns
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
//        Gate::authorize('update-user',$id);
        $request->validated();

        $userInfo = UserInfo::find($id);

        $user = User::find($userInfo->user_id);
        $user->role = $request->role;
        $user->password = Hash::make($request->password);
        $user->p_code = $request->p_code;
        $user->save();


        $userInfo->user_id = $user->id;
        $userInfo->department_id = $request->departmentId;
        $userInfo->full_name = $request->full_name;
        $userInfo->work_phone = $request->work_phone;
        $userInfo->house_phone = $request->house_phone;
        $userInfo->phone = $request->phone;
        $userInfo->n_code = $request->n_code;
        $userInfo->position = $request->position;
        $userInfo->save();

        $departments = Department::find($request->departmentId);
        $organizations = Organization::where('department_id',$departments->id)->get();
        foreach ($organizations as $organization){
            if (DB::table('organization_user')
                ->where('organization_id',$organization->id)
                ->where('user_id',$user->id)
                ->exists())
            {
                $organization->users()->updateExistingPivot($user->id, [
                    'updated_at' => now(),
                ]);
            }else{
                $organization->users()->attach($user->id);
            }
        }
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
