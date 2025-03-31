<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserInfo;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PhoneListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = UserInfo::with('user.roles', 'department:id,department_name')
            ->select('id', 'user_id', 'department_id', 'full_name', 'work_phone', 'house_phone', 'phone')
            ->whereHas('user.roles', function ($query) {
                $query->where('name', '!=', 'super-admin');
            });

        $originalUsersCount = $query->count(); // Count before any filtering

        // Apply filters based on request parameters
        if ($request->filled('department')) {
            $query->whereHas('department', function ($q) use ($request) {
                $q->where('department_name', 'like', '%' . $request->department . '%');
            });
        }
        if ($request->filled('full_name')) {
            $query->where('full_name', 'like', '%' . $request->full_name . '%');
        }
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }
        if ($request->filled('house_phone')) {
            $query->where('house_phone', 'like', '%' . $request->house_phone . '%');
        }
        if ($request->filled('work_phone')) {
            $query->where('work_phone', 'like', '%' . $request->work_phone . '%');
        }
        if ($request->filled('role')) {
            $query->whereHas('user.roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role);
            });
        }

        $userInfos = $query->paginate(5);

        $filteredUsersCount = $userInfos->total();

        $user = auth()->user();
        $showAllColumns = false;

        if ($user->hasRole(UserRole::SUPER_ADMIN->value) || $user->hasRole(UserRole::ADMIN->value)) {
            $showAllColumns = true;
        }

        $roles = Role::all();

        return view('phoneList.index', [
            'userInfos' => $userInfos,
            'showAllColumns' => $showAllColumns,
            'roles' => $roles,
            'originalUsersCount' => $originalUsersCount,
            'filteredUsersCount' => $filteredUsersCount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Gate::authorize('update-phone-list');
        $request->validate([
            'work_phone' => 'required|numeric',
            'house_phone' => 'required|numeric',
            'phone' => 'required|numeric|digits:11',
        ]);
        UserInfo::where('id', $id)->update([
            'house_phone' => $request->input('house_phone'),
            'work_phone' => $request->input('work_phone'),
            'phone' => $request->input('phone'),
        ]);
        return redirect()->back()->with('status', 'اطلاعات با موفقیت آپدیت شد');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
