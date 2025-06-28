@php use App\Models\MeetingUser; @endphp
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
            {{__('پیام های دریافتی')}}
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
                <option value="MeetingGuestInvitation">{{ __('دعوتنامه به عنوان مهمان') }}</option>
                <option value="MeetingConfirmed">{{ __(' برگزاری جلسه') }}</option>
                <option value="MeetingCancelled">{{ __(' لغو جلسه') }}</option>
                <option value="AcceptInvitation">{{ __('تایید دعوتنامه') }}</option>
                <option value="DenyInvitation">{{ __('رد دعوتنامه') }}</option>
                <option value="ReplacementForMeeting">{{ __('انتخاب جانشین') }}</option>
                <option value="AssignedNewTask">{{ __('دریافت اقدام') }}</option>
                <option value="UpdatedTaskTimeOut">{{ __('ویرایش مهلت اقدام') }}</option>
                <option value="UpdatedTaskBody">{{ __('ویرایش بند مذاکره') }}</option>
                <option value="DeniedTaskNotification">{{ __('رد اقدام') }}</option>
            </x-select-input>
        </div>

        {{-- Message Status Dropdown --}}
        <div class="w-full sm:w-64">
            <x-input-label for="message_status" value="{{ __('وضعیت پیام') }}"/>
            <x-select-input id="message_status" wire:model.defer="message_status">
                <option value="">{{ __('همه وضعیت‌ها') }}</option>
                <option value="unread">{{ __('خوانده نشده') }}</option>
                <option value="read">{{ __('خوانده شده') }}</option>
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


