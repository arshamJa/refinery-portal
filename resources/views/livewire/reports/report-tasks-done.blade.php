<div>

    <x-sessionMessage name="status"/>
    <x-template>
        <nav class="flex justify-between mb-4">
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
        <div class="mx-auto bg-white w-full">
            <div class="relative shadow-md sm:rounded-lg overflow-hidden">
                <!-- Table Header -->
                <div
                    class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <!-- Search Bar -->
                    <div class="w-full md:w-1/2">
                        <x-text-input wire:model.live="search" placeholder="جست و جو" class="mt-4" dir="rtl"/>
                    </div>
                </div>
                <!-- Table Body -->
                <div class="overflow-x-auto" dir="rtl">
                    <x-table.table>
                        <x-slot name="head">
                            <th class="px-4 py-3">{{__('ردیف')}}</th>
                            <th class="px-4 py-3">{{__('جلسه')}}</th>
                            <th class="px-4 py-3">{{__('افدام کننده')}}</th>
                            <th class="px-4 py-3">{{__('تاریخ مهلت اقدام')}}</th>
                            <th class="px-4 py-3">{{__('تاریخ انجام اقدام')}}</th>
                            <th class="px-4 py-3">{{__('اقدامات')}}</th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach($this->tasks as $task)
                                <tr class="px-4 py-3 border-b text-center" wire:key="{{$task->id}}">
                                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$loop->index+1}}</td>
                                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->meeting->title}}</td>
                                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->full_name()}}</td>
                                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->time_out}}</td>
                                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->sent_date}}</td>
                                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->body}}</td>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-table.table>
                    <nav
                        class="flex flex-col md:flex-row mt-14 justify-between items-start md:items-center space-y-3 md:space-y-0 p-4">
                        {{ $this->tasks->withQueryString()->links(data:['scrollTo'=>false]) }}
                    </nav>
                </div>
            </div>
        </div>
    </x-template>


</div>
