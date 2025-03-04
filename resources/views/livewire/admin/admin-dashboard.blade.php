<div>
    {{--        <div wire:ignore class="w-80">--}}
    {{--            <canvas id="myChart"></canvas>--}}
    {{--        </div>--}}
    {{--        <script>--}}
    {{--            Chart.defaults.font.size = 16;--}}
    {{--            Chart.defaults.font.family = 'sans-serif';--}}
    {{--            const ctx = document.getElementById('myChart');--}}
    {{--            new Chart(ctx, {--}}
    {{--                type: 'doughnut',--}}
    {{--                data: {--}}
    {{--                    labels: ['کاربر', 'دپارتمان', 'سامانه'],--}}
    {{--                    datasets: [{--}}
    {{--                        label: 'تعداد',--}}
    {{--                        data: [{{$this->users}}, {{$this->departments}},{{$this->organizations->count()}}],--}}
    {{--                        backgroundColor: [--}}
    {{--                            'rgb(121,71,82)',--}}
    {{--                            'rgb(54, 162, 235)',--}}
    {{--                            'rgb(183,231,13)'--}}
    {{--                        ],--}}
    {{--                        borderWidth: 1,--}}
    {{--                    }]--}}
    {{--                },--}}
    {{--                options: {--}}
    {{--                    scales: {--}}
    {{--                        y: {--}}
    {{--                            beginAtZero: true--}}
    {{--                        }--}}
    {{--                    }--}}
    {{--                }--}}
    {{--            });--}}
    {{--        </script>--}}
    <div class="grid grid-cols-3 gap-4 mt-20">

        <div class="col-span-3 grid grid-cols-1 mb-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow-lg p-6 overflow-hidden relative">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-100 to-blue-300 opacity-20"></div>
                <div class="flex items-center justify-between z-10">
                    <div class="flex items-center space-x-3">
                        <span class="text-lg font-bold  text-gray-700"> {{__('تعداد کاربران')}}</span>
                    </div>
                    <span class="text-3xl font-bold text-blue-600">{{$this->users}}</span>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 overflow-hidden relative">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-100 to-blue-300 opacity-20"></div>
                <div class="flex items-center justify-between z-10">
                    <div class="flex items-center space-x-3">
                        <span class="text-lg font-bold  text-gray-700"> {{__('تعداد سامانه')}}</span>
                    </div>
                    <span class="text-3xl font-bold text-blue-600">{{$this->organizations->count()}}</span>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 overflow-hidden relative">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-100 to-blue-300 opacity-20"></div>
                <div class="flex items-center justify-between z-10">
                    <div class="flex items-center space-x-3">
                        <span class="text-lg font-bold  text-gray-700">{{__('تعداد دپارتمان')}}</span>
                    </div>
                    <span class="text-3xl font-bold text-blue-600">{{$this->departments}}</span>
                </div>
            </div>
        </div>

        <x-notifications/>
    </div>

</div>
