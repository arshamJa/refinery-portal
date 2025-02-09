<div>
    <x-sessionMessage name="status"/>
    <x-template>
{{--        <div wire:ignore class="w-80">--}}
{{--            <canvas id="myChart"></canvas>--}}
{{--        </div>--}}
{{--        <script>--}}
{{--            const users = {{$this->users}};--}}
{{--            const departments = {{$this->departments}};--}}
{{--            const organizations =  {{$this->organizations->count()}};--}}
{{--            const ctx = document.getElementById('myChart');--}}
{{--            new Chart(ctx, {--}}
{{--                type: 'doughnut',--}}
{{--                data: {--}}
{{--                    labels: ['کاربر', 'دپارتمان', 'سامانه'],--}}
{{--                    datasets: [{--}}
{{--                        label: 'تعداد',--}}
{{--                        data: [users,departments,organizations],--}}
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

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-4 pb-4 border-b">
            <div class="bg-[#27445D] text-[#FBF5DD] p-4 rounded-lg">
                <h3 class="text-sm font-semibold"> {{__('تعداد کاربران')}}</h3>
                <p class="text-lg font-bold"> {{$this->users}}</p>
            </div>
            <div class="bg-[#BE3144] text-[#FBF5DD] p-4 rounded-lg">
                <h3 class="text-sm font-semibold"> {{__('تعداد سامانه')}}</h3>
                <p class="text-lg font-bold">{{$this->organizations->count()}}</p>
            </div>
            <div class="bg-[#E17564] text-[#FBF5DD] p-4 rounded-lg">
                <h3 class="text-sm font-semibold">{{__('تعداد دپارتمان')}}</h3>
                <p class="text-lg font-bold"> {{$this->departments}}</p>
            </div>
        </div>
        <x-notifications/>

    </x-template>
</div>
