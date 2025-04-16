<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StorePhoneRequest;
use App\Models\Role;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PhoneListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $showAllColumns = $user->hasRole(UserRole::SUPER_ADMIN->value) || $user->hasRole(UserRole::ADMIN->value);

        $query = UserInfo::with([
            'user.roles:id,name',
            'department:id,department_name',
        ])->select('id', 'user_id', 'department_id', 'full_name', 'work_phone', 'house_phone', 'phone')
            ->whereHas('user.roles', fn ($q) => $q->where('name', '!=', UserRole::SUPER_ADMIN->value));

        $originalUsersCount = $query->count();

        $query->when($request->filled('department'), fn ($q) =>
                $q->whereHas('department', fn ($q2) =>
                $q2->where('department_name', 'like', '%' . $request->department . '%')))
            ->when($request->filled('full_name'), fn ($q) =>
                $q->where('full_name', 'like', '%' . $request->full_name . '%'))
            ->when($request->filled('phone'), fn ($q) =>
                $q->where('phone', 'like', '%' . $request->phone . '%'))
            ->when($request->filled('house_phone'), fn ($q) =>
                $q->where('house_phone', 'like', '%' . $request->house_phone . '%'))
            ->when($request->filled('work_phone'), fn ($q) =>
                $q->where('work_phone', 'like', '%' . $request->work_phone . '%'))
            ->when($request->filled('role'), fn ($q) =>
                $q->whereHas('user.roles', fn ($q2) =>
                $q2->where('roles.id', $request->role)));

        $userInfos = $query->paginate(5);
        $filteredUsersCount = $userInfos->total();

        // Fetch roles excluding SUPER_ADMIN
        $roles = Role::where('name', '!=', UserRole::SUPER_ADMIN->value)
            ->select(['id', 'name'])
            ->get();

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
    public function store(StorePhoneRequest $request)
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
        $userInfo = UserInfo::with(['user', 'department'])
            ->select('id','user_id','department_id','full_name','work_phone','house_phone','phone')
            ->findOrFail($id);
//        Gate::authorize('update', $userInfo);
        return view('phoneList.edit',[
            'userInfo' => $userInfo
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
//        dd($request->all());
        $userInfo = UserInfo::findOrFail($id);
//        Gate::authorize('update', $userInfo);
        $validatedData = $request->validated();
        $userInfo->update([
            'house_phone' => $validatedData['house_phone'],
            'work_phone' => $validatedData['work_phone'],
            'phone' => $validatedData['phone'],
        ]);
        return to_route('phone-list.index')->with('status', 'اطلاعات با موفقیت آپدیت شد');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
