@php use App\Enums\UserPermission;use App\Enums\UserRole;use App\Models\Organization; @endphp
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-notification-link wire:navigate href="{{route('dashboard.meeting')}}">
        <x-icon.meeting-icon/>
        <h3 class="text-sm font-semibold"> {{__('داشبورد جلسات')}}</h3>
    </x-notification-link>
    @if(!auth()->user()->hasRole(UserRole::ADMIN->value))
        <x-notification-link href="{{ route('employee.organization') }}">
            <x-icon.organization-icon/>
            <h3 class="text-sm font-semibold">{{ __('سامانه ها') }}</h3>
        </x-notification-link>
    @endif
    <x-notification-link href="{{route('phone-list.index')}}">
        <x-icon.phone-icon/>
        <h3 class="text-sm font-semibold">
            {{ auth()->user()->hasRole(UserRole::USER->value) ? __('دفترچه تلفنی') : __('مدیریت دفترچه تلفنی') }}
        </h3>
    </x-notification-link>
    <x-notification-link href="{{route('blogs.index')}}">
        <x-icon.blog-icon/>
        <h3 class="text-sm font-semibold"> {{__('اخبار و اطلاعیه')}}</h3>
    </x-notification-link>
    @can('has-permission-and-role', [UserPermission::TASK_REPORT_TABLE,UserRole::ADMIN])
        <x-notification-link href="{{route('meeting.report.table')}}">
            <x-icon.report-icon/>
            <h3 class="text-sm font-semibold"> {{__('گزارش جلسه و اقدامات شرکت')}}</h3>
        </x-notification-link>
    @endcan
</div>

