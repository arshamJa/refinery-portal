@php use App\Enums\UserPermission;use App\Enums\UserRole;use App\Models\MeetingUser; @endphp
@php use App\Enums\MeetingUserStatus;use App\Models\User; @endphp
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
            {{__('پیام های دریافتی')}}
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
        <span
            class="bg-[#FF6F61] ring-2 ring-offset-2 ring-blue-400 text-white shadow-lg flex gap-3 items-center justify-start pointer-events-none p-4 rounded-lg">
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
        </span>
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

    <form wire:submit.prevent="filterMessage" class="flex flex-col sm:flex-row items-start sm:items-end gap-4 mb-6">
        {{-- Message Type Dropdown --}}
        <div class="w-full sm:w-64">
            <x-input-label for="filter" value="{{ __('نوع پیام') }}"/>
            <x-select-input id="filter" wire:model.defer="filter">
                <option value="">{{ __('همه پیام‌ها') }}</option>
                <option value="invitation">{{ __('دعوتنامه') }}</option>
                <option value="invitation_response">{{ __('پاسخ دعوتنامه') }}</option>
                <option value="meeting_status">{{ __('برگزاری یا لغو جلسه') }}</option>
                <option value="ReplacementForMeeting">{{ __('انتخاب جانشین') }}</option>
                <option value="UpdatedTask">{{ __('بروزرسانی اقدام') }}</option>
                <option value="AssignedNewTask">{{ __('دریافت اقدام') }}</option>
                <option value="task_action">{{ __('تایید یا رد بند مذاکره') }}</option>
            </x-select-input>
        </div>

        {{-- Message Status Dropdown --}}
        <div class="w-full sm:w-64">
            <x-input-label for="message_status" value="{{ __('وضعیت پیام') }}"/>
            <x-select-input id="message_status" wire:model.defer="message_status">
                <option value="">{{ __('همه وضعیت‌ها') }}</option>
                <option value="unread">{{ __('خوانده نشده') }}</option>
                <option value="read">{{ __('خوانده شده') }}</option>
                <option value="archived">{{ __('بایگانی شده') }}</option>
            </x-select-input>
        </div>

        <div class="col-span-6 lg:col-span-2 flex justify-start flex-row gap-4 mt-4 lg:mt-0">
            <x-search-button>{{ __('جست و جو') }}</x-search-button>
            @if($filter !== '' || $message_status !== '')
                <x-view-all-link href="{{ route('received.message') }}">
                    {{ __('نمایش همه') }}
                </x-view-all-link>
            @endif
        </div>
    </form>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-12" wire:poll.visible.60s>
        <x-table.table>
            <x-slot name="head">
                <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                    @foreach (['نوع پیام','تاریخ دریافت پیام', 'فرستنده', 'متن',
// 'وضعیت جلسه'
 'اقدامات','وضعیت بایگانی'] as $th)
                        <x-table.heading
                            class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}">
                            {{ __($th) }}
                        </x-table.heading>
                    @endforeach
                </x-table.row>
            </x-slot>
            <x-slot name="body">
                @forelse ($this->userNotifications as $notification)
                    <x-table.row
                        wire:key="notification-{{$notification->id}}"
                        wire:click="markAsRead({{ $notification->id }})"
                        class="cursor-pointer {{ !$notification->isReadByRecipient() ? 'bg-yellow-100 font-bold' : 'bg-white' }} hover:bg-gray-50 transition-colors duration-200">
                        <x-table.cell class="border-none">
                            @php
                                $attrs = $notification->getTypeLabelAttributes();
                            @endphp
                            <span class="font-bold {{ $attrs['text'] }}">
                                {{ $attrs['label'] }}
                            </span>
                        </x-table.cell>
                        <x-table.cell
                            class="whitespace-nowrap">{{ $notification->getNotificationDateTime() }}</x-table.cell>
                        <x-table.cell>{{ $notification->sender->user_info->full_name ?? 'N/A' }}
                            <span class="text-sm text-gray-500">({{ $notification->getSenderRoleLabel() }})</span>
                        </x-table.cell>
                        <x-table.cell class="whitespace-normal break-words max-w-xs">
                            {{ $notification->getNotificationMessage() }}
                        </x-table.cell>
                        {{--                                                    <x-table.cell class="whitespace-nowrap">--}}
                        {{--                                                         <span--}}
                        {{--                                                             class="{{$notification->notifiable->status->badgeColor() }} text-sm font-medium px-3 py-1 rounded-lg">--}}
                        {{--                                                            {{ $notification->notifiable->status->label() }}--}}
                        {{--                                                        </span>--}}
                        {{--                                                    </x-table.cell>--}}
                        <x-table.cell class="whitespace-nowrap">
                            @if ($notification->type === 'MeetingInvitation' || $notification->type === 'MeetingGuestInvitation' || $notification->type === 'MeetingBossInvitation' || $notification->type === 'ReplacementForMeeting')
                                {{-- Handle meeting invitation responses --}}
                                @php
                                    $meeting = $notification->notifiable;
                                    $meetingUser = MeetingUser::where('user_id', auth()->id())
                                                    ->where('meeting_id', $meeting->id)
                                                    ->first();

                                    $replacementUserFullName = null;

                                    if ($meetingUser && $meetingUser->replacement) {
                                        $replacementUser = User::with('user_info')->find($meetingUser->replacement);
                                        $replacementUserFullName = $replacementUser?->user_info?->full_name ?? 'N/A';
                                    }
                                @endphp

                                @if ($meetingUser)
                                    @if ($meetingUser->is_present === MeetingUserStatus::PENDING->value)
                                        <div class="flex gap-2 items-center justify-center mt-4 md:mt-0">
                                            <x-accept-button wire:click="acceptMeeting({{ $meeting->id }})">
                                                {{ __('تایید') }}
                                            </x-accept-button>
                                            <x-cancel-button wire:click="openModalDeny({{ $meetingUser->id }})">
                                                {{ __('رد') }}
                                            </x-cancel-button>
                                        </div>
                                    @elseif ($meetingUser->is_present === MeetingUserStatus::IS_PRESENT->value)
                                        <span
                                            class="text-green-600 font-bold">{{ __('شما دعوت به این جلسه را پذیرفتید') }}</span>
                                        @if ($replacementUserFullName)
                                            <span
                                                class="text-green-700">{{ __('و آقا/خانم') }} {{ $replacementUserFullName }} {{ __('به عنوان جانشین خود انتخاب کردید') }}</span>
                                        @endif
                                    @elseif ($meetingUser->is_present === MeetingUserStatus::IS_NOT_PRESENT->value)
                                        <span
                                            class="text-red-600 font-bold">{{ __('شما دعوت به این جلسه را نپذیرفتید') }}</span>
                                    @endif
                                @endif
                            @elseif ($notification->type === 'MeetingConfirmed')
                                <span class="text-green-500 font-bold">{{ __('تایید جلسه توسط دبیر') }}</span>
                            @elseif ($notification->type === 'MeetingCancelled')
                                <span class="text-red-600 font-bold">{{ __('لغو جلسه توسط دبیر') }}</span>
                            @elseif ($notification->isTaskRelated())
                                <a href="{{ route('view.task.page', ['meeting' => $notification->notifiable_id]) }}">
                                    <x-secondary-button>{{ __('نمایش صورتجلسه') }}</x-secondary-button>
                                </a>
                            @else
                                <span class="text-gray-400 text-sm italic">---</span>
                            @endif

                        </x-table.cell>
                        <x-table.cell class="whitespace-nowrap">
                            <div>
{{--                                @if ($notification->isReadByRecipient())--}}
{{--                                    <span class="text-gray-500">{{ __('خوانده شده') }}</span>--}}
{{--                                @else--}}
{{--                                    <span class="text-red-500 font-bold">{{ __('خوانده نشده') }}</span>--}}
{{--                                @endif--}}
                                @if (! $notification->trashed())
                                    <form method="POST" action="{{ route('notifications.archive', $notification->id) }}">
                                        @csrf
                                        <x-secondary-button type="submit">
                                            {{ __('بایگانی') }}
                                        </x-secondary-button>
                                    </form>
                                @else
{{--                                    <form method="POST" action="{{ route('notifications.restore', $notification->id) }}">--}}
{{--                                        @csrf--}}
{{--                                        <x-cancel-button type="submit">--}}
{{--                                            {{ __('بازیابی') }}--}}
{{--                                        </x-cancel-button>--}}
{{--                                    </form>--}}
                                    <span class="text-gray-500 text-sm">{{ __('بایگانی شده') }}</span>
                                @endif
                            </div>
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
        {{ $this->userNotifications->links() }}
    </div>


    <x-modal name="deny-invitation" maxWidth="4xl" :closable="false">
        @if($meetingUserId)
            <form wire:submit="deny" class="text-sm text-gray-800">
                {{-- Header --}}
                <div class="flex justify-between items-center px-6 py-4 bg-gray-100 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800">{{ __('رد دعوت به جلسه') }}</h2>
                    <a href="{{route('received.message')}}"
                       class="text-gray-400 hover:text-red-500 transition duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </div>
                {{-- Body --}}
                <div class="px-6 py-4 space-y-6 text-gray-800 max-h-[70vh] overflow-y-auto" dir="rtl">
                    {{-- Question --}}
                    <h3 class="text-sm text-gray-900">
                        {{ __('آیا مطمئن هستید که می‌خواهید جلسه') }}
                        <span class="font-medium">{{ $title }}</span>
                        {{ __('را رد کنید؟') }}
                    </h3>

                    {{-- Denial Reason --}}
                    <div class="space-y-2">
                        @foreach (['در تاریخ برگزاری جلسه در شرکت حضور ندارم',
                                    'در تاریخ برگزاری جلسه در جلسه دیگری دعوت هستم',
                                    'دلیل سوم',
                                    'دلیل چهارم'] as $reasonOption)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" wire:model="reason" value="{{ $reasonOption }}" name="reason"
                                       class="text-blue-600">
                                <span>{{ $reasonOption }}</span>
                            </label>
                        @endforeach
                        <x-input-error :messages="$errors->get('reason')" class="mt-2"/>
                    </div>
                    <div class="border-b mb-2 pb-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="absent" class="mr-2">
                            <span>{{__('امکان شرکت در جلسه را ندارم.')}}</span>
                        </label>
                        <x-input-error :messages="$errors->get('absent')" class="mt-2"/>
                    </div>

                    {{-- Replacement Fields --}}
                    @if (!$this->hasNotificationType('ReplacementForMeeting'))
                        <div class="space-y-3">


                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="checkBox" class="mr-2">
                                <span>{{__('در صورت انتخاب جانشین، گزینه تیک را زده و فیلدهای زیر را پر کنید:')}}</span>
                            </label>
                            <div class="space-y-3" x-show="$wire.checkBox">
                                <div class="flex items-center gap-2">
                                    <label class="text-sm">
                                        {{ __('در جلسه نمی‌توانم شرکت کنم ولی جانشین این جانب، آقا/خانم') }}
                                    </label>
                                    <input type="text" wire:model="full_name" placeholder="نام و نام خانوادگی"
                                           class="w-52 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                                </div>
                                <div class="flex items-center gap-2">
                                    <label class="text-sm">{{ __('و شماره پرسنلی') }}</label>
                                    <input type="text" wire:model="p_code" placeholder="شماره پرسنلی"
                                           class="w-40 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                                    <span>{{ __('در جلسه مذکور شرکت می‌نماید.') }}</span>
                                </div>
                                <x-input-error :messages="$errors->get('full_name')" class="mt-2"/>
                                <x-input-error :messages="$errors->get('p_code')" class="mt-2"/>
                                <x-input-error :messages="$errors->get('checkBox')" class="mt-2"/>
                            </div>
                        </div>
                    @else
                        <div class="p-4 text-sm text-red-600 bg-red-100 rounded-md">
                            {{__('شما قبلاً به عنوان جانشین برای این جلسه انتخاب شده‌اید و نمی‌توانید جانشین دیگری معرفی کنید.')}}
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="flex justify-between items-center px-6 gap-x-3 py-4 bg-gray-100 border-t border-gray-200">
                    <x-accept-button type="submit" wire:loading.attr="disabled" wire:target="deny"
                                     wire:loading.class="opacity-50">
                        <span wire:loading.remove wire:target="deny">{{ __('ارسال') }}</span>
                        <span wire:loading wire:target="deny" class="ml-2">{{ __('در حال ارسال...') }}</span>
                    </x-accept-button>
                    <a href="{{route('received.message')}}">
                        <x-cancel-button type="button">
                            {{ __('لغو') }}
                        </x-cancel-button>
                    </a>

                </div>

            </form>
        @endif
    </x-modal>

</div>
