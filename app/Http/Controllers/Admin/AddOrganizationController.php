<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class AddOrganizationController extends Controller
{
    public function index(string $id)
    {
        $userId = User::where('id', $id)->value('id');
        $users = User::with('organizations')->where('id', $id)->get();
        $organizations = Organization::with('users')->get(['id', 'organization_name']);
        return view('addOrganization', [
            'id' => $userId,
            'users' => $users,
            'organizations' => $organizations
        ]);
    }

    public function store(Request $request, string $id)
    {
        $validated = $request->validate([
            'organization_ids' => 'required'
        ]);
        $user = User::find($id);
        foreach ($request->organization_ids as $organization_id) {
            if (DB::table('organization_user')
                ->where('user_id', $user->id)
                ->where('organization_id', $organization_id)
                ->exists()) {
                DB::table('organization_user')
                    ->where('organization_id', $organization_id)
                    ->where('user_id', $user->id)
                    ->update(['updated_at' => now()]);
            } else {
                $user->organizations()->attach($organization_id);
            }
        }
        return to_route('addOrganization', $id)->with('status', 'سامانه به کاربر داده شد');
    }

    public function destroy(string $user_id, $organizations)
    {
        DB::table('organization_user')
            ->where('user_id', $user_id)
            ->where('organization_id', $organizations)
            ->delete();
        return to_route('addOrganization', $user_id)->with('status', 'سامانه با موفقیت حذف شد');
    }

    // this is for deleting organization form organization_table
    public function deleteOrganization(string $id)
    {
        $organization = Organization::findOrFail($id);
        $old_image_path = public_path('storage/'.$organization->image);
        if (File::exists($old_image_path)) {
            File::delete($old_image_path);
        }
        // Delete the organization record
        $organization->delete();
        return redirect()->route('organizations')
            ->with('success', 'سامانه با موفقیت حذف شد.');
    }

    public function deleteDepartment(string $id)
    {
        $department = Department::findOrFail($id);
        $department->delete();
        return redirect()->route('departments.index')
            ->with('success', 'دپارتمان با موفقیت حذف شد.');
    }

}
