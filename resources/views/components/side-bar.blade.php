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
<div class="flex items-center border-b border-amber-50 pb-2">
    @if(auth()->user()->profile_photo_path)
        <img class="rounded-full m-2 w-16 h-16 object-cover"
             src="{{ auth()->user()->profilePhoto() }}"
             alt="">
    @endif
    <div class="mr-2 text-sm">
        <p>{{auth()->user()->full_name()}} </p>
        <p> {{__('نقش')}} :
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
        <p>{{__('واحد')}}
            : {{auth()->user()->user_info->department->department_name}}</p>
    </div>
</div>
<ul class="w-full mt-2">
    <li>
        <x-link.responsive-link wire:navigate.hover
                                href="{{Illuminate\Support\Facades\URL::signedRoute('profile')}}"
                                :active="request()->is('profile')"> {{__('پروفایل')}}
        </x-link.responsive-link>
        @if(auth()->user()->role === 'admin')
            <x-link.responsive-link href="{{route('organizations.index')}}"
                                    :active="request()->is('organizations')">{{__('لیست سامانه')}}
            </x-link.responsive-link>
        @else
            <x-link.responsive-link href="{{route('employee.organization')}}"
                                    :active="request()->is('employee/organization')">{{__('لیست سامانه')}}
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
    </li>
</ul>
<div class="absolute bottom-2 left-0 w-full pr-3">
    <ul>
        <li>
            <form action="{{\route('logout')}}" method="post">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-1 w-full text-base font-medium  rounded-md px-4 py-2 text-right text-white  transition ease-in-out duration-200 hover:bg-gray-700">
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
