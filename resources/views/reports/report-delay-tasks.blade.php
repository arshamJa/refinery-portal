@php use Carbon\Carbon; @endphp
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
            <li class="flex items-center h-full">
                <a href="{{route('meeting.report')}}"
                   class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                    <span> {{__('گزارش جلسات شرکت')}}</span>
                </a>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                 stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            <li>
                <span
                    class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                    {{__('گزارش اقدامات انجام شده خارج از مهلت مقرر')}}
                </span>
            </li>
        </ol>
    </nav>
        <form method="GET" action="{{route('tasksWithDelay')}}">
            @csrf
            <div class="grid gap-4 px-3 sm:px-0 lg:grid-cols-6 items-end">
                <!-- Search Input -->
                <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                    <x-input-label for="search" value="{{ __('جست و جو') }}"/>
                    <x-search-input>
                        <x-text-input type="text" id="search" name="search"
                                      class="block ps-10"
                                      placeholder="{{ __('عبارت مورد نظر را وارد کنید...') }}"/>
                    </x-search-input>
                </div>

                <!-- Date Inputs (side-by-side) -->
                <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <x-input-label for="start_date" value="{{ __('تاریخ شروع') }}"/>
                            <x-date-input>
                                <x-text-input id="start_date" name="start_date" class="block ps-10"/>
                            </x-date-input>
                        </div>
                        <div class="flex-1">
                            <x-input-label for="end_date" value="{{ __('تاریخ پایان') }}"/>
                            <x-date-input>
                                <x-text-input id="end_date" name="end_date" class="block ps-10"/>
                            </x-date-input>
                        </div>
                    </div>
                </div>

                <!-- Search + Show All Buttons -->
                <div class="col-span-6 lg:col-span-2 flex justify-start lg:justify-end flex-row gap-4 mt-4 lg:mt-0">
                    <x-search-button>{{ __('جست و جو') }}</x-search-button>
                    @if(request()->has('search') || request()->has('start_date'))
                        <x-view-all-link href="{{route('tasksWithDelay')}}">
                            {{ __('نمایش همه') }}
                        </x-view-all-link>
                    @endif
                </div>
                <!-- Export Button under the right group -->
                <!-- For Completed Tasks With Delay -->
                <div class="col-span-6 lg:col-start-5 lg:col-span-2 flex justify-start lg:justify-end mt-2">
                    <x-export-link href="{{ route('tasks.report.completed.withDelay.download', request()->query()) }}">
                        {{ __('خروجی Excel') }}
                    </x-export-link>
                </div>
            </div>
        </form>



    <div class="overflow-x-auto shadow-md sm:rounded-lg mt-4">
        <x-table.table>
            <x-slot name="head">
                <x-table.row class="bg-gray-100 dark:bg-gray-800 whitespace-nowrap">
                    @foreach (['ردیف', 'موضوع جلسه و دبیر جلسه','افدام کننده','تاریخ انجام اقدام','تاریخ مهلت اقدام','مدت زمان تاخیر','قابلیت'] as $th)
                        <x-table.heading
                            class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}">
                            {{ __($th) }}
                        </x-table.heading>
                    @endforeach
                </x-table.row>
            </x-slot>

            <x-slot name="body">
                @php
                    $grouped = $taskUsers->groupBy(fn($item) => $item->task->meeting->id);
                    $rowIndex = ($taskUsers->currentPage() - 1) * $taskUsers->perPage();
                @endphp

                @forelse($grouped as $meetingId => $group)
                    @php
                        $meeting = $group->first()->task->meeting;
                        $rowspan = $group->count();
                    @endphp

                    @foreach ($group as $i => $taskUser)
                        <x-table.row class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-100">
                            {{-- Row number --}}
                            <x-table.cell>{{ ++$rowIndex }}</x-table.cell>
                            {{-- Meeting summary + Scriptorium (only for first row in group) --}}
                            @if ($i === 0)
                                <x-table.cell class="text-right align-top" rowspan="{{ $rowspan }}">
                                    <div>
                                        <strong>{{ $taskUser->task->meeting->title  }}</strong>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        دبیر جلسه: {{$taskUser->task->meeting->scriptorium}}
                                    </div>
                                </x-table.cell>
                            @endif

                            {{-- Action taker --}}
                            <x-table.cell>{{ $taskUser->user->user_info->full_name ?? '---' }}</x-table.cell>

                            {{-- Sent Date --}}
                            <x-table.cell>{{ $taskUser->sent_date }}</x-table.cell>

                            {{-- Deadline --}}
                            <x-table.cell>{{ $taskUser->time_out }}</x-table.cell>

                            {{-- Delay Duration --}}
                            <x-table.cell>
                                @php
                                    $date1 = Carbon::parse($taskUser->sent_date);
                                    $date2 = Carbon::parse($taskUser->time_out);
                                    $diff = $date1->diff($date2);
                                    $formattedDiff = '';
                                    if ($diff->y > 0) $formattedDiff .= $diff->y . ' سال ';
                                    if ($diff->m > 0) $formattedDiff .= $diff->m . ' ماه ';
                                    if ($diff->d > 0) $formattedDiff .= $diff->d . ' روز ';
                                    if ($diff->h > 0) $formattedDiff .= $diff->h . ' ساعت ';
                                    if ($diff->i > 0) $formattedDiff .= $diff->i . ' دقیقه ';
                                    if ($diff->s > 0) $formattedDiff .= $diff->s . ' ثانیه ';
                                @endphp
                                {{ trim($formattedDiff) }}
                            </x-table.cell>
                            <x-table.cell>
                                <a href="{{route('participant.task.report',
                                ['meeting_id'=>$taskUser->task->meeting_id,'user_id'=>$taskUser->user_id])}}">
                                    <x-edit-button>
                                        {{__('نمایش جزئیات')}}
                                    </x-edit-button>
                                </a>
                            </x-table.cell>
                        </x-table.row>
                    @endforeach
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

    <div class="mt-2">
            {{ $taskUsers->withQueryString()->links(data:['scrollTo'=>false]) }}
        </div>

</x-app-layout>
