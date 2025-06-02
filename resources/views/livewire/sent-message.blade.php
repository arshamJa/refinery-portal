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
            {{__('پیام های ارسالی')}}
        </span>
        </li>
    </x-breadcrumb>


    <div class="flex justify-center items-center mb-8 gap-4">
        <a href="{{ route('sent.message') }}" wire:navigate wire:click="$set('activeTab', 'sent')"
           class="px-6 py-4 rounded-lg transition-all duration-300 ease-in-out shadow-lg outline-none focus:outline-none focus:ring-2 focus:ring-offset-2 hover:ring-2 hover:ring-offset-2 hover:ring-blue-400
       {{ $activeTab === 'sent' ? 'bg-[#FF6F61] text-white' : 'bg-white text-gray-700' }}">
        <span class="text-sm font-medium">
            {{ __('پیام های ارسالی') }}
        </span>
        </a>
        <a href="{{ route('received.message') }}" wire:navigate wire:click="$set('activeTab', 'received')"
           class="px-6 py-4 rounded-lg transition-all duration-300 ease-in-out shadow-lg outline-none focus:outline-none focus:ring-2 focus:ring-offset-2 hover:ring-2 hover:ring-offset-2 hover:ring-blue-400
       {{ $activeTab === 'received' ? 'bg-[#FF6F61] text-white' : 'bg-white text-gray-700' }}">
        <span class="text-sm font-medium">
            {{ __('پیام های دریافتی') }}
        </span>
        </a>
    </div>

    <form wire:submit.prevent="filterMessage" class="flex flex-col sm:flex-row items-start sm:items-end gap-4 mb-6">
        {{-- Message Type Dropdown --}}
        <div class="w-full sm:w-64">
            <x-input-label for="filter" value="{{ __('نوع پیام') }}"/>
            <x-select-input id="filter" wire:model.defer="filter">
                <option value="">{{ __('همه پیام‌ها') }}</option>
                <option value="MeetingInvitation">{{ __('دعوتنامه') }}</option>
                <option value="MeetingGuestInvitation">{{ __('دعوتنامه به مهمان') }}</option>
                <option value="MeetingConfirmed">{{ __(' برگزاری جلسه') }}</option>
                <option value="MeetingCancelled">{{ __(' لغو جلسه') }}</option>
                <option value="AcceptInvitation">{{ __('تایید دعوتنامه') }}</option>
                <option value="DenyInvitation">{{ __('رد دعوتنامه') }}</option>
                <option value="ReplacementForMeeting">{{ __('انتخاب جانشین') }}</option>
                <option value="AssignedNewTask">{{ __('ارسال اقدامات') }}</option>
                <option value="UpdatedTaskTimeOut">{{ __('ویرایش مهلت اقدام') }}</option>
                <option value="UpdatedTaskBody">{{ __('ویرایش بند مذاکره') }}</option>
                <option value="DeniedTaskNotification">{{ __('رد اقدام') }}</option>
            </x-select-input>
        </div>


        <div class="col-span-6 lg:col-span-2 flex justify-start flex-row gap-4 mt-4 lg:mt-0">
            <x-search-button>{{ __('جست و جو') }}</x-search-button>
            @if($filter !== '')
                <x-view-all-link href="{{ route('sent.message') }}">
                    {{ __('نمایش همه') }}
                </x-view-all-link>
            @endif
        </div>
    </form>


    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-12" wire:poll.visible.60s>
        <x-table.table>
            <x-slot name="head">
                <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                    @foreach (['نوع پیام','تاریخ ارسال پیام', 'گیرنده(دبیر/کاربر)', 'متن'] as $th)
                        <x-table.heading
                            class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}">
                            {{ __($th) }}
                        </x-table.heading>
                    @endforeach
                </x-table.row>
            </x-slot>
            <x-slot name="body">
                @forelse ($this->userNotifications as $notification)
                    <x-table.row wire:key="notification-{{ $notification->id }}"
                                 class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-50">
                        <x-table.cell class="border-none">
                            @if($notification->type === 'MeetingInvitation')
                                <span class="text-indigo-600 font-semibold">{{ __('دعوتنامه') }}</span>
                            @elseif($notification->type === 'MeetingGuestInvitation')
                                <span class="text-purple-600 font-semibold">{{ __('دعوتنامه به مهمان') }}</span>
                            @elseif($notification->type === 'MeetingConfirmed')
                                <span class="text-green-700 font-semibold">{{ __(' برگزاری جلسه') }}</span>
                            @elseif($notification->type === 'MeetingCancelled')
                                <span class="text-red-600 font-semibold">{{ __(' لغو جلسه') }}</span>
                            @elseif($notification->type === 'AcceptInvitation')
                                <span class="text-emerald-600 font-semibold">{{ __('تایید دعوتنامه') }}</span>
                            @elseif($notification->type === 'ReplacementForMeeting')
                                <span class="text-yellow-600 font-semibold">{{ __('انتخاب جانشین') }}</span>
                            @elseif($notification->type === 'DenyInvitation')
                                <span class="text-orange-600 font-semibold">{{ __('رد دعوتنامه') }}</span>
                            @elseif($notification->type === 'AssignedNewTask')
                                <span class="text-blue-700 font-semibold">{{ __('ارسال اقدام') }}</span>
                            @elseif($notification->type === 'UpdatedTaskTimeOut')
                                <span class="text-cyan-700 font-semibold">{{ __('ویرایش مهلت اقدام') }}</span>
                            @elseif($notification->type === 'UpdatedTaskBody')
                                <span class="text-sky-700 font-semibold">{{ __('ویرایش بند مذاکره') }}</span>
                                @elseif($notification->type === 'DeniedTaskNotification')
                                    <span class="text-red-600 font-semibold">{{ __('رد اقدام') }}</span>
                            @endif
                        </x-table.cell>
                        <x-table.cell class="whitespace-nowrap">{{ $this->getSentNotificationDateTime($notification) }}</x-table.cell>
                        <x-table.cell>{{ $notification->recipient->user_info->full_name ?? 'N/A' }}</x-table.cell>
                        <x-table.cell>{{ $this->getNotificationMessage($notification) }}</x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="5" class="text-center text-sm text-gray-600">{{ __('پیام جدیدی وجود ندارد') }}</x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-table.table>
    </div>
    <div class="mt-2 mb-10">
        {{ $this->userNotifications->withQueryString()->links(data: ['scrollTo' => false]) }}
    </div>

</div>
