@php use App\Enums\UserPermission; @endphp
@php use App\Enums\UserRole; @endphp
<div>
    <nav class="flex justify-between mb-4 mt-20">
        <ol class="inline-flex items-center mb-3 space-x-1 text-xs text-neutral-500 [&_.active-breadcrumb]:text-neutral-600 [&_.active-breadcrumb]:font-medium sm:mb-0">
            <li class="flex items-center h-full">
                <a href="{{route('dashboard')}}"
                   class="inline-flex items-center gap-1 px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
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
                class="inline-flex items-center gap-1 px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6"/>
                                </svg>
                <span> {{__('گزارش اقدامات شرکت')}}</span>
            </span>
            </li>
        </ol>
    </nav>
    @can('has-permission-and-role', [UserPermission::TASK_REPORT_TABLE,UserRole::ADMIN])
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('meeting.report.table') }}"
               class="bg-[#FCF7F8] hover:ring-2 hover:ring-blue-400 hover:ring-offset-2 text-black flex gap-3 items-center justify-start shadow-lg rounded-lg p-4 transition-all duration-300 ease-in-out">
            <span class="text-sm font-medium">
                {{ __('گزارش جلسات شرکت') }}
            </span>
            </a>
            <a href="{{ route('task.report.table') }}"
               class="bg-[#FF6F61] ring-2 ring-offset-2 ring-blue-400 text-white pointer-events-none flex gap-3 items-center justify-start shadow-lg rounded-lg p-4 transition-all duration-300 ease-in-out">
            <span class="text-sm font-medium">
               {{ __('گزارش اقدامات شرکت') }}
            </span>
            </a>
        </div>


        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <div
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg p-6 col-span-1">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">
                    {{ __('نمودار وضعیت اقدامات') }}
                </h3>
                @if ($statusFilter !== null)
                    <a href="{{ route('task.report.table') }}">
                        <x-primary-button>
                            {{__('نمایش همه وضعیت')}}
                        </x-primary-button>
                    </a>
                @endif

                <div class="pr-12 mt-2" wire:ignore>
                    <div class="w-48 h-48" id="pie-chart"></div>
                </div>
                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                    @php
                        $statuses = [
                            ['color' => 'bg-green-500', 'text' => ' text-green-500' , 'label' => 'اقدامات انجام شده در مهلت مقرر'],
                            ['color' => 'bg-blue-500 ','text' => ' text-blue-500','label' => 'اقدامات انجام نشده در مهلت مقرر'],
                            ['color' => 'bg-yellow-500', 'text' => ' text-yellow-500','label' => 'اقدامات انجام شده خارج مهلت مقرر'],
                            ['color' => 'bg-red-600 ', 'text' => ' text-red-600' ,'label' => 'اقدامات انجام نشده خارج مهلت مقرر'],
                        ];
                    @endphp
                    @foreach ($statuses as $status)
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full {{ $status['color'] }}"></span>
                            <span class="{{$status['text']}}">{{ $status['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div
                class="col-span-1 lg:col-span-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg p-4">
                <form wire:submit="filterTasks">
                    <div class="grid gap-4 px-3 sm:px-0 lg:grid-cols-6 items-end mb-2">
                        <div class="col-span-6 sm:col-span-3 lg:col-span-3">
                            <!-- Search Input -->
                            <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                                <x-input-label for="search" value="{{ __('جست و جو') }}"/>
                                <x-search-input>
                                    <x-text-input type="text" id="search" wire:model="search" class="block ps-10"
                                                  placeholder="{{ __('عبارت مورد نظر را وارد کنید...') }}"/>
                                </x-search-input>
                            </div>
                        </div>
                        <!-- Dates Input -->
                        <div class="col-span-6 sm:col-span-3 lg:col-span-3">
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="flex-1">
                                    <x-input-label for="start_date" value="{{ __('تاریخ شروع') }}"/>
                                    <x-date-input>
                                        <x-text-input id="start_date" wire:model="start_date" class="persian-date block ps-10"/>
                                    </x-date-input>
                                </div>
                                <div class="flex-1">
                                    <x-input-label for="end_date" value="{{ __('تاریخ پایان') }}"/>
                                    <x-date-input>
                                        <x-text-input id="end_date" wire:model="end_date" class="persian-date block ps-10"/>
                                    </x-date-input>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-6 lg:col-span-3 flex justify-start flex-row gap-4 mt-4 lg:mt-0">
                            <x-search-button>{{ __('جست و جو') }}</x-search-button>
                            @if($search || $start_date)
                                <x-view-all-link href="{{route('task.report.table')}}">
                                    {{ __('نمایش همه') }}
                                </x-view-all-link>
                            @endif
                        </div>
                        <!-- Export Button -->
                        <div class="col-span-6 lg:col-start-5 lg:col-span-3 flex justify-start lg:justify-end mt-2">
                            <x-export-link wire:click.prevent="exportExcel" wire:loading.attr="disabled"
                                           class="relative">
                                {{-- Spinner while loading --}}
                                <svg wire:loading wire:target="exportExcel"
                                     class="animate-spin h-5 w-5 mr-2 text-white"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                                </svg>
                                {{-- Button Text --}}
                                <span wire:loading.remove wire:target="exportExcel">
                            {{ __('خروجی Excel') }}
                        </span>
                                <span wire:loading wire:target="exportExcel">
                            {{ __('در حال دریافت...') }}
                        </span>
                            </x-export-link>
                        </div>
                    </div>
                </form>


                <div class="overflow-x-auto shadow-md sm:rounded-lg mt-4">
                    <x-table.table>
                        <x-slot name="head">
                            <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                                @foreach (['#', 'موضوع جلسه و دبیر جلسه', 'اقدام کننده',  'تاریخ مهلت اقدام', 'تاریخ انجام اقدام','وضعیت زمان','قابلیت'] as $th)
                                    <x-table.heading
                                        class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}">
                                        {{ __($th) }}
                                    </x-table.heading>
                                @endforeach
                            </x-table.row>
                        </x-slot>
                        <x-slot name="body">
                            @php
                                $rowIndex = ($this->tasks->currentPage() - 1) * $this->tasks->perPage();
                            @endphp

                            @forelse ($this->tasks as $taskUser)
                                <x-table.row
                                    class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800">
                                    <x-table.cell>{{ ++$rowIndex }}</x-table.cell>

                                    <x-table.cell>
                                        <div><strong>{{ $taskUser->task->meeting->title }}</strong></div>
                                        <div class="text-sm text-gray-500">
                                            {{ __('دبیر جلسه:') }} {{ $taskUser->task->meeting->scriptorium->user_info->full_name ?? '---'}}
                                        </div>
                                    </x-table.cell>

                                    <x-table.cell>{{  $taskUser->user->user_info->full_name ?? '---' }}</x-table.cell>

                                    <x-table.cell>{{ $taskUser->time_out ?? '---' }}</x-table.cell>

                                    <x-table.cell>{{ $taskUser->sent_date ?? '---' }}</x-table.cell>

                                    <x-table.cell>
                                        @if (!empty($taskUser->sent_date))
                                            <span class="font-bold text-blue-600">ارسال شده</span>
                                        @elseif ($taskUser->remaining_diff)
                                            <span
                                                class="font-bold text-green-500">{{ $taskUser->remaining_diff }}</span>
                                        @elseif ($taskUser->past_diff)
                                            <span class="font-bold text-red-600">{{ $taskUser->past_diff }}</span>
                                        @else
                                            ---
                                        @endif
                                    </x-table.cell>

                                    <x-table.cell>
                                        @can('has-permission-and-role', [UserPermission::TASK_REPORT_TABLE,UserRole::ADMIN])
                                            <a href="{{ route('participant.task.report', $taskUser->id) }}">
                                                <x-edit-button>{{ __('نمایش') }}</x-edit-button>
                                            </a>
                                        @endcan
                                    </x-table.cell>
                                </x-table.row>
                            @empty
                                <x-table.row>
                                    <x-table.cell colspan="7" class="py-6 text-center">
                                        {{ __('رکوردی یافت نشد ...') }}
                                    </x-table.cell>
                                </x-table.row>
                            @endforelse
                        </x-slot>

                    </x-table.table>
                </div>
                <div class="mt-2 mb-4">
                    {{ $this->tasks->withQueryString()->links(data: ['scrollTo' => false]) }}
                </div>

            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const taskPercentages = @json($this->percentages);

                const StatusCodes = {
                    EARLY_SENT: 1,
                    LATE_SENT: 2,
                    PENDING_IN_TIME: 3,
                    PENDING_LATE: 4
                };

                const chartLabels = {
                    1: "اقدامات انجام شده در مهلت مقرر",
                    2: "اقدامات انجام شده خارج مهلت مقرر",
                    3: "اقدامات انجام نشده در مهلت مقرر",
                    4: "اقدامات انجام نشده خارج مهلت مقرر"
                };
                const getChartOptions = () => {
                    return {
                        series: [1, 2, 3, 4].map(key => taskPercentages[key] || 0),
                        labels: [1, 2, 3, 4].map(key => chartLabels[key]),
                        colors: [
                            "#22c55e", "#facc15", "#3b82f6", "#dc2626"
                        ],
                        chart: {
                            type: "pie",
                            height: 250,
                            width: 250,
                            events: {
                                dataPointSelection: function (event, chartContext, config) {
                                    const selectedStatus = [1, 2, 3, 4][config.dataPointIndex];
                                    Livewire.dispatch('updateTaskStatusFilter', {status: selectedStatus});
                                }
                            }
                        },
                        stroke: {
                            colors: ["white"],
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: val => val.toFixed(1) + "%"
                        },
                        legend: {show: false},
                        plotOptions: {
                            pie: {
                                size: 160,
                                dataLabels: {
                                    offset: -20
                                }
                            }
                        }
                    };
                };

                if (document.getElementById("pie-chart") && typeof ApexCharts !== 'undefined') {
                    const chart = new ApexCharts(document.getElementById("pie-chart"), getChartOptions());
                    chart.render();
                }
            });
        </script>

    @endcan

</div>
