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
                <li>
                    <a href="{{route('message')}}"
                       class="inline-flex items-center px-2 py-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                        <span>{{__('لیست پیغام های دریافتی')}}</span>
                    </a>
                </li>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                     stroke="currentColor" class="w-3 h-3 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
                <li>
                    <span
                        class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                      {{__('پاسخ اعضای جلسه به دعوتنامه')}}
                    </span>
                </li>
            </ol>

            <a href="{{route('meetings.index')}}">
                <x-primary-button>{{__('مشاهده جدول جلسات')}}</x-primary-button>
            </a>
        </nav>


        @foreach($this->meetings as $meeting)
            @foreach($this->meetingUsers as $meetingUser)
                <div class="mb-4">
                    <div class="bg-teal-50 border-t-2 border-teal-500 rounded-lg p-4 dark:bg-teal-800/30">
                        <div class="flex">
                            <div class="shrink-0">
                            <span
                                class="inline-flex justify-center items-center size-8 rounded-full border-4 border-teal-100 bg-teal-200 text-teal-800 dark:border-teal-900 dark:bg-teal-800 dark:text-teal-400">
                                 @if($meetingUser->is_present == 1)
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor"
                                         class="shrink-0 size-4 text-green-600"><path stroke-linecap="round"
                                                                                      stroke-linejoin="round"
                                                                                      d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                @else
                                    <svg class="shrink-0 size-4 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                         width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12"
                                                                                                                 cy="12"
                                                                                                                 r="10"></circle><path
                                            d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
                                @endif
                        </span>
                            </div>
                            <div class="ms-3 w-full">
                                <h3 class="text-gray-800 font-semibold dark:text-white">
                                    {{$meeting->title}}
                                </h3>
                                <div
                                    class="text-sm text-gray-700 flex justify-between items-center dark:text-neutral-400">
                                    <span>{{__('آقا/خانم')}}
                                        <span class="font-bold">{{$meetingUser->user->user_info->full_name}}</span>
                                        <span>{{__('درخواست به جلسه را ')}}</span>
                                        @if($meetingUser->is_present == 1)
                                            <span class="font-bold">{{__('قبول کرد')}}</span>
                                        @else
                                            <span class="font-bold">{{__('رد کرد')}}</span>
                                            <span
                                                class="block mt-2">{{__('دلیل رد دعوتنامه : ')}}{{$meetingUser->reason_for_absent ?? null}}</span>
                                        @endif
                                    </span>
                                    <form action="{{route('markNotification',$meetingUser->id)}}" method="post">
                                        @csrf
                                        <x-primary-button type="submit">{{__('متوجه شدم')}}</x-primary-button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach

        {{--            <div class="p-4 h-auto">--}}
        {{--                <div class="mx-auto bg-white w-full">--}}
        {{--                    <div class="relative shadow-md sm:rounded-lg overflow-hidden">--}}
        {{--                        <!-- Table Header -->--}}
        {{--                        <div--}}
        {{--                            class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">--}}
        {{--                            <!-- Search Bar -->--}}
        {{--                            <div class="w-full md:w-1/2">--}}
        {{--                                <form class="flex items-center">--}}
        {{--                                    <label for="simple-search" class="sr-only">Search</label>--}}
        {{--                                    <div class="relative w-full">--}}
        {{--                                        <div--}}
        {{--                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">--}}
        {{--                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500"--}}
        {{--                                                 fill="currentColor" viewbox="0 0 20 20"--}}
        {{--                                                 xmlns="http://www.w3.org/2000/svg">--}}
        {{--                                                <path fill-rule="evenodd"--}}
        {{--                                                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"--}}
        {{--                                                      clip-rule="evenodd"/>--}}
        {{--                                            </svg>--}}
        {{--                                        </div>--}}
        {{--                                        <input type="text" wire:model.live="search" id="simple-search" dir="rtl"--}}
        {{--                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full"--}}
        {{--                                               placeholder="جست و جو" required="">--}}
        {{--                                    </div>--}}
        {{--                                </form>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                        <!-- Table Body -->--}}
        {{--                        <div class="overflow-x-auto" dir="rtl">--}}
        {{--                            <x-table.table>--}}
        {{--                                <x-slot name="head">--}}
        {{--                                    <th class="py-3"></th>--}}
        {{--                                    <th class="px-4 py-3">{{__('موضوع جلسه')}}</th>--}}
        {{--                                    <th class="px-4 py-3">{{__('دبیر جلسه')}}</th>--}}
        {{--                                    <th class="px-4 py-3">{{__('تاریخ جلسه')}}</th>--}}
        {{--                                    <th class="px-4 py-3">{{__('ساعت جلسه')}}</th>--}}
        {{--                                    <th class="px-4 py-3">{{__('تعداد درخواستی')}}</th>--}}
        {{--                                    <th class="px-2 py-3">{{__('وضعیت')}}</th>--}}
        {{--                                </x-slot>--}}
        {{--                                <x-slot name="body">--}}
        {{--                                    @foreach($this->meetings as $meeting)--}}
        {{--                                        <tr class="px-4 py-3 border-b text-center" wire:key="{{$meeting->id}}">--}}
        {{--                                            <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">--}}
        {{--                                                @if($meeting->is_cancelled == '0')--}}
        {{--                                                    <span class="relative flex size-3">--}}
        {{--                                                            <span--}}
        {{--                                                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-sky-400 opacity-75"></span>--}}
        {{--                                                            <span--}}
        {{--                                                                class="relative inline-flex size-3 rounded-full bg-sky-500"></span>--}}
        {{--                                                        </span>--}}
        {{--                                                @endif--}}
        {{--                                            </td>--}}
        {{--                                            <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$meeting->title}}</td>--}}
        {{--                                            <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$meeting->scriptorium}}</td>--}}
        {{--                                            <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$meeting->date}}</td>--}}
        {{--                                            <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$meeting->time}}</td>--}}
        {{--                                            <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">--}}
        {{--                                                <a class="flex justify-center"--}}
        {{--                                                   href="{{route('presentUsers',$meeting->id)}}">--}}
        {{--                                                    @if($meeting->is_cancelled == '0')--}}
        {{--                                                        <span class="relative flex size-3">--}}
        {{--                                                            <span--}}
        {{--                                                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-sky-400 opacity-75"></span>--}}
        {{--                                                            <span--}}
        {{--                                                                class="relative inline-flex size-3 rounded-full bg-sky-500"></span>--}}
        {{--                                                        </span>--}}
        {{--                                                    @endif--}}
        {{--                                                    <span--}}
        {{--                                                        class="hover:underline-offset-2 hover:underline ">{{__('مشاهده')}}</span>--}}
        {{--                                                </a>--}}
        {{--                                            </td>--}}
        {{--                                            <td class="px-2 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">--}}
        {{--                                                @if($meeting->is_cancelled == '0')--}}
        {{--                                                    <span class="text-blue-600 font-bold">--}}
        {{--                                                            {{__('در حال بررسی...')}}--}}
        {{--                                                        </span>--}}
        {{--                                                @elseif($meeting->is_cancelled == '-1')--}}
        {{--                                                    <span class="text-green-600 font-bold">--}}
        {{--                                                            {{__('جلسه برگزار میشود')}}--}}
        {{--                                                        </span>--}}
        {{--                                                @elseif($meeting->is_cancelled == '1')--}}
        {{--                                                    <span class="text-red-600 font-bold">--}}
        {{--                                                            {{__('جلسه لغو شد')}}--}}
        {{--                                                        </span>--}}
        {{--                                                @endif--}}
        {{--                                            </td>--}}
        {{--                                        </tr>--}}
        {{--                                    @endforeach--}}
        {{--                                </x-slot>--}}
        {{--                            </x-table.table>--}}
        {{--                            <nav--}}
        {{--                                class="flex flex-col md:flex-row mt-14 justify-between items-start md:items-center space-y-3 md:space-y-0 p-4">--}}
        {{--                                {{ $this->meetings->withQueryString()->links(data:['scrollTo'=>false]) }}--}}
        {{--                            </nav>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}
    </x-template>


</div>
