<div>

    {{--    <x-template>--}}


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

    <div class="grid grid-cols-2 place-content-around place-items-center">
        <div class="w-full">
            <h2 class="text-2xl font-semibold mb-6">{{__('گزارش اقدامات')}}</h2>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium">{{__('انجام شده در مهلت مقرر')}}</span>
                        <a href="{{route('tasksFinishedOnTime')}}"
                           class="cursor-pointer hover:underline hover:underline-offset-2 transition ease-in-out">
                            {{__('نمایش')}}
                        </a>
                    </div>
                    <div class="flex-1 bg-green-300 rounded-l-md h-8 relative">
                        <div class="bg-green-600 h-full rounded-l-md"
                             style="width:{{$this->tasksOnTimePercentage()}}%;"></div>
                        <span
                            class="absolute top-1/2 left-2 transform -translate-y-1/2 text-sm font-medium text-green-800">({{$this->tasksOnTimePercentage()}}%)</span>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-2 mt-2">
                        <span class="text-sm font-medium">{{__('انجام شده خارج از مهلت مقرر')}}</span>
                        <a href="{{route('tasksDoneWithDelay')}}"
                           class="cursor-pointer hover:underline hover:underline-offset-2 transition ease-in-out">
                            {{__('نمایش')}}
                        </a>
                    </div>
                    <div class="flex-1 bg-yellow-300 rounded-l-md h-8 relative">
                        <div class="bg-yellow-600 h-full rounded-l-md"
                             style="width:{{$this->tasksDoneWithDelayPercentage()}}%;"></div>
                        <span
                            class="absolute top-1/2 left-2 transform -translate-y-1/2 text-sm font-medium text-yellow-800">({{$this->tasksDoneWithDelayPercentage()}}%)</span>
                    </div>

                </div>

                <div>
                    <div class="flex justify-between mb-2 mt-2">
                        <span class="text-sm font-medium">{{__('انجام نشده در مهلت مقرر')}}</span>
                        <a href="{{route('tasksNotFinishedOnTime')}}"
                           class="cursor-pointer hover:underline hover:underline-offset-2 transition ease-in-out">
                            {{__('نمایش')}}
                        </a>
                    </div>
                    <div class="flex-1 bg-red-300 rounded-l-md h-8 relative">
                        <div class="bg-red-600 h-full rounded-l-md"
                             style="width:{{$this->tasksNotDonePercentage()}}%;"></div>
                        <span
                            class="absolute top-1/2 left-2 transform -translate-y-1/2 text-sm font-medium text-red-800">({{$this->tasksNotDonePercentage()}}%)</span>
                    </div>

                </div>
            </div>
        </div>
        <div class="w-2/3">
            <div wire:ignore>
                <canvas id="largeChart"></canvas>
            </div>
        </div>
    </div>


    @script
    <script>
        {{--const tasks =  {{$this->taskOnTime}};--}}
        const tasksOnTime = {{$this->tasksOnTime}};
        const tasksNotDone = {{$this->tasksNotDone}};
        const tasksDoneWithDelay = {{$this->tasksDoneWithDelay}};
        Chart.defaults.font.size = 16;
        Chart.defaults.font.family = 'sans-serif';
        const largeChart = new Chart(document.getElementById('largeChart'), {
            type: 'doughnut',
            data: {
                labels: ['اقدامات انجام شده در مهلت مقرر', 'اقدامات انجام نشده در مهلت مقرر', 'اقدامات انجام شده خارج از مهلت مقرر'],
                datasets: [{
                    label: 'تعداد',
                    data: [tasksOnTime, tasksNotDone, tasksDoneWithDelay],
                    backgroundColor: [
                        'rgb(121,71,82)',
                        'rgb(54, 162, 235)',
                        'rgb(183,231,13)'
                    ],
                    borderColor: [
                        'rgba(221,235,157,0.35)',
                        'rgba(87,143,202,0.35)',
                        'rgba(249,110,42,0.35)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            usePointStyle: true, // This enables circular labels
                            pointStyle: 'circle', // Explicitly sets the style to 'circle' (optional, as it's the default)
                        }
                    }
                }
            }
        });
    </script>
    @endscript
    {{--    </x-template>--}}
</div>
