@php use App\Enums\UserRole; @endphp
<x-app-layout>
    @if (auth()->user()->hasAnyRoles([UserRole::ADMIN->value, UserRole::SUPER_ADMIN->value]))
        <livewire:admin.admin-dashboard/>
    @elseif ( auth()->user()->hasAnyRoles([UserRole::OPERATOR->value, UserRole::USER->value]) )
        <livewire:employee.employee-dashboard/>
    @endif
</x-app-layout>
