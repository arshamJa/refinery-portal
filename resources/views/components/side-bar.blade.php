<div>
{{--    <button @click="open = !open"--}}
{{--            class="text-gray-50 hover:bg-gray-50 transition ease-in-out duration-300 rounded-md p-1 hover:text-gray-800 focus:outline-none">--}}
{{--        <svg :class="open ? 'hidden' : 'block'"--}}
{{--             x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
{{--             stroke="currentColor" class="size-6 block">--}}
{{--            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>--}}
{{--        </svg>--}}
{{--    </button>--}}
    <div class="flex justify-between px-2 items-center">
        <div class="mr-2 text-sm">
            <p class="text-lg font-semibold mb-1 text-right">{{auth()->user()->full_name()}}</p>
            <p class="text-sm text-gray-400 text-right"> {{__('نقش')}} :
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
            <p class="text-sm text-gray-400 text-right">{{__('واحد')}}
                : {{auth()->user()->user_info->department->department_name}}</p>
        </div>
        <div class="flex justify-center">
            @if(auth()->user()->profile_photo_path)
                <img class="rounded-full m-2 w-16 h-16 object-cover" src="{{ auth()->user()->profilePhoto() }}" alt="">
            @endif
        </div>
    </div>
    <hr class="border-gray-700 my-4 mx-1">
</div>
<ul class="w-full mt-2">
{{--    <li class="space-y-2">--}}
{{--        <x-link.responsive-link href="{{route('dashboard')}}"--}}
{{--                                :active="request()->is('dashboard')">--}}
{{--            {{__('داشبورد')}}--}}
{{--        </x-link.responsive-link>--}}
{{--        @if(auth()->user()->user_info->is_dictionary_allowed)--}}
{{--            @can('view-any')--}}
{{--                <x-link.responsive-link href="{{route('translate')}}"--}}
{{--                                        :active="request()->is('translate')">--}}
{{--                    {{__('دیکشنری')}}--}}
{{--                </x-link.responsive-link>--}}
{{--            @endcan--}}
{{--        @endif--}}
{{--        @if(auth()->user()->user_info->is_blog_allowed)--}}
{{--            <x-link.responsive-link href="{{route('blogs.index')}}"--}}
{{--                                    :active="request()->is('blogs')">{{__('اخبار و اطلاعیه')}}--}}
{{--            </x-link.responsive-link>--}}
{{--        @endif--}}
{{--        @if(auth()->user()->user_info->is_phoneList_allowed)--}}
{{--            <x-link.responsive-link href="{{route('phones.index')}}"--}}
{{--                                    :active="request()->is('phones')">{{__('دفترچه تلفنی')}}--}}
{{--            </x-link.responsive-link>--}}
{{--        @endif--}}
{{--    </li>--}}
    <li class="space-y-2">
        <x-link.responsive-link wire:navigate.hover
                                href="{{Illuminate\Support\Facades\URL::signedRoute('profile')}}"
                                :active="request()->is('profile')" class="flex items-center gap-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z"/>
            </svg>

            {{__('پروفایل')}}
        </x-link.responsive-link>
        @if(auth()->user()->role === 'admin')
            <x-link.responsive-link href="{{route('organizations.index')}}"
                                    :active="request()->is('organizations')" class="flex items-center gap-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                </svg>
                {{__('لیست سامانه')}}
            </x-link.responsive-link>
        @else
            <x-link.responsive-link href="{{route('employee.organization')}}"
                                    :active="request()->is('employee/organization')" class="flex items-center gap-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                </svg>
                {{__('لیست سامانه')}}
            </x-link.responsive-link>
        @endif
        @can('view-user')
            <x-link.responsive-link href="{{route('newUser.index')}}"
                                    :active="request()->is('users/table')" class="flex items-center gap-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5"/>
                </svg>
                {{__('جدول کاربران')}}
            </x-link.responsive-link>
        @endcan
        @can('view-department-organization')
            <x-link.responsive-link
                href="{{Illuminate\Support\Facades\URL::signedRoute('organization.department.manage')}}"
                :active="request()->is('departments/organizations')" class="flex items-center gap-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/>
                </svg>
                {{__('مدیریت سامانه/دپارتمان')}}
            </x-link.responsive-link>
        @endcan
        @can('view-user')
            <x-link.responsive-link
                href="{{Illuminate\Support\Facades\URL::signedRoute('employeeAccess')}}"
                :active="request()->is('employee/access')">
                {{__('جدول دسترسی کاربران')}}
            </x-link.responsive-link>
        @endcan
    </li>
</ul>
<div class="absolute bottom-2 left-0 w-full pr-2">
    <ul>
        <li>
            <form action="{{route('logout')}}" method="post">
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
