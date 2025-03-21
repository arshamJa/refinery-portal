<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Organization;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class OrgDepManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with([
            'user_info:id,department_id,user_id,full_name',
            'organizations:id,organization_name', // Eager load organization names
            'user_info.department:id,department_name' // Eager load department name for user_info
        ])->whereNull('deleted_at')->select('id');

        $originalUsersCount = $query->count(); // Count before any filtering

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user_info.department', function ($department) use ($search) {
                $department->where('department_name', 'like', '%'.$search.'%');
            })
                ->orWhereHas('organizations', function ($org) use ($search) {
                    $org->where('organization_name', 'like', '%'.$search.'%');
                })
                ->orWhereHas('user_info', function ($userInfoQuery) use ($search) {
                    $userInfoQuery->where('full_name', 'like', '%'.$search.'%');
                });
        }
        $users = $query->paginate(5)->appends(['search' => $request->input('search')]);
        $filteredUsersCount = $users->total();

        return view('admin.dep-org-management', [
            'users' => $users,
            'originalUsersCount' => $originalUsersCount,
            'filteredUsersCount' => $filteredUsersCount,
        ]);
    }

    public function departmentOrganizationConnection()
    {
        Gate::authorize('create-department-organization');
        $departments = DB::table('departments')->select('id', 'department_name')->get();
        $organizations = DB::table('organizations')->select('id', 'organization_name')->get();
        $userInfos = DB::table('user_infos')->select('id', 'user_id', 'full_name')
            ->get();

        return view('admin.user-dep-org', [
            'departments' => $departments,
            'organizations' => $organizations,
            'userInfos' => $userInfos
        ]);
    }
    public function store(Request $request)
    {
        Gate::authorize('create-department-organization');

        $validated = $request->validate([
            'departmentId' => 'required',
            'organization_ids' => 'required',
        ]);

        $departmentId = $validated['departmentId'];
        $organizationIds = $validated['organization_ids'];

        if(!is_array($organizationIds)){
            $organizationIds = explode(',', $organizationIds);
            $organizationIds = array_map('trim', $organizationIds);
        }
        // Retrieve user IDs in a single query
        $userIds = UserInfo::where('department_id', $departmentId)->get('user_id');
        // Retrieve users in a single query
        $users = User::whereIn('id', $userIds)->get();
        foreach ($organizationIds as $organization_id) {
            // Update organization's department_id
            Organization::where('id', $organization_id)->update(['department_id' => $departmentId]);

            foreach ($users as $user){
                if (DB::table('organization_user')
                    ->where('organization_id',$organization_id)
                    ->where('user_id',$user->id)->exists())
                {
                    DB::table('organization_user')
                        ->where('organization_id',$organization_id)
                        ->where('user_id',$user->id)
                        ->update(['updated_at' => now() ]);
                }else{
                   DB::table('organization_user')->insert([
                       'organization_id' => $organization_id,
                       'user_id' => $user->id
                   ]);
                }
            }
        }
        return to_route('department.organization.connection')->with('status', 'ارتباط دپارتمان و سامانه انجام شد');
    }

}
