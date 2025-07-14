@php use App\Enums\UserPermission;use App\Enums\UserRole;@endphp
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
                <span>{{__('جدول جلسات')}}</span>
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
        <span
           class="bg-[#FF6F61] ring-2 ring-offset-2 ring-blue-400 text-white shadow-lg flex gap-3 items-center justify-start pointer-events-none p-4 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-5">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
            </svg>
            <h3 class="text-sm font-semibold">  {{__('پیام های ارسالی')}}</h3>
        </span>
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
                            @php
                                $attrs = $notification->getTypeLabelAttributes();
                            @endphp
                            <span class="font-bold {{ $attrs['text'] }}">
                            {{ $attrs['label'] }}
                        </x-table.cell>
                        <x-table.cell class="whitespace-nowrap">{{ $notification->getNotificationDateTime() }}</x-table.cell>
                        <x-table.cell>{{ $notification->recipient->user_info->full_name ?? 'N/A' }}</x-table.cell>
                        <x-table.cell>{{ $notification->getSentMessage() }}</x-table.cell>
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
