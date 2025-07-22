<?php

namespace App\Http\Controllers;

use App\Enums\UserPermission;
use App\Enums\UserRole;
use App\Http\Requests\StorePhoneRequest;
use App\Models\Phone;
use App\Models\Role;
use App\Models\UserInfo;
use App\Rules\farsi_chs;
use App\Rules\PhoneNumberRule;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
        ])
            ->whereDoesntHave('user.roles', fn ($q) => $q->where('name', UserRole::SUPER_ADMIN->value))
            ->select('id', 'user_id', 'department_id', 'full_name', 'work_phone', 'house_phone', 'phone');
        $originalUsersCount = $query->count();
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $user = auth()->user();
                // Everyone can search these fields
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('work_phone', 'like', "%{$search}%")
                    ->orWhereHas('department', fn ($q2) =>
                    $q2->where('department_name', 'like', "%{$search}%"))
                    ->orWhereHas('user.roles', fn ($q2) =>
                    $q2->where('name', 'like', "%{$search}%"));
                // Conditionally allow phone/house_phone search only for admins
                if ($user->hasRole(UserRole::SUPER_ADMIN->value) || $user->hasRole(UserRole::ADMIN->value) ||
                    $user->hasPermissionTo(UserPermission::PHONE_PERMISSIONS->value)) {
                    $q->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('house_phone', 'like', "%{$search}%");
                }
            });
        }
        $userInfos = $query->paginate(10);
        $filteredUsersCount = $userInfos->total();
        return view('phoneList.index', [
            'userInfos' => $userInfos,
            'showAllColumns' => $showAllColumns,
            'originalUsersCount' => $originalUsersCount,
            'filteredUsersCount' => $filteredUsersCount,
        ]);
    }

    public function create()
    {
        return view('phoneList.create');
    }

    public function store(Request $request)
    {
        $cleaned = $request->all();

        $cleaned['phone'] = $this->cleanPhone($request->input('phone'));
        $cleaned['house_phone'] = $this->cleanPhone($request->input('house_phone'));
        $cleaned['work_phone'] = $this->cleanPhone($request->input('work_phone'));

        $validated = validator($cleaned, [
            'full_name' => ['bail', 'required', 'string', 'min:5', 'max:255', new farsi_chs()],
            'phone' => ['bail','required', 'numeric', 'digits:11', new PhoneNumberRule()],
            'house_phone' => ['bail', 'required', 'numeric', 'digits_between:5,10'],
            'work_phone' => ['bail', 'required', 'numeric', 'digits_between:5,10'],
        ])->validate();

        Phone::create([
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'house_phone' => $validated['house_phone'],
            'work_phone' => $validated['work_phone'],
        ]);
        return to_route('phone-list.index')->with('status', 'اطلاعات با موفقیت ثبت شد');

    }
    private function cleanPhone($value): ?string
    {
        return $value ? preg_replace('/(?!^\+)[^\d]/', '', $value) : null;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('has-permission-and-role',[UserPermission::PHONE_PERMISSIONS->value,UserRole::ADMIN->value]);
        $userInfo = UserInfo::with(['user', 'department'])
            ->select('id','user_id','department_id','full_name','work_phone','house_phone','phone')
            ->findOrFail($id);
        return view('phoneList.edit',[
            'userInfo' => $userInfo
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(StorePhoneRequest $request, string $id)
    {
        Gate::authorize('has-permission-and-role',[UserPermission::PHONE_PERMISSIONS->value,UserRole::ADMIN->value]);
        $userInfo = UserInfo::findOrFail($id);
        $validatedData = $request->validated();
        $userInfo->update([
            'house_phone' => $validatedData['house_phone'],
            'work_phone' => $validatedData['work_phone'],
            'phone' => $validatedData['phone'],
        ]);
        return to_route('phone-list.index')->with('status', 'اطلاعات با موفقیت آپدیت شد');
    }
}
