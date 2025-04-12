@php use App\Enums\UserRole; @endphp
<x-app-layout>
    @if (auth()->user()->hasRole(UserRole::ADMIN->value) ||
        auth()->user()->hasRole(UserRole::SUPER_ADMIN->value))
        <livewire:admin.admin-dashboard/>
    @elseif (auth()->user()->hasRole(UserRole::OPERATOR->value))
        <livewire:operator.operator-dashboard/>
    @elseif (auth()->user()->hasRole(UserRole::USER->value))
        <livewire:employee.employee-dashboard/>
    @endif
</x-app-layout>
