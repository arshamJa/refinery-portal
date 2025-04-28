{{--<div>--}}
{{--    <x-breadcrumb>--}}
{{--        <li class="flex items-center h-full">--}}
{{--            <a href="{{route('dashboard')}}"--}}
{{--               class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">--}}
{{--                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                    <path--}}
{{--                        d="M13.6986 3.68267C12.7492 2.77246 11.2512 2.77244 10.3018 3.68263L4.20402 9.52838C3.43486 10.2658 3 11.2852 3 12.3507V19C3 20.1046 3.89543 21 5 21H8.04559C8.59787 21 9.04559 20.5523 9.04559 20V13.4547C9.04559 13.2034 9.24925 13 9.5 13H14.5456C14.7963 13 15 13.2034 15 13.4547V20C15 20.5523 15.4477 21 16 21H19C20.1046 21 21 20.1046 21 19V12.3507C21 11.2851 20.5652 10.2658 19.796 9.52838L13.6986 3.68267Z"--}}
{{--                        fill="currentColor"></path>--}}
{{--                </svg>--}}
{{--                <span>{{__('داشبورد')}}</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"--}}
{{--             stroke="currentColor" class="w-3 h-3 text-gray-400">--}}
{{--            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>--}}
{{--        </svg>--}}
{{--        <li class="flex items-center h-full">--}}
{{--            <a href="{{route('message')}}"--}}
{{--               class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">--}}
{{--                <span>{{__('پیغام های دریافتی')}}</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"--}}
{{--             stroke="currentColor" class="w-3 h-3 text-gray-400">--}}
{{--            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>--}}
{{--        </svg>--}}
{{--        <li class="flex items-center h-full">--}}
{{--            <span class="active-breadcrumb">{{__('لیست اقدامات ارسال شده به دبیرجلسه')}}</span>--}}
{{--        </li>--}}
{{--    </x-breadcrumb>--}}

{{--    <div x-data="{ openSessions: {} }">--}}
{{--        @foreach($this->meetings as $meeting)--}}
{{--            <div class="mb-4 border border-gray-200 rounded-xl shadow-sm overflow-hidden bg-white">--}}
{{--                <!-- Session Toggle Button -->--}}
{{--                <button--}}
{{--                    class="w-full bg-gray-100 hover:bg-gray-200 p-4 flex justify-between items-center text-gray-800 font-semibold"--}}
{{--                    @click="openSessions[{{ $meeting->id }}] = !openSessions[{{ $meeting->id }}]">--}}
{{--                    <span>{{ $meeting->title }}</span>--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"--}}
{{--                         stroke="currentColor"--}}
{{--                         :class="{ 'rotate-180': openSessions[{{ $meeting->id }}] }"--}}
{{--                         class="w-4 h-4 transition-transform duration-200 ease-out">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5"/>--}}
{{--                    </svg>--}}
{{--                </button>--}}

{{--                <!-- Session Content -->--}}
{{--                <div x-show="openSessions[{{ $meeting->id }}]"--}}
{{--                     x-transition:enter="transition ease-out duration-300"--}}
{{--                     x-transition:enter-start="opacity-0 scale-95"--}}
{{--                     x-transition:enter-end="opacity-100 scale-100"--}}
{{--                     x-transition:leave="transition ease-in duration-200"--}}
{{--                     x-transition:leave-start="opacity-100 scale-100"--}}
{{--                     x-transition:leave-end="opacity-0 scale-95"--}}
{{--                     x-cloak--}}
{{--                     class="p-4 bg-white">--}}
{{--                    <div class="overflow-x-auto rounded-md">--}}
{{--                        <table class="w-full text-sm text-gray-700 text-center rtl:text-right">--}}
{{--                            <thead class="bg-gray-50 text-gray-600 border-b text-sm font-semibold">--}}
{{--                            <tr>--}}
{{--                                <th class="px-4 py-3">اقدام کننده</th>--}}
{{--                                <th class="px-4 py-3">اقدامات</th>--}}
{{--                                <th class="px-4 py-3">مهلت اقدام</th>--}}
{{--                                <th class="px-4 py-3">تاریخ ارسال</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody class="divide-y divide-gray-100">--}}
{{--                            @foreach($meeting->tasks as $task)--}}
{{--                                <tr class="hover:bg-gray-50 odd:bg-gray-50">--}}
{{--                                    <td class="px-4 py-3 whitespace-nowrap font-medium">{{ $task->full_name() }}</td>--}}
{{--                                    <td class="px-4 py-3 whitespace-normal text-right">{{ $task->body }}</td>--}}
{{--                                    <td class="px-4 py-3 whitespace-nowrap text-gray-600">{{ $task->time_out }}</td>--}}
{{--                                    <td class="px-4 py-3 whitespace-nowrap text-gray-600">{{ $task->sent_date }}</td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endforeach--}}
{{--    </div>--}}

{{--</div>--}}
