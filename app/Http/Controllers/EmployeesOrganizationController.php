<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeesOrganizationController extends Controller
{
    public function index(Request $request)
    {
        $search = trim(strip_tags($request->input('search', ''))); // Sanitize input

        // Get current user's related organization IDs
        $organizationIds = DB::table('organization_user')
            ->where('user_id', auth()->id())
            ->pluck('organization_id');

        // Get matching organizations
        $organizations = Organization::whereIn('id', $organizationIds)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('organization_name', 'like', "%{$search}%")
                        ->orWhere('url', 'like', "%{$search}%");
                });
            })
            ->get(['id', 'organization_name', 'url', 'image']);

        return view('employee-organization.index', compact('organizations', 'search'));
    }
}
