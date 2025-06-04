<div>

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
            <a href="{{route('dashboard.meeting')}}"
               class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                <span>{{__('جلسات')}}</span>
            </a>
        </li>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
             stroke="currentColor" class="w-3 h-3 text-gray-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
        </svg>
        <li>
            <span
                class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
            <span>{{__('اقدامات من')}}</span>
            </span>
        </li>
    </x-breadcrumb>


    <form wire:submit="applyFilters"
          class="flex flex-col sm:flex-row items-center justify-between gap-4 py-4 bg-white border-b border-gray-200 rounded-t-xl">
        <div class="grid gap-4 px-3 w-full sm:px-0 lg:grid-cols-6 items-end">
            <!-- Search Input -->
            <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                <x-input-label for="search" value="{{ __('جست و جو') }}"/>
                <x-search-input>
                    <x-text-input type="text" id="search" wire:model.debounce="search" class="block ps-10"
                                  placeholder="{{ __('عبارت مورد نظر را وارد کنید...') }}"/>
                </x-search-input>
            </div>

            <!-- Status Filter -->
            <div class="col-span-6 sm:col-span-1 ">
                <x-input-label for="statusFilter" value="{{ __('وضعیت اقدامات') }}"/>
                <x-select-input id="statusFilter" wire:model="statusFilter">
                    <option value="">{{__('همه وضعیت‌ها')}}</option>
                    <option value="1">{{__('انجام دادم')}}</option>
                    <option value="0">{{__('انجام ندادم')}}</option>
                </x-select-input>
            </div>
            <!-- Search + Show All Buttons -->
            <div class="col-span-6 lg:col-span-3 flex justify-start md:justify-end flex-row gap-4 mt-4 lg:mt-0">
                <x-search-button>{{ __('جست و جو') }}</x-search-button>
                @if($search !== '' || $statusFilter !== null)
                    <x-view-all-link href="{{ route('my.task.table') }}">
                        {{ __('نمایش همه') }}
                    </x-view-all-link>
                @endif
            </div>

        </div>
    </form>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-12">
        <x-table.table>
            <x-slot name="head">
                <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                    @foreach (['#','موضوع جلسه','خلاصه مذاکره','مهلت انجام اقدام','شرح اقدام','تاریخ ارسال اقدام','',''] as $th)
                        <x-table.heading
                            class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}">
                            {{ __($th) }}
                        </x-table.heading>
                    @endforeach
                </x-table.row>
            </x-slot>
            <x-slot name="body">
                    @forelse($this->taskUsers as $taskUser)
                        <x-table.row wire:key="task-{{ $taskUser->id }}" class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-50">
                            <x-table.cell>{{ ($this->taskUsers->currentPage() - 1) * $this->taskUsers->perPage() + $loop->iteration }}</x-table.cell>
                            <x-table.cell>{{ $taskUser->task->meeting->title ?? '-' }}</x-table.cell>
                            <x-table.cell>
                                {{ Str::words($taskUser->task->body?? '---' , 5 , '...')}}
                            </x-table.cell>
                            <x-table.cell>{{ $taskUser->task->time_out ?? '-' }}</x-table.cell>
                            <x-table.cell>{{ Str::words($taskUser->body_task ?? '---' , 5 , '...')}}</x-table.cell>
                            <x-table.cell>{{ $taskUser->sent_date ?? '---' }}</x-table.cell>
                            <x-table.cell>
                                <a href="{{route('view.task.page', $taskUser->task->meeting->id )}}">
                                    <x-secondary-button>
                                        {{__('نمایش صورتجلسه')}}
                                    </x-secondary-button>
                                </a>
                            </x-table.cell>
                            <x-table.cell>
                                <x-primary-button wire:click.prevent="view({{$taskUser->id}})">
                                    {{ __('نمایش جزئیات') }}
                                </x-primary-button>
                                {{--                                <x-dropdown>--}}
                                {{--                                    <x-slot name="trigger">--}}
                                {{--                                        <button class="hover:bg-gray-200 rounded-full p-1 transition">--}}
                                {{--                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"--}}
                                {{--                                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"--}}
                                {{--                                                 class="w-5 h-5 text-gray-600">--}}
                                {{--                                                <path stroke-linecap="round" stroke-linejoin="round"--}}
                                {{--                                                      d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>--}}
                                {{--                                            </svg>--}}
                                {{--                                        </button>--}}
                                {{--                                    </x-slot>--}}
                                {{--                                    <x-slot name="content">--}}
                                {{--                                        <x-dropdown-link wire:click.prevent="view({{$taskUser->id}})">--}}
                                {{--                                            {{ __('نمایش جزئیات') }}--}}
                                {{--                                        </x-dropdown-link>--}}
                                {{--                                        @if($meeting->status != MeetingStatus::IS_CANCELLED->value and $meeting->status != MeetingStatus::IS_NOT_CANCELLED->value)--}}
                                {{--                                            <x-dropdown-link href="#">--}}
                                {{--                                                {{ __('ویرایش') }}--}}
                                {{--                                            </x-dropdown-link>--}}
                                {{--                                        @endif--}}
                                {{--                                        @if($meeting->status == MeetingStatus::PENDING->value or $meeting->status == MeetingStatus::IS_CANCELLED->value)--}}
                                {{--                                            <x-dropdown-link wire:click.prevent="deleteMeeting({{$meeting->id}})"--}}
                                {{--                                                             class="text-red-600 ">--}}
                                {{--                                                {{ __('حذف') }}--}}
                                {{--                                            </x-dropdown-link>--}}
                                {{--                                        @endif--}}
                                {{--                                    </x-slot>--}}
                                {{--                                </x-dropdown>--}}
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.row>
                            <x-table.cell colspan="7"
                                          class="text-center text-sm text-gray-600">{{ __('پیام جدیدی وجود ندارد') }}</x-table.cell>
                        </x-table.row>
                    @endforelse
            </x-slot>
        </x-table.table>
    </div>
    <div class="mt-2">
        {{ $this->taskUsers->withQueryString()->links(data: ['scrollTo' => false]) }}
    </div>

</div>
