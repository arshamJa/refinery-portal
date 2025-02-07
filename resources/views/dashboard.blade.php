<x-app-layout>
    @if(auth()->user()->role === 'admin')
        {{--        @can('admin-dashboard')--}}
        <livewire:admin.admin-dashboard/>
        {{--        @endcan--}}
    @endif
    @if(auth()->user()->role === 'operator_news' || auth()->user()->role === 'operator_phones')
        {{--        @can('operator-dashboard')--}}
        <livewire:operator.operator-dashboard/>
    @endif
    {{--        @endcan--}}
    @if(auth()->user()->role === 'employee')
        {{--        @can('employee-dashboard')--}}
        <livewire:employee.employee-dashboard/>
        {{--        @endcan--}}
    @endif
</x-app-layout>
