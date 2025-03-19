<div>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex gap-4 mb-4 text-xl font-semibold text-gray-900 dark:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                    </svg>
                    {{__('نمودار جلسات')}}
                </div>
                <div class="flex justify-between mb-12">
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{__('تعداد کل جلسات :')}} <span class="font-semibold text-gray-900 dark:text-white" id="total-meetings-value">0</span></div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{__('تعداد جلسات لغو شده :')}} <span class="font-semibold text-gray-900 dark:text-white" id="cancelled-meetings-value">0</span></div>
                </div>
                <div id="column-chart" class="mb-4"></div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-orange-100 dark:bg-orange-900 rounded-lg p-4 text-center">
                        <span class="text-orange-600 dark:text-orange-300 text-3xl font-semibold" id="user-count">{{$this->users}}</span>
                        <p class="text-sm flex items-center justify-center gap-2 text-gray-500 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                            {{__('کاربران')}}
                        </p>
                    </div>
                    <div class="bg-teal-100 dark:bg-teal-900 rounded-lg p-4 text-center">
                        <span class="text-teal-600 dark:text-teal-300 text-3xl font-semibold" id="system-count">{{$this->organizations->count()}}</span>
                        <p class="text-sm flex items-center justify-center gap-2  text-gray-500 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                            </svg>
                            {{__('سامانه')}}
                        </p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 rounded-lg p-4 text-center">
                        <span class="text-blue-600 dark:text-blue-300 text-3xl font-semibold" id="department-count">{{$this->departments}}</span>
                        <p class="text-sm flex items-center justify-center gap-2 text-gray-500 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                            </svg>
                            {{__('دپارتمان')}}
                        </p>
                    </div>
                </div>
                <div id="radial-chart"></div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mt-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Monthly Overview</h2>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <label for="yearSelect" class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-400">Year:</label>
                    <select wire:model="currentYear" id="yearSelect" dir="ltr"
                            class="border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200">
                        @foreach (array_keys($yearData) as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center">
                    <label for="monthSelect" class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-400">Period:</label>
                    <select wire:model="currentMonth" id="monthSelect" dir="ltr"
                            class="border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200">
                        <option value="0">فروردین - شهریور</option>
                        <option value="1">مهر - اسفند</option>
                    </select>
                </div>
            </div>
            <div class="w-full rounded-lg" id="bar-chart"></div>
        </div>
    </div>


{{--    <div class="max-w-sm w-full bg-white rounded-lg shadow-sm dark:bg-gray-800 p-4 md:p-6">--}}
{{--        <div class="flex justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">--}}
{{--            <div class="flex items-center">--}}
{{--                <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center me-3">--}}
{{--                    <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" aria-hidden="true"--}}
{{--                         xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 19">--}}
{{--                        <path--}}
{{--                            d="M14.5 0A3.987 3.987 0 0 0 11 2.1a4.977 4.977 0 0 1 3.9 5.858A3.989 3.989 0 0 0 14.5 0ZM9 13h2a4 4 0 0 1 4 4v2H5v-2a4 4 0 0 1 4-4Z"/>--}}
{{--                        <path--}}
{{--                            d="M5 19h10v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2ZM5 7a5.008 5.008 0 0 1 4-4.9 3.988 3.988 0 1 0-3.9 5.859A4.974 4.974 0 0 1 5 7Zm5 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm5-1h-.424a5.016 5.016 0 0 1-1.942 2.232A6.007 6.007 0 0 1 17 17h2a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5ZM5.424 9H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h2a6.007 6.007 0 0 1 4.366-5.768A5.016 5.016 0 0 1 5.424 9Z"/>--}}
{{--                    </svg>--}}
{{--                </div>--}}
{{--                <div>--}}
{{--                    <h5 class="leading-none text-2xl font-bold text-gray-900 dark:text-white pb-1">3.4k</h5>--}}
{{--                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Leads generated per week</p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div>--}}
{{--          <span--}}
{{--              class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-green-900 dark:text-green-300">--}}
{{--            <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"--}}
{{--                 viewBox="0 0 10 14">--}}
{{--              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                    d="M5 13V1m0 0L1 5m4-4 4 4"/>--}}
{{--            </svg>--}}
{{--            42.5%--}}
{{--          </span>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="grid grid-cols-2">--}}
{{--            <dl class="flex items-center">--}}
{{--                <dt class="text-gray-500 dark:text-gray-400 text-sm font-normal me-1">Money spent:</dt>--}}
{{--                <dd class="text-gray-900 text-sm dark:text-white font-semibold">$3,232</dd>--}}
{{--            </dl>--}}
{{--            <dl class="flex items-center justify-end">--}}
{{--                <dt class="text-gray-500 dark:text-gray-400 text-sm font-normal me-1">Conversion rate:</dt>--}}
{{--                <dd class="text-gray-900 text-sm dark:text-white font-semibold">1.2%</dd>--}}
{{--            </dl>--}}
{{--        </div>--}}
{{--        <div id="column-chart"></div>--}}
{{--        <div class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">--}}
{{--            <div class="flex justify-between items-center pt-5">--}}
{{--                <!-- Button -->--}}
{{--                <button--}}
{{--                    id="dropdownDefaultButton"--}}
{{--                    data-dropdown-toggle="lastDaysdropdown"--}}
{{--                    data-dropdown-placement="bottom"--}}
{{--                    class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"--}}
{{--                    type="button">--}}
{{--                    Last 7 days--}}
{{--                    <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"--}}
{{--                         viewBox="0 0 10 6">--}}
{{--                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                              d="m1 1 4 4 4-4"/>--}}
{{--                    </svg>--}}
{{--                </button>--}}
{{--                <!-- Dropdown menu -->--}}
{{--                <div id="lastDaysdropdown"--}}
{{--                     class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">--}}
{{--                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">--}}
{{--                        <li>--}}
{{--                            <a href="#"--}}
{{--                               class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Yesterday</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="#"--}}
{{--                               class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Today</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="#"--}}
{{--                               class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last--}}
{{--                                7 days</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="#"--}}
{{--                               class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last--}}
{{--                                30 days</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="#"--}}
{{--                               class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last--}}
{{--                                90 days</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--                <a--}}
{{--                    href="#"--}}
{{--                    class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-blue-600 hover:text-blue-700 dark:hover:text-blue-500  hover:bg-gray-100 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 px-3 py-2">--}}
{{--                    Leads Report--}}
{{--                    <svg class="w-2.5 h-2.5 ms-1.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"--}}
{{--                         fill="none" viewBox="0 0 6 10">--}}
{{--                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                              d="m1 9 4-4-4-4"/>--}}
{{--                    </svg>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}


{{--    <div class="w-full grid grid-cols-2 gap-4 dark:bg-gray-800 py-4">--}}
{{--        <div class="p-2 border rounded-md shadow-md">--}}
{{--            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">--}}
{{--                <div class="grid grid-cols-3 gap-3 mb-2">--}}
{{--                    <dl class="bg-orange-50 dark:bg-gray-600 rounded-lg flex flex-col items-center justify-center h-[78px]">--}}
{{--                        <dt class="w-8 h-8 rounded-full bg-orange-100 dark:bg-gray-500 text-orange-600 dark:text-orange-300 text-sm font-medium flex items-center justify-center mb-1">--}}
{{--                            <span id="user-count">{{$this->users}}</span>--}}
{{--                        </dt>--}}
{{--                        <dd class="text-orange-600 dark:text-orange-300 text-sm font-medium">{{__('کاربر')}}</dd>--}}
{{--                    </dl>--}}
{{--                    <dl class="bg-teal-50 dark:bg-gray-600 rounded-lg flex flex-col items-center justify-center h-[78px]">--}}
{{--                        <dt class="w-8 h-8 rounded-full bg-teal-100 dark:bg-gray-500 text-teal-600 dark:text-teal-300 text-sm font-medium flex items-center justify-center mb-1">--}}
{{--                            <span id="system-count">{{$this->organizations->count()}}</span>--}}
{{--                        </dt>--}}
{{--                        <dd class="text-teal-600 dark:text-teal-300 text-sm font-medium">{{__('سامانه')}}</dd>--}}
{{--                    </dl>--}}
{{--                    <dl class="bg-blue-50 dark:bg-gray-600 rounded-lg flex flex-col items-center justify-center h-[78px]">--}}
{{--                        <dt class="w-8 h-8 rounded-full bg-blue-100 dark:bg-gray-500 text-blue-600 dark:text-blue-300 text-sm font-medium flex items-center justify-center mb-1">--}}
{{--                            <span id="department-count">{{$this->departments}}</span>--}}
{{--                        </dt>--}}
{{--                        <dd class="text-blue-600 dark:text-blue-300 text-sm font-medium">{{__('دپارتمان')}}</dd>--}}
{{--                    </dl>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="py-6" id="radial-chart"></div>--}}
{{--        </div>--}}

{{--        <div class="border p-2 rounded-md shadow-md">--}}
{{--            <select wire:model="currentYear" id="yearSelect" dir="ltr"--}}
{{--                    class="border border-gray-300 rounded-md px-3 w-1/4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">--}}
{{--                @foreach (array_keys($yearData) as $year)--}}
{{--                    <option value="{{ $year }}">{{ $year }}</option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--            <select wire:model="currentMonth" id="monthSelect" dir="ltr"--}}
{{--                    class="border border-gray-300 rounded-md px-3 py-2 w-1/3 focus:outline-none focus:ring-2 focus:ring-blue-500">--}}
{{--                <option value="0">فروردین - شهریور</option>--}}
{{--                <option value="1">مهر - اسفند</option>--}}
{{--            </select>--}}
{{--            <div class="w-full rounded-lg shadow-sm px-6 py-4" id="bar-chart"></div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="max-w-4xl mt-16">
        <x-notifications/>
    </div>


    <script>
        // this is Column-Chart
        const options1 = {
            colors: ["#1A56DB", "#FDBA8C"],
            series: [
                {
                    name: "Organic",
                    color: "#1A56DB",
                    data: [
                        {x: "Mon", y: 231},
                        {x: "Tue", y: 122},
                        {x: "Wed", y: 63},
                        {x: "Thu", y: 421},
                        {x: "Fri", y: 122},
                        {x: "Sat", y: 323},
                        {x: "Sun", y: 111},
                    ],
                },
                {
                    name: "Social media",
                    color: "#FDBA8C",
                    data: [
                        {x: "Mon", y: 232},
                        {x: "Tue", y: 113},
                        {x: "Wed", y: 341},
                        {x: "Thu", y: 224},
                        {x: "Fri", y: 522},
                        {x: "Sat", y: 411},
                        {x: "Sun", y: 243},
                    ],
                },
            ],
            chart: {
                type: "bar",
                height: "320px",
                fontFamily: "Inter, sans-serif",
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "70%",
                    borderRadiusApplication: "end",
                    borderRadius: 8,
                },
            },
            tooltip: {
                shared: true,
                intersect: false,
                style: {
                    fontFamily: "Inter, sans-serif",
                },
            },
            states: {
                hover: {
                    filter: {
                        type: "darken",
                        value: 1,
                    },
                },
            },
            stroke: {
                show: true,
                width: 0,
                colors: ["transparent"],
            },
            grid: {
                show: false,
                strokeDashArray: 4,
                padding: {
                    left: 2,
                    right: 2,
                    top: -14
                },
            },
            dataLabels: {
                enabled: false,
            },
            legend: {
                show: false,
            },
            xaxis: {
                floating: false,
                labels: {
                    show: true,
                    style: {
                        fontFamily: "Inter, sans-serif",
                        cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
                    }
                },
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
            },
            yaxis: {
                show: false,
            },
            fill: {
                opacity: 1,
            },
        }
        if (document.getElementById("column-chart") && typeof ApexCharts !== 'undefined') {
            const chart = new ApexCharts(document.getElementById("column-chart"), options1);
            chart.render();
        }
        // the end of Column-Chart


        // this is the Radial-Chart
        const users = {{$this->users}};
        const organizations = {{$this->organizations->count()}};
        const departments = {{$this->departments}};
        const userCountElement = document.getElementById('user-count');
        const systemCountElement = document.getElementById('system-count');
        const departmentCountElement = document.getElementById('department-count');
        const threshold = 50; // Set your threshold here
        if (parseInt(userCountElement.textContent) > threshold) {
            userCountElement.textContent += '+';
        }
        if (parseInt(systemCountElement.textContent) > threshold) {
            systemCountElement.textContent += '+';
        }
        if (parseInt(departmentCountElement.textContent) > threshold) {
            departmentCountElement.textContent += '+';
        }
        const getChartOptions = () => {
            return {
                series: [users, organizations, departments],
                colors: ["#1C64F2", "#16BDCA", "#FDBA8C"],
                chart: {
                    height: "350px",
                    width: "100%",
                    type: "radialBar",
                    sparkline: {
                        enabled: true,
                    },
                },
                plotOptions: {
                    radialBar: {
                        track: {
                            background: '#E5E7EB',
                        },
                        dataLabels: {
                            show: false,
                        },
                        hollow: {
                            margin: 0,
                            size: "32%",
                        }
                    },
                },
                grid: {
                    show: false,
                    strokeDashArray: 4,
                    padding: {
                        left: 2,
                        right: 2,
                        top: -23,
                        bottom: -20,
                    },
                },
                labels: ["کاربر", "سامانه", "دپارتمان"],
                legend: {
                    show: true,
                    position: "bottom",
                    fontFamily: "Inter, sans-serif",
                },
                tooltip: {
                    enabled: true,
                    x: {
                        show: false,
                    },
                },
                yaxis: {
                    show: false,
                    labels: {
                        formatter: function (value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
        if (document.getElementById("radial-chart") && typeof ApexCharts !== 'undefined') {
            const chart = new ApexCharts(document.querySelector("#radial-chart"), getChartOptions());
            chart.render();
        }
        // the end of Radial-Chart


        // this is the Bar-Chart
        const yearData = @json($yearData);
        let currentYear = {{$currentYear}};
        let currentMonth = {{$currentMonth}};

        function getTasks(year, month, status) {
            if (yearData[year] && yearData[year][status] && yearData[year][status][month]) {
                return yearData[year][status][month];
            }
            return 0;
        }

        function generateYearData(years) {
            const result = {};
            years.forEach(year => {
                result[year] = {
                    done: [],
                    notDone: [],
                    delayed: [],
                };
                let startMonth = currentMonth === 0 ? 1 : 7; // Determine start month
                let endMonth = currentMonth === 0 ? 6 : 12; // Determine end month
                for (let month = startMonth; month <= endMonth; month++) {
                    result[year].done.push(getTasks(year, month, 'done'));
                    result[year].notDone.push(getTasks(year, month, 'notDone'));
                    result[year].delayed.push(getTasks(year, month, 'delayed'));

                }
            });
            return result;
        }

        const years = Object.keys(yearData).map(Number);
        let processedYearData = generateYearData(years);
        const options = {
            series: [
                {
                    name: "اقدامات انجام شده",
                    color: "#31C48D",
                    data: processedYearData[currentYear].done,
                },
                {
                    name: "اقدامات انجام نشده",
                    data: processedYearData[currentYear].notDone,
                    color: "#F05252",
                },
                {
                    name: "اقدامات انجام شده با تاخیر",
                    data: processedYearData[currentYear].delayed,
                    color: "#e11ab2",
                }
            ],
            chart: {
                sparkline: {
                    enabled: false,
                },
                type: "bar",
                width: "100%",
                height: 400,
                toolbar: {
                    show: false,
                }
            },
            fill: {
                opacity: 1,
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    columnWidth: "100%",
                    borderRadiusApplication: "end",
                    borderRadius: 6,
                    dataLabels: {
                        position: "top",
                    },
                },
            },
            legend: {
                show: true,
                position: "bottom",
                fontSize: "16px",
            },
            dataLabels: {
                enabled: false,
            },
            tooltip: {
                shared: true,
                intersect: false,
                formatter: function (value) {
                    return value
                }
            },
            xaxis: {
                labels: {
                    show: true,
                    style: {
                        fontFamily: "Inter, sans-serif",
                        cssClass: 'text-sm font-normal fill-gray-600 dark:fill-gray-400'
                    },
                    formatter: function (value) {
                        return value
                    }
                },
                categories: generateCategories(currentYear, currentMonth),
                axisTicks: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                },
            },
            yaxis: {
                labels: {
                    show: true,
                    style: {
                        fontFamily: "Inter, sans-serif",
                        cssClass: 'text-sm font-normal fill-gray-900 dark:fill-gray-400'
                    },
                    // offsetX: -35
                }
            },
            grid: {
                show: true,
                strokeDashArray: 4,
                padding: {
                    left: 2,
                    right: 2,
                    top: -20
                },
            },
            fill: {
                opacity: 1,
            }
        };

        function generateCategories(year, month) {
            const months1 = ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور"];
            const months2 = ["مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"];

            if (month === 0) {
                return months1;
            } else {
                return months2;
            }
        }

        if (document.getElementById("bar-chart") && typeof ApexCharts !== 'undefined') {
            const chart = new ApexCharts(document.getElementById("bar-chart"), options);
            chart.render();

            document.getElementById("yearSelect").addEventListener("change", function () {
                currentYear = parseInt(this.value);
                processedYearData = generateYearData(years); // Regenerate data
                options.xaxis.categories = generateCategories(currentYear, currentMonth);
                options.series[0].data = processedYearData[currentYear].done;
                options.series[1].data = processedYearData[currentYear].notDone;
                options.series[2].data = processedYearData[currentYear].delayed;

                chart.updateOptions(options);
            });
            document.getElementById("monthSelect").addEventListener("change", function () {
                currentMonth = parseInt(this.value);
                processedYearData = generateYearData(years); // Regenerate data
                options.xaxis.categories = generateCategories(currentYear, currentMonth);
                options.series[0].data = processedYearData[currentYear].done;
                options.series[1].data = processedYearData[currentYear].notDone;
                options.series[2].data = processedYearData[currentYear].delayed;

                chart.updateOptions(options);
            });
        }
        // the end of Bar-Chart


    </script>


</div>
