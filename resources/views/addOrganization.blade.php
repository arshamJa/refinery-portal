<x-app-layout>
    <nav class="flex justify-between mb-4 mt-20">
        <ol class="inline-flex items-center mb-3 space-x-1 text-xs text-neutral-500 [&_.active-breadcrumb]:text-neutral-600 [&_.active-breadcrumb]:font-medium sm:mb-0">
            <li class="flex items-center h-full">
                <a href="{{route('dashboard')}}"
                   class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.6986 3.68267C12.7492 2.77246 11.2512 2.77244 10.3018 3.68263L4.20402 9.52838C3.43486 10.2658 3 11.2852 3 12.3507V19C3 20.1046 3.89543 21 5 21H8.04559C8.59787 21 9.04559 20.5523 9.04559 20V13.4547C9.04559 13.2034 9.24925 13 9.5 13H14.5456C14.7963 13 15 13.2034 15 13.4547V20C15 20.5523 15.4477 21 16 21H19C20.1046 21 21 20.1046 21 19V12.3507C21 11.2851 20.5652 10.2658 19.796 9.52838L13.6986 3.68267Z"
                            fill="currentColor"></path>
                    </svg>
                    <span>{{__('داشبورد')}}</span>
                </a>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                 stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            <li>
            <span
                class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                {{__('تنظیمات راهبری')}}
            </span>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                 stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            <li>
                <a href="{{route('organization.department.manage')}}"
                   class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5"
                         stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/>
                    </svg>
                    <span>  {{__('مدیریت دپارتمان/سامانه')}}</span>
                </a>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                 stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            <li>
            <span
                class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                {{__('افزودن سامانه')}}
            </span>
            </li>
        </ol>
    </nav>


{{--    <div class="p-4 mb-2 sm:p-8 bg-white dark:bg-gray-800 drop-shadow-xl sm:rounded-lg">--}}
{{--        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 border-b pb-2">--}}
{{--            {{ __('افزودن سامانه برای: ') }}--}}
{{--        </h2>--}}
{{--        <form action="{{route('addOrganization.store',$id)}}" method="post"--}}
{{--              enctype="multipart/form-data">--}}
{{--            @csrf--}}
{{--            <div id="organizations_dropdown" data-users='@json($organizations)'--}}
{{--                 class="relative w-full mt-2 mb-4" style="direction: rtl;">--}}
{{--                <x-input-label class="mb-2" :value="__('سامانه')"/>--}}
{{--                <button id="organizations-dropdown-btn" type="button"--}}
{{--                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-right text-gray-800 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center">--}}
{{--                    <span id="organizations-selected-text" class="truncate">انتخاب سامانه‌ها</span>--}}
{{--                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"--}}
{{--                         viewBox="0 0 24 24">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>--}}
{{--                    </svg>--}}
{{--                </button>--}}
{{--                <div id="organizations-dropdown-menu"--}}
{{--                     class="hidden absolute mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-lg z-10">--}}
{{--                    <div class="px-4 py-2">--}}
{{--                        <input id="organizations-dropdown-search" type="text" placeholder="جست و جو"--}}
{{--                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"/>--}}
{{--                    </div>--}}
{{--                    <ul id="organizations-dropdown-list" class="max-h-48 overflow-auto"></ul>--}}
{{--                    <div id="organizations-no-result" class="px-4 py-2 text-gray-500 hidden">موردی یافت نشد--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div id="organizations-selected-container" class="mt-2 flex flex-wrap gap-2"></div>--}}
{{--                <input type="hidden" name="organization_ids" id="organizations-hidden-input"--}}
{{--                       value='{{ json_encode(old("organization_ids") ?? []) }}'>--}}
{{--                <x-input-error :messages="$errors->get('organization_ids')" class="mt-2"/>--}}
{{--            </div>--}}


{{--            <x-primary-button type="submit" class="mt-6 ml-2">--}}
{{--                {{ __('ذخیره') }}--}}
{{--            </x-primary-button>--}}
{{--            <x-secondary-button>--}}
{{--                <a href="{{route('organization.department.manage')}}" class="flex">--}}
{{--                    {{__('بازگشت')}}--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"--}}
{{--                         stroke-width="1.5"--}}
{{--                         stroke="currentColor" class="size-4 mr-1">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round"--}}
{{--                              d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/>--}}
{{--                    </svg>--}}
{{--                </a>--}}
{{--            </x-secondary-button>--}}
{{--        </form>--}}

{{--        <div class="p-4 mb-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">--}}
{{--            <div class="max-w-xl">--}}
{{--                <section>--}}
{{--                    <header>--}}
{{--                        <h2 class="text-lg mb-4 font-medium text-gray-900 dark:text-gray-100">--}}
{{--                            {{ __('لیست سامانه های در دسترس کاربر') }}--}}
{{--                        </h2>--}}
{{--                    </header>--}}
{{--                    <div>--}}
{{--                        @foreach($users as $user)--}}
{{--                            <div class="my-1">--}}
{{--                                @foreach($user->organizations as $org)--}}
{{--                                    {{$org->organization_name}}--}}
{{--                                    <form action="{{route('addOrganization.delete',--}}
{{--                                                [ 'id' => $user->id , 'organizations' => $org->id ])}}" method="post">--}}
{{--                                        @csrf--}}
{{--                                        @method('DELETE')--}}
{{--                                        <x-danger-button type="submit">{{__('حذف')}}</x-danger-button>--}}
{{--                                    </form>--}}
{{--                                    <br>--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                </section>--}}

{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}


</x-app-layout>
