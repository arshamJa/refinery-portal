<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
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
        $query = Organization::with(['department:id,department_name']);
        $originalOrganizationsCount = (clone $query)->count();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('organization_name', 'like', "%{$searchTerm}%")
                ->orWhereHas('department', function ($q) use ($searchTerm) {
                    $q->where('department_name', 'like', "%{$searchTerm}%");
                });
        }
        $departments = Department::all();
        $organizations = $query->paginate(10)->appends(['search' => $request->input('search')]);
        $filteredOrganizationsCount = $organizations->total();
        $orgs = Organization::select('id', 'organization_name')->get();
        return view('admin.dep-org-management', [
            'organizations' => $organizations,
            'orgs' => $orgs,
            'originalOrganizationsCount' => $originalOrganizationsCount,
            'filteredOrganizationsCount' => $filteredOrganizationsCount,
            'departments' => $departments
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
        dd($request->all());
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
