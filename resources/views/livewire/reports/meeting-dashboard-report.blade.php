<div>
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
                 {{__('داشبورد جلسات')}}
             </span>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-2 gap-4 place-content-around">

            <div>
                <div class="grid grid-cols-1 mb-4 md:grid-cols-2 gap-4 max-w-2xl mx-auto">
                    <div class="bg-white rounded-lg shadow-lg p-6 overflow-hidden relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-100 to-blue-300 opacity-20"></div>
                        <div class="flex items-center justify-between z-10">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-bold  text-gray-700">{{__('تعداد کل جلسات')}}</span>
                            </div>
                            <span class="text-3xl font-bold text-blue-600">{{$this->allMeetings}}</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-lg p-6 overflow-hidden relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-green-100 to-green-300 opacity-20"></div>
                        <div class="flex items-center justify-between z-10">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-bold  text-gray-700">{{__('تعداد کل اقدامات')}}</span>
                            </div>
                            <span class="text-3xl font-bold text-green-600">{{$this->allTasks}}</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-4 border rounded-md shadow-md p-4">
                    <h2 class="text-2xl font-semibold mb-6">{{__('گزارش اقدامات')}}</h2>
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm font-medium">{{__('انجام شده در مهلت مقرر')}}</span>
                            <a href="{{route('completedTasks')}}"
                               class="cursor-pointer hover:underline hover:underline-offset-2 transition ease-in-out">
                                {{__('نمایش')}}
                            </a>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-[#605C3C] h-2.5 rounded-full" style="width:{{$this->tasksOnTimePercentage()}}%;"></div>
                        </div>
                        <div class="mt-2 text-right text-xs text-gray-500">({{$this->tasksOnTimePercentage()}}%)</div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-2 mt-2">
                            <span class="text-sm font-medium">{{__('انجام شده خارج از مهلت مقرر')}}</span>
                            <a href="{{route('tasksWithDelay')}}"
                               class="cursor-pointer hover:underline hover:underline-offset-2 transition ease-in-out">
                                {{__('نمایش')}}
                            </a>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-[#1f4037] h-2.5 rounded-full" style="width:{{$this->tasksDoneWithDelayPercentage()}}%;"></div>
                        </div>
                        <div class="mt-2 text-right text-xs text-gray-500">({{$this->tasksDoneWithDelayPercentage()}}%)</div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-2 mt-2">
                            <span class="text-sm font-medium">{{__('انجام نشده در مهلت مقرر')}}</span>
                            <a href="{{route('incompleteTasks')}}"
                               class="cursor-pointer hover:underline hover:underline-offset-2 transition ease-in-out">
                                {{__('نمایش')}}
                            </a>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-[#2C5364] h-2.5 rounded-full"  style="width:{{$this->tasksNotDonePercentage()}}%;"></div>
                        </div>
                        <div class="mt-2 text-right text-xs text-gray-500">({{$this->tasksNotDonePercentage()}}%)</div>
                    </div>
                </div>
            </div>

        <div>
            <select id="yearSelect"  class="mt-4 px-4 py-2 border rounded">
                <option value="1400">1400</option>
                <option value="1401">1401</option>
                <option value="1402" selected>1402</option>
                <option value="1403">1403</option>
                <option value="1404">1404</option>
                <option value="1405">1405</option>
            </select>

            <select id="monthSelect" class="mt-4 px-4 py-2 border rounded">
                <option value="0">فروردین-شهریور</option>
                <option value="1">مهر-اسفند</option>
            </select>

            <div class="w-full rounded-lg shadow-sm px-6 py-4" id="bar-chart"></div>

        </div>

        <script>
            let currentYear = parseInt(document.getElementById("yearSelect").value);
            let currentMonth = parseInt(document.getElementById("monthSelect").value);
            const yearData = {
                "1400": {
                    "done": ["1000", "1200", "1400", "1100", "1300", "1500"],
                    "notDone": ["600", "700", "800", "500", "900", "1000"],
                    "delayed": ["500", "600", "700", "400", "800", "900"]
                },
                "1401": {
                    "done": ["1200", "1400", "1600", "1300", "1500", "1700"],
                    "notDone": ["700", "800", "900", "600", "1000", "1100"],
                    "delayed": ["600", "700", "800", "500", "900", "1000"]
                },
                "1402": {
                    "done": ["1420", "1620", "1820", "1420", "1650", "2120"],
                    "notDone": ["788", "810", "866", "788", "1100", "1200"],
                    "delayed": ["788", "810", "866", "788", "1100", "1200"]
                },
                "1403": {
                    "done": ["1600", "1800", "2000", "1600", "1800", "2200"],
                    "notDone": ["900", "1000", "1100", "800", "1200", "1300"],
                    "delayed": ["800", "900", "1000", "700", "1100", "1200"]
                },
                "1404": {
                    "done": ["1800", "2000", "2200", "1800", "2000", "2400"],
                    "notDone": ["1000", "1100", "1200", "900", "1300", "1400"],
                    "delayed": ["900", "1000", "1100", "800", "1200", "1300"]
                },
                "1405": {
                    "done": ["2000", "2200", "2400", "2000", "2200", "2600"],
                    "notDone": ["1100", "1200", "1300", "1000", "1400", "1500"],
                    "delayed": ["1000", "1100", "1200", "900", "1300", "1400"]
                }
            };
            const options = {
                series: [
                    {
                        name: "اقدامات انجام شده",
                        color: "#31C48D",
                        data: yearData[currentYear].done,
                    },
                    {
                        name: "اقدامات انجام نشده",
                        data: yearData[currentYear].notDone,
                        color: "#F05252",
                    },
                    {
                        name: "اقدامات انجام شده با تاخیر",
                        data: yearData[currentYear].delayed,
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
                    fontSize:"16px",
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
                        formatter: function(value) {
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
                    options.xaxis.categories = generateCategories(currentYear, currentMonth);
                    options.series[0].data = yearData[currentYear].done;
                    options.series[1].data = yearData[currentYear].notDone;
                    options.series[2].data = yearData[currentYear].delayed;

                    chart.updateOptions(options);
                });

                document.getElementById("monthSelect").addEventListener("change", function () {
                    currentMonth = parseInt(this.value);
                    options.xaxis.categories = generateCategories(currentYear, currentMonth);

                    chart.updateOptions(options);
                });
            }
        </script>

    </div>
</div>