{{--        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-12" wire:poll.visible.60s>--}}
{{--            <x-table.table>--}}
{{--                <x-slot name="head">--}}
{{--                    <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">--}}
{{--                        @foreach (['نوع پیام','تاریخ دریافت پیام', 'فرستنده(دبیر/کاربر)', 'متن',--}}
{{--    // 'وضعیت جلسه'--}}
{{--     'اقدامات','وضعیت پیام'] as $th)--}}
{{--                            <x-table.heading--}}
{{--                                class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}">--}}
{{--                                {{ __($th) }}--}}
{{--                            </x-table.heading>--}}
{{--                        @endforeach--}}
{{--                    </x-table.row>--}}
{{--                </x-slot>--}}
{{--                <x-slot name="body">--}}
{{--                    @forelse ($this->userNotifications as $notification)--}}
{{--                        <x-table.row--}}
{{--                            wire:key="notification-{{$notification->id}}"--}}
{{--                            wire:click="markAsRead({{ $notification->id }})"--}}
{{--                            class="cursor-pointer {{ !$notification->isReadByRecipient() ? 'bg-yellow-100 font-bold' : 'bg-white' }} hover:bg-gray-50 transition-colors duration-200">--}}
{{--                            <x-table.cell class="border-none">--}}
{{--                                @php--}}
{{--                                    $attrs = $notification->getTypeLabelAttributes();--}}
{{--                                @endphp--}}
{{--                                <span class="font-bold {{ $attrs['text'] }}">--}}
{{--                                {{ $attrs['label'] }}--}}
{{--                            </span>--}}
{{--                            </x-table.cell>--}}
{{--                            <x-table.cell--}}
{{--                                class="whitespace-nowrap">{{ $notification->getNotificationDateTime() }}</x-table.cell>--}}
{{--                            <x-table.cell>{{ $notification->sender->user_info->full_name ?? 'N/A' }}</x-table.cell>--}}
{{--                            <x-table.cell class="whitespace-normal break-words max-w-xs">--}}
{{--                                {{ $notification->getNotificationMessage() }}--}}
{{--                            </x-table.cell>--}}
{{--                            --}}{{--                        <x-table.cell class="whitespace-nowrap">--}}
{{--                            --}}{{--                             <span--}}
{{--                            --}}{{--                                 class="{{$notification->notifiable->status->badgeColor() }} text-sm font-medium px-3 py-1 rounded-lg">--}}
{{--                            --}}{{--                                {{ $notification->notifiable->status->label() }}--}}
{{--                            --}}{{--                            </span>--}}
{{--                            --}}{{--                        </x-table.cell>--}}
{{--                            <x-table.cell class="whitespace-nowrap">--}}
{{--                                @if ($notification->type === 'MeetingInvitation' || $notification->type === 'MeetingGuestInvitation' || $notification->type === 'ReplacementForMeeting')--}}
{{--                                    @php--}}
{{--                                        $meeting = $notification->notifiable;--}}

{{--                                        // Get a single MeetingUser record (not a collection)--}}
{{--                                        $meetingUser = \App\Models\MeetingUser::where('user_id', auth()->id())--}}
{{--                                                        ->where('meeting_id', $meeting->id)--}}
{{--                                                        ->first();--}}

{{--                                        $replacementUserFullName = null;--}}

{{--                                        if ($meetingUser && $meetingUser->replacement) {--}}
{{--                                            $replacementUser = \App\Models\User::with('user_info')->find($meetingUser->replacement);--}}
{{--                                            $replacementUserFullName = $replacementUser?->user_info?->full_name ?? 'N/A';--}}
{{--                                        }--}}
{{--                                    @endphp--}}

{{--                                    @if ($meetingUser)--}}
{{--                                        @if ($meetingUser->is_present === MeetingUserStatus::PENDING->value)--}}
{{--                                            <div class="flex gap-2 items-center justify-center mt-4 md:mt-0">--}}
{{--                                                <x-accept-button wire:click="acceptMeeting({{ $meeting->id }})">--}}
{{--                                                    {{ __('تایید') }}--}}
{{--                                                </x-accept-button>--}}
{{--                                                <x-cancel-button wire:click="openModalDeny({{ $meetingUser->id }})">--}}
{{--                                                    {{ __('رد') }}--}}
{{--                                                </x-cancel-button>--}}
{{--                                            </div>--}}
{{--                                        @elseif ($meetingUser->is_present === MeetingUserStatus::IS_PRESENT->value)--}}
{{--                                            <span class="text-green-600 font-bold">--}}
{{--                                                {{ __('شما دعوت به این جلسه را پذیرفتید') }}--}}
{{--                                            </span>--}}
{{--                                            @if ($replacementUserFullName)--}}
{{--                                                <span class="text-green-700">--}}
{{--                                                {{ __('و آقا/خانم') }} {{ $replacementUserFullName }} {{ __('به عنوان جانشین خود انتخاب کردید') }}--}}
{{--                                            </span>--}}
{{--                                            @endif--}}
{{--                                        @elseif ($meetingUser->is_present === MeetingUserStatus::IS_NOT_PRESENT->value)--}}
{{--                                            <span--}}
{{--                                                class="text-red-600 font-bold">{{ __('شما دعوت به این جلسه را نپذیرفتید') }}</span>--}}
{{--                                        @endif--}}
{{--                                    @else--}}
{{--                                        <span--}}
{{--                                            class="text-gray-400 text-sm italic">{{ __('وضعیت شما در این جلسه مشخص نیست') }}</span>--}}
{{--                                    @endif--}}
{{--                                @endif--}}

{{--                                @if ($notification->type === 'MeetingConfirmed')--}}
{{--                                    <span class="text-green-600 font-bold">--}}
{{--                                                                        {{ __('تایید جلسه توسط دبیر') }}--}}
{{--                                                                    </span>--}}
{{--                                @elseif ($notification->type === 'MeetingCancelled')--}}
{{--                                    <span class="text-red-600 font-bold">--}}
{{--                                                                        {{ __('لغو جلسه توسط دبیر') }}--}}
{{--                                                                    </span>--}}
{{--                                @elseif (($notification->isTaskRelated()))--}}
{{--                                    <a href="{{ route('view.task.page', ['meeting' => $notification->notifiable_id]) }}">--}}
{{--                                        <x-secondary-button>{{ __('نمایش صورتجلسه') }}</x-secondary-button>--}}
{{--                                    </a>--}}
{{--                                @endif--}}
{{--                            </x-table.cell>--}}
{{--                            <x-table.cell class="whitespace-nowrap">--}}
{{--                                <div id="read-status-{{ $notification->id }}">--}}
{{--                                    @if ($notification->isReadByRecipient())--}}
{{--                                        <span class="text-gray-500">{{ __('خوانده شده') }}</span>--}}
{{--                                    @else--}}
{{--                                        <span class="text-red-500 font-bold">{{ __('خوانده نشده') }}</span>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </x-table.cell>--}}
{{--                        </x-table.row>--}}
{{--                    @empty--}}
{{--                        <x-table.row>--}}
{{--                            <x-table.cell colspan="7"--}}
{{--                                          class="text-center text-sm text-gray-600">{{ __('پیام جدیدی وجود ندارد') }}</x-table.cell>--}}
{{--                        </x-table.row>--}}
{{--                    @endforelse--}}
{{--                </x-slot>--}}
{{--            </x-table.table>--}}
{{--        </div>--}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-12" wire:poll.visible.60s>
        <x-table.table>
            <x-slot name="head">
                <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                    @foreach (['نوع پیام','تاریخ دریافت پیام', 'فرستنده(دبیر/کاربر)', 'متن', 'اقدامات','وضعیت پیام'] as $th)
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
                        class="cursor-pointer {{ !$notification->isReadByRecipient() ? 'bg-yellow-100 font-bold' : 'bg-white' }} hover:bg-gray-50 transition-colors duration-200"
                    >
                        <x-table.cell class="border-none">
                            @php
                                $attrs = $notification->getTypeLabelAttributes();
                            @endphp
                            <span class="font-bold {{ $attrs['text'] }}">
                            {{ $attrs['label'] }}
                        </span>
                        </x-table.cell>

                        <x-table.cell class="whitespace-nowrap">{{ $notification->getNotificationDateTime() }}</x-table.cell>

                        <x-table.cell>{{ $notification->sender->user_info->full_name ?? 'N/A' }}</x-table.cell>

                        <x-table.cell class="whitespace-normal break-words max-w-xs">
                            {{ $notification->getNotificationMessage() }}
                        </x-table.cell>

                        <x-table.cell class="whitespace-nowrap">
                            @if ($notification->canShowActionButtons())
                                <div class="flex gap-2 items-center justify-center mt-4 md:mt-0">
                                    <x-accept-button wire:click="acceptMeeting({{ $notification->notifiable_id }})">
                                        {{ __('تایید') }}
                                    </x-accept-button>
                                    <x-cancel-button wire:click="openModalDeny({{ $notification->getMeetingUserForCurrentUser()->id }})">
                                        {{ __('رد') }}
                                    </x-cancel-button>
                                </div>
                            @else
                                @php
                                    $statusLabel = $notification->getUserMeetingStatusLabel();
                                @endphp
                                @if ($statusLabel)
                                    <span class="{{ $notification->getUserMeetingStatusLabel() === __('شما دعوت به این جلسه را نپذیرفتید') ? 'text-red-600 font-bold' : 'text-green-600 font-bold' }}">
                                    {!! $statusLabel !!}
                                </span>
                                @endif
                            @endif

                            @if ($notification->type === 'MeetingConfirmed')
                                <span class="text-green-600 font-bold">
                                {{ __('تایید جلسه توسط دبیر') }}
                            </span>
                            @elseif ($notification->type === 'MeetingCancelled')
                                <span class="text-red-600 font-bold">
                                {{ __('لغو جلسه توسط دبیر') }}
                            </span>
                            @elseif ($notification->isTaskRelated())
                                <a href="{{ route('view.task.page', ['meeting' => $notification->notifiable_id]) }}">
                                    <x-secondary-button>{{ __('نمایش صورتجلسه') }}</x-secondary-button>
                                </a>
                            @endif
                        </x-table.cell>

                        <x-table.cell class="whitespace-nowrap">
                            <div id="read-status-{{ $notification->id }}">
                                @if ($notification->isReadByRecipient())
                                    <span class="text-gray-500">{{ __('خوانده شده') }}</span>
                                @else
                                    <span class="text-red-500 font-bold">{{ __('خوانده نشده') }}</span>
                                @endif
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="7" class="text-center text-sm text-gray-600">{{ __('پیام جدیدی وجود ندارد') }}</x-table.cell>
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
                        @foreach (['دلیل اول', 'دلیل دوم', 'دلیل سوم', 'دلیل چهارم'] as $reasonOption)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" wire:model="reason" value="{{ $reasonOption }}" name="reason"
                                       class="text-blue-600">
                                <span>{{ $reasonOption }}</span>
                            </label>
                        @endforeach
                        <x-input-error :messages="$errors->get('reason')" class="mt-2"/>
                    </div>

                    {{-- Replacement Fields --}}
                    @if (!$this->hasNotificationType('ReplacementForMeeting'))
                        <div class="space-y-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="checkBox" class="mr-2">
                                <span>{{__('در صورت انتخاب جانشین، فیلدهای زیر را پر کنید:')}}</span>
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


    {{--    <x-modal name="accept-invitation" maxWidth="4xl" :closable="false">--}}
    {{--        @if($meetingId)--}}
    {{--            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4" dir="rtl">--}}
    {{--                --}}{{--                 Header--}}
    {{--                <div class="sm:flex sm:items-center mb-4 border-b pb-3">--}}
    {{--                    <div--}}
    {{--                        class="mx-auto shrink-0 flex items-center justify-center size-12 rounded-full bg-red-100 sm:mx-0 sm:size-10">--}}
    {{--                        <svg class="size-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"--}}
    {{--                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">--}}
    {{--                            <path stroke-linecap="round" stroke-linejoin="round"--}}
    {{--                                  d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>--}}
    {{--                        </svg>--}}
    {{--                    </div>--}}
    {{--                    <div class="mt-3 text-center sm:mt-0 sm:ms-4 sm:text-start">--}}
    {{--                        <h3 class="text-sm text-gray-900 dark:text-gray-100">--}}
    {{--                            {{ __('آیا مطمئن هستید که می‌خواهید در جلسه') }}--}}
    {{--                            <span class="font-medium">{{ $meeting->title }}</span>--}}
    {{--                            {{ __('شرکت کنید؟') }}--}}
    {{--                        </h3>--}}
    {{--                    </div>--}}
    {{--                </div>--}}

    {{--            </div>--}}
    {{--            @if (!$this->isAlreadyRepresentative)--}}
    {{--                --}}{{--  Replacement Section--}}
    {{--                <div class="p-4 space-y-3">--}}
    {{--                    <input type="checkbox" wire:model="checkBox" class="mr-2">--}}
    {{--                    <span>در صورت انتخاب جانشین، فیلدهای زیر را پر کنید:</span>--}}
    {{--                    <div class="space-y-3" x-show="$wire.checkBox">--}}
    {{--                        <div class="flex items-center gap-2">--}}
    {{--                            <label class="text-sm">--}}
    {{--                                {{ __('در جلسه نمی‌توانم شرکت کنم ولی جانشین این جانب، آقا/خانم') }}--}}
    {{--                            </label>--}}
    {{--                            <input type="text" wire:model="full_name" placeholder="نام و نام خانوادگی"--}}
    {{--                                   class="w-52 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">--}}
    {{--                        </div>--}}
    {{--                        <div class="flex items-center gap-2">--}}
    {{--                            <label class="text-sm">{{ __('و شماره پرسنلی') }}</label>--}}
    {{--                            <input type="text" wire:model="p_code" placeholder="شماره پرسنلی"--}}
    {{--                                   class="w-40 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">--}}
    {{--                            <span>{{ __('در جلسه مذکور شرکت می‌نماید.') }}</span>--}}
    {{--                        </div>--}}
    {{--                        <x-input-error :messages="$errors->get('full_name')" class="mt-2"/>--}}
    {{--                        <x-input-error :messages="$errors->get('p_code')" class="mt-2"/>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            @endif--}}
    {{--            --}}{{--  Footer Buttons--}}
    {{--            <div class="flex justify-between items-center px-6 gap-x-3 py-4 bg-gray-100">--}}
    {{--                <x-accept-button wire:click="accept({{ $meetingId }})">--}}
    {{--                    {{ __('تایید') }}--}}
    {{--                </x-accept-button>--}}
    {{--                <x-cancel-button wire:click="close">--}}
    {{--                    {{ __('لغو') }}--}}
    {{--                </x-cancel-button>--}}
    {{--            </div>--}}

    {{--        @endif--}}
    {{--    </x-modal>--}}
</div>
