<div>

    <div class="grid lg:grid-cols-2 mt-20">
        <div class="p-2">
            <x-notifications/>
        </div>
        <div class="px-8 pb-6 pt-2 rounded-xl h-[600px] overflow-y-auto space-y-6">
            @if ($this->getTodaysMeeting->isNotEmpty())
                <h1 class="text-2xl font-semibold text-[#4332BD] text-center mb-4">
                    {{ __('لیست جلسات امروز') }}
                </h1>

                <div class="grid gap-5">
                    @foreach ($this->getTodaysMeeting as $meeting)
                        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-gray-200">
                            <div class="space-y-4">
                                <h2 class="text-xl font-semibold text-[#4332BD]">{{ $meeting->title }}</h2>

                                <div class="flex flex-col space-y-2 text-sm text-gray-700">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </div>
                                        <span class="font-medium">{{ $meeting->time }}</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                            </svg>
                                        </div>
                                        <span class="font-mono">{{ $meeting->date }}</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                            </svg>
                                        </div>
                                        <span>{{ $meeting->location }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-gray-200 text-center">
                    <div class="flex flex-col items-center space-y-4 text-gray-500">
                        <div class="w-12 h-12 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium">{{ __('هیچ جلسه‌ای برای امروز ثبت نشده است.') }}</p>
                    </div>
                </div>
            @endif
        </div>


    </div>
    {{--    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">--}}
    {{--        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">--}}

    {{--            <div class="bg-white rounded-lg shadow-md p-6">--}}
    {{--                <select wire:model="currentYearMeeting" id="yearSelectMeeting" dir="ltr"--}}
    {{--                        class="border border-gray-300 w-32 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">--}}
    {{--                    @foreach (array_keys($yearDataMeeting) as $year)--}}
    {{--                        <option value="{{ $year }}">{{ $year }}</option>--}}
    {{--                    @endforeach--}}
    {{--                </select>--}}
    {{--                <select wire:model="currentMonthMeeting" id="monthSelectMeeting" dir="ltr"--}}
    {{--                        class="border border-gray-300 w-40 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">--}}
    {{--                    <option value="0">فروردین - شهریور</option>--}}
    {{--                    <option value="1">مهر - اسفند</option>--}}
    {{--                </select>--}}
    {{--                <div class="flex gap-2 items-center mb-4 mt-4 text-xl font-semibold text-gray-900 dark:text-white">--}}
    {{--                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
    {{--                         stroke="currentColor"--}}
    {{--                         class="size-6">--}}
    {{--                        <path stroke-linecap="round" stroke-linejoin="round"--}}
    {{--                              d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6"/>--}}
    {{--                    </svg>--}}
    {{--                    {{ __('نمودار جلسات') }}--}}
    {{--                </div>--}}
    {{--                <div class="flex justify-between mb-8">--}}
    {{--                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('تعداد کل جلسات :') }} <span--}}
    {{--                            class="font-semibold text-gray-900 dark:text-white"--}}
    {{--                            id="total-meetings-value">{{$this->allMeetings}}</span></div>--}}
    {{--                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('تعداد جلسات لغو شده :') }} <span--}}
    {{--                            class="font-semibold text-gray-900 dark:text-white"--}}
    {{--                            id="cancelled-meetings-value">{{$this->allCancelledMeetings}}</span></div>--}}
    {{--                </div>--}}
    {{--                <div id="column-chart" class="mb-4"></div>--}}
    {{--            </div>--}}
    {{--            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">--}}
    {{--                <div class="grid grid-cols-3 gap-4 mb-6">--}}
    {{--                    <div class="bg-orange-100 dark:bg-orange-900 rounded-lg p-4 text-center">--}}
    {{--                        <span class="text-orange-600 dark:text-orange-300 text-3xl font-semibold"--}}
    {{--                              id="user-count">{{$this->users}}</span>--}}
    {{--                        <p class="text-sm flex items-center justify-center gap-2 text-gray-500 dark:text-gray-400">--}}
    {{--                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
    {{--                                 stroke="currentColor" class="size-5">--}}
    {{--                                <path stroke-linecap="round" stroke-linejoin="round"--}}
    {{--                                      d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>--}}
    {{--                            </svg>--}}
    {{--                            {{__('کاربران')}}--}}
    {{--                        </p>--}}
    {{--                    </div>--}}
    {{--                    <div class="bg-teal-100 dark:bg-teal-900 rounded-lg p-4 text-center">--}}
    {{--                        <span class="text-teal-600 dark:text-teal-300 text-3xl font-semibold"--}}
    {{--                              id="system-count">{{$this->organizations->count()}}</span>--}}
    {{--                        <p class="text-sm flex items-center justify-center gap-2  text-gray-500 dark:text-gray-400">--}}
    {{--                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
    {{--                                 stroke="currentColor" class="size-5">--}}
    {{--                                <path stroke-linecap="round" stroke-linejoin="round"--}}
    {{--                                      d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/>--}}
    {{--                            </svg>--}}
    {{--                            {{__('سامانه')}}--}}
    {{--                        </p>--}}
    {{--                    </div>--}}
    {{--                    <div class="bg-blue-100 dark:bg-blue-900 rounded-lg p-4 text-center">--}}
    {{--                        <span class="text-blue-600 dark:text-blue-300 text-3xl font-semibold"--}}
    {{--                              id="department-count">{{$this->departments}}</span>--}}
    {{--                        <p class="text-sm flex items-center justify-center gap-2 text-gray-500 dark:text-gray-400">--}}
    {{--                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
    {{--                                 stroke="currentColor" class="size-5">--}}
    {{--                                <path stroke-linecap="round" stroke-linejoin="round"--}}
    {{--                                      d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"/>--}}
    {{--                            </svg>--}}
    {{--                            {{__('دپارتمان')}}--}}
    {{--                        </p>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div id="radial-chart"></div>--}}
    {{--            </div>--}}
    {{--        </div>--}}

    {{--        <div class="bg-white dark:bg-gray-800 items-center rounded-lg shadow-md p-6 mt-6 grid grid-cols-1 lg:grid-cols-2 gap-8 place-content-around">--}}
    {{--            <div>--}}
    {{--                <div class="grid grid-cols-1 mb-4 md:grid-cols-2 gap-4 max-w-2xl mx-auto">--}}
    {{--                    <div class="bg-white rounded-lg shadow-lg p-6 overflow-hidden relative">--}}
    {{--                        <div class="absolute inset-0 bg-gradient-to-br from-blue-100 to-blue-300 opacity-20"></div>--}}
    {{--                        <div class="flex items-center justify-between z-10">--}}
    {{--                            <div class="flex items-center space-x-3">--}}
    {{--                                <span class="text-lg font-bold  text-gray-700">{{__('تعداد کل جلسات')}}</span>--}}
    {{--                            </div>--}}
    {{--                            <span class="text-3xl font-bold text-blue-600">{{$this->allMeetings}}</span>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="bg-white rounded-lg shadow-lg p-6 overflow-hidden relative">--}}
    {{--                        <div class="absolute inset-0 bg-gradient-to-br from-green-100 to-green-300 opacity-20"></div>--}}
    {{--                        <div class="flex items-center justify-between z-10">--}}
    {{--                            <div class="flex items-center space-x-3">--}}
    {{--                                <span class="text-lg font-bold  text-gray-700">{{__('تعداد کل اقدامات')}}</span>--}}
    {{--                            </div>--}}
    {{--                            <span class="text-3xl font-bold text-green-600">{{$this->allTasks}}</span>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div class="space-y-4 border rounded-md shadow-md p-4">--}}
    {{--                    <h2 class="text-2xl font-semibold mb-6">{{__('گزارش اقدامات')}}</h2>--}}
    {{--                    <div>--}}
    {{--                        <div class="flex justify-between mb-2">--}}
    {{--                            <span class="text-sm font-medium">{{__('انجام شده در مهلت مقرر')}}</span>--}}
    {{--                            <a href="{{route('completedTasks')}}"--}}
    {{--                               class="cursor-pointer hover:underline hover:underline-offset-2 transition ease-in-out">--}}
    {{--                                {{__('نمایش')}}--}}
    {{--                            </a>--}}
    {{--                        </div>--}}
    {{--                        <div class="w-full bg-gray-200 rounded-full h-2.5">--}}
    {{--                            <div class="bg-[#605C3C] h-2.5 rounded-full"--}}
    {{--                                 style="width:{{$this->tasksOnTimePercentage()}}%;"></div>--}}
    {{--                        </div>--}}
    {{--                        <div class="mt-2 text-right text-xs text-gray-500">({{$this->tasksOnTimePercentage()}}%)</div>--}}
    {{--                    </div>--}}
    {{--                    <div>--}}
    {{--                        <div class="flex justify-between mb-2 mt-2">--}}
    {{--                            <span class="text-sm font-medium">{{__('انجام شده خارج از مهلت مقرر')}}</span>--}}
    {{--                            <a href="{{route('tasksWithDelay')}}"--}}
    {{--                               class="cursor-pointer hover:underline hover:underline-offset-2 transition ease-in-out">--}}
    {{--                                {{__('نمایش')}}--}}
    {{--                            </a>--}}
    {{--                        </div>--}}
    {{--                        <div class="w-full bg-gray-200 rounded-full h-2.5">--}}
    {{--                            <div class="bg-[#1f4037] h-2.5 rounded-full"--}}
    {{--                                 style="width:{{$this->tasksDoneWithDelayPercentage()}}%;"></div>--}}
    {{--                        </div>--}}
    {{--                        <div class="mt-2 text-right text-xs text-gray-500">({{$this->tasksDoneWithDelayPercentage()}}%)--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div>--}}
    {{--                        <div class="flex justify-between mb-2 mt-2">--}}
    {{--                            <span class="text-sm font-medium">{{__('انجام نشده در مهلت مقرر')}}</span>--}}
    {{--                            <a href="{{route('incompleteTasks')}}"--}}
    {{--                               class="cursor-pointer hover:underline hover:underline-offset-2 transition ease-in-out">--}}
    {{--                                {{__('نمایش')}}--}}
    {{--                            </a>--}}
    {{--                        </div>--}}
    {{--                        <div class="w-full bg-gray-200 rounded-full h-2.5">--}}
    {{--                            <div class="bg-[#2C5364] h-2.5 rounded-full"--}}
    {{--                                 style="width:{{$this->tasksNotDonePercentage()}}%;"></div>--}}
    {{--                        </div>--}}
    {{--                        <div class="mt-2 text-right text-xs text-gray-500">({{$this->tasksNotDonePercentage()}}%)</div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}

    {{--            <div>--}}
    {{--                <h2 class="text-xl flex items-center gap-2 font-semibold mb-4 text-gray-900 dark:text-white">--}}
    {{--                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
    {{--                         stroke="currentColor"--}}
    {{--                         class="size-6">--}}
    {{--                        <path stroke-linecap="round" stroke-linejoin="round"--}}
    {{--                              d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6"/>--}}
    {{--                    </svg>--}}
    {{--                    {{__('نمودار اقدامات')}}--}}
    {{--                </h2>--}}
    {{--                <div class="flex items-center justify-between mb-4">--}}
    {{--                    <div class="flex items-center gap-4">--}}
    {{--                        <label for="yearSelect"--}}
    {{--                               class="mr-2 text-sm text-gray-700 dark:text-gray-400">{{__('سال :')}}</label>--}}
    {{--                        <select wire:model="currentYear" id="yearSelect" dir="ltr"--}}
    {{--                                class="border w-32 border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200">--}}
    {{--                            @foreach (array_keys($yearData) as $year)--}}
    {{--                                <option value="{{ $year }}">{{ $year }}</option>--}}
    {{--                            @endforeach--}}
    {{--                        </select>--}}
    {{--                    </div>--}}
    {{--                    <div class="flex items-center gap-4">--}}
    {{--                        <label for="monthSelect"--}}
    {{--                               class="mr-2 text-sm text-gray-700 dark:text-gray-400">{{__('ماه :')}}</label>--}}
    {{--                        <select wire:model="currentMonth" id="monthSelect" dir="ltr"--}}
    {{--                                class="border w-40 border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200">--}}
    {{--                            <option value="0">فروردین - شهریور</option>--}}
    {{--                            <option value="1">مهر - اسفند</option>--}}
    {{--                        </select>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div class="w-full rounded-lg" id="bar-chart"></div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}


    {{--    <script>--}}
    {{--        const yearDataMeeting = @json($yearDataMeeting);--}}
    {{--        let currentYearMeeting = {{ $currentYearMeeting }};--}}
    {{--        let currentMonthMeeting = {{ $currentMonthMeeting }};--}}
    {{--        const users = {{$this->users}};--}}
    {{--        const organizations = {{$this->organizations->count()}};--}}
    {{--        const departments = {{$this->departments}};--}}
    {{--        const yearData = @json($yearData);--}}
    {{--        let currentYear = {{$currentYear}};--}}
    {{--        let currentMonth = {{$currentMonth}};--}}
    {{--    </script>--}}
    {{--    <script src="{{asset('charts.js')}}"></script>--}}


</div>
