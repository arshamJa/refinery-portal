<div>

    {{--    <x-template>--}}


    <nav class="flex justify-between mb-4 my-14">
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

    <main class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="grid grid-cols-1 gap-4 h-1/2">
            <div>
                <a href="{{route('tasksFinishedOnTime')}}"
                   class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow duration-300 cursor-pointer flex items-center justify-between">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 ml-2" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="font-semibold">اقدامات انجام شده در مهلت مقرر</span>
                    </div>
                    <div class="flex items-center">
                        <span class="font-bold text-lg ml-1">{{$this->tasksOnTime}}</span>
                        <span class="text-sm text-gray-500">({{ $this->tasksOnTimePercentage() }}%)</span>
                    </div>
                </a>
            </div>

            <div>
                <a href="{{route('tasksNotFinishedOnTime')}}"
                   class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow duration-300 cursor-pointer flex items-center justify-between">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500 ml-2" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold">گزارش اقدامات انجام نشده در مهلت مقرر</span>
                    </div>
                    <div class="flex items-center">
                        <span class="font-bold text-lg ml-1">{{$this->tasksNotDone}}</span>
                        <span class="text-sm text-gray-500">({{ $this->tasksNotDonePercentage() }}%)</span>
                    </div>
                </a>
            </div>

            <div>
                <a href="{{route('tasksDoneWithDelay')}}"
                   class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow duration-300 cursor-pointer flex items-center justify-between">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 ml-2" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span class="font-semibold">گزارش اقدامات انجام شده خارج از مهلت مقرر</span>
                    </div>
                    <div class="flex items-center">
                        <span class="font-bold text-lg ml-1">{{$this->tasksDoneWithDelay}}</span>
                        <span class="text-sm text-gray-500">({{ $this->tasksDoneWithDelayPercentage() }}%)</span>
                    </div>
                </a>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow px-10 pt-6 pb-1">
            <div wire:ignore>
                <canvas id="largeChart"></canvas>
            </div>
        </div>

    </main>


    @script
    <script>
        {{--const tasks =  {{$this->taskOnTime}};--}}
        const tasksOnTime = {{$this->tasksOnTime}};
        const tasksNotDone = {{$this->tasksNotDone}};
        const tasksDoneWithDelay = {{$this->tasksDoneWithDelay}};
        Chart.defaults.font.size = 16;
        Chart.defaults.font.family = 'sans-serif';
        const largeChart = new Chart(document.getElementById('largeChart'), {
            type: 'pie',
            data: {
                labels: ['اقدامات انجام شده در مهلت مقرر', 'اقدامات انجام نشده در مهلت مقرر', 'اقدامات انجام شده خارج از مهلت مقرر'],
                datasets: [{
                    label: 'تعداد',
                    data: [tasksOnTime, tasksNotDone, tasksDoneWithDelay],
                    backgroundColor: [
                        'rgb(121,71,82)',
                        'rgb(54, 162, 235',
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
                }
            }
        });
    </script>
    @endscript
    {{--    </x-template>--}}
</div>
