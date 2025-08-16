@php use App\Enums\UserPermission;use App\Enums\UserRole;@endphp
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <!-- Meeting dashboard -->
    @can('has-permission' , UserPermission::VIEW_MEETING_DASHBOARD)
        <x-notification-link href="{{route('dashboard.meeting')}}">
            <x-icon.meeting-icon/>
            <h3 class="text-sm font-semibold"> {{__('داشبورد جلسات')}}</h3>
        </x-notification-link>
    @endcan

    <!-- Organization -->
    @can('has-permission' , UserPermission::VIEW_ORGANIZATIONS)
        @if(!auth()->user()->hasRole(UserRole::ADMIN->value))
            <x-notification-link href="{{ route('employee.organization') }}">
                <x-icon.organization-icon/>
                <h3 class="text-sm font-semibold">{{ __('سامانه ها') }}</h3>
            </x-notification-link>
        @endif
    @endcan

    <!-- Phone list -->
    @can('has-permission', UserPermission::PHONE_PERMISSIONS)
        <x-notification-link href="{{ route('phone-list.index') }}">
            <x-icon.phone-icon/>
            <h3 class="text-sm font-semibold">
                {{ __('مدیریت دفترچه تلفنی') }}
            </h3>
        </x-notification-link>
    @elsecan('has-permission', UserPermission::VIEW_PHONE_LISTS)
        <x-notification-link href="{{ route('phone-list.index') }}">
            <x-icon.phone-icon/>
            <h3 class="text-sm font-semibold">
                {{ __('دفترچه تلفنی') }}
            </h3>
        </x-notification-link>
    @endcan

    <!-- Blog -->
    @can('has-permission' , UserPermission::VIEW_BLOG)
        <x-notification-link href="{{ route('blogs.index') }}">
            <x-icon.blog-icon/>
            <h3 class="text-sm font-semibold"> {{ __('اخبار و اطلاعیه') }} </h3>
        </x-notification-link>
    @elsecan('has-permission',UserPermission::NEWS_PERMISSIONS)
        <x-notification-link href="{{ route('blogs.index') }}">
            <x-icon.blog-icon/>
            <h3 class="text-sm font-semibold"> {{ __('مدیریت اخبار و اطلاعیه') }} </h3>
        </x-notification-link>
    @endcan

    <!-- Refinery report -->
    @can('has-permission', UserPermission::TASK_REPORT_TABLE)
        <x-notification-link href="{{route('meeting.report.table')}}">
            <x-icon.report-icon/>
            <h3 class="text-sm font-semibold"> {{__('گزارش جلسات و اقدامات')}}</h3>
        </x-notification-link>
    @endcan
</div>

