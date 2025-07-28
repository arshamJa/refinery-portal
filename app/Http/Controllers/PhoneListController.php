<?php

namespace App\Http\Controllers;

use App\Enums\UserPermission;
use App\Enums\UserRole;
use App\Http\Requests\StorePhoneRequest;
use App\Models\Department;
use App\Models\OperatorPhones;
use App\Models\Phone;
use App\Models\ResidentPhones;
use App\Models\Role;
use App\Models\UserInfo;
use App\Rules\farsi_chs;
use App\Rules\NationalCodeRule;
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

        $operatorPhones = collect();
        $phones = collect();

        // 1. Fetch OperatorPhones records if needed
        if ($source === 'all' || $source === 'operator_phones') {
            $operatorPhonesQuery = OperatorPhones::with('department:id,department_name')
                ->select('id', 'department_id', 'n_code', 'p_code', 'position', 'full_name', 'work_phone', 'house_phone', 'phone', 'created_at');

            if ($search) {
                $operatorPhonesQuery->where(function ($q) use ($search, $user) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('work_phone', 'like', "%{$search}%")
                        ->orWhereHas('department', fn($q2) =>
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

            $operatorPhones = $operatorPhonesQuery->get();
            $originalUsersCount = $operatorPhonesQuery->count();
        } else {
            $originalUsersCount = 0;
        }

        // 2. Fetch ResidentPhones records if needed
        if ($source === 'all' || $source === 'resident_phones') {
            $residentPhonesQuery = ResidentPhones::select('id', 'full_name', 'work_phone', 'house_phone', 'phone', 'position', 'n_code', 'p_code', 'created_at');

            if ($search) {
                $residentPhonesQuery->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('work_phone', 'like', "%{$search}%")
                        ->orWhere('house_phone', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            $phones = $residentPhonesQuery->get();
        }

        // 3. Combine into unified structure
        $combined = collect();

        foreach ($operatorPhones as $info) {
            $combined->push([
                'source' => 'operator_phones',
                'id' => $info->id,
                'full_name' => $info->full_name,
                'work_phone' => $info->work_phone,
                'house_phone' => $info->house_phone,
                'phone' => $info->phone,
                'department_name' => optional($info->department)->department_name ?? 'بدون واحد',
                'created_at' => $info->created_at,
            ]);
        }

        foreach ($phones as $phone) {
            $combined->push([
                'source' => 'resident_phones',
                'id' => $phone->id,
                'full_name' => $phone->full_name,
                'work_phone' => $phone->work_phone,
                'house_phone' => $phone->house_phone,
                'phone' => $phone->phone,
                'department_name' => null,
                'created_at' => $phone->created_at,
            ]);
        }

        // 4. Sort combined list by oldest (created_at)
        $combined = $combined->sortBy('created_at')->values();

        // 5. Manual pagination
        $page = $request->input('page', 1);
        $perPage = 10;
        $pagedCombined = new LengthAwarePaginator(
            $combined->forPage($page, $perPage),
            $combined->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // 6. Return view
        return view('phoneList.index', [
            'combinedData' => $pagedCombined,
            'showAllColumns' => $showAllColumns,
            'originalUsersCount' => $originalUsersCount,
            'filteredUsersCount' => $combined->count(),
            'selectedSource' => $source,
        ]);
    }


    public function createResident()
    {
        return view('phoneList.create-resident');
    }
    public function createOperator()
    {
        $departments = Department::select('id', 'department_name')->get();
        return view('phoneList.create-operator', compact('departments'));
    }

    public function storeResident(Request $request)
    {
        Gate::authorize('has-permission-and-role', [
            UserPermission::PHONE_PERMISSIONS->value,
            UserRole::ADMIN->value,
        ]);
        $input = $request->all();
        $phones = $this->cleanPhones($input);
        $input = array_merge($input, $phones);
        $validated = validator($input, $this->validationRulesForResident())->validate();
        ResidentPhones::create($validated);
        return redirect()->route('phone-list.index')->with('status', 'شماره عموم با موفقیت افزوده شد.');
    }
    public function storeOperator(Request $request)
    {
        Gate::authorize('has-permission-and-role', [
            UserPermission::PHONE_PERMISSIONS->value,
            UserRole::ADMIN->value,
        ]);
        $input = $request->all();
        $phones = $this->cleanPhones($input);
        $input = array_merge($input, $phones);
        $validated = validator($input, $this->validationRulesForOperator())->validate();
        OperatorPhones::create($validated);

        return redirect()->route('phone-list.index')->with('status', 'شماره کارمند با موفقیت افزوده شد.');
    }

    public function editOperator(string $id)
    {
        Gate::authorize('has-permission-and-role', [
            UserPermission::PHONE_PERMISSIONS->value,
            UserRole::ADMIN->value,
        ]);
        $record = OperatorPhones::with('department:id,department_name')
            ->select('id', 'department_id', 'n_code', 'p_code', 'position', 'full_name', 'work_phone', 'house_phone', 'phone')
            ->findOrFail($id);
        $departments = Department::select('id', 'department_name')->orderBy('department_name')->get();
        return view('phoneList.edit-operator', compact('record', 'departments'));
    }
    public function editResident(string $id)
    {
        Gate::authorize('has-permission-and-role', [
            UserPermission::PHONE_PERMISSIONS->value,
            UserRole::ADMIN->value,
        ]);
        $record = ResidentPhones::select('id', 'full_name', 'work_phone', 'house_phone', 'phone', 'position', 'n_code', 'p_code')
            ->findOrFail($id);
        return view('phoneList.edit-resident', compact('record'));
    }


    public function updateOperator(Request $request, string $id)
    {
        Gate::authorize('has-permission-and-role', [
            UserPermission::PHONE_PERMISSIONS->value,
            UserRole::ADMIN->value,
        ]);

        $input = $request->all();
        $phones = $this->cleanPhones($input);
        $input = array_merge($input, $phones);
        $validated = validator($input, $this->validationRulesForOperator())->validate();
        $record = OperatorPhones::findOrFail($id);
        $record->update([
            'full_name' => $validated['full_name'],
            'department_id' => $validated['departmentId'],
            'n_code' => $validated['n_code'],
            'p_code' => $validated['p_code'],
            'position' => $validated['position'],
            'phone' => $validated['phone'],
            'house_phone' => $validated['house_phone'],
            'work_phone' => $validated['work_phone'],
        ]);
        return to_route('phone-list.index')->with('status', 'اطلاعات با موفقیت آپدیت شد');
    }
    public function updateResident(Request $request, string $id)
    {
        Gate::authorize('has-permission-and-role', [
            UserPermission::PHONE_PERMISSIONS->value, UserRole::ADMIN->value,
        ]);
        $input = $request->all();
        $phones = $this->cleanPhones($input);
        $input = array_merge($input, $phones);
        $validated = validator($input, $this->validationRulesForResident())->validate();
        $record = ResidentPhones::findOrFail($id);
        $record->update([
            'full_name'   => $validated['full_name'],
            'n_code'      => $validated['n_code'],
            'p_code'      => $validated['p_code'],
            'position'    => $validated['position'],
            'phone'       => $validated['phone'],
            'house_phone' => $validated['house_phone'],
            'work_phone'  => $validated['work_phone'],
        ]);
        return to_route('phone-list.index')->with('status', 'اطلاعات با موفقیت آپدیت شد');
    }



    public function destroy(string $source, string $id)
    {
        Gate::authorize('has-permission-and-role', [
            UserPermission::PHONE_PERMISSIONS->value,
            UserRole::ADMIN->value,
        ]);
        if ($source === 'operator_phones') {
            $record = OperatorPhones::findOrFail($id);
        } elseif ($source === 'resident_phones') {
            $record = ResidentPhones::findOrFail($id);
        } else {
            abort(404, 'Invalid source');
        }
        $record->delete();
        return redirect()->route('phone-list.index')->with('status', 'رکورد با موفقیت حذف شد.');
    }

    private function validationRulesForOperator(): array
    {
        return [
            'full_name' => ['bail', 'required', 'string', 'min:5', 'max:255', new farsi_chs()],
            'phone' => ['bail', 'required', 'numeric', 'digits:11', new PhoneNumberRule()],
            'house_phone' => ['bail', 'required', 'numeric', 'digits_between:5,10'],
            'work_phone' => ['bail', 'required', 'numeric', 'digits_between:5,10'],
            'p_code' => ['bail', 'required', 'numeric', 'digits:6'],
            'n_code' => ['bail', 'required', 'numeric', 'digits:10', new NationalCodeRule()],
            'position' => ['bail', 'required', 'string', 'max:255'],
            'departmentId' => ['required', 'exists:departments,id'],
        ];
    }
    private function validationRulesForResident(): array
    {
        return [
            'full_name' => ['bail', 'required', 'string', 'min:5', 'max:255', new farsi_chs()],
            'phone' => ['bail', 'required', 'numeric', 'digits:11', new PhoneNumberRule()],
            'house_phone' => ['bail', 'required', 'numeric', 'digits_between:5,10'],
            'work_phone' => ['bail', 'required', 'numeric', 'digits_between:5,10'],
            'p_code' => ['bail', 'required', 'numeric', 'digits:6'],
            'n_code' => ['bail', 'required', 'numeric', 'digits:10', new NationalCodeRule()],
            'position' => ['bail', 'required', 'string', 'max:255'],
        ];
    }

    private function cleanPhone($value): ?string
    {
        return $value ? preg_replace('/(?!^\+)[^\d]/', '', $value) : null;
    }
    private function cleanPhones(array $input): array
    {
        return [
            'phone' => $this->cleanPhone($input['phone'] ?? null),
            'house_phone' => $this->cleanPhone($input['house_phone'] ?? null),
            'work_phone' => $this->cleanPhone($input['work_phone'] ?? null),
        ];
    }
}
