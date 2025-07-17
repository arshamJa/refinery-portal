@php use App\Enums\MeetingStatus;use App\Enums\MeetingUserStatus;use App\Enums\UserPermission;use App\Enums\UserRole; @endphp
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
        <li>
            <span
                class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                {{__('جدول جلسات')}}
            </span>
        </li>
    </x-breadcrumb>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        @can('has-permission-and-role', [UserPermission::SCRIPTORIUM_PERMISSIONS,UserRole::ADMIN])
            <a href="{{ route('meeting.create') }}"
               class="bg-[#FCF7F8] hover:ring-2 hover:ring-blue-400 hover:ring-offset-2 text-black shadow-lg flex gap-3 items-center justify-start transition-all duration-300 ease-in-out p-4 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span class="text-sm font-medium">
                         {{ __('ایجاد جلسه جدید') }}
                         </span>
            </a>
        @endcan
        <a href="{{ route('my.task.table') }}"
           class="bg-[#FCF7F8] hover:ring-2 hover:ring-blue-400 hover:ring-offset-2 text-black shadow-lg flex gap-3 items-center justify-start transition-all duration-300 ease-in-out p-4 rounded-lg">
            <span class="text-sm font-medium">
                {{ __('اقدامات من') }}
            </span>
        </a>
        <a href="{{route('received.message')}}"
           class="bg-[#FCF7F8] hover:ring-2 hover:ring-blue-400 hover:ring-offset-2 text-black shadow-lg flex gap-3 items-center justify-start transition-all duration-300 ease-in-out p-4 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-5">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
            </svg>
            <h3 class="text-sm font-semibold">  {{__('پیام های دریافتی')}}</h3>
            @if($this->unreadReceivedCount() > 0)
                <span class="ml-2 bg-[#FF7F50] text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                            {{ $this->unreadReceivedCount() > 10 ? '+10' : $this->unreadReceivedCount() }}
                        </span>
            @endif
        </a>
        <a href="{{route('sent.message')}}"
           class="bg-[#FCF7F8] hover:ring-2 hover:ring-blue-400 hover:ring-offset-2 text-black shadow-lg flex gap-3 items-center justify-start transition-all duration-300 ease-in-out p-4 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-5">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
            </svg>
            <h3 class="text-sm font-semibold">  {{__('پیام های ارسالی')}}</h3>
        </a>
    </div>


    <form wire:submit="filterMeetings"
          class="flex flex-col sm:flex-row items-center justify-between gap-4 py-4 bg-white border-gray-200 rounded-t-xl">
        <div class="grid gap-4 px-3 sm:px-0 lg:grid-cols-6 items-end">
            <!-- Search Input -->
            <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                <x-input-label for="search" value="{{ __('جست و جو') }}"/>
                <x-search-input>
                    <x-text-input type="text" id="search" wire:model="search" class="block ps-10"
                                  placeholder="{{ __('عبارت مورد نظر را وارد کنید...') }}"/>
                </x-search-input>
            </div>

            <!-- Status Filter -->
            <div class="col-span-6 sm:col-span-1">
                <x-input-label for="statusFilter" value="{{ __('وضعیت جلسه') }}"/>
                <x-select-input id="statusFilter" wire:model="statusFilter">
                    <option value="">{{__('همه وضعیت‌ها')}}</option>
                    <option value="{{MeetingStatus::PENDING->value}}">{{__('در حال بررسی دعوتنامه')}}</option>
                    <option value="{{MeetingStatus::IS_CANCELLED->value}}">{{__('لغو شد')}}</option>
                    <option value="{{MeetingStatus::IS_NOT_CANCELLED->value}}">{{__('تشکیل می‌شود')}}</option>
                    <option value="{{MeetingStatus::IS_IN_PROGRESS->value}}">{{__('در حال برگزاری')}}</option>
                    <option value="{{MeetingStatus::IS_FINISHED->value}}">{{__('خاتمه یافت')}}</option>
                </x-select-input>
            </div>

            <div class="col-span-6 sm:col-span-1">
                <x-input-label for="scriptoriumFilter" value="{{ __('دبیرجلسات') }}"/>
                <x-select-input id="scriptoriumFilter" wire:model="scriptoriumFilter">
                    <option value="all">{{__('همه جلسات')}}</option>
                    <option value="mine">{{__('جلسات من')}}</option>
                </x-select-input>
            </div>

            @can('has-permission-and-role', [UserPermission::SCRIPTORIUM_PERMISSIONS,UserRole::ADMIN])
                <!-- Date Inputs (side-by-side) -->
                <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <x-input-label for="start_date" value="{{ __('تاریخ شروع') }}"/>
                            <x-date-input>
                                <x-text-input id="start_date" wire:model="start_date" class="block ps-10"/>
                            </x-date-input>
                        </div>
                        <div class="flex-1">
                            <x-input-label for="end_date" value="{{ __('تاریخ پایان') }}"/>
                            <x-date-input>
                                <x-text-input id="end_date" wire:model="end_date" class="block ps-10"/>
                            </x-date-input>
                        </div>
                    </div>
                </div>
            @endcan
            <!-- Search + Show All Buttons -->
            <div class="col-span-6 lg:col-span-2 flex justify-start flex-row gap-4 mt-4 lg:mt-0">
                <x-search-button>{{ __('جست و جو') }}</x-search-button>
                @if($search || $start_date || $statusFilter !== '' || $scriptoriumFilter !== '')
                    <x-view-all-link href="{{ route('dashboard.meeting') }}">
                        {{ __('نمایش همه') }}
                    </x-view-all-link>
                @endif
            </div>
            @can('has-permission-and-role', [UserPermission::SCRIPTORIUM_PERMISSIONS,UserRole::ADMIN])
                <!-- Export Button under the right group -->
                <div class="col-span-6 lg:col-start-5 lg:col-span-2 flex justify-start lg:justify-end mt-2">
                    <x-export-link wire:click.prevent="exportExcel" wire:loading.attr="disabled"
                                   class="relative">
                        {{-- Spinner while loading --}}
                        <svg wire:loading wire:target="exportExcel"
                             class="animate-spin h-5 w-5 mr-2 text-white"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                        </svg>
                        {{-- Button Text --}}
                        <span wire:loading.remove wire:target="exportExcel">
                            {{ __('خروجی Excel') }}
                        </span>
                        <span wire:loading wire:target="exportExcel">
                            {{ __('در حال دریافت...') }}
                        </span>
                    </x-export-link>
                </div>
            @endcan

        </div>
    </form>

    <div class="relative overflow-visible shadow-md rounded-t-md mb-12 mt-4" wire:poll.visible.60s>
        <x-table.table>
            <x-slot name="head">
                <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                    @foreach ([
                              '#','موضوع جلسه','دبیر جلسه','نقش شما','واحد متولی جلسه','تاریخ','ساعت',
//                              'رد/تایید(جلسه)',
                              'وضعیت جلسه','اتاق جلسه','جزئیات'
                          ] as $th)
                        <x-table.heading
                            class="px-6 py-3 {{ $loop->first ? '' : 'border-r border-gray-200 dark:border-gray-700' }}">
                            {{ __($th) }}
                        </x-table.heading>
                    @endforeach
                </x-table.row>
            </x-slot>
            <x-slot name="body">
                @forelse($this->meetings as $meeting)
                    <x-table.row wire:key="meeting-{{ $meeting->id }}"
                                 class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-50">
                        <x-table.cell>{{ ($this->meetings->currentPage() - 1) * $this->meetings->perPage() + $loop->iteration }}</x-table.cell>
                        <x-table.cell>{{$meeting->title}}</x-table.cell>
                        <x-table.cell>{{ $meeting->scriptorium->user_info->full_name ?? '—' }}</x-table.cell>
                        <x-table.cell>{{$meeting->getRoleForUser(auth()->user())}}</x-table.cell>
                        <x-table.cell>{{ $meeting->scriptorium->user_info->department->department_name ?? '—' }}</x-table.cell>
                        <x-table.cell>{{$meeting->date}}</x-table.cell>
                        <x-table.cell
                            class="whitespace-nowrap">{{ $meeting->time }}{{ $meeting->end_time ? ' - '.$meeting->end_time : '' }}</x-table.cell>
                        <x-table.cell class="whitespace-nowrap">
                            <span class="{{ $meeting->status->badgeColor() }} text-xs font-medium px-3 py-1 rounded-lg">
                                {{ $meeting->status->label() }}
                            </span>
                        </x-table.cell>
                        <x-table.cell class="whitespace-nowrap">
                            @if($meeting->status == MeetingStatus::IS_NOT_CANCELLED && auth()->id() === $meeting->scriptorium_id)
                                <button wire:click="startMeeting({{ $meeting->id }})"
                                        class="w-full px-4 py-2 rounded-lg text-xs font-semibold transition duration-300 ease-in-out bg-pink-500 text-white hover:bg-pink-600 hover:outline-none hover:ring-2 hover:ring-pink-500 hover:ring-offset-2">
                                    {{ __('شروع جلسه') }}
                                </button>
                            @elseif($meeting->status == MeetingStatus::IS_IN_PROGRESS || $meeting->status == MeetingStatus::IS_FINISHED)
                                <a href="{{ route('view.task.page',  $meeting->id) }}"
                                   class="w-full px-4 py-2 rounded-lg text-xs font-semibold transition duration-300 ease-in-out bg-neutral-600 text-white hover:bg-neutral-700 hover:outline-none hover:ring-2 hover:ring-neutral-500 hover:ring-offset-2">
                                    {{ __('نمایش صورتجلسه') }}
                                </a>
                            @else
                                {{__('---')}}
                            @endif
                        </x-table.cell>
                        <x-table.cell class="relative">
                            <x-dropdown>
                                <x-slot name="trigger">
                                    <button class="hover:bg-gray-200 rounded-full p-1 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                             class="w-5 h-5 text-gray-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link wire:click.prevent="view({{$meeting->id}})">
                                        {{ __('نمایش') }}
                                    </x-dropdown-link>
                                    @if($meeting->status === MeetingStatus::PENDING &&
                                            auth()->id() === $meeting->scriptorium_id)
                                        <x-dropdown-link href="{{ route('meeting.edit', $meeting->id) }}">
                                            {{ __('ویرایش') }}
                                        </x-dropdown-link>
                                    @endif
                                    @if(($meeting->status === MeetingStatus::PENDING || $meeting->status === MeetingStatus::IS_CANCELLED) &&
                                        auth()->id() === $meeting->scriptorium_id)
                                        <x-dropdown-link wire:click.prevent="deleteMeeting({{ $meeting->id }})"
                                                         class="text-red-600">
                                            {{ __('حذف') }}
                                        </x-dropdown-link>
                                    @endif
                                </x-slot>
                            </x-dropdown>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="11" class="py-6">
                            {{__('رکوردی یافت نشد...')}}
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-table.table>
    </div>
    <div class="mt-2">
        {{ $this->meetings->withQueryString()->links(data: ['scrollTo' => false]) }}
    </div>

    <x-modal name="view-meeting-modal" maxWidth="4xl" :closable="false">
        @if ($selectedMeeting)
            @php
                $guests = is_string($selectedMeeting->guest)
                    ? json_decode($selectedMeeting->guest, true)
                    : (is_array($selectedMeeting->guest) ? $selectedMeeting->guest : []);

                $innerGuests = $selectedMeeting->meetingUsers->where('is_guest', true);
                $participants = $selectedMeeting->meetingUsers->where('is_guest', false)
                ->filter(fn($mu) => $mu->user_id !== $selectedMeeting->boss_id);
            @endphp

            <div class="p-6 max-h-[85vh] overflow-y-auto text-sm text-gray-800 dark:text-gray-200 space-y-10">

                {{--                 Meeting Title--}}
                <div class="flex items-center justify-between border-b pb-4">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $selectedMeeting->title }}</h2>
                    </div>
                    <a href="{{route('dashboard.meeting')}}"
                       class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </div>
                {{--                 Meeting Info--}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-[15px]">
                    <div
                        class="p-4 rounded-xl shadow-md space-y-2 text-sm border bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600">
                        <div>
                            <strong>{{ __('رئیس جلسه:') }}</strong>{{ $selectedMeeting->boss->user_info->full_name ?? '---' }}
                        </div>
                        <div>
                            <strong>{{ __('واحد رئیس:') }}</strong>{{ $selectedMeeting->boss->user_info->department->department_name ?? '---' }}
                        </div>
                        <div>
                            <strong>{{ __('سمت رئیس:') }}</strong>{{ $selectedMeeting->boss->user_info->position ?? '---' }}
                        </div>
                    </div>
                    <div
                        class="p-4 rounded-xl shadow-md space-y-2 text-sm border bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600">
                        <div>
                            <strong>{{ __('دبیر جلسه:') }}</strong>{{ $selectedMeeting->scriptorium->user_info->full_name ?? '---' }}
                        </div>
                        <div>
                            <strong>{{ __('واحد دبیر:') }}</strong>{{ $selectedMeeting->scriptorium->user_info->department->department_name ?? '---' }}
                        </div>
                        <div>
                            <strong>{{ __('سمت دبیر:') }}</strong>{{ $selectedMeeting->scriptorium->user_info->position ?? '---' }}
                        </div>
                    </div>
                    <div
                        class="p-4 rounded-xl shadow-md space-y-2 text-sm border bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600">
                        <div><strong>{{ __('مکان:') }}</strong> {{ $selectedMeeting->location }}</div>
                        <div><strong>{{ __('زمان:') }}</strong> {{ $selectedMeeting->time }}</div>
                        <div><strong>{{ __('تاریخ:') }}</strong>{{ $selectedMeeting->date}}</div>
                    </div>
                    <div
                        class="p-4 rounded-xl shadow-md space-y-2 text-sm border bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600">
                        <div><strong>{{ __('کمیته یا واحد برگزار کننده:') }}</strong> {{ $selectedMeeting->unit_held}}
                        </div>
                        <div><strong>{{ __('پذیرایی:') }}</strong> {{ $selectedMeeting->treat ? 'دارد' : 'ندارد' }}
                        </div>
                    </div>
                </div>
                {{--                 Participants--}}
                <div>
                    <h3 class="text-xl font-bold border-b pb-2 mb-4">{{ __('اعضای جلسه') }}</h3>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($participants as $user)
                            @php
                                $status = $user->is_present;
                                $statusClass  = match($status) {
                                    MeetingUserStatus::IS_PRESENT->value => 'bg-green-400',
                                    MeetingUserStatus::IS_NOT_PRESENT->value => 'bg-[#E96742]',
                                    MeetingUserStatus::PENDING->value => 'bg-gray-100 border-gray-200',
                                };
                                $replacement = ($user->is_present == -1 && $user->replacementName()) ? $user->replacementName() : null;
                                $reason = $status == -1 ? $user->reason_for_absent : null;
                            @endphp
                            <x-meeting-card
                                :name="$user->user->user_info->full_name ?? 'N/A'"
                                :unit="$user->user->user_info->department->department_name ?? 'N/A'"
                                :position="$user->user->user_info->position ?? 'N/A'"
                                :statusClass="$statusClass"
                                :replacement="$replacement"
                                :reason="$reason"
                            />
                        @endforeach
                    </div>
                </div>
                {{--                 Inner Guests--}}
                <div>
                    <h3 class="text-xl font-bold border-b pb-2 mb-4">{{ __('مهمانان درون سازمانی') }}</h3>
                    @if ($innerGuests->isNotEmpty())
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($innerGuests as $user)
                                @php
                                    $status = $user->is_present;
                                    $statusClass = match($status) {
                                        MeetingUserStatus::IS_PRESENT->value => 'bg-green-400',
                                        MeetingUserStatus::IS_NOT_PRESENT->value => 'bg-[#E96742]',
                                        MeetingUserStatus::PENDING->value => 'bg-gray-100 border-gray-200',
                                    };
                                    $replacement = ($status == -1 && $user->replacementName()) ? $user->replacementName() : null;
                                    $reason = $status == -1 ? $user->reason_for_absent : null;
                                @endphp
                                <x-meeting-card
                                    :name="$user->user->user_info->full_name ?? 'N/A'"
                                    :unit="$user->user->user_info->department->department_name ?? 'N/A'"
                                    :position="$user->user->user_info->position ?? 'N/A'"
                                    :statusClass="$statusClass"
                                    :replacement="$replacement"
                                    :reason="$reason"
                                />
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">{{ __('مهمان درون سازمانی وجود ندارد') }}</p>
                    @endif
                </div>
                {{--                 Outer Guests--}}
                <div>
                    <h3 class="text-xl font-bold border-b pb-2 mb-4">{{ __('مهمانان برون سازمانی') }}</h3>
                    @if (!empty($guests))
                        <ul class="space-y-2">
                            @foreach ($guests as $guest)
                                <li class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg text-sm shadow">
                                    <div class="font-medium">
                                        {{ __('نام:') }} {{ $guest['name'] ?? 'نام ندارد' }}
                                    </div>
                                    @if (!empty($guest['companyName']))
                                        <div class="text-gray-500">{{ __('شرکت:') }} {{ $guest['companyName'] }}</div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">{{ __('مهمان برون سازمانی وجود ندارد') }}</p>
                    @endif
                </div>
                {{--                 Buttons--}}
                <div class="flex justify-between gap-4 pt-6">
                    @if($selectedMeeting->status === MeetingStatus::PENDING)
                        @can('handle-own-meeting',$meeting)
                            <div>
                                <x-primary-button wire:click="acceptMeeting({{$selectedMeeting->id}})" class="ml-2">
                                    {{ __('تایید جلسه') }}
                                </x-primary-button>
                                <x-danger-button wire:click="denyMeeting({{$selectedMeeting->id}})">
                                    {{ __('لغو جلسه') }}
                                </x-danger-button>
                            </div>
                        @endcan
                    @endif
                    <div>
                        <a href="{{route('dashboard.meeting')}}">
                            <x-secondary-button>
                                {{ __('بستن') }}
                            </x-secondary-button>
                        </a>
                    </div>
                </div>

            </div>
        @endif
    </x-modal>
    <x-modal name="delete-meeting-modal" maxWidth="4xl" :closable="false">
        @if ($selectedMeeting)
            @if(auth()->id() === $selectedMeeting->scriptorium_id)
                <form method="POST" action="{{ route('meeting.destroy', $selectedMeeting->id) }}">
                    @csrf
                    @method('DELETE')
                    <div class="flex flex-row px-6 py-4 bg-gray-100 text-start">
                        <p class="text-xl font-bold text-red-600 dark:text-red-400">
                            {{ __('آیا از حذف جلسه زیر اطمینان دارید؟') }}
                        </p>
                    </div>
                    <div class="px-6 py-4" dir="rtl">
                        <div class="mt-4 text-sm text-gray-600">
                            {{--                                                     Show a bit of meeting info for confirmation--}}
                            <ul class="list-disc list-inside text-sm space-y-2">
                                <li><strong>{{ __('عنوان جلسه:') }}</strong> {{ $selectedMeeting->title }}</li>
                                <li><strong>{{ __('تاریخ:') }}</strong> {{ $selectedMeeting->date }}</li>
                                <li><strong>{{ __('زمان:') }}</strong> {{ $selectedMeeting->time }}</li>
                            </ul>

                            <p class="text-xs text-red-500 dark:text-red-300 mt-2">
                                {{ __('توجه: این عمل غیرقابل بازگشت است و تمام اطلاعات مرتبط با این جلسه (شامل اعضا و مهمانان) حذف خواهند شد.') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-row justify-between px-6 py-4 bg-gray-100">
                        <x-primary-button type="submit">
                            {{ __('حذف جلسه') }}
                        </x-primary-button>
                        <x-cancel-button x-on:click="$dispatch('close')" class="text-gray-700 dark:text-gray-300">
                            {{ __('لغو') }}
                        </x-cancel-button>
                    </div>
                </form>
            @endif
        @endif
    </x-modal>


</div>
