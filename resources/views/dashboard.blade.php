@php use App\UserRole; @endphp
<x-app-layout>
    @if (Auth::check())
        @if(Auth::user()->roles->contains('name', UserRole::ADMIN->value) ||
            Auth::user()->roles->contains('name', UserRole::SUPER_ADMIN->value))
            <livewire:admin.admin-dashboard/>
        @elseif(Auth::user()->roles->contains('name', UserRole::OPERATOR->value))
            <livewire:operator.operator-dashboard/>
        @elseif(Auth::user()->roles->contains('name', UserRole::USER->value))
            <livewire:employee.employee-dashboard/>
        @endif
    @endif
</x-app-layout>
