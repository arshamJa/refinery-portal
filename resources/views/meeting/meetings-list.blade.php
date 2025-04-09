<x-app-layout>
    <nav class="flex justify-between mb-4 mt-20">
        <ol class="inline-flex items-center mb-3 space-x-1 text-xs text-neutral-500 [&_.active-breadcrumb]:text-neutral-600 [&_.active-breadcrumb]:font-medium sm:mb-0">
            <li class="flex items-center h-full">
                <a href="{{route('dashboard')}}"
                   class="inline-flex items-center px-2 gap-1 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
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
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                          stroke="currentColor" class="w-3.5 h-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5"/>
                </svg>
                    {{__('لیست جلسات در حال برگزاری')}}
                </span>
            </li>
        </ol>
    </nav>
    <div class="pt-4 sm:px-10 sm:pt-6 border shadow-md rounded-md">


        <form method="GET" action="{{ route('meetingsList') }}" class="mb-4">
            @csrf
            <div class="grid gap-4 px-3 sm:px-0 lg:grid-cols-6 items-end">
                <!-- Search Input -->
                <div class="col-span-6 lg:col-span-2">
                    <x-input-label for="search" value="{{ __('جست و جو') }}"/>
                    <x-search-input>
                        <x-text-input type="text" id="search" name="search"
                                      class="block ps-10"
                                      placeholder="{{ __('عبارت مورد نظر را وارد کنید...') }}"/>
                    </x-search-input>
                </div>

                <!-- Status Select -->
                <div class="col-span-6 lg:col-span-1">
                    <x-input-label for="search" value="{{ __('وضعیت جلسه') }}"/>
                    <x-select-input name="is_cancelled" id="is_cancelled">
                        <option value="">...</option>
                        <option value="0">{{ __('در حال بررسی ...') }}</option>
                        <option value="-1">{{ __('تایید شده') }}</option>
                        <option value="1">{{ __('لغو شده') }}</option>
                    </x-select-input>
                </div>

                <!-- Search + Show All Buttons -->
                <div class="col-span-6 lg:col-span-3 flex justify-start lg:justify-end flex-row gap-4 mt-4 lg:mt-0">
                    <x-search-button>{{ __('جست و جو') }}</x-search-button>
                    @if ($originalMeetingsCount != $filteredMeetingsCount)
                        <x-view-all-link href="{{ route('meetingsList') }}">
                            {{ __('نمایش همه') }}
                        </x-view-all-link>
                    @endif
                </div>
            </div>
        </form>

        <div class="overflow-x-auto overflow-y-hidden">
            <table class="min-w-[1000px] w-full text-sm text-center text-gray-700 bg-white dark:bg-gray-800">
                <thead
                    class="text-sm text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    @foreach (['جلسات', 'دبیرجلسه','تاریخ','ساعت','مکان',
                               'حاضرین','مشاهده اعضا','وضعیت جلسه'] as $th)
                        <th class="px-4 py-3">{{ __($th) }}</th>
                    @endforeach
                    <th scope="col" class="px-6 py-3"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($meetings as $meeting)
                    <tr class="relative text-center border-b dark:border-gray-700">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$meeting->title}}
                                                    @if($meeting->tasks->where('request_task',!null)->value('request_task'))
                                                        <span class="absolute right-2 top-1/2 -translate-y-1/2 flex h-3 w-3"><span
                                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span><span
                                                                class="relative inline-flex rounded-full h-3 w-3 bg-sky-500"></span></span>
                                                    @endif
                        </th>
                        <td class="px-6 py-4">
                            {{$meeting->scriptorium}}
                        </td>
                        <td class="px-6 py-4">
                            {{$meeting->date}}
                        </td>
                        <td class="px-6 py-4">
                            {{$meeting->time}}
                        </td>
                        <td class="px-6 py-4">
                            {{$meeting->location}}
                        </td>
                        <td class="px-6 py-4">
                            {{$meeting->meetingUsers->where('meeting_id',$meeting->id)->where('is_present','1')->count()}}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{route('presentUsers',$meeting->id)}}"
                               class="hover:underline font-bold text-black"> {{__('نمایش')}}</a>
                        </td>
                        <td class="px-6 py-4">
                            @if($meeting->is_cancelled == '0')
                                {{__('درحال بررسی...')}}
                            @elseif($meeting->is_cancelled == '1')
                                {{__('جلسه لغو شد')}}
                            @elseif($meeting->is_cancelled == '-1')
                                {{__('جلسه تشکیل میشود')}}
                            @endif
                        </td>
                        <td class="px-6 py-4">
{{--                             Display the "Add Tasks" button based on the condition--}}
                            @if($meeting->is_cancelled == -1 && (!isset($allUsersHaveTasks[$meeting->id]) || $allUsersHaveTasks[$meeting->id] === false))
                                <a href="{{ route('tasks.create', $meeting->id) }}">
                                    <x-primary-button>
                                        {{ __('افزودن اقدامات') }}
                                    </x-primary-button>
                                </a>
                            @elseif((isset($allUsersHaveTasks[$meeting->id]) && $allUsersHaveTasks[$meeting->id]))
                                <p>{{__('اقدامات برای تمامی اعضا ارسال شد')}}</p>
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
        </div>
        <span class="p-2 mx-2">
                {{ $meetings->withQueryString()->links(data:['scrollTo'=>false]) }}
        </span>

    </div>
</x-app-layout>

