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
            {{__('پیام های ارسالی')}}
        </span>
        </li>
    </x-breadcrumb>


    <div class="flex justify-center items-center mb-8 gap-4">
        <a href="{{ route('sent.message') }}" wire:navigate
           class="px-6 py-4 rounded-lg transition-all duration-300 ease-in-out shadow-lg outline-none focus:outline-none focus:ring-2 focus:ring-offset-2 hover:ring-2 hover:ring-offset-2 hover:ring-blue-400
{{--       {{ request()->routeIs('sent.message') ? 'bg-[#FF6F61] text-white' : 'bg-white text-gray-700' }}"--}}
           {{ $activeTab === 'sent' ? 'bg-[#FF6F61] text-white' : 'bg-white text-gray-700' }}"
        >
        <span class="text-sm font-medium">
            {{ __('پیام های ارسالی') }}
        </span>
        </a>

        <a href="{{ route('received.message') }}" wire:navigate
           class="px-6 py-4 rounded-lg transition-all duration-300 ease-in-out shadow-lg outline-none focus:outline-none focus:ring-2 focus:ring-offset-2 hover:ring-2 hover:ring-offset-2 hover:ring-blue-400
{{--       {{ request()->routeIs('received.message') ? 'bg-[#FF6F61] text-white' : 'bg-white text-gray-700' }}--}}
       {{ $activeTab === 'received' ? 'bg-[#FF6F61] text-white' : 'bg-white text-gray-700' }}"
        >
        <span class="text-sm font-medium">
            {{ __('پیام های دریافتی') }}
        </span>
        </a>
    </div>


    <div class="flex justify-between mb-4">
        <button wire:click="toggleUnreadOnly" class="px-4 py-2 bg-blue-500 text-white rounded-md">
            {{ $unreadOnly ? 'نمایش تمامی پیام ها' : 'نمایش پیام های خوانده نشده' }}
        </button>
    </div>

    <x-table.table>
        <x-slot name="head">
            <x-table.row>
                @foreach (['نوع پیام','تاریخ ارسال پیام', 'گیرنده', 'متن', 'وضعیت خواندن'] as $th)
                    <x-table.heading>{{ __($th) }}</x-table.heading>
                @endforeach
            </x-table.row>
        </x-slot>

        <x-slot name="body">
            @forelse ($this->userNotifications as $notification)
                <x-table.row class="hover:bg-gray-50" wire:key="notification-{{ $notification->id }}">
                    <x-table.cell>
                        @if($notification->type === 'MeetingInvitation')
                            <span class="text-blue-600 font-bold">{{ __('ارسال دعوتنامه') }}</span>
                        @elseif($notification->type === 'MeetingGuestInvitation')
                            <span class="text-pink-600 font-bold">{{ __('ارسال دعوتنامه به مهمان') }}</span>
                        @elseif($notification->type === 'AcceptInvitation')
                            <span class="text-green-600 font-bold">{{ __('تایید دعوتنامه') }}</span>
                        @endif
                    </x-table.cell>
                    <x-table.cell>{{ $this->getSentNotificationDateTime($notification) }}</x-table.cell>
                    <x-table.cell>{{ $notification->recipient->user_info->full_name ?? 'N/A' }}</x-table.cell>
                    <x-table.cell>{{ $this->getNotificationMessage($notification) }}</x-table.cell>
                    <x-table.cell>
                        @if (!$notification->isReadBySender())
                            <button wire:click="markAsRead({{ $notification->id }})"
                                    class="text-blue-500 underline">{{ __('متوجه شدم') }}</button>
                        @else
                            <span class="text-gray-500">{{ __('خوانده شده') }}</span>
                        @endif
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
    <span class="p-2 mx-2">
                    {{ $this->userNotifications->withQueryString()->links(data: ['scrollTo' => false]) }}
            </span>
</div>
