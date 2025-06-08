@php use App\Enums\UserPermission;use App\Enums\UserRole;use App\Models\Notification; @endphp
<div class="h-full flex flex-col">
    <!-- Profile Image and Name -->
    <div class="flex flex-col justify-center items-center text-gray-100 px-3 pb-4 font-bold border-b border-gray-500">
        <div>
            @if(auth()->user()->profile_photo_path)
                <img class="rounded-full m-2 w-14 h-14 object-cover" src="{{ auth()->user()->profilePhoto() }}" alt="">
            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor"
                     class="shrink-0 rounded-full m-2 w-14 h-14 ">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>

            @endif
        </div>
        <div class="text-center">
            <p class="text-lg font-semibold mb-1">{{auth()->user()->full_name()}}</p>
            <p class="text-sm"> {{__('نقش:')}} {{ auth()->user()->getTranslatedRole() }}</p>
            <p class="text-sm">{{__('واحد:')}} {{auth()->user()->user_info->department->department_name}}</p>
        </div>
    </div>

    <!-- Sidebar -->
    <nav class="flex-1 overflow-y-auto p-2">
        <ul class="w-full mt-2">
            <li class="mb-2">
                <x-link.responsive-link wire:navigate href="{{route('dashboard')}}"
                                        :active="request()->is('dashboard')" class="flex items-center gap-2 pr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                    </svg>
                    {{__('داشبورد')}}
                </x-link.responsive-link>
            </li>
            <li class="mb-2">
                <div x-data="{ openMeeting: false }" class="w-full">
                    <button @click="openMeeting = !openMeeting"
                            class="w-full flex items-center justify-between px-4 py-2 font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition ease-in-out duration-300 rounded-md relative">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                            </svg>
                            <span>{{__('جلسات')}}</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': openMeeting}"
                             class="h-4 w-4 transition-transform"
                             viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <div x-show="openMeeting" x-transition class="mt-1 space-y-1 pr-3">
                        <x-link.responsive-link href="{{route('meeting.create')}}"
                                                :active="request()->is('create/new/meeting')"
                                                class="flex items-center gap-x-2 text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            {{__('ایجاد جلسه جدید')}}
                        </x-link.responsive-link>
                        <x-link.responsive-link wire:navigate href="{{route('my.task.table')}}"
                                                :active="request()->is('my/task/table')"
                                                class="flex items-center gap-x-2 text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.98.626-1.813 1.5-2.122"/>
                            </svg>
                            {{__('اقدامات من')}}
                        </x-link.responsive-link>
                        <x-link.responsive-link wire:navigate href="{{route('dashboard.meeting')}}"
                                                :active="request()->is('dashboard/meeting')"
                                                class="flex items-center gap-x-2 text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75"/>
                            </svg>
                            {{__('جدول جلسات')}}
                        </x-link.responsive-link>
                        @can('has-permission', UserPermission::TASK_REPORT_TABLE)
                            <x-link.responsive-link href="{{route('meeting.report')}}"
                                                    :active="request()->is('meeting/report')"
                                                    class="flex items-center gap-x-2 text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5"
                                     stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6"/>
                                </svg>
                                {{__('گزارش جلسات شرکت')}}
                            </x-link.responsive-link>
                        @endcan
                    </div>
                </div>
            </li>
            <li class="mb-2">
                <div x-data="{ openMessage: false }" class="w-full" wire:poll.visible.60s>
                    <button @click="openMessage = !openMessage"
                            class="w-full flex items-center justify-between px-4 py-2 font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition ease-in-out duration-300 rounded-md relative">
                        <div class="flex items-center gap-2">
                            @if($this->messagesNotification > 0)
                                <div class="relative flex items-center">
                                <span class="relative flex h-3 w-3">
                                    <span
                                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-gray-100 opacity-75"></span>
                                    <span class="relative inline-flex h-3 w-3 rounded-full bg-sky-50"></span>
                                </span>
                                </div>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5"
                                     stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                                </svg>
                            @endif
                            <span>{{__('پیام ها')}}</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': openMessage}"
                             class="h-4 w-4 transition-transform"
                             viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <div x-show="openMessage" x-transition class="mt-1 space-y-1 pr-3">
                        <x-link.responsive-link wire:navigate href="{{route('received.message')}}"
                                                :active="request()->is('received/message')"
                                                class="flex items-center gap-x-2 text-xs">
                            <span class="flex justify-between w-full">
                                <span>
                                    {{__('پیام های دریافتی')}}
                                </span>
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
                            <span class="flex justify-between w-full">
                            <span>{{__('پیام های ارسالی')}}</span></span>
                        </x-link.responsive-link>
                    </div>
                </div>
            </li>
            <li class="mb-2">
                <x-link.responsive-link wire:navigate href="{{route('employee.organization')}}"
                                        :active="request()->is('employee/organization')"
                                        class="flex items-center gap-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m7.875 14.25 1.214 1.942a2.25 2.25 0 0 0 1.908 1.058h2.006c.776 0 1.497-.4 1.908-1.058l1.214-1.942M2.41 9h4.636a2.25 2.25 0 0 1 1.872 1.002l.164.246a2.25 2.25 0 0 0 1.872 1.002h2.092a2.25 2.25 0 0 0 1.872-1.002l.164-.246A2.25 2.25 0 0 1 16.954 9h4.636M2.41 9a2.25 2.25 0 0 0-.16.832V12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 12V9.832c0-.287-.055-.57-.16-.832M2.41 9a2.25 2.25 0 0 1 .382-.632l3.285-3.832a2.25 2.25 0 0 1 1.708-.786h8.43c.657 0 1.281.287 1.709.786l3.284 3.832c.163.19.291.404.382.632M4.5 20.25h15A2.25 2.25 0 0 0 21.75 18v-2.625c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125V18a2.25 2.25 0 0 0 2.25 2.25Z"/>
                    </svg>
                    {{__('سامانه ها')}}
                </x-link.responsive-link>
            </li>
            <li class="mb-2">
                <x-link.responsive-link wire:navigate href="{{route('blogs.index')}}"
                                        :active="request()->is('blogs')" class="flex items-center gap-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z"/>
                    </svg>
                    {{__('اخبار و اطلاعیه')}}
                </x-link.responsive-link>
            </li>
            <li class="mb-2">
                <x-link.responsive-link wire:navigate href="{{route('phone-list.index')}}"
                                        :active="request()->is('phone-list')" class="flex items-center gap-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M14.25 9.75v-4.5m0 4.5h4.5m-4.5 0 6-6m-3 18c-8.284 0-15-6.716-15-15V4.5A2.25 2.25 0 0 1 4.5 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44-.054.902-.417 1.173l-1.293.97a1.062 1.062 0 0 0-.38 1.21 12.035 12.035 0 0 0 7.143 7.143c.441.162.928-.004 1.21-.38l.97-1.293a1.125 1.125 0 0 1 1.173-.417l4.423 1.106c.5.125.852.575.852 1.091V19.5a2.25 2.25 0 0 1-2.25 2.25h-2.25Z"/>
                    </svg>
                    {{__('دفترچه تلفنی')}}
                </x-link.responsive-link>
            </li>
            <li class="mb-2">
                @can('side-bar-notifications')
                    <div x-data="{ openSetting: false }" class="w-full mb-4">
                        <button @click="openSetting = !openSetting"
                                class="w-full flex items-center justify-between px-4 py-2 font-medium text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M4.5 8.25h15m-15 4.5h15m-15 4.5h15"/>
                                </svg>
                                <span>{{ __('تنظیمات راهبری') }}</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': openSetting}"
                                 class="h-4 w-4 transition-transform"
                                 viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div x-show="openSetting" x-transition class="mt-1 space-y-1 pr-3">
                            <x-link.responsive-link href="{{route('users.create')}}"
                                                    :active="request()->is('users/create')"
                                                    class="flex items-center text-sm gap-x-2 text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                                {{__('ساخت کاربر جدید')}}
                            </x-link.responsive-link>
                            <x-link.responsive-link href="{{route('users.index')}}"
                                                    :active="request()->is('users/table')"
                                                    class="flex items-center gap-x-2 text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5"
                                     stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5"/>
                                </svg>
                                {{__('جدول کاربران')}}
                            </x-link.responsive-link>
                            <x-link.responsive-link
                                href="{{route('organization.department.manage')}}"
                                :active="request()->is('department/organization/manage')"
                                class="flex items-center gap-x-2 text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5"
                                     stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/>
                                </svg>
                                {{__('مدیریت سامانه/دپارتمان')}}
                            </x-link.responsive-link>
                            @if(auth()->user()->hasRole(UserRole::SUPER_ADMIN->value))
                                <x-link.responsive-link href="{{route('role.permission.table')}}"
                                                        :active="request()->is('roles/permissions')"
                                                        class="flex items-center gap-x-2 text-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5"
                                         stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M10.05 4.575a1.575 1.575 0 1 0-3.15 0v3m3.15-3v-1.5a1.575 1.575 0 0 1 3.15 0v1.5m-3.15 0 .075 5.925m3.075.75V4.575m0 0a1.575 1.575 0 0 1 3.15 0V15M6.9 7.575a1.575 1.575 0 1 0-3.15 0v8.175a6.75 6.75 0 0 0 6.75 6.75h2.018a5.25 5.25 0 0 0 3.712-1.538l1.732-1.732a5.25 5.25 0 0 0 1.538-3.712l.003-2.024a.668.668 0 0 1 .198-.471 1.575 1.575 0 1 0-2.228-2.228 3.818 3.818 0 0 0-1.12 2.687M6.9 7.575V12m6.27 4.318A4.49 4.49 0 0 1 16.35 15m.002 0h-.002"/>
                                    </svg>
                                    {{__('مدیریت نقش و دسترسی')}}
                                </x-link.responsive-link>
                            @endif
                        </div>
                    </div>
                @endcan
            </li>
        </ul>
    </nav>

    <div class="py-4 px-2 border-t border-gray-500">
        <form action="{{route('logout')}}" method="post">
            @csrf
            <x-cancel-button type="submit" class="inline-flex justify-center items-center gap-1 w-full">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                </svg>
                <span class="text-sm">{{__('خروج')}}</span>
            </x-cancel-button>
        </form>
    </div>
</div>
