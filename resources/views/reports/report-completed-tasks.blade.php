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
                    <span>  {{__('داشبورد جلسات')}}</span>
                </a>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                 stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            <li>
            <span
                class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                {{__('گزارش اقدامات انجام شده در مهلت مقرر')}}
            </span>
            </li>
        </ol>
    </nav>
    <div class="pt-4 px-10 sm:pt-6 border shadow-md rounded-md">
        <form method="GET" action="{{route('completedTasks')}}">
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
                        <x-view-all-link href="{{route('completedTasks')}}">
                            {{ __('نمایش همه') }}
                        </x-view-all-link>
                    @endif
                </div>
                <!-- Export Button under the right group -->
                <!-- For Completed Tasks -->
                <div class="col-span-6 lg:col-start-5 lg:col-span-2 flex justify-start lg:justify-end mt-2">
                    <x-export-link href="{{ route('tasks.report.completed.download', request()->query()) }}">
                        {{ __('خروجی Excel') }}
                    </x-export-link>
                </div>
            </div>
        </form>

        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead
                class="text-sm text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                @foreach (['ردیف', 'موضوع جلسه','دبیر جلسه', 'اقدام کننده',
                              'تاریخ انجام اقدام','تاریخ مهلت اقدام'] as $th)
                    <th class="px-4 py-3">{{ __($th) }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @forelse($tasks as $task)
                <tr class="px-4 py-3 border-b text-center" wire:key="{{$task->id}}">
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$loop->iteration}}</td>
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->meeting->title}}</td>
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->meeting->scriptorium}}</td>
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->full_name()}}</td>
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->sent_date}}</td>
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->time_out}}</td>
                </tr>
            @empty
                <tr class="border-b dark:border-gray-700">
                    <th colspan="8"
                        class="text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                        {{__('رکوردی یافت نشد ...')}}
                    </th>
                </tr>
            @endforelse
            </tbody>
        </table>
        <span class="p-2 mx-2">
            {{ $tasks->withQueryString()->links(data:['scrollTo'=>false]) }}
        </span>
    </div>
</x-app-layout>
