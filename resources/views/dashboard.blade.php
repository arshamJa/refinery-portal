<x-app-layout>
    @if (Auth::check())
        @if(Auth::user()->roles->contains('name', \App\UserRole::ADMIN->value))
            <livewire:admin.admin-dashboard/>
        @elseif(Auth::user()->roles->contains('name', \App\UserRole::OPERATOR->value))
            <livewire:operator.operator-dashboard/>
        @elseif(Auth::user()->roles->contains('name', \App\UserRole::USER->value))
            <livewire:employee.employee-dashboard/>
        @endif
    @endif
</x-app-layout>
