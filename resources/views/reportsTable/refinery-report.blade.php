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
               <span> {{__('گزارش شرکت')}}</span>
            </span>
            </li>
        </ol>
    </nav>


    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4">
        <!-- Meeting Status Card -->
        <div
            class="w-full bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700 p-4 md:p-6">
            <div class="flex justify-between items-start w-full mb-4">
                <div class="flex-col items-center">
                    <div class="flex items-center">
                        <a href="#">
                            <x-primary-button>
                                {{__('نمایش جدول جلسات')}}
                            </x-primary-button>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Pie Chart -->
            <div id="pie-chart" class="py-6"></div>
        </div>
        <!-- Task Status Card -->
        <div
            class="w-full bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700 p-4 md:p-6">
            <div class="flex justify-between items-start w-full mb-4">
                <div class="flex-col items-center">
                    <div class="flex items-center">
                        <a href="#">
                            <x-primary-button>
                                {{__('نمایش جدول اقدامات')}}
                            </x-primary-button>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Pie Chart -->
            <div id="task-pie-chart" class="py-6"></div>
        </div>
    </div>


    <script>
        // Meetings chart
        document.addEventListener('DOMContentLoaded', function () {
            const meetingPercentages = @json($percentages);
            const MeetingStatus = {
                PENDING: 0,
                IS_CANCELLED: 1,
                IS_NOT_CANCELLED: -1,
                IS_IN_PROGRESS: 2,
                IS_FINISHED: 3
            };
            const meetingLabels = {
                [MeetingStatus.PENDING]: "در حال بررسی",
                [MeetingStatus.IS_CANCELLED]: "لغو شد",
                [MeetingStatus.IS_NOT_CANCELLED]: "برگزار می‌شود",
                [MeetingStatus.IS_IN_PROGRESS]: "در حال برگزاری",
                [MeetingStatus.IS_FINISHED]: "جلسه خاتمه یافت",
            };
            const chartData = Object.keys(MeetingStatus).map(key => {
                const statusValue = MeetingStatus[key].toString();
                return meetingPercentages[statusValue] || 0;
            });
            const chartLabels = Object.keys(MeetingStatus).map(key => meetingLabels[MeetingStatus[key]]);
            const getChartOptions = () => ({
                series: chartData,
                labels: chartLabels,
                colors: ["#1C64F2", "#EF4444", "#10B981", "#F59E0B", "#6366F1"],
                chart: {
                    height: 420,
                    width: "100%",
                    type: "pie",
                },
                stroke: {colors: ["white"]},
                dataLabels: {
                    enabled: true,
                    style: {fontFamily: "Inter, sans-serif"},
                    formatter: val => val.toFixed(1) + "%"
                },
                legend: {position: "bottom", fontFamily: "Inter, sans-serif"},
                plotOptions: {
                    pie: {dataLabels: {offset: -25}, size: "100%"},
                },
            });
            if (document.getElementById("pie-chart") && typeof ApexCharts !== 'undefined') {
                const chart = new ApexCharts(document.getElementById("pie-chart"), getChartOptions());
                chart.render();
            }



            // Tasks chart
            const taskPercentages = @json($taskPercentages);
            const TaskStatus = {
                ON_TIME_DONE: 0,         // انجام شده در مهلت مقرر
                LATE_DONE: 1,            // انجام شده خارج از مهلت مقرر
                ON_TIME_NOT_DONE: 2,     // انجام نشده در مهلت مقرر
                LATE_NOT_DONE: 3         // انجام نشده خارج از مهلت مقرر
            };
            const taskLabels = {
                [TaskStatus.ON_TIME_DONE]: "انجام شده در مهلت مقرر",
                [TaskStatus.LATE_DONE]: "انجام شده خارج از مهلت مقرر",
                [TaskStatus.ON_TIME_NOT_DONE]: "انجام نشده در مهلت مقرر",
                [TaskStatus.LATE_NOT_DONE]: "انجام نشده خارج از مهلت مقرر",
            };
            const taskChartData = Object.keys(TaskStatus).map(key => taskPercentages[TaskStatus[key]] || 0);
            const taskChartLabels = Object.keys(TaskStatus).map(key => taskLabels[TaskStatus[key]]);
            const getTaskChartOptions = () => {
                return {
                    series: taskChartData,
                    labels: taskChartLabels,
                    colors: ["#10B981", "#F59E0B", "#EF4444", "#6B7280"], // green, amber, red, gray
                    chart: {
                        height: 420,
                        width: "100%",
                        type: "pie",
                    },
                    stroke: {
                        colors: ["white"],
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontFamily: "Inter, sans-serif",
                        },
                    },
                    legend: {
                        position: "bottom",
                        fontFamily: "Inter, sans-serif",
                    },
                    plotOptions: {
                        pie: {
                            dataLabels: {
                                offset: -25
                            },
                            size: "100%",
                        },
                    },
                };
            };
            if (document.getElementById("task-pie-chart") && typeof ApexCharts !== 'undefined') {
                const taskChart = new ApexCharts(document.getElementById("task-pie-chart"), getTaskChartOptions());
                taskChart.render();
            }
        });

    </script>


</x-app-layout>
