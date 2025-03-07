<div>
    <x-sessionMessage name="status"/>
    <header class="bg-white shadow" dir="rtl">
        <div class="mx-auto max-w-7xl px-2 py-4 sm:px-6 lg:px-2">
            <x-breadcrumb>
                    <li class="flex items-center h-full">
                        <a href="{{route('dashboard')}}"
                           class="inline-flex items-center px-2 py-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M13.6986 3.68267C12.7492 2.77246 11.2512 2.77244 10.3018 3.68263L4.20402 9.52838C3.43486 10.2658 3 11.2852 3 12.3507V19C3 20.1046 3.89543 21 5 21H8.04559C8.59787 21 9.04559 20.5523 9.04559 20V13.4547C9.04559 13.2034 9.24925 13 9.5 13H14.5456C14.7963 13 15 13.2034 15 13.4547V20C15 20.5523 15.4477 21 16 21H19C20.1046 21 21 20.1046 21 19V12.3507C21 11.2851 20.5652 10.2658 19.796 9.52838L13.6986 3.68267Z"
                                    fill="currentColor"></path>
                            </svg>
                            <span>{{__('داشبورد')}}</span>
                        </a>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                        </svg>
                        <span class="active-breadcrumb">{{__('لیست وظایف')}}</span>
                    </li>
            </x-breadcrumb>
        </div>
    </header>

    <div class="p-4 h-auto">
        <section class="p-3 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <!-- Start coding here -->
                <div class="bg-white relative shadow-md sm:rounded-lg overflow-hidden">
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="w-full md:w-3/6">
                            <form class="flex items-center">
                                <label for="simple-search" class="sr-only">Search</label>
                                <div class="relative w-full">
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
                                    <input wire:model.live.debounce.500ms="search" type="text" dir="rtl"
                                           placeholder="جست و جو ..."
                                           class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                            </form>
                        </div>
                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <div class="flex items-center justify-center w-full md:w-auto gap-2">
                                {{--                                --}}{{--                                @can('create')--}}
                                {{--                                <a href="{{Illuminate\Support\Facades\URL::signedRoute('tasks.create')}}">--}}
                                {{--                                    <x-primary-button>--}}
                                {{--                                        {{__('وظیفه')}}--}}
                                {{--                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"--}}
                                {{--                                             stroke-width="1.5" stroke="currentColor" class="size-4 mr-1">--}}
                                {{--                                            <path stroke-linecap="round" stroke-linejoin="round"--}}
                                {{--                                                  d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>--}}
                                {{--                                        </svg>--}}
                                {{--                                    </x-primary-button>--}}
                                {{--                                </a>--}}
                                {{--                                --}}{{--                                @endcan--}}
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto" dir="rtl">
                        <x-table.table>
                            <x-slot name="head">
                                <th class="px-4 py-3">{{__('ردیف')}}</th>
                                <th class="px-4 py-3">{{__('جلسه')}}</th>
                                <th class="px-4 py-3">{{__('موضوع وظیفه')}}</th>
                                <th class="px-4 py-3">{{__('وضعیت')}}</th>
                                <th class="px-4 py-3"></th>
                            </x-slot>

                            <x-slot name="body">
                                @forelse($this->tasks as $task)
                                    <tr class="px-4 py-3 border-b text-center"
                                        wire:key="{{$task->id}}">
                                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">
                                            {{$loop->index+1}}
                                        </td>
                                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">
                                            {{$task->meeting->title}}
                                        </td>
                                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">
                                            {{$task->title}}
                                        </td>

                                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">
                                            @if($task->is_completed)
                                                <span
                                                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase">
                                                    {{__('تمام')}}
                                                </span>
                                            @else
                                                <span class="text-red-600 font-bold">
                                                    {{__('ناتمام')}}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 flex gap-x-3 justify-center whitespace-no-wrap text-sm leading-5 text-coll-gray-900">
                                            <x-buttons.show-button href="{{route('tasks.show',$task->id)}}"/>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="px-4 py-3 border-b text-center">
                                        <td colspan="6"
                                            class="py-6 px-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">
                                            {{__('رکوردی یافت نشد...')}}
                                        </td>
                                    </tr>
                                @endforelse
                            </x-slot>
                        </x-table.table>
                        <span class="p-2 mx-2">
                            {{ $this->tasks->withQueryString()->links(data:['scrollTo'=>false]) }}
                        </span>
                    </div>
                </div>
            </div>
        </section>
    </div>

</div>
