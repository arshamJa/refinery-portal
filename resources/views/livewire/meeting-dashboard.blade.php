@php use App\Enums\MeetingStatus; @endphp
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
                {{__('جلسات')}}
            </span>
        </li>
    </x-breadcrumb>

    <div class="mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @can('create-meeting')
                <a href="{{ route('meeting.create') }}"
                   class="inline-flex items-center gap-3 px-6 py-4 rounded-lg text-white bg-gradient-to-r from-[#0A74DA] to-[#34B3F1] transition-all duration-300 ease-in-out shadow-lg hover:outline-none hover:ring-2 hover:ring-offset-2 hover:ring-[#0A74DA] disabled:opacity-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <span class="text-sm font-medium">
                         {{ __('ایجاد جلسه جدید') }}
                         </span>
                </a>
            @endcan
                <a href="{{ route('my.task.table') }}"
                   class="inline-flex items-center gap-3 px-6 py-4 rounded-lg text-white bg-gradient-to-r from-[#FF6F61] to-[#F2A900] transition-all duration-300 ease-in-out shadow-lg hover:outline-none hover:ring-2 hover:ring-offset-2 hover:ring-[#FF6F61] disabled:opacity-50">
            <span class="text-sm font-medium">
                {{ __('اقدامات من') }}
            </span>
                </a>
        </div>
    </div>

        <form wire:submit="filterMeetings"
              class="flex flex-col sm:flex-row items-center justify-between gap-4 py-4 bg-white border-b border-gray-200 rounded-t-xl">
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

                <!-- Search + Show All Buttons -->
                <div class="col-span-6 lg:col-span-2 flex justify-start flex-row gap-4 mt-4 lg:mt-0">
                    <x-search-button>{{ __('جست و جو') }}</x-search-button>
                    @if($search || $start_date || $statusFilter !== '' || $scriptoriumFilter !== '')
                        <x-view-all-link href="{{ route('dashboard.meeting') }}">
                            {{ __('نمایش همه') }}
                        </x-view-all-link>
                    @endif
                </div>

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


            </div>
        </form>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-12" wire:poll.visible.60s>
        <x-table.table>
            <x-slot name="head">
                <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                    @foreach ([
                              '#','موضوع جلسه','دبیر جلسه','واحد سازمانی','تاریخ','ساعت','رد/تایید(جلسه)','وضعیت جلسه','اتاق جلسه','جزئیات'
                          ] as $th)
                        <x-table.heading
                            class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}">
                            {{ __($th) }}
                        </x-table.heading>
                    @endforeach
                </x-table.row>
            </x-slot>
            <x-slot name="body">
                <x-table.row
                    class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-50">
                    @forelse($this->meetings as $meeting)
                        <x-table.row wire:key="meeting-{{ $meeting->id }}" class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-50">
                            <x-table.cell>{{ ($this->meetings->currentPage() - 1) * $this->meetings->perPage() + $loop->iteration }}</x-table.cell>
                            <x-table.cell>{{$meeting->title}}</x-table.cell>
                            <x-table.cell>{{$meeting->scriptorium}}</x-table.cell>
                            <x-table.cell>{{$meeting->unit_organization}}</x-table.cell>
                            <x-table.cell>{{$meeting->date}}</x-table.cell>
                            <x-table.cell>{{ $meeting->time }}{{ $meeting->end_time ? ' - '.$meeting->end_time : '' }}</x-table.cell>
                            @if(auth()->user()->user_info->full_name === $meeting->scriptorium)
                                <x-table.cell>
                                    <a href="{{route('presentUsers',$meeting->id)}}">
                                        <x-secondary-button>
                                            {{__('نمایش')}}
                                        </x-secondary-button>
                                    </a>
                                </x-table.cell>
                            @else
                                <x-table.cell>
                                    {{__('---')}}
                                </x-table.cell>
                            @endif
                            <x-table.cell class="whitespace-nowrap">
                                @switch($meeting->status)
                                    @case(App\Enums\MeetingStatus::PENDING)
                                        <span class="bg-yellow-100 text-yellow-600 text-sm font-medium px-3 py-1 rounded-lg">
                                            {{ __('درحال بررسی دعوتنامه') }}
                                        </span>
                                        @break
                                    @case(App\Enums\MeetingStatus::IS_CANCELLED)
                                        <span class="bg-red-100 text-red-600 text-sm font-medium px-3 py-1 rounded-lg">
                                            {{ __('جلسه لغو شد') }}
                                        </span>
                                        @break
                                    @case(App\Enums\MeetingStatus::IS_NOT_CANCELLED)
                                        <span class="bg-green-100 text-green-600 text-sm font-medium px-3 py-1 rounded-lg">
                                            {{ __('جلسه برگزار میشود') }}
                                        </span>
                                        @break
                                    @case(App\Enums\MeetingStatus::IS_IN_PROGRESS)
                                        <span class="bg-blue-100 text-blue-600 text-sm font-medium px-3 py-1 rounded-lg">
                                            {{ __('جلسه درحال برگزاری است') }}
                                        </span>
                                        @break
                                    @case(App\Enums\MeetingStatus::IS_FINISHED)
                                        <span class="bg-gray-100 text-gray-700 text-sm font-medium px-3 py-1 rounded-lg">
                                            {{ __('جلسه خاتمه یافت') }}
                                        </span>
                                        @break
                                @endswitch
                            </x-table.cell>
                            <!-- Start Meeting Button (New Column) -->
                            <x-table.cell class="whitespace-nowrap">
                                @if($meeting->status == MeetingStatus::IS_NOT_CANCELLED && auth()->user()->user_info->full_name === $meeting->scriptorium)
                                    <button wire:click="startMeeting({{ $meeting->id }})"
                                            class="w-full px-4 py-2 rounded-lg text-xs font-semibold transition duration-300 ease-in-out bg-pink-500 text-white hover:bg-pink-600 hover:outline-none hover:ring-2 hover:ring-pink-500 hover:ring-offset-2">
                                        {{ __('شروع جلسه') }}
                                    </button>
                                @elseif($meeting->status == MeetingStatus::IS_IN_PROGRESS || $meeting->status == MeetingStatus::IS_FINISHED)
                                    <a href="{{route('tasks.create',$meeting->id)}}"
                                       class="w-full px-4 py-2 rounded-lg text-xs font-semibold transition duration-300 ease-in-out bg-neutral-600 text-white hover:bg-neutral-700 hover:outline-none hover:ring-2 hover:ring-neutral-500 hover:ring-offset-2">
                                        {{ __('نمایش صورتجلسه') }}
                                    </a>
                                @else
                                    {{__('---')}}
                                @endif
                            </x-table.cell>
                            <x-table.cell>
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
                                        @if($meeting->status === MeetingStatus::PENDING && auth()->user()->user_info->full_name === $meeting->scriptorium)
                                            <x-dropdown-link href="{{route('meeting.edit',$meeting->id)}}">
                                                {{ __('ویرایش') }}
                                            </x-dropdown-link>
                                        @endif
                                        @if(( $meeting->status == MeetingStatus::PENDING || $meeting->status == MeetingStatus::IS_CANCELLED ) &&
                                            auth()->user()->user_info->full_name === $meeting->scriptorium)
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
                </x-table.row>
            </x-slot>
        </x-table.table>
    </div>
    <div class="mt-2">
        {{ $this->meetings->withQueryString()->links(data: ['scrollTo' => false]) }}
    </div>






































    <x-modal name="view-meeting-modal" maxWidth="4xl">
        @if ($selectedMeeting)
            @php
                $guests = is_string($selectedMeeting->guest)
                    ? json_decode($selectedMeeting->guest, true)
                    : (is_array($selectedMeeting->guest) ? $selectedMeeting->guest : []);
                $innerGuests = $selectedMeeting->meetingUsers->where('is_guest', true);
                $participants = $selectedMeeting->meetingUsers->where('is_guest', false);
            @endphp

            <div class="p-6 max-h-[85vh] overflow-y-auto text-sm text-gray-800 dark:text-gray-200 space-y-8">
                {{-- Meeting Title --}}
                <div class="flex items-center justify-between border-b pb-4">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $selectedMeeting->title }}</h2>
                        <p class="text-sm text-gray-500 mt-2">{{ __('جزئیات جلسه') }}</p>
                    </div>
                    <!-- Close Button with X Icon -->
                    <button
                        class="text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none"
                        x-on:click="$dispatch('close')"
                        aria-label="Close modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                </div>

                {{-- Meeting Info --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-[15px]">
                    <x-meeting-info label="{{ __('رئیس جلسه') }}" :value="$selectedMeeting->boss"/>
                    <x-meeting-info label="{{ __('دبیرجلسه') }}" :value="$selectedMeeting->scriptorium"/>
                    <x-meeting-info label="{{ __('واحد سازمانی') }}" :value="$selectedMeeting->unit_organization"/>
                    <x-meeting-info label="{{ __('سمت دبیرجلسه') }}" :value="$selectedMeeting->position_organization"/>
                    <x-meeting-info label="{{ __('مکان') }}" :value="$selectedMeeting->location"/>
                    <x-meeting-info label="{{ __('تاریخ') }}" :value="$selectedMeeting->date"/>
                    <x-meeting-info label="{{ __('زمان') }}" :value="$selectedMeeting->time"/>
                    <x-meeting-info label="{{ __('کمیته یا واحد برگزار کننده') }}"
                                    :value="$selectedMeeting->unit_held"/>
                    <x-meeting-info label="{{ __('پذیرایی') }}" :value="$selectedMeeting->treat ? 'دارد' : 'ندارد'"/>
                    <x-meeting-info label="{{ __('نام درخواست دهنده') }}" :value="$selectedMeeting->applicant"/>
                </div>

                {{-- Participants --}}
                <div>
                    <h3 class="text-xl font-bold border-b pb-2 mb-4">{{ __('اعضای جلسه') }}</h3>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($participants as $user)
                            <x-meeting-card
                                :name="$user->user->user_info->full_name ?? 'N/A'"
                                :unit="$user->user->user_info->department->department_name ?? 'N/A'"
                                :position="$user->user->user_info->position ?? 'N/A'"/>
                        @endforeach
                    </div>
                </div>

                {{-- Outer Guests --}}
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

                {{-- Inner Guests --}}
                <div>
                    <h3 class="text-xl font-bold border-b pb-2 mb-4">{{ __('مهمانان درون سازمانی') }}</h3>
                    @if ($innerGuests->isNotEmpty())
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($innerGuests as $user)
                                <x-meeting-card
                                    :name="$user->user->user_info->full_name ?? 'N/A'"
                                    :unit="$user->user->user_info->department->department_name ?? 'N/A'"
                                    :position="$user->user->user_info->position ?? 'N/A'"/>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">{{ __('مهمان درون سازمانی وجود ندارد') }}</p>
                    @endif
                </div>

            </div>
        @endif
    </x-modal>

    <x-modal name="delete-meeting-modal">
        @if ($selectedMeeting)
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
                        {{--                         Show a bit of meeting info for confirmation--}}
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
                    <x-danger-button type="submit">
                        {{ __('حذف جلسه') }}
                    </x-danger-button>
                    <x-secondary-button x-on:click="$dispatch('close')" class="text-gray-700 dark:text-gray-300">
                        {{ __('لغو') }}
                    </x-secondary-button>
                </div>
            </form>
        @endif
    </x-modal>


</div>
