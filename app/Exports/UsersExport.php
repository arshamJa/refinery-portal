<?php

namespace App\Exports;

use App\Models\UserInfo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class UsersExport implements FromCollection, WithHeadings, WithTitle
{
    protected ?string $search;
    public function __construct(?string $search = null)
    {
        $this->search = $search;
    }

    public function collection()
    {
            $query = UserInfo::with([
                'user.roles.permissions',
                'user.permissions',
                'user.roles',
                'user.organizations',
                'user:id,p_code',
                'department:id,department_name',
            ])

            ->where('full_name', '!=', 'Arsham Jamali')
                ->select(['id', 'user_id', 'department_id', 'full_name', 'n_code', 'phone', 'house_phone', 'work_phone', 'position']);
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('full_name', 'like', "%{$this->search}%")
                    ->orWhere('n_code', 'like', "%{$this->search}%")
                    ->orWhere('position', 'like', "%{$this->search}%")
                    ->orWhereHas('user', fn($q) =>
                    $q->where('p_code', 'like', "%{$this->search}%"))
                    ->orWhereHas('department', fn($q) =>
                    $q->where('department_name', 'like', "%{$this->search}%"))
                    ->orWhereHas('user.organizations', fn($q) =>
                    $q->where('organization_name', 'like', "%{$this->search}%"))
                    ->orWhereHas('user.roles', fn($q) =>
                    $q->where('name', 'like', "%{$this->search}%"))
                    ->orWhereHas('user.permissions', fn($q) =>
                    $q->where('name', 'like', "%{$this->search}%"));
            });
        }

        return $query->get()->map(function ($userInfo,$index) {
            $user = $userInfo->user;

            $roles = $user?->roles->pluck('name')->implode(', ') ?? '—';
            $organizations = $user?->organizations->pluck('organization_name')->implode(', ') ?? '—';

            // Merge permissions from roles and direct user permissions
            $rolePermissions = $user?->roles->flatMap->permissions ?? collect();
            $directPermissions = $user?->permissions ?? collect();
            $allPermissions = $rolePermissions->merge($directPermissions)->unique('id')->pluck('name')->implode(', ') ?? '—';

            return [
                'ردیف'              => $index + 1,
                'نام و نام خانوادگی' => $userInfo->full_name,
                'کد ملی'             => $userInfo->n_code,
                'کد پرسنلی'          => $user->p_code ?? '—',
                'شماره همراه'        => $userInfo->phone ?? '—',
                'تلفن منزل'          => $userInfo->house_phone ?? '—',
                'تلفن محل کار'        => $userInfo->work_phone ?? '—',
                'سمت'                => $userInfo->position ?? '—',
                'دپارتمان'           => $userInfo->department->department_name ?? '—',
                'نقش‌ها'             => $roles,
                'سامانه‌ها'          => $organizations,
                'دسترسی‌ها'          => $allPermissions
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ردیف',
            'نام و نام خانوادگی',
            'کد ملی',
            'کد پرسنلی',
            'شماره همراه',
            'تلفن منزل',
            'تلفن محل کار',
            'سمت',
            'دپارتمان',
            'نقش‌ها',
            'سامانه‌ها',
            'دسترسی‌ها',
        ];
    }
    public function title(): string
    {
        return 'لیست کاربران';
    }
}
