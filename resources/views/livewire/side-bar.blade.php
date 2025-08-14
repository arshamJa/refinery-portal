@php use App\Enums\UserRole; @endphp
@php use App\Enums\UserPermission; @endphp
{{--<div class="h-full flex flex-col">--}}
{{--    <!-- Profile Image and Name -->--}}
{{--    <div--}}
{{--        class="flex items-center justify-start text-gray-100 pb-3 font-bold border-b border-gray-500 mt-12 sm:mt-2 space-x-3">--}}
{{--        <div class="flex-shrink-0 rounded-full overflow-hidden transition-all duration-300">--}}
{{--            @if(auth()->user()->profile_photo_path)--}}
{{--                <img class="rounded-full m-2 w-14 h-14 object-cover" src="{{ auth()->user()->profilePhoto() }}" alt="">--}}
{{--            @else--}}
{{--                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
{{--                     stroke="currentColor"--}}
{{--                     class="shrink-0 rounded-full m-2 w-14 h-14 ">--}}
{{--                    <path stroke-linecap="round" stroke-linejoin="round"--}}
{{--                          d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>--}}
{{--                </svg>--}}
{{--            @endif--}}
{{--        </div>--}}
{{--        <div class="text-sm">--}}
{{--            <p>{{auth()->user()->full_name()}}</p>--}}
{{--            <p> {{__('نقش:')}} {{ auth()->user()->getTranslatedRole() }}</p>--}}
{{--            <p>{{__('واحد:')}} {{auth()->user()->user_info->department->department_name}}</p>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <!-- Sidebar -->--}}
{{--    <nav class="flex-1 overflow-y-auto p-2">--}}
{{--        <ul class="w-full mt-2">--}}
{{--            <li class="mb-2">--}}
{{--                <x-link.responsive-link wire:navigate href="{{route('dashboard')}}"--}}
{{--                                        :active="request()->is('dashboard')" class="flex items-center gap-x-2">--}}
{{--                    <x-icon.dashboard-icon/>--}}
{{--                    {{__('داشبورد')}}--}}
{{--                </x-link.responsive-link>--}}
{{--            </li>--}}
{{--            <li class="mb-2">--}}
{{--                <x-link.responsive-link href="{{Illuminate\Support\Facades\URL::signedRoute('profile')}}"--}}
{{--                                        :active="request()->is('profile')" class="flex items-center gap-x-2">--}}
{{--                    <x-icon.profile-icon/>--}}
{{--                    {{__('پروفایل')}}--}}
{{--                </x-link.responsive-link>--}}
{{--            </li>--}}
{{--            @if(!auth()->user()->hasRole(UserRole::ADMIN->value))--}}
{{--                <li class="mb-2">--}}
{{--                    <x-link.responsive-link href="{{route('employee.organization')}}"--}}
{{--                                            :active="request()->is('employee/organization')"--}}
{{--                                            class="flex items-center gap-x-2">--}}
{{--                        <x-icon.organization-icon/>--}}
{{--                        {{__('سامانه ها')}}--}}
{{--                    </x-link.responsive-link>--}}
{{--                </li>--}}
{{--            @endif--}}
{{--            <li class="mb-2">--}}
{{--                <x-link.responsive-link href="{{ route('phone-list.index') }}"--}}
{{--                                        :active="request()->is('phone/list')" class="flex items-center gap-x-2">--}}
{{--                    <x-icon.phone-icon/>--}}
{{--                    {{ auth()->user()->hasRole(UserRole::USER->value) ? __('دفترچه تلفنی') : __('مدیریت دفترچه تلفنی') }}--}}
{{--                </x-link.responsive-link>--}}
{{--            </li>--}}
{{--            <li class="mb-2">--}}
{{--                <div x-data="{ openMeeting: false }" class="w-full">--}}
{{--                    <button @click="openMeeting = !openMeeting"--}}
{{--                            class="w-full flex items-center justify-between pl-2 pr-3 py-2 font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition ease-in-out duration-300 rounded-md relative">--}}
{{--                        <span class="flex items-center gap-x-2">--}}
{{--                            <x-icon.meeting-icon/>--}}
{{--                            <span>{{__('داشبورد جلسات')}}</span>--}}
{{--                        </span>--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': openMeeting}"--}}
{{--                             class="h-4 w-4 transition-transform"--}}
{{--                             viewBox="0 0 20 20" fill="currentColor">--}}
{{--                            <path fill-rule="evenodd"--}}
{{--                                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z"--}}
{{--                                  clip-rule="evenodd"/>--}}
{{--                        </svg>--}}
{{--                    </button>--}}
{{--                    <div x-show="openMeeting" class="mt-1 space-y-1 pr-3">--}}
{{--                        <x-link.responsive-link href="{{route('meeting.create')}}"--}}
{{--                                                :active="request()->is('create/new/meeting')"--}}
{{--                                                class="flex items-center gap-x-2 text-xs">--}}
{{--                            {{__('ایجاد جلسه جدید')}}--}}
{{--                        </x-link.responsive-link>--}}
{{--                        <x-link.responsive-link wire:navigate href="{{route('my.task.table')}}"--}}
{{--                                                :active="request()->is('my/task/table')"--}}
{{--                                                class="flex items-center gap-x-2 text-xs">--}}
{{--                            {{__('اقدامات من')}}--}}
{{--                        </x-link.responsive-link>--}}
{{--                        <x-link.responsive-link wire:navigate href="{{route('dashboard.meeting')}}"--}}
{{--                                                :active="request()->is('meeting/dashboard')"--}}
{{--                                                class="flex items-center gap-x-2 text-xs">--}}
{{--                            {{__('جدول جلسات')}}--}}
{{--                        </x-link.responsive-link>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </li>--}}
{{--            @can('has-permission-and-role', [UserPermission::TASK_REPORT_TABLE,UserRole::ADMIN])--}}
{{--                <li class="mb-2">--}}
{{--                    <div x-data="{ openReport: false }" class="w-full">--}}
{{--                        <button @click="openReport = !openReport"--}}
{{--                                class="w-full flex items-center justify-between pl-2 pr-3 py-2 font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition ease-in-out duration-300 rounded-md relative">--}}
{{--                            <span class="flex items-center gap-2">--}}
{{--                                <x-icon.report-icon/>--}}
{{--                                <span>{{__('گزارش شرکت')}}</span>--}}
{{--                            </span>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': openReport}"--}}
{{--                                 class="h-4 w-4 transition-transform"--}}
{{--                                 viewBox="0 0 20 20" fill="currentColor">--}}
{{--                                <path fill-rule="evenodd"--}}
{{--                                      d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z"--}}
{{--                                      clip-rule="evenodd"/>--}}
{{--                            </svg>--}}
{{--                        </button>--}}
{{--                        <div x-show="openReport" class="mt-1 space-y-1 pr-3">--}}
{{--                            <a href="{{route('meeting.report.table')}}"--}}
{{--                               class="flex items-center gap-x-2 text-xs text-right rounded-md px-3 py-2 font-medium transition ease-in-out duration-300 text-gray-300 hover:bg-gray-700 hover:text-white--}}
{{--                            {{ request()->is('meeting/report/table') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">--}}
{{--                                {{__('گزارش جلسات شرکت')}}--}}
{{--                            </a>--}}
{{--                            <a href="{{route('task.report.table')}}"--}}
{{--                               class="flex items-center gap-x-2 text-xs text-right rounded-md px-3 py-2 font-medium transition ease-in-out duration-300 text-gray-300 hover:bg-gray-700 hover:text-white--}}
{{--                            {{ request()->is('task/report/table') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">--}}
{{--                                {{__('گزارش اقدامات شرکت')}}--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </li>--}}
{{--            @endcan--}}
{{--            <li class="mb-2">--}}
{{--                <div x-data="{ openMessage: false }" class="w-full" wire:poll.visible.60s>--}}
{{--                    <button @click="openMessage = !openMessage"--}}
{{--                            class="w-full flex items-center justify-between pl-2 pr-4 py-2 font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition ease-in-out duration-300 rounded-md relative">--}}
{{--                        <span class="relative flex items-center gap-x-2">--}}
{{--                            <span class="relative">--}}
{{--                                @if($this->messagesNotification > 0)--}}
{{--                                    <x-icon.pulse-icon/>--}}
{{--                                @endif--}}
{{--                                    <x-icon.message-icon/>--}}
{{--                            </span>--}}
{{--                            {{ __('پیام ها') }}--}}
{{--                        </span>--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': openMessage}"--}}
{{--                             class="h-4 w-4 transition-transform"--}}
{{--                             viewBox="0 0 20 20" fill="currentColor">--}}
{{--                            <path fill-rule="evenodd"--}}
{{--                                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z"--}}
{{--                                  clip-rule="evenodd"/>--}}
{{--                        </svg>--}}
{{--                    </button>--}}
{{--                    <div x-show="openMessage" class="mt-1 space-y-1 pr-3">--}}
{{--                        <x-link.responsive-link wire:navigate href="{{route('received.message')}}"--}}
{{--                                                :active="request()->is('received/message')"--}}
{{--                                                class="flex items-center gap-x-2 text-xs">--}}
{{--                            <span class="flex justify-between w-full">--}}
{{--                                    {{__('پیام های دریافتی')}}--}}
{{--                                @if($this->unreadReceivedCount() > 0)--}}
{{--                                    <span class="text-gray-100 font-bold">--}}
{{--                                    {{ $this->unreadReceivedCount() > 10 ? '+10' : $this->unreadReceivedCount() }}--}}
{{--                                    </span>--}}
{{--                                @endif--}}
{{--                            </span>--}}
{{--                        </x-link.responsive-link>--}}
{{--                        <x-link.responsive-link wire:navigate href="{{route('sent.message')}}"--}}
{{--                                                :active="request()->is('sent/message')"--}}
{{--                                                class="flex items-center gap-x-2 text-xs">--}}
{{--                            <span>--}}
{{--                                {{__('پیام های ارسالی')}}--}}
{{--                            </span>--}}
{{--                        </x-link.responsive-link>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </li>--}}
{{--            <li class="mb-2">--}}
{{--                @if(auth()->user()->hasRole(UserRole::USER->value) ||--}}
{{--                    !auth()->user()->hasPermissionTo(UserPermission::NEWS_PERMISSIONS->value))--}}
{{--                    <x-link.responsive-link href="{{route('blogs.index')}}" :active="request()->is('blogs')"--}}
{{--                                            class="flex items-center gap-x-2">--}}
{{--                        <x-icon.blog-icon/>--}}
{{--                        {{__('صفحه اخبار و اطلاعیه')}}--}}
{{--                    </x-link.responsive-link>--}}
{{--                @else--}}
{{--                    <div x-data="{ openSetting: false }" class="w-full">--}}
{{--                        <button @click="openSetting = !openSetting"--}}
{{--                                class="w-full flex items-center justify-between pl-2 pr-3 py-2 font-medium text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition">--}}
{{--                            <span class="flex items-center gap-2">--}}
{{--                                <x-icon.blog-icon/>--}}
{{--                                <span>{{ __('اخبار و اطلاعیه') }}</span>--}}
{{--                            </span>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': openSetting}"--}}
{{--                                 class="h-4 w-4 transition-transform"--}}
{{--                                 viewBox="0 0 20 20" fill="currentColor">--}}
{{--                                <path fill-rule="evenodd"--}}
{{--                                      d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z"--}}
{{--                                      clip-rule="evenodd"/>--}}
{{--                            </svg>--}}
{{--                        </button>--}}
{{--                        <div x-show="openSetting" class="mt-1 space-y-1 pr-3">--}}
{{--                            <x-link.responsive-link href="{{route('blogs.create')}}"--}}
{{--                                                    :active="request()->is('blogs/create')"--}}
{{--                                                    class="flex items-center gap-x-2 text-xs">--}}
{{--                                {{__('درج خبر و اطلاعیه')}}--}}
{{--                            </x-link.responsive-link>--}}
{{--                            <x-link.responsive-link href="{{route('blogs.index')}}"--}}
{{--                                                    :active="request()->is('blogs')"--}}
{{--                                                    class="flex items-center gap-x-2 text-xs">--}}
{{--                                {{__('صفحه اخبار و اطلاعیه')}}--}}
{{--                            </x-link.responsive-link>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endif--}}
{{--            </li>--}}
{{--            @can('admin-role')--}}
{{--                <li class="mb-2">--}}
{{--                    <div x-data="{ openSetting: false }" class="w-full mb-4">--}}
{{--                        <button @click="openSetting = !openSetting"--}}
{{--                                class="w-full flex items-center justify-between pl-2 pr-3 py-2 font-medium text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition">--}}
{{--                            <span class="flex items-center gap-2">--}}
{{--                                <x-icon.setting-icon/>--}}
{{--                                <span>{{ __('تنظیمات راهبری') }}</span>--}}
{{--                            </span>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': openSetting}"--}}
{{--                                 class="h-4 w-4 transition-transform"--}}
{{--                                 viewBox="0 0 20 20" fill="currentColor">--}}
{{--                                <path fill-rule="evenodd"--}}
{{--                                      d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z"--}}
{{--                                      clip-rule="evenodd"/>--}}
{{--                            </svg>--}}
{{--                        </button>--}}
{{--                        <div x-show="openSetting" class="mt-1 space-y-1 pr-3">--}}
{{--                            <x-link.responsive-link href="{{route('organizations')}}"--}}
{{--                                                    :active="request()->is('organizations')"--}}
{{--                                                    class="flex items-center gap-x-2 text-xs">--}}
{{--                                {{__('جدول سامانه')}}--}}
{{--                            </x-link.responsive-link>--}}
{{--                            <x-link.responsive-link href="{{route('departments.index')}}"--}}
{{--                                                    :active="request()->is('departments')"--}}
{{--                                                    class="flex items-center gap-x-2 text-xs">--}}
{{--                                {{__('جدول دپارتمان')}}--}}
{{--                            </x-link.responsive-link>--}}
{{--                            <x-link.responsive-link href="{{route('users.create')}}"--}}
{{--                                                    :active="request()->is('users/create')"--}}
{{--                                                    class="flex items-center gap-x-2 text-xs">--}}
{{--                                {{__('ساخت کاربر جدید')}}--}}
{{--                            </x-link.responsive-link>--}}
{{--                            <x-link.responsive-link href="{{route('users.index')}}"--}}
{{--                                                    :active="request()->is('users/table')"--}}
{{--                                                    class="flex items-center gap-x-2 text-xs">--}}
{{--                                {{__('مدیریت کاربران')}}--}}
{{--                            </x-link.responsive-link>--}}
{{--                            <x-link.responsive-link href="{{route('organization.department.manage')}}"--}}
{{--                                                    :active="request()->is('department/organization/manage')"--}}
{{--                                                    class="flex items-center gap-x-2 text-xs">--}}
{{--                                {{__('مدیریت سامانه/دپارتمان')}}--}}
{{--                            </x-link.responsive-link>--}}
{{--                            @if(auth()->user()->hasRole(UserRole::SUPER_ADMIN->value))--}}
{{--                                <x-link.responsive-link href="{{route('role.permission.table')}}"--}}
{{--                                                        :active="request()->is('roles/permissions')"--}}
{{--                                                        class="flex items-center gap-x-2 text-xs">--}}
{{--                                    {{__('مدیریت نقش و دسترسی')}}--}}
{{--                                </x-link.responsive-link>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </li>--}}
{{--            @endcan--}}
{{--        </ul>--}}
{{--    </nav>--}}

{{--    <div class="py-4 px-2 border-t border-gray-500">--}}
{{--        <form action="{{route('logout')}}" method="post">--}}
{{--            @csrf--}}
{{--            <x-cancel-button type="submit" class="inline-flex justify-center items-center gap-1 w-full">--}}
{{--                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
{{--                     stroke="currentColor" class="size-5">--}}
{{--                    <path stroke-linecap="round" stroke-linejoin="round"--}}
{{--                          d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>--}}
{{--                </svg>--}}
{{--                <span class="text-sm">{{__('خروج')}}</span>--}}
{{--            </x-cancel-button>--}}
{{--        </form>--}}
{{--    </div>--}}
{{--</div>--}}

<div>

    <!-- Profile Image and Name -->
    <div class="flex items-center justify-start gap-x-2 text-gray-100 font-bold border-b border-gray-500">
        <div class="py-2 flex-shrink-0 rounded-full overflow-hidden">
            @if(auth()->user()->profile_photo_path)
                <img
                    :class="expanded ? 'w-16 h-16' : 'w-12 h-12'"
                    class="rounded-full object-cover transition-all duration-300 ease-in-out"
                    src="{{ auth()->user()->profilePhoto() }}" alt="Profile Photo">
            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor"
                     class="rounded-full object-cover transition-all duration-300 ease-in-out"
                     :class="expanded ? 'w-16 h-16' : 'w-12 h-12'">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>
            @endif
        </div>
        <div class="text-sm overflow-hidden whitespace-nowrap"
             :class="expanded ? 'opacity-100 max-w-xs ml-2' : 'opacity-0 max-w-0 ml-0'"
             style="transition-property: opacity, max-width, margin-left;">
            <p>{{auth()->user()->full_name()}}</p>
            <p> {{__('نقش:')}} {{ auth()->user()->getTranslatedRole() }}</p>
            <p>{{__('واحد:')}} {{auth()->user()->user_info->department->department_name}}</p>
        </div>
    </div>


    <!-- Normal Icon -->
    <div class="flex items-center gap-x-2 whitespace-nowrap">
        <ul class="w-full mt-2 relative">
            <li class="mb-2">
                <x-link.responsive-link wire:navigate href="{{route('dashboard')}}"
                                        :active="request()->is('dashboard')" class="flex items-center gap-x-2">
                    <span> <x-icon.dashboard-icon/></span>
                    <span x-show="expanded">{{__('داشبورد')}}</span>
                </x-link.responsive-link>
            </li>
            <li class="mb-2">
                <x-link.responsive-link href="{{Illuminate\Support\Facades\URL::signedRoute('profile')}}"
                                        :active="request()->is('profile')" class="flex items-center gap-x-2">
                    <span><x-icon.profile-icon/></span>
                    <span x-show="expanded"> {{__('پروفایل')}}</span>
                </x-link.responsive-link>
            </li>
            <li class="mb-2">
                @if(!auth()->user()->hasRole(UserRole::ADMIN->value))
                    <x-link.responsive-link href="{{route('employee.organization')}}"
                                            :active="request()->is('employee/organization')"
                                            class="flex items-center gap-x-2">
                        <span> <x-icon.organization-icon/></span>
                        <span x-show="expanded"> {{__('سامانه ها')}}</span>
                    </x-link.responsive-link>
                @endif
            </li>
            <li class="mb-2">
                <x-link.responsive-link href="{{ route('phone-list.index') }}"
                                        :active="request()->is('phone/list')" class="flex items-center gap-x-2">
                    <span><x-icon.phone-icon/></span>
                    <span
                        x-show="expanded">{{ auth()->user()->hasRole(UserRole::USER->value) ? __('دفترچه تلفنی') : __('مدیریت دفترچه تلفنی') }}</span>
                </x-link.responsive-link>
            </li>
            <!-- Meeting Dropdown -->
            <li class="mb-2 cursor-pointer relative" x-data="{ dropdownOpen: false }"
                @click.away="dropdownOpen = false">
                <div @click="dropdownOpen = !dropdownOpen"
                     class="flex items-center gap-x-2 text-sm rounded-md px-3 py-2 font-medium transition ease-in-out duration-300 text-gray-300 hover:bg-gray-700 hover:text-white">
                    <span><x-icon.meeting-icon fill="white"/></span>
                    <span x-show="expanded" class="flex items-center justify-between w-full gap-x-2">
                       <span> {{__('داشبورد جلسات')}}</span>
                        <!-- Rotating Arrow SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': dropdownOpen}"
                                 class="h-4 w-4 transition-transform"
                                 viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </span>
                </div>
                <!-- Dropdown when sidebar is expanded -->
                <div x-show="dropdownOpen && expanded" x-transition class="mt-1 space-y-1 pr-3">
                    <x-link.responsive-link href="{{route('meeting.create')}}"
                                            :active="request()->is('create/new/meeting')"
                                            class="flex items-center gap-x-2 text-xs">
                        {{__('ایجاد جلسه جدید')}}
                    </x-link.responsive-link>
                    <x-link.responsive-link wire:navigate href="{{route('my.task.table')}}"
                                            :active="request()->is('my/task/table')"
                                            class="flex items-center gap-x-2 text-xs">
                        {{__('اقدامات من')}}
                    </x-link.responsive-link>
                    <x-link.responsive-link wire:navigate href="{{route('dashboard.meeting')}}"
                                            :active="request()->is('meeting/dashboard')"
                                            class="flex items-center gap-x-2 text-xs">
                        {{__('جدول جلسات')}}
                    </x-link.responsive-link>
                </div>
                <!-- Dropdown when sidebar is collapsed -->
                <div x-show="dropdownOpen && !expanded" x-transition
                     class="absolute right-full top-0 mt-0 mr-2 bg-white rounded-lg shadow-lg w-40 z-[9999]"
                     style="min-width: 10rem;">
                    <div class="rounded-lg ring-1 ring-black ring-opacity-10 py-1 bg-white dark:bg-gray-800 shadow-xl">
                        <x-dropdown-link href="{{route('meeting.create')}}">
                            {{__('ایجاد جلسه جدید')}}
                        </x-dropdown-link>
                        <x-dropdown-link wire:navigate href="{{route('my.task.table')}}">
                            {{__('اقدامات من')}}
                        </x-dropdown-link>
                        <x-dropdown-link wire:navigate href="{{route('dashboard.meeting')}}">
                            {{__('جدول جلسات')}}
                        </x-dropdown-link>
                    </div>
                </div>
            </li>
            <!-- Message Dropdown -->
            <li class="mb-2 relative cursor-pointer" x-data="{ messageDropdown: false }"
                @click.away="messageDropdown = false">
                <div @click="messageDropdown = !messageDropdown"
                     class="flex items-center w-full justify-between gap-x-2 text-sm rounded-md px-3 py-2 font-medium transition ease-in-out duration-300 text-gray-300 hover:bg-gray-700 hover:text-white">
                    <span class="relative flex items-center gap-x-2">
                        <span class="relative">
                            @if($this->messagesNotification > 0)
                                <x-icon.pulse-icon/>
                            @endif
                            <x-icon.message-icon/>
                        </span>
                        <span x-show="expanded">{{ __('پیام ها') }}</span>
                    </span>
                    <svg x-show="expanded" xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': messageDropdown}"
                         class="h-4 w-4 transition-transform"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z"
                              clip-rule="evenodd"/>
                    </svg>
                </div>

                <!-- Dropdown when sidebar is expanded -->
                <div x-show="messageDropdown && expanded" x-transition class="mt-1 space-y-1 pr-3">
                    <x-link.responsive-link wire:navigate href="{{route('received.message')}}"
                                            :active="request()->is('received/message')"
                                            class="flex items-center gap-x-2 text-xs">
                    <span class="flex justify-between w-full">
                        {{__('پیام های دریافتی')}}
                        @if($this->unreadReceivedCount() > 0)
                            <span class="text-gray-100 font-bold">
                                {{ $this->unreadReceivedCount() > 10 ? '+10' : $this->unreadReceivedCount() }}
                            </span>
                        @endif
                    </span>
                    </x-link.responsive-link>
                    <x-link.responsive-link wire:navigate href="{{route('sent.message')}}"
                                            :active="request()->is('sent/message')"
                                            class="flex items-center gap-x-2 text-xs">
                        {{__('پیام های ارسالی')}}
                    </x-link.responsive-link>
                </div>

                <!-- Dropdown when sidebar is collapsed -->
                <div x-show="messageDropdown && !expanded" x-transition
                     class="absolute right-full top-0 mt-0 mr-2 bg-white rounded-lg shadow-lg w-40 z-50"
                     style="min-width: 10rem;">
                    <div class="rounded-lg ring-1 ring-black ring-opacity-10 py-1 bg-white dark:bg-gray-800 shadow-xl">
                        <x-dropdown-link wire:navigate href="{{route('received.message')}}"
                                         class="flex justify-between">
                            {{__('پیام های دریافتی')}}
                            @if($this->unreadReceivedCount() > 0)
                                <span class="text-gray-700 font-bold ml-1">
                        {{ $this->unreadReceivedCount() > 10 ? '+10' : $this->unreadReceivedCount() }}
                            </span>
                            @endif
                        </x-dropdown-link>
                        <x-dropdown-link wire:navigate href="{{route('sent.message')}}">
                            {{__('پیام های ارسالی')}}
                        </x-dropdown-link>
                    </div>
                </div>
            </li>
            <!-- Blog Dropdown -->
            <li class="mb-2 relative cursor-pointer" x-data="{ blogDropdown: false }"
                @click.away="blogDropdown = false">
                @if(auth()->user()->hasRole(UserRole::USER->value) ||
                    !auth()->user()->hasPermissionTo(UserPermission::NEWS_PERMISSIONS->value))
                    <x-link.responsive-link href="{{route('blogs.index')}}" :active="request()->is('blogs')"
                                            class="flex items-center gap-x-2 px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition ease-in-out duration-300">
                        <x-icon.blog-icon/>
                        <span x-show="expanded">{{ __('صفحه اخبار و اطلاعیه') }}</span>
                    </x-link.responsive-link>
                @else
                    <div>
                        <div @click="blogDropdown = !blogDropdown"
                             class="flex items-center gap-x-2 text-sm rounded-md px-3 py-2 font-medium transition ease-in-out duration-300 text-gray-300 hover:bg-gray-700 hover:text-white cursor-pointer select-none">
                            <span><x-icon.blog-icon/></span>
                            <span x-show="expanded" class="flex items-center justify-between w-full gap-x-2">
                    <span>{{ __('اخبار و اطلاعیه') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': blogDropdown}"
                         class="h-4 w-4 transition-transform"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z"
                              clip-rule="evenodd"/>
                    </svg>
                </span>
                        </div>

                        <!-- Dropdown when sidebar is expanded -->
                        <div x-show="blogDropdown && expanded" x-transition class="mt-1 space-y-1 pr-3">
                            <x-link.responsive-link href="{{route('blogs.create')}}"
                                                    :active="request()->is('blogs/create')"
                                                    class="flex items-center gap-x-2 text-xs">
                                {{ __('درج خبر و اطلاعیه') }}
                            </x-link.responsive-link>
                            <x-link.responsive-link href="{{route('blogs.index')}}"
                                                    :active="request()->is('blogs')"
                                                    class="flex items-center gap-x-2 text-xs">
                                {{ __('صفحه اخبار و اطلاعیه') }}
                            </x-link.responsive-link>
                        </div>

                        <!-- Dropdown when sidebar is collapsed -->
                        <div x-show="blogDropdown && !expanded" x-transition
                             class="absolute right-full top-0 mt-0 mr-2 bg-white rounded-lg shadow-lg w-40 z-50"
                             style="min-width: 10rem;">
                            <div
                                class="rounded-lg ring-1 ring-black ring-opacity-10 py-1 bg-white dark:bg-gray-800 shadow-xl">
                                <x-dropdown-link href="{{route('blogs.create')}}">
                                    {{ __('درج خبر و اطلاعیه') }}
                                </x-dropdown-link>
                                <x-dropdown-link href="{{route('blogs.index')}}">
                                    {{ __('صفحه اخبار و اطلاعیه') }}
                                </x-dropdown-link>
                            </div>
                        </div>
                    </div>
                @endif
            </li>
            {{-- Company Report Dropdown --}}
            @can('has-permission-and-role', [UserPermission::TASK_REPORT_TABLE, UserRole::ADMIN])
                <li class="mb-2 cursor-pointer relative" x-data="{ reportDropdown: false }"
                    @click.away="reportDropdown = false">
                    <div @click="reportDropdown = !reportDropdown"
                         class="flex items-center gap-x-2 text-sm rounded-md px-3 py-2 font-medium transition ease-in-out duration-300 text-gray-300 hover:bg-gray-700 hover:text-white">
                        <span><x-icon.report-icon fill="white"/></span>
                        <span x-show="expanded" class="flex items-center justify-between w-full gap-x-2">
                <span>{{ __('گزارش شرکت') }}</span>
                            <!-- Rotating Arrow -->
                <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': reportDropdown}"
                     class="h-4 w-4 transition-transform"
                     viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z"
                          clip-rule="evenodd"/>
                </svg>
            </span>
                    </div>
                    <!-- Dropdown when sidebar is expanded -->
                    <div x-show="reportDropdown && expanded" x-transition class="mt-1 space-y-1 pr-3">
                        <x-link.responsive-link href="{{ route('meeting.report.table') }}"
                                                :active="request()->is('meeting/report/table')"
                                                class="flex items-center gap-x-2 text-xs">
                            {{ __('گزارش جلسات شرکت') }}
                        </x-link.responsive-link>
                        <x-link.responsive-link href="{{ route('task.report.table') }}"
                                                :active="request()->is('task/report/table')"
                                                class="flex items-center gap-x-2 text-xs">
                            {{ __('گزارش اقدامات شرکت') }}
                        </x-link.responsive-link>
                    </div>
                    <!-- Dropdown when sidebar is collapsed -->
                    <div x-show="reportDropdown && !expanded" x-transition
                         class="absolute right-full top-0 mt-0 mr-2 bg-white rounded-lg shadow-lg w-48 z-[9999]"
                         style="min-width: 10rem;">
                        <div
                            class="rounded-lg ring-1 ring-black ring-opacity-10 py-1 bg-white dark:bg-gray-800 shadow-xl">
                            <x-dropdown-link href="{{ route('meeting.report.table') }}">
                                {{ __('گزارش جلسات شرکت') }}
                            </x-dropdown-link>
                            <x-dropdown-link href="{{ route('task.report.table') }}">
                                {{ __('گزارش اقدامات شرکت') }}
                            </x-dropdown-link>
                        </div>
                    </div>
                </li>
            @endcan
            <!-- Setting Dropdown -->
            @can('admin-role')
                <li class="mb-2 relative cursor-pointer" x-data="{ settingDropdown: false }"
                    @click.away="settingDropdown = false">
                    <div @click="settingDropdown = !settingDropdown"
                         class="flex items-center gap-x-2 text-sm rounded-md px-3 py-2 font-medium transition ease-in-out duration-300 text-gray-300 hover:bg-gray-700 hover:text-white cursor-pointer select-none">
                        <span><x-icon.setting-icon/></span>
                        <span x-show="expanded" class="flex items-center justify-between w-full gap-x-2">
                        <span>{{ __('تنظیمات راهبری') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': settingDropdown}"
                             class="h-4 w-4 transition-transform"
                             viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </span>
                    </div>

                    <!-- Dropdown when sidebar is expanded -->
                    <div x-show="settingDropdown && expanded" x-transition class="mt-1 space-y-1 pr-3">
                        <x-link.responsive-link href="{{route('organizations')}}"
                                                :active="request()->is('organizations')"
                                                class="flex items-center gap-x-2 text-xs">
                            {{ __('جدول سامانه') }}
                        </x-link.responsive-link>
                        <x-link.responsive-link href="{{route('departments.index')}}"
                                                :active="request()->is('departments')"
                                                class="flex items-center gap-x-2 text-xs">
                            {{ __('جدول دپارتمان') }}
                        </x-link.responsive-link>
                        <x-link.responsive-link href="{{route('users.create')}}"
                                                :active="request()->is('users/create')"
                                                class="flex items-center gap-x-2 text-xs">
                            {{ __('ساخت کاربر جدید') }}
                        </x-link.responsive-link>
                        <x-link.responsive-link href="{{route('users.index')}}"
                                                :active="request()->is('users/table')"
                                                class="flex items-center gap-x-2 text-xs">
                            {{ __('مدیریت کاربران') }}
                        </x-link.responsive-link>
                        <x-link.responsive-link href="{{route('organization.department.manage')}}"
                                                :active="request()->is('department/organization/manage')"
                                                class="flex items-center gap-x-2 text-xs">
                            {{ __('مدیریت سامانه/دپارتمان') }}
                        </x-link.responsive-link>
                        @if(auth()->user()->hasRole(UserRole::SUPER_ADMIN->value))
                            <x-link.responsive-link href="{{route('role.permission.table')}}"
                                                    :active="request()->is('roles/permissions')"
                                                    class="flex items-center gap-x-2 text-xs">
                                {{ __('مدیریت نقش و دسترسی') }}
                            </x-link.responsive-link>
                        @endif
                    </div>

                    <!-- Dropdown when sidebar is collapsed -->
                    <div x-show="settingDropdown && !expanded" x-transition
                         class="absolute right-full top-0 mt-0 mr-2 bg-white rounded-lg shadow-lg w-48 z-50"
                         style="min-width: 12rem;">
                        <div
                            class="rounded-lg ring-1 ring-black ring-opacity-10 py-1 bg-white dark:bg-gray-800 shadow-xl">
                            <x-dropdown-link href="{{route('organizations')}}">
                                {{ __('جدول سامانه') }}
                            </x-dropdown-link>
                            <x-dropdown-link href="{{route('departments.index')}}">
                                {{ __('جدول دپارتمان') }}
                            </x-dropdown-link>
                            <x-dropdown-link href="{{route('users.create')}}">
                                {{ __('ساخت کاربر جدید') }}
                            </x-dropdown-link>
                            <x-dropdown-link href="{{route('users.index')}}">
                                {{ __('مدیریت کاربران') }}
                            </x-dropdown-link>
                            <x-dropdown-link href="{{route('organization.department.manage')}}">
                                {{ __('مدیریت سامانه/دپارتمان') }}
                            </x-dropdown-link>
                            @if(auth()->user()->hasRole(UserRole::SUPER_ADMIN->value))
                                <x-dropdown-link href="{{route('role.permission.table')}}">
                                    {{ __('مدیریت نقش و دسترسی') }}
                                </x-dropdown-link>
                            @endif
                        </div>
                    </div>
                </li>
            @endcan

            <li class="my-2 pt-2 border-t border-gray-500">
                <form action="{{route('logout')}}" method="post">
                    @csrf
                    <x-cancel-button type="submit" class="inline-flex justify-center items-center gap-1 w-full">
                        <span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                   stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                        </svg></span>
                        <span x-show="expanded" class="text-sm">{{__('خروج')}}</span>
                    </x-cancel-button>
                </form>
            </li>
        </ul>
    </div>
</div>



