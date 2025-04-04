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
            <li>
                <a href="{{route('meeting.create')}}"
                   class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                    <span>{{__('ایجاد جلسه جدید')}}</span>
                </a>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                 stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            <li>
                    <span class="inline-flex items-center px-2 py-1.5 space-x-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5"/>
                        </svg>
                        <span
                            class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                           {{__('جدول جلسات')}}
                        </span>
                    </span>
            </li>
    </x-breadcrumb>

    <div class="pt-4 sm:px-10 sm:pt-6 border shadow-md rounded-md">

        <form method="GET" action="{{route('meeting.table')}}">
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
            </div>
            <div class="w-full flex gap-4 items-center pl-4 py-2 mt-1">
                <x-search-button>{{__('جست و جو')}}</x-search-button>
                @if ($originalMeetingsCount != $filteredMeetingsCount)
                    <x-view-all-link href="#">{{__('نمایش همه')}}</x-view-all-link>
                @endif
            </div>
        </form>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead
                class="text-sm text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                @foreach (['ردیف', 'موضوع جلسه', 'دبیر جلسه', 'تاریخ جلسه', 'ساعت جلسه', 'قابلیت'] as $th)
                    <th class="px-4 py-3">{{ __($th) }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @forelse($meetings as $meeting)
                <tr class="px-4 py-3 border-b text-center">
                    <td class="px-4 py-4">{{$loop->index+1}}</td>
                    <td class="px-4 py-4">{{$meeting->title}}</td>
                    <td class="px-4 py-4">{{$meeting->scriptorium}}</td>
                    <td class="px-4 py-4">{{$meeting->date}}</td>
                    <td class="px-4 py-4">{{$meeting->time}}</td>
                    <td class="px-4 py-4 flex justify-center">
                        <x-dropdown>
                            <x-slot name="trigger">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-6 cursor-pointer">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                                </svg>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link href="{{route('meeting.show',$meeting->id)}}">
                                    {{__('نمایش')}}
                                </x-dropdown-link>
                                <x-dropdown-link href="{{route('meeting.edit',$meeting->id)}}">
                                    {{__('ویرایش')}}
                                </x-dropdown-link>
                                <form action="{{route('meeting.destroy',$meeting->id)}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <x-dropdown-link type="submit">
                                        {{__('حذف')}}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
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
           {{ $meetings->withQueryString()->links(data:['scrollTo'=>false]) }}
       </span>
    </div>

</x-app-layout>
