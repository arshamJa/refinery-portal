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
                            {{__('گزارش اقدامات انجام نشده در مهلت مقرر')}}
                        </span>
            </li>
        </ol>
    </nav>
    <div class="pt-4 px-10 sm:pt-6 border shadow-md rounded-md">
        <form method="GET" action="{{route('incompleteTasks')}}">
            @csrf
            <div class="grid grid-cols-2 items-end gap-4">
                <div class="col-span-1 gap-4 grid grid-cols-2">
                    <div>
                        <x-input-label value="{{__('تاریخ شروع')}}" class="mb-2"/>
                        <x-text-input name="start_date"/>
                    </div>
                    <div>
                        <x-input-label value="{{__('تاریخ پایان')}}" class="mb-2"/>
                        <x-text-input name="end_date"/>
                    </div>
                </div>
                <div class="relative">
                    <div
                        class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500"
                             fill="currentColor" viewbox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <x-text-input type="text" name="search" placeholder="جست و جو..."/>
                </div>
            </div>
            <div class="w-full flex gap-4 items-center pl-4 py-2 mt-1">
                <button type="submit"
                        class="inline-flex gap-1 items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"/>
                    </svg>
                    {{__('فیلتر')}}
                </button>
                <a href="{{route('incompleteTasks')}}"
                   class="px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{__('نمایش همه')}}
                </a>
            </div>
        </form>


        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead
                class="text-sm text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="px-4 py-3">{{__('ردیف')}}</th>
                <th class="px-4 py-3">{{__('موضوع جلسه')}}</th>
                <th class="px-4 py-3">{{__('دبیر جلسه')}}</th>
                <th class="px-4 py-3">{{__('افدام کننده')}}</th>
                <th class="px-4 py-3">{{__('تاریخ انجام اقدام')}}</th>
                <th class="px-4 py-3">{{__('تاریخ مهلت اقدام')}}</th>
                <th class="px-4 py-3">{{__('مدت زمان گذشته')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($tasks as $task)
                <tr class="px-4 py-3 border-b text-center" wire:key="{{$task->id}}">
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$loop->index+1}}</td>
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->meeting->title}}</td>
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->meeting->scriptorium}}</td>
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->full_name()}}</td>
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">
                        @if(!$task->sent_date)
                            <span class="text-red-500">{{__('اقدامی انجام نشده')}}</span>
                        @else
                            {{$task->sent_date}}
                        @endif
                    </td>
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->time_out}}</td>
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">
                        @php

                            $gr_day = now()->day;
                            $gr_month = now()->month;
                            $gr_year = now()->year;
                            $time = gregorian_to_jalali($gr_year,$gr_month,$gr_day,'/');

                            $date1 = Carbon::parse($task->time_out);
                            $date2 = Carbon::parse($time);
                            $diff = $date1->diff($date2);
                            $formattedDiff = '';

                            if ($diff->y > 0) {$formattedDiff .= $diff->y . ' سال';}
                            if ($diff->m > 0) {$formattedDiff .= $diff->m . ' ماه';}
                            if ($diff->d > 0) {$formattedDiff .= $diff->d . ' روز';}
                            if ($diff->h > 0) {$formattedDiff .= $diff->h . ' ساعت';}
                            if ($diff->i > 0) {$formattedDiff .= $diff->i . ' دقیقه';}
                            if ($diff->s > 0) {$formattedDiff .= $diff->s . ' ثانیه';}

                            // Remove the trailing comma and space if there's any output.
                            $formattedDiff = rtrim($formattedDiff, ', ');
                        @endphp
                        {{ $formattedDiff }}
                    </td>
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
