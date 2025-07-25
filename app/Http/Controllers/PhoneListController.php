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
    public function index(Request $request)
    {
        $user = auth()->user();
        $showAllColumns = $user->hasRole(UserRole::SUPER_ADMIN->value) || $user->hasRole(UserRole::ADMIN->value);
        $search = $request->input('search');
        $source = $request->input('source', 'all');

        $userInfos = collect();
        $phones = collect();

        // 1. Fetch UserInfo records if needed
        if ($source === 'all' || $source === 'user_info') {
            $userInfoQuery = UserInfo::with('department:id,department_name')
                ->whereDoesntHave('user.roles', fn ($q) =>
                $q->where('name', UserRole::SUPER_ADMIN->value))
                ->select('id', 'user_id', 'department_id', 'full_name', 'work_phone', 'house_phone', 'phone');

            if ($search) {
                $userInfoQuery->where(function ($q) use ($search, $user) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('work_phone', 'like', "%{$search}%")
                        ->orWhereHas('department', fn ($q2) =>
                        $q2->where('department_name', 'like', "%{$search}%"));

                    if (
                        $user->hasRole(UserRole::SUPER_ADMIN->value) ||
                        $user->hasRole(UserRole::ADMIN->value) ||
                        $user->hasPermissionTo(UserPermission::PHONE_PERMISSIONS->value)
                    ) {
                        $q->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('house_phone', 'like', "%{$search}%");
                    }
                });
            }

            $userInfos = $userInfoQuery->get();
            $originalUsersCount = $userInfoQuery->count();
        } else {
            $originalUsersCount = 0;
        }

        // 2. Fetch Phone records if needed
        if ($source === 'all' || $source === 'phone') {
            $phonesQuery = Phone::query();
            if ($search) {
                $phonesQuery->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('work_phone', 'like', "%{$search}%")
                        ->orWhere('house_phone', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            }
            $phones = $phonesQuery->get();
        }

        // 3. Combine into unified structure
        $combined = collect();
        foreach ($userInfos as $info) {
            $combined->push([
                'source' => 'user_info',
                'id' => $info->id,
                'full_name' => $info->full_name,
                'work_phone' => $info->work_phone,
                'house_phone' => $info->house_phone,
                'phone' => $info->phone,
                'department_name' => optional($info->department)->department_name ?? 'بدون واحد',
            ]);
        }
        foreach ($phones as $phone) {
            $combined->push([
                'source' => 'phone',
                'id' => $phone->id,
                'full_name' => $phone->full_name,
                'work_phone' => $phone->work_phone,
                'house_phone' => $phone->house_phone,
                'phone' => $phone->phone,
                'department_name' => null,
            ]);
        }

        // Sort combined list by full_name
        $combined = $combined->sortBy('full_name')->values();

        // 4. Manual pagination of the combined collection
        $page = $request->input('page', 1);
        $perPage = 10;
        $pagedCombined = new LengthAwarePaginator(
            $combined->forPage($page, $perPage),
            $combined->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // 5. Pass data to the view
        return view('phoneList.index', [
            'combinedData' => $pagedCombined,
            'showAllColumns' => $showAllColumns,
            'originalUsersCount' => $originalUsersCount,
            'filteredUsersCount' => $combined->count(),
            'selectedSource' => $source,
        ]);
    }

    public function create()
    {
        Gate::authorize('has-permission-and-role', [
            UserPermission::PHONE_PERMISSIONS->value,
            UserRole::ADMIN->value,
        ]);
        return view('phoneList.create');
    }
    public function store(Request $request)
    {
        Gate::authorize('has-permission-and-role', [
            UserPermission::PHONE_PERMISSIONS->value,
            UserRole::ADMIN->value,
        ]);
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
    public function edit(string $source, string $id)
    {
        Gate::authorize('has-permission-and-role', [
            UserPermission::PHONE_PERMISSIONS->value,
            UserRole::ADMIN->value,
        ]);
        if ($source === 'user_info') {
            $record = UserInfo::with(['user', 'department'])
                ->select('id', 'user_id', 'department_id', 'full_name', 'work_phone', 'house_phone', 'phone')
                ->findOrFail($id);
        } elseif ($source === 'phone') {
            $record = Phone::select('id', 'full_name', 'work_phone', 'house_phone', 'phone')
                ->findOrFail($id);
        } else {
            abort(404, 'Invalid source type.');
        }

        return view('phoneList.edit', [
            'record' => $record,
            'source' => $source,
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(StorePhoneRequest $request, string $source, string $id)
    {
        Gate::authorize('has-permission-and-role', [
            UserPermission::PHONE_PERMISSIONS->value,
            UserRole::ADMIN->value,
        ]);

        $validatedData = $request->validated();

        if ($source === 'user_info') {
            $record = UserInfo::findOrFail($id);
        } elseif ($source === 'phone') {
            $record = Phone::findOrFail($id);
        } else {
            abort(404, 'Invalid source');
        }

        $record->update([
            'house_phone' => $validatedData['house_phone'],
            'work_phone' => $validatedData['work_phone'],
            'phone' => $validatedData['phone'],
        ]);

        return to_route('phone-list.index')->with('status', 'اطلاعات با موفقیت آپدیت شد');
    }
    public function destroy(string $source, string $id)
    {
        Gate::authorize('has-permission-and-role', [
            UserPermission::PHONE_PERMISSIONS->value,
            UserRole::ADMIN->value,
        ]);

        if ($source === 'user_info') {
            $record = UserInfo::findOrFail($id);
        } elseif ($source === 'phone') {
            $record = Phone::findOrFail($id);
        } else {
            abort(404, 'Invalid source');
        }

        $record->delete();

        return redirect()->route('phone-list.index')->with('status', 'رکورد با موفقیت حذف شد.');
    }


}
