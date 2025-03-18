@php use App\Models\Task; @endphp
<x-app-layout>
    <x-breadcrumb>
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
            <span class="active-breadcrumb">{{__('لیست جلساتی که در آن شرکت کردم')}}</span>
        </li>
    </x-breadcrumb>
    <div class="pt-4 sm:px-10 sm:pt-6 border shadow-md rounded-md">
        <form method="GET" action="{{ route('attended.meetings') }}">
            @csrf
            <div class="grid grid-cols-2 items-end gap-4">
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
                    <x-text-input type="text" name="search"/>
                </div>
                <select name="task_status"
                        class="text-sm bg-white w-2/5 border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                    <option>{{__('وضعیت اقدامات...')}}</option>
                    <option value="1">{{__('انجام شده')}}</option>
                    <option value="0">{{__('انجام نشده')}}</option>
                </select>
            </div>
            <div class="w-full flex gap-4 items-center pl-4 py-2 mt-1">
                <x-search-button>{{__('جست و جو')}}</x-search-button>
                @if ($originalTasksCount != $filteredTasksCount)
                    <x-view-all-link href="{{route('attended.meetings')}}">  {{__('نمایش همه')}}</x-view-all-link>
                @endif
            </div>

        </form>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead
                class="text-sm text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="px-4 py-3">{{__('ردیف')}}</th>
                <th class="px-4 py-3">{{__('موضوع جلسه')}}</th>
                <th class="px-4 py-3">{{__('دبیر جلسه')}}</th>
                <th class="px-4 py-3">{{__('تاریخ جلسه')}}</th>
                <th class="px-4 py-3">{{__('مهلت اقدام')}}</th>
                <th class="px-4 py-3">{{__('تاریخ انجام اقدام')}}</th>
                <th class="px-4 text-center"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($tasks as $task)
                <tr class="px-4 py-3 border-b text-center">
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$loop->index+1}}</td>
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->meeting->title}}</td>
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->meeting->scriptorium}}</td>
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->meeting->date}}</td>
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->time_out}}</td>
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->sent_date}}</td>
                    <td class="px-4 py-3">
                        @if($tasks && !$task->is_completed)
                            <a href="{{route('task.list',$task->meeting->id)}}">
                                <x-primary-button>
                                    {{__('نمایش اقدامات')}}
                                </x-primary-button>
                            </a>
                        @endif
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
