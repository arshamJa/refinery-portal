<div>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <select wire:model="currentYearMeeting" id="yearSelectMeeting" dir="ltr"
                        class="border border-gray-300 w-32 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach (array_keys($yearDataMeeting) as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
                <select wire:model="currentMonthMeeting" id="monthSelectMeeting" dir="ltr"
                        class="border border-gray-300 w-40 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="0">فروردین - شهریور</option>
                    <option value="1">مهر - اسفند</option>
                </select>
                <div class="flex gap-2 items-center mb-4 mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor"
                         class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6"/>
                    </svg>
                    {{ __('نمودار جلسات') }}
                </div>
                <div class="flex justify-between mb-8">
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('تعداد کل جلسات :') }} <span
                            class="font-semibold text-gray-900 dark:text-white"
                            id="total-meetings-value">{{$this->allMeetings}}</span></div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('تعداد جلسات لغو شده :') }} <span
                            class="font-semibold text-gray-900 dark:text-white"
                            id="cancelled-meetings-value">{{$this->allCancelledMeetings}}</span></div>
                </div>
                <div id="column-chart" class="mb-4"></div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-orange-100 dark:bg-orange-900 rounded-lg p-4 text-center">
                        <span class="text-orange-600 dark:text-orange-300 text-3xl font-semibold"
                              id="user-count">{{$this->users}}</span>
                        <p class="text-sm flex items-center justify-center gap-2 text-gray-500 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>
                            </svg>
                            {{__('کاربران')}}
                        </p>
                    </div>
                    <div class="bg-teal-100 dark:bg-teal-900 rounded-lg p-4 text-center">
                        <span class="text-teal-600 dark:text-teal-300 text-3xl font-semibold"
                              id="system-count">{{$this->organizations->count()}}</span>
                        <p class="text-sm flex items-center justify-center gap-2  text-gray-500 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/>
                            </svg>
                            {{__('سامانه')}}
                        </p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 rounded-lg p-4 text-center">
                        <span class="text-blue-600 dark:text-blue-300 text-3xl font-semibold"
                              id="department-count">{{$this->departments}}</span>
                        <p class="text-sm flex items-center justify-center gap-2 text-gray-500 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"/>
                            </svg>
                            {{__('دپارتمان')}}
                        </p>
                    </div>
                </div>
                <div id="radial-chart"></div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mt-6">
            <h2 class="text-xl flex items-center gap-2 font-semibold mb-4 text-gray-900 dark:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor"
                     class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6"/>
                </svg>
                {{__('نمودار اقدامات')}}
            </h2>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-4">
                    <label for="yearSelect"
                           class="mr-2 text-sm text-gray-700 dark:text-gray-400">{{__('سال :')}}</label>
                    <select wire:model="currentYear" id="yearSelect" dir="ltr"
                            class="border w-32 border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200">
                        @foreach (array_keys($yearData) as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-4">
                    <label for="monthSelect"
                           class="mr-2 text-sm text-gray-700 dark:text-gray-400">{{__('ماه :')}}</label>
                    <select wire:model="currentMonth" id="monthSelect" dir="ltr"
                            class="border w-40 border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200">
                        <option value="0">فروردین - شهریور</option>
                        <option value="1">مهر - اسفند</option>
                    </select>
                </div>
            </div>
            <div class="w-full rounded-lg" id="bar-chart"></div>
        </div>
    </div>


    <script>
        const yearDataMeeting = @json($yearDataMeeting);
        let currentYearMeeting = {{ $currentYearMeeting }};
        let currentMonthMeeting = {{ $currentMonthMeeting }};
        const users = {{$this->users}};
        const organizations = {{$this->organizations->count()}};
        const departments = {{$this->departments}};
        const yearData = @json($yearData);
        let currentYear = {{$currentYear}};
        let currentMonth = {{$currentMonth}};
    </script>
    <script src="{{asset('charts.js')}}"></script>


</div>
