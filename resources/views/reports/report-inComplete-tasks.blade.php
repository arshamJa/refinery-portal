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
                    <span>  {{__('گزارش اقدامات')}}</span>
                </a>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                 stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            <li>
                        <span
                            class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                            {{__('گزارش اقدامات انجام نشده در مهلت مقرر')}}
                        </span>
            </li>
        </ol>
    </nav>
    <form method="GET" action="{{route('incompleteTasks')}}">
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
                    <x-view-all-link href="{{route('incompleteTasks')}}">
                        {{ __('نمایش همه') }}
                    </x-view-all-link>
                @endif
            </div>
            <!-- Export Button under the right group -->
            <!-- For Incomplete Tasks -->
            <div class="col-span-6 lg:col-start-5 lg:col-span-2 flex justify-start lg:justify-end mt-2">
                <x-export-link href="{{ route('tasks.report.incomplete.download', request()->query()) }}">
                    {{ __('خروجی Excel') }}
                </x-export-link>
            </div>

        </div>
    </form>

    <div class="overflow-x-auto shadow-md sm:rounded-lg mt-4">
        <x-table.table>
            <x-slot name="head">
                <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                    @foreach (['#', 'موضوع جلسه','دبیر جلسه', 'افدام کننده',
                          'تاریخ انجام اقدام','تاریخ مهلت اقدام','مدت زمان'] as $th)
                        <x-table.heading
                            class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}">
                            {{ __($th) }}
                        </x-table.heading>
                    @endforeach
                </x-table.row>
            </x-slot>
            <x-slot name="body">
                @forelse($taskUsers as $taskUser)
                    <x-table.row wire:key="taskUser-{{ $taskUser->id }}" class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-50">
                        <x-table.cell class="border-r-0">{{ ($taskUsers->currentPage() - 1) * $taskUsers->perPage() + $loop->iteration }}</x-table.cell>
                        <x-table.cell>{{ $taskUser->task->meeting->title }}</x-table.cell>
                        <x-table.cell>{{ $taskUser->task->meeting->scriptorium }}</x-table.cell>
                        <x-table.cell>{{ $taskUser->full_name() }}</x-table.cell>
                        <x-table.cell>
                            @if(!$taskUser->sent_date)
                                <span class="text-red-500">{{__('اقدامی انجام نشده')}}</span>
                            @else
                                {{$taskUsersk->sent_date}}
                            @endif
                        </x-table.cell>
                        <x-table.cell>{{ $taskUser->time_out }}</x-table.cell>
                        <x-table.cell>
                            {{ $taskUser->formatted_diff ?? 'N/A' }}
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="6" class="py-6 text-center">
                            {{ __('رکوردی یافت نشد ...') }}
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-table.table>

    </div>
    <div class="mt-2">
        {{ $taskUsers->withQueryString()->links(data: ['scrollTo' => false]) }}
    </div>

</x-app-layout>
