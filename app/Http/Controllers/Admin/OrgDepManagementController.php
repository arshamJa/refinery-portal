<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
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
            'organizations:id,organization_name',
            'user_info.department:id,department_name'
        ])
            ->whereDoesntHave('roles', fn($q) => $q->where('name', UserRole::SUPER_ADMIN->value)
            )
            ->select('id');

        $originalUsersCount = (clone $query)->count(); // Clone to get unfiltered count

        if (
            $request->filled('full_name') ||
            $request->filled('department_name') ||
            $request->filled('organization')
        ) {
            $query->where(function ($q) use ($request) {
                if ($request->filled('full_name')) {
                    $q->whereHas('user_info', function ($userInfoQuery) use ($request) {
                        $userInfoQuery->where('full_name', 'like', '%'.$request->input('full_name').'%');
                    });
                }

                if ($request->filled('department_name')) {
                    $q->whereHas('user_info.department', function ($departmentQuery) use ($request) {
                        $departmentQuery->where('department_name', 'like', '%'.$request->input('department_name').'%');
                    });
                }

                if ($request->filled('organization')) {
                    $q->whereHas('organizations', function ($orgQuery) use ($request) {
                        $orgQuery->where('organization_name', 'like', '%'.$request->input('organization').'%');
                    });
                }
            });
        }

        $users = $query->paginate(10)->appends(['search' => $request->input('search')]);
        $filteredUsersCount = $users->total();

        return view('admin.dep-org-management', [
            'users' => $users,
            'originalUsersCount' => $originalUsersCount,
            'filteredUsersCount' => $filteredUsersCount,
        ]);
    }

    public function departmentOrganizationConnection()
    {
        Gate::authorize('admin-role');
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
        Gate::authorize('admin-role');
        $validated = $request->validate([
            'departmentId' => 'required',
            'organization_ids' => 'required',
        ]);
        $departmentId = $validated['departmentId'];
        $organizationIds = $validated['organization_ids'];

        if (!is_array($organizationIds)) {
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

            foreach ($users as $user) {
                if (DB::table('organization_user')
                    ->where('organization_id', $organization_id)
                    ->where('user_id', $user->id)->exists()) {
                    DB::table('organization_user')
                        ->where('organization_id', $organization_id)
                        ->where('user_id', $user->id)
                        ->update(['updated_at' => now()]);
                } else {
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
