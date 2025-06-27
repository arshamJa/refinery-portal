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
                 {{__('گزارش جلسات شرکت')}}
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

            <div class=" w-full bg-white rounded-lg shadow-sm dark:bg-gray-800">
                <!-- Line Chart -->
                <div class="py-6" id="pie-chart"></div>
            </div>

            @script
            <script>
                const getChartOptions = () => {
                    const tasksOnTime = {{$this->tasksOnTime()}};
                    const tasksWithDelay = {{$this->tasksDoneWithDelay()}};
                    const tasksNotDoneOnTime = {{$this->tasksNotDoneOnTime()}};
                    const tasksNotDoneWithDelay = {{$this->tasksNotDoneWithDelay()}};

                    return {
                        series: [tasksOnTime, tasksWithDelay, tasksNotDoneOnTime, tasksNotDoneWithDelay],
                        colors: ["#605C3C", "#1f4037", "#2C5364", "#B22222"],
                        chart: {
                            height: 420,
                            width: "100%",
                            type: "pie",
                            events: {
                                dataPointSelection: function (event, chartContext, config) {
                                    const routes = [
                                        "{{ route('completedTasks') }}",
                                        "{{ route('tasksWithDelay') }}",
                                        "{{ route('incompleteTasksOnTime') }}",
                                        "{{ route('incompleteTasksWithDelay') }}"
                                    ];
                                    window.location.href = routes[config.dataPointIndex];
                                }
                            }
                        },
                        stroke: {
                            colors: ["white"],
                        },
                        plotOptions: {
                            pie: {
                                size: "100%",
                                dataLabels: {
                                    offset: -25
                                }
                            },
                        },
                        labels: [
                            "{{ __('انجام شده در مهلت مقرر') }}",
                            "{{ __('انجام شده خارج از مهلت مقرر') }}",
                            "{{ __('انجام نشده در مهلت مقرر') }}",
                            "{{ __('انجام نشده خارج از مهلت مقرر') }}"
                        ],
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontFamily: "Inter, sans-serif",
                            },
                            formatter: function (val, opts) {
                                return opts.w.config.series[opts.seriesIndex];
                            }
                        },
                        legend: {
                            position: "bottom",
                            fontFamily: "Inter, sans-serif",
                        }
                    };
                };

                // ✅ Render chart here
                if (document.getElementById("pie-chart") && typeof ApexCharts !== 'undefined') {
                    const chart = new ApexCharts(document.getElementById("pie-chart"), getChartOptions());
                    chart.render();
                }
            </script>
            @endscript

        </div>
        <div>
            <select wire:model="currentYear" id="yearSelect" dir="ltr"
                    class="border border-gray-300 rounded-md px-3 w-1/4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach (array_keys($yearData) as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
            <select wire:model="currentMonth" id="monthSelect" dir="ltr"
                    class="border border-gray-300 rounded-md px-3 py-2 w-1/3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="0">فروردین - شهریور</option>
                <option value="1">مهر - اسفند</option>
            </select>

            <div class="w-full rounded-lg shadow-sm px-6 py-4" id="bar-chart"></div>

        </div>
        @script
        <script>
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
        </script>
        @endscript


    </div>
</div>
