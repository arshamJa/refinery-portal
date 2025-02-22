{{--@php use App\Models\MeetingUser;use Illuminate\Support\Facades\Route; @endphp--}}
{{--@php use App\Models\Task; @endphp--}}
{{--<div--}}
{{--    class="rounded-xl border-2 shadow-md mt-6 w-full md:w-1/3 space-y-6 sm:mt-8 lg:mt-0 lg:max-w-xs xl:max-w-md bg-gray-50">--}}
{{--    <div class="flex items-center py-2 gap-y-4">--}}
{{--        <div>--}}
{{--            <img class="rounded-full m-2 w-16 h-16 object-cover"--}}
{{--                 src="{{ auth()->user()->profilePhoto() }}"--}}
{{--                 alt="">--}}
{{--        </div>--}}
{{--        <div class="mr-2 text-sm">--}}
{{--            <p>{{auth()->user()->full_name()}} </p>--}}
{{--            <p> {{__('نقش')}} :--}}
{{--                @if(auth()->user()->role === 'admin')--}}
{{--                    {{__('ادمین')}}--}}
{{--                @elseif(auth()->user()->role === 'employee')--}}
{{--                    {{__('کاربر')}}--}}
{{--                @elseif(auth()->user()->role === 'operator_news')--}}
{{--                    {{__('اپراتور اخبار و اطلاعیه')}}--}}
{{--                @elseif(auth()->user()->role === 'operator_phones')--}}
{{--                    {{__('اپراتور دفترچه تلفنی')}}--}}
{{--                @endif--}}
{{--            </p>--}}
{{--            <p>{{__('واحد')}}--}}
{{--                : {{auth()->user()->user_info->department->department_name}}</p>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="overflow-y-auto px-3 h-full">--}}
{{--        <ul>--}}
{{--            <li class="border-b">--}}
{{--                <x-link.responsive-link wire:navigate.hover--}}
{{--                    href="{{Illuminate\Support\Facades\URL::signedRoute('profile')}}"--}}
{{--                                        :active="request()->is('profile')"> {{__('پروفایل')}}--}}
{{--                </x-link.responsive-link>--}}
{{--            </li>--}}
{{--        </ul>--}}
{{--    </div>--}}
{{--    <div class="overflow-y-auto px-3 h-full">--}}
{{--        <ul>--}}
{{--            <li class="space-y-1 pb-1">--}}
{{--                <x-link.responsive-link--}}
{{--                    href="{{Illuminate\Support\Facades\URL::signedRoute('meetings.index')}}"--}}
{{--                    :active="request()->is('dashboard/meetings')">{{__('صورت جلسات')}}--}}
{{--                </x-link.responsive-link>--}}
{{--                <a href="{{route('tasks.index')}}"--}}
{{--                   class="{{( Route::is('tasks.index')) ? 'bg-gray-700 text-white' : 'text-gray-800 hover:bg-gray-700 hover:text-white' }} flex justify-between items-center rounded-md px-3 py-2 text-base font-medium transition ease-in-out duration-200">--}}
{{--                    <span>{{__('لیست وظایف')}}</span>--}}
{{--                    @if(Task::where('user_id',auth()->user()->id)->where('is_completed',0)->count() > 0)--}}
{{--                        <span class="bg-gray-400 text-white py-1 px-2.5 rounded-md">--}}
{{--                            {{Task::where('user_id',auth()->user()->id)->where('is_completed',0)->count()}}--}}
{{--                        </span>--}}
{{--                    @endif--}}
{{--                </a>--}}
{{--                @if(auth()->user()->role === 'admin')--}}
{{--                    <x-link.responsive-link href="{{route('organizations.index')}}"--}}
{{--                                            :active="request()->is('organizations')">{{__('لیست سامانه')}}--}}
{{--                    </x-link.responsive-link>--}}
{{--                @else--}}
{{--                    <x-link.responsive-link href="{{route('employee.organization')}}"--}}
{{--                                            :active="request()->is('employee/organization')">{{__('لیست سامانه')}}--}}
{{--                    </x-link.responsive-link>--}}
{{--                @endif--}}
{{--                @can('view-user')--}}
{{--                    <x-link.responsive-link href="{{route('newUser.index')}}"--}}
{{--                                            :active="request()->is('users/table')">{{__('جدول کاربران')}}--}}
{{--                    </x-link.responsive-link>--}}
{{--                @endcan--}}
{{--                @can('view-department-organization')--}}
{{--                    <x-link.responsive-link--}}
{{--                        href="{{Illuminate\Support\Facades\URL::signedRoute('organization.department.manage')}}"--}}
{{--                        :active="request()->is('departments/organizations')">{{__('مدیریت سامانه/دپارتمان')}}--}}
{{--                    </x-link.responsive-link>--}}
{{--                @endcan--}}
{{--                @can('view-user')--}}
{{--                    <x-link.responsive-link--}}
{{--                        href="{{Illuminate\Support\Facades\URL::signedRoute('employeeAccess')}}"--}}
{{--                        :active="request()->is('employee/access')">{{__('جدول دسترسی کاربران')}}--}}
{{--                    </x-link.responsive-link>--}}
{{--                @endcan--}}
{{--            </li>--}}
{{--            <li class="space-y-1 py-1 my-2 border-t">--}}
{{--                <form action="{{\route('logout')}}" method="post">--}}
{{--                    @csrf--}}
{{--                    <button type="submit"--}}
{{--                            class="inline-flex items-center gap-1 w-full text-base font-medium  rounded-md px-4 py-2 text-gray-700 text-right hover:text-white transition ease-in-out duration-200 hover:bg-gray-800">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
{{--                             stroke="currentColor" class="size-5">--}}
{{--                            <path stroke-linecap="round" stroke-linejoin="round"--}}
{{--                                  d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>--}}
{{--                        </svg>--}}
{{--                        <span>{{__('خروج')}}</span>--}}
{{--                    </button>--}}
{{--                </form>--}}
{{--            </li>--}}
{{--        </ul>--}}
{{--    </div>--}}
{{--</div>--}}
<div>
    <div class="flex justify-center">
        @if(auth()->user()->profile_photo_path)
            <img class="rounded-full m-2 w-16 h-16 object-cover" src="{{ auth()->user()->profilePhoto() }}" alt="">
        @endif
    </div>
    <div class="mr-2 text-sm">
        <p class="text-lg font-semibold mb-1 text-right">{{auth()->user()->full_name()}}</p>
        <p class="text-sm text-gray-400 text-right" > {{__('نقش')}} :
            @if(auth()->user()->role === 'admin')
                {{__('ادمین')}}
            @elseif(auth()->user()->role === 'employee')
                {{__('کاربر')}}
            @elseif(auth()->user()->role === 'operator_news')
                {{__('اپراتور اخبار و اطلاعیه')}}
            @elseif(auth()->user()->role === 'operator_phones')
                {{__('اپراتور دفترچه تلفنی')}}
            @endif
        </p>
        <p class="text-sm text-gray-400 text-right" >{{__('واحد')}}
            : {{auth()->user()->user_info->department->department_name}}</p>
    </div>
    <hr class="border-gray-700 my-4 mx-1">
</div>
<ul class="w-full mt-2">
    <li class="space-y-2">
        <x-link.responsive-link wire:navigate.hover
                                href="{{Illuminate\Support\Facades\URL::signedRoute('profile')}}"
                                :active="request()->is('profile')" class="flex items-center gap-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            {{__('پروفایل')}}
        </x-link.responsive-link>
        @if(auth()->user()->role === 'admin')
            <x-link.responsive-link href="{{route('organizations.index')}}"
                                    :active="request()->is('organizations')" class="flex items-center gap-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                {{__('لیست سامانه')}}
            </x-link.responsive-link>
        @else
            <x-link.responsive-link href="{{route('employee.organization')}}"
                                    :active="request()->is('employee/organization')" class="flex items-center gap-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                {{__('لیست سامانه')}}
            </x-link.responsive-link>
        @endif
        @can('view-user')
            <x-link.responsive-link href="{{route('newUser.index')}}"
                                    :active="request()->is('users/table')">{{__('جدول کاربران')}}
            </x-link.responsive-link>
        @endcan
        @can('view-department-organization')
            <x-link.responsive-link
                href="{{Illuminate\Support\Facades\URL::signedRoute('organization.department.manage')}}"
                :active="request()->is('departments/organizations')">{{__('مدیریت سامانه/دپارتمان')}}
            </x-link.responsive-link>
        @endcan
        @can('view-user')
            <x-link.responsive-link
                href="{{Illuminate\Support\Facades\URL::signedRoute('employeeAccess')}}"
                :active="request()->is('employee/access')">{{__('جدول دسترسی کاربران')}}
            </x-link.responsive-link>
        @endcan


        <x-link.responsive-link
                href="{{route('calender')}}"
                :active="request()->is('calender')" class="flex items-center gap-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z" />
            </svg>
            {{__('تقویم')}}
        </x-link.responsive-link>


    </li>
</ul>
<div class="absolute bottom-2 left-0 w-full">
    <ul>
        <li>
            <form action="{{\route('logout')}}" method="post">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-1 w-full text-base font-medium rounded-md px-4 py-2 text-right text-white transition ease-in-out duration-200 hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                    </svg>
                    <span>{{__('خروج')}}</span>
                </button>
            </form>
        </li>
    </ul>
</div>
