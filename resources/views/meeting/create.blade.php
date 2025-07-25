@php use App\Enums\UserPermission;use App\Enums\UserRole; @endphp
<x-app-layout>

    <nav class="flex justify-between mb-4 mt-16 pb-4">
        <ol class="inline-flex items-center mb-3 space-x-1 text-xs text-neutral-500 [&_.active-breadcrumb]:text-neutral-600 [&_.active-breadcrumb]:font-medium sm:mb-0">
            <li class="flex items-center h-full">
                <a href="{{route('dashboard')}}"
                   class="inline-flex items-center gap-1 px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
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
                <a href="{{route('dashboard.meeting')}}"
                   class="inline-flex items-center gap-1 px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                    <span>{{__('جدول جلسات')}}</span>
                </a>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                 stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            <li>
            <span
                class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
               {{__('ایجاد جلسه جدید')}}
            </span>
            </li>
        </ol>
    </nav>
    @can('has-permission-and-role', [UserPermission::SCRIPTORIUM_PERMISSIONS,UserRole::ADMIN])

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">

            <span
                class="bg-[#FF6F61] ring-2 ring-offset-2 ring-blue-400 text-white shadow-lg flex gap-3 items-center justify-start pointer-events-none p-4 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span class="text-sm font-medium">
                         {{ __('ایجاد جلسه جدید') }}
                         </span>
            </span>

            <a href="{{ route('my.task.table') }}"
               class="bg-[#FCF7F8] hover:ring-2 hover:ring-blue-400 hover:ring-offset-2 text-black shadow-lg flex gap-3 items-center justify-start transition-all duration-300 ease-in-out p-4 rounded-lg">
            <span class="text-sm font-medium">
                {{ __('اقدامات من') }}
            </span>
            </a>
            <a href="{{route('received.message')}}"
               class="bg-[#FCF7F8] hover:ring-2 hover:ring-blue-400 hover:ring-offset-2 text-black shadow-lg flex gap-3 items-center justify-start transition-all duration-300 ease-in-out p-4 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                </svg>
                <h3 class="text-sm font-semibold">  {{__('پیام های دریافتی')}}</h3>
                @if($unreadReceivedCount > 0)
                    <span class="ml-2 bg-[#FF7F50] text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                    {{ $unreadReceivedCount > 10 ? '+10' :  $unreadReceivedCount }}
                </span>
                @endif
            </a>
            <a href="{{route('sent.message')}}"
               class="bg-[#FCF7F8] hover:ring-2 hover:ring-blue-400 hover:ring-offset-2 text-black shadow-lg flex gap-3 items-center justify-start transition-all duration-300 ease-in-out p-4 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                </svg>
                <h3 class="text-sm font-semibold">  {{__('پیام های ارسالی')}}</h3>
            </a>
        </div>
        <form action="{{route('meeting.store')}}" method="post" class="mb-12" enctype="multipart/form-data">
            @csrf
            <div class="p-4 mb-2 sm:p-8 bg-white dark:bg-gray-800 drop-shadow-xl sm:rounded-lg">
                {{--                        Meeting Information--}}
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 border-b pb-2">
                    {{ __('بخش اطلاعات جلسه') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2">
                    {{--                                Meeting Title--}}
                    <div>
                        <x-input-label for="title" :value="__('موضوع جلسه')"/>
                        <x-text-input name="title" id="title"
                                      value="{{old('title')}}" class="block "
                                      type="text" autofocus/>
                        <x-input-error :messages="$errors->get('title')"/>
                    </div>
                    {{--                                Meeting Location--}}
                    <div>
                        <x-input-label for="location" :value="__('محل برگزاری جلسه')"/>
                        <x-text-input name="location" id="location"
                                      value="{{old('location')}}"
                                      class="block " type="text" autofocus/>
                        <x-input-error :messages="$errors->get('location')"/>
                    </div>
                    {{--                                Treating--}}
                    <div>
                        <x-input-label for="treat" :value="__('پذیرایی')"/>
                        <label for="yes">
                            {{ __('بلی') }}
                            <input type="radio" name="treat"
                                   value="true" {{ old('treat') === 'true' ? 'checked' : '' }}>
                        </label>
                        <label for="no" class="mr-3">
                            {{ __('خیر') }}
                            <input type="radio" name="treat"
                                   value="false" {{ old('treat') === 'false' ? 'checked' : '' }}>
                        </label>
                        <x-input-error :messages="$errors->get('treat')"/>
                    </div>
                </div>
                {{--                        Time & Date--}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2 ">
                    <div>
                        <x-input-label for="year" :value="__('سال')"/>
                        <x-select-input name="year" id="year" dir="ltr">
                            <option value="">...سال</option>
                            @for($i = 1404; $i <= 1430; $i++)
                                <option value="{{$i}}" @if(old('year', $year ?? '') == $i) selected @endif>
                                    {{$i}}
                                </option>
                            @endfor
                        </x-select-input>
                        <x-input-error :messages="$errors->get('year')"/>
                    </div>
                    <div>
                        @php
                            $persian_months = ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور","مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"];
                        @endphp
                        <x-input-label for="month" :value="__('ماه')"/>
                        <x-select-input name="month" id="month" dir="ltr">
                            <option value="">...ماه</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" @if(old('month', $month ?? '') == $i) selected @endif>
                                    {{ $persian_months[$i - 1] }}
                                </option>
                            @endfor
                        </x-select-input>
                        <x-input-error :messages="$errors->get('month')"/>
                    </div>
                    <div>
                        <x-input-label for="day" :value="__('روز')"/>
                        <x-select-input name="day" id="day" dir="ltr">
                            <option value="">...روز</option>
                            @for($i = 1; $i <= 31; $i++)
                                <option value="{{$i}}" @if(old('day', $day ?? '') == $i) selected @endif>
                                    {{$i}}
                                </option>
                            @endfor
                        </x-select-input>
                        <x-input-error :messages="$errors->get('day')"/>
                    </div>
                    <div>
                        <x-input-label for="time" :value="__('ساعت جلسه')"/>
                        <x-text-input name="time" id="time"
                                      value="{{old('time')}}"
                                      class="block" type="text" placeholder="{{__('00:00')}}"
                                      autofocus/>
                        <x-input-error :messages="$errors->get('time')"/>
                    </div>
                </div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 mt-4 border-b pb-2">
                    {{ __('بخش دبیرجلسه') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2">
                    {{-- Boss Dropdown --}}
                    <div id="boss_dropdown" data-users='@json($participants)' class="relative w-full col-span-2"
                         style="direction: rtl;">
                        <x-input-label class="mb-1.5" :value="__('رئیس جلسه')"/>
                        <button id="boss-dropdown-btn" type="button"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-right text-gray-800 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center">
                            <span id="boss-selected-text" class="truncate">...</span>
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="boss-dropdown-menu"
                             class="hidden absolute mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-lg z-10">
                            <div class="px-4 py-2">
                                <input id="boss-dropdown-search" type="text" placeholder="جست و جو"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                            </div>
                            <ul id="boss-dropdown-list" class="max-h-48 overflow-auto"></ul>
                            <div id="boss-no-result" class="px-4 py-2 text-gray-500 hidden">موردی یافت نشد</div>
                        </div>
                        <input type="hidden" name="boss" id="boss-hidden-input" value="{{ old('boss') }}">
                        <x-input-error :messages="$errors->get('boss')" class="mt-2"/>
                    </div>
                    {{--                --}}{{-- Scriptorium Dropdown --}}
                    {{--                @php--}}
                    {{--                    $authUserId = auth()->id();--}}
                    {{--                    $authUserInfo = $users->firstWhere('user_id', $authUserId);--}}
                    {{--                @endphp--}}
                    {{--                <div id="scriptorium_dropdown" data-users='@json($users)' class="relative w-full col-span-2"--}}
                    {{--                     style="direction: rtl;">--}}
                    {{--                    <x-input-label class="mb-1.5" :value="__('دبیر جلسه')"/>--}}
                    {{--                    <button id="scriptorium-dropdown-btn" type="button"--}}
                    {{--                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-right text-gray-800 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center">--}}
                    {{--                    <span id="scriptorium-selected-text" class="truncate">--}}
                    {{--                        @if ($authUserInfo)--}}
                    {{--                            <div>--}}
                    {{--                                <div><strong>{{ $authUserInfo['full_name'] }}</strong></div>--}}
                    {{--                                <div class="text-xs text-gray-500">--}}
                    {{--                                    {{ $authUserInfo['department_name'] ?? '' }} - {{ $authUserInfo['position'] ?? '' }}--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}
                    {{--                        @else--}}
                    {{--                            انتخاب کنید--}}
                    {{--                        @endif--}}
                    {{--                    </span>--}}
                    {{--                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"--}}
                    {{--                             viewBox="0 0 24 24">--}}
                    {{--                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>--}}
                    {{--                        </svg>--}}
                    {{--                    </button>--}}
                    {{--                    <div id="scriptorium-dropdown-menu"--}}
                    {{--                         class="hidden absolute mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-lg z-10">--}}
                    {{--                        <div class="px-4 py-2">--}}
                    {{--                            <input id="scriptorium-dropdown-search" type="text" placeholder="جست و جو"--}}
                    {{--                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"/>--}}
                    {{--                        </div>--}}
                    {{--                        <ul id="scriptorium-dropdown-list" class="max-h-48 overflow-auto"></ul>--}}
                    {{--                        <div id="scriptorium-no-result" class="px-4 py-2 text-gray-500 hidden">موردی یافت نشد</div>--}}
                    {{--                    </div>--}}
                    {{--                    --}}{{-- Hidden Inputs --}}
                    {{--                    <input type="hidden" name="scriptorium" id="scriptorium-hidden-input"--}}
                    {{--                           value="{{ old('scriptorium', $authUserInfo['user_id'] ?? '') }}">--}}
                    {{--                    <x-input-error :messages="$errors->get('scriptorium')" class="mt-2"/>--}}
                    {{--                </div>--}}
                    @php
                        $authUserId = auth()->id();
                        $authUserInfo = $users->firstWhere('user_id', $authUserId);
                    @endphp

                    <div id="scriptorium_display" class="relative w-full col-span-2" style="direction: rtl;">
                        <x-input-label class="mb-1.5" :value="__('دبیر جلسه')"/>
                        <div
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-right text-gray-800 bg-gray-50 flex justify-between items-center cursor-default">
                        <span id="scriptorium-selected-text" class="truncate w-full">
                            @if ($authUserInfo)
                                <div>
                                    <div><strong>{{ $authUserInfo['full_name'] }}</strong></div>
                                    <div class="text-xs text-gray-500">
                                        {{ $authUserInfo['department_name'] ?? '' }} - {{ $authUserInfo['position'] ?? '' }}
                                    </div>
                                </div>
                            @else
                                <div class="text-gray-500">نامشخص</div>
                            @endif
                        </span>
                        </div>
                    </div>

                    <div>
                        <x-input-label for="unit_held" :value="__('کمیته یا واحد برگزار کننده جلسه')"/>
                        <x-text-input name="unit_held" id="unit_held" value="{{old('unit_held')}}"
                                      class="block" type="text" autofocus/>
                        <x-input-error :messages="$errors->get('unit_held')"/>
                    </div>
                </div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 mt-4 border-b pb-2">
                    {{ __('بخش اعضا و مهمان') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2 mb-2">

                    {{-- Participants Dropdown (multi-select) --}}
                    <div id="participants_dropdown" data-users='@json($participants)' class="relative w-full col-span-2"
                         style="direction: rtl;">
                        <x-input-label class="mb-1.5" :value="__('شرکت‌کنندگان')"/>
                        <button id="participants-dropdown-btn" type="button"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-right text-gray-800 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center">
                            <span id="participants-selected-text" class="truncate">انتخاب شرکت‌کنندگان</span>
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="participants-dropdown-menu"
                             class="hidden absolute mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-lg z-10">
                            <div class="px-4 py-2">
                                <input id="participants-dropdown-search" type="text" placeholder="جست و جو"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                            </div>
                            <ul id="participants-dropdown-list" class="max-h-48 overflow-auto"></ul>
                            <div id="participants-no-result" class="px-4 py-2 text-gray-500 hidden">موردی یافت نشد</div>
                        </div>
                        <div id="participants-selected-container" class="mt-2 flex flex-wrap gap-2"></div>
                        <input type="hidden" name="holders" id="participants-hidden-input"
                               value='{{ json_encode(explode(",", old("holders", ""))) }}'>
                        <x-input-error :messages="$errors->get('holders')" class="mt-2"/>
                    </div>
                    {{-- innerGuest Dropdown (multi-select) --}}
                    <div id="innerGuest_dropdown" data-users='@json($participants)' class="relative w-full col-span-2"
                         style="direction: rtl;">
                        <x-input-label class="mb-1.5" :value="__('مهمانان داخلی')"/>
                        <button id="innerGuest-dropdown-btn" type="button"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-right text-gray-800 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center">
                            <span id="innerGuest-selected-text" class="truncate">انتخاب مهمانان داخلی</span>
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="innerGuest-dropdown-menu"
                             class="hidden absolute mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-lg z-10">
                            <div class="px-4 py-2">
                                <input id="innerGuest-dropdown-search" type="text" placeholder="جست و جو"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                            </div>
                            <ul id="innerGuest-dropdown-list" class="max-h-48 overflow-auto"></ul>
                            <div id="innerGuest-no-result" class="px-4 py-2 text-gray-500 hidden">موردی یافت نشد</div>
                        </div>
                        <div id="innerGuest-selected-container" class="mt-2 flex flex-wrap gap-2"></div>
                        <input type="hidden" name="innerGuest" id="innerGuest-hidden-input"
                               value='{{ json_encode(explode(",", old("innerGuest", ""))) }}'>
                        <x-input-error :messages="$errors->get('innerGuest')" class="mt-2"/>
                    </div>

                    {{--                              Guests --}}
                    <div class="col-span-2">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 mt-4 pb-2">
                            {{ __('لیست مهمانان برون سازمانی') }}
                        </h3>
                        {{--                                 Hidden data element for JS --}}
                        <div id="guests-data" data-outer-guests="{{ json_encode(old('guests.outer', [])) }}"></div>
                        <div id="outer-organization-table"
                             class="overflow-x-auto rounded-lg border border-gray-300 shadow mb-4">
                            <table id="guests-outer-table"
                                   class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-right">#</th>
                                    <th class="px-4 py-2 text-right">نام و نام خانوادگی</th>
                                    <th class="px-4 py-2 text-right">نام شرکت</th>
                                    <th class="px-4 py-2 text-center">عملیات</th>
                                </tr>
                                </thead>
                                <tbody id="guests-outer-tbody" class="divide-y divide-gray-100">
                                <!-- Dynamic rows for outer organization will be inserted here -->
                                </tbody>
                                <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-left">
                                        <button type="button" id="add-outer-guest-btn"
                                                class="inline-flex items-center px-4 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                            افزودن مهمان
                                        </button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-span-4 mt-12">
                        <x-primary-button type="submit" class="ml-2">
                            {{ __('ارسال') }}
                        </x-primary-button>
                        <a href="{{route('dashboard.meeting')}}">
                            <x-cancel-button>
                                {{__('انصراف')}}
                            </x-cancel-button>
                        </a>
                    </div>
                </div>
            </div>
        </form>

    @endcan

</x-app-layout>

