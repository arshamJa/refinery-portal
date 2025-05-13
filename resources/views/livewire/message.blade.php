@php use App\Enums\MeetingStatus;use App\Models\MeetingUser; @endphp
@php use App\Models\Meeting; @endphp
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
            {{__('پیغام های دریافتی')}}
        </span>
        </li>
    </x-breadcrumb>

    <div class="w-full overflow-x-auto overflow-y-hidden mb-4 pb-12" wire:poll.visible.60s>
        <x-table.table>
            <x-slot name="head">
                <x-table.row>
                    <x-table.heading>{{__('نوع پیام')}}</x-table.heading>
                    <x-table.heading>{{__('فرستنده')}}</x-table.heading>
                    <x-table.heading>{{__('متن/بند مذاکره')}}</x-table.heading>
                    <x-table.heading>{{__('اقدام شما / درخواست ها')}}</x-table.heading>
                    <x-table.heading>{{__('وضعیت جلسه')}}</x-table.heading>
                    <x-table.heading>{{__('عملیات')}}</x-table.heading>
                </x-table.row>
            </x-slot>
            <x-slot name="body">
                @foreach($this->meetingUsers as $meetingUser)
                    <x-table.row class="hover:bg-gray-50">
                        <x-table.cell>
                             <span
                                 class="block w-full bg-yellow-100 text-xs text-gray-800 font-medium px-3 py-1 rounded-lg">
                            {{__('دعوت به جلسه')}}</span>
                        </x-table.cell>
                        <x-table.cell>{{ $meetingUser->meeting->scriptorium }}</x-table.cell>
                        <x-table.cell>
                            <p class="text-sm text-gray-600">
                                {{ __('شما در تاریخ ') }}<span
                                    class="underline">{{ $meetingUser->meeting->date }}</span>
                                {{ __('و در ساعت ') }}<span class="underline">{{ $meetingUser->meeting->time }}</span>
                                {{ __('در جلسه ') }}<span class="underline">{{ $meetingUser->meeting->title }}</span>
                                {{ __(' دعوت شده‌اید') }}
                            </p>
                        </x-table.cell>
                        <x-table.cell>
                            @if(! $meetingUser->is_present() && ! $meetingUser->is_absent())
                                <div class="flex gap-2 mt-4 md:mt-0">
                                    <x-accept-button wire:click="openModalAccept({{$meetingUser->meeting_id}})">
                                        {{('تایید')}}
                                    </x-accept-button>
                                    <x-cancel-button wire:click="openModalDeny({{$meetingUser->meeting_id}})">
                                        {{('رد')}}
                                    </x-cancel-button>
                                </div>
                            @endif
                            @if($meetingUser->is_present())
                                {{ __('شما دعوت به این جلسه را') }} <span
                                    class="text-green-700">{{ __('پذیرفتید') }}</span>
                                @if($meetingUser->replacement)
                                    {{ __('و آقا/خانم') }} <span
                                        class="text-green-700">{{ $meetingUser?->replacementName() }}</span>
                                    {{ __('به عنوان جانشین خود انتخاب کردید') }}
                                @endif
                            @elseif($meetingUser->is_absent())
                                {{ __('شما دعوت به این جلسه را') }} <span
                                    class="text-red-600 font-bold">{{ __('نپذیرفتید') }}</span>
                            @endif
                        </x-table.cell>
                        <x-table.cell>
                            @switch($meetingUser->meeting->status)

                                @case(MeetingStatus::IS_CANCELLED)
                                    <span
                                        class="block w-full bg-red-500 text-xs text-white font-medium px-3 py-1 rounded-lg m-1">
                                            {{ __('جلسه لغو شد') }}
                                        </span>
                                    @break

                                @case(MeetingStatus::IS_NOT_CANCELLED)
                                    <span
                                        class="block w-full bg-green-500 text-xs text-white font-medium px-3 py-1 rounded-lg m-1">
                                            {{ __('جلسه برگزار میشود') }}
                                        </span>
                                    @break

                                @case(MeetingStatus::IS_IN_PROGRESS)
                                    <span
                                        class="block w-full bg-blue-500 text-xs text-white font-medium px-3 py-1 rounded-lg m-1">
                                                    {{ __('جلسه درحال برگزاری است') }}
                                                </span>
                                    @break

                                @case(MeetingStatus::IS_FINISHED)
                                    <span
                                        class="block w-full bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-lg m-1">
                                            {{ __('جلسه خاتمه یافت') }}
                                        </span>
                                    @break
                                @default
                                    <span
                                        class="block w-full bg-yellow-400 text-xs text-gray-800 font-medium px-3 py-1 rounded-lg m-1">
                                                {{ __('درحال بررسی دعوتنامه') }}
                                            </span>

                            @endswitch
                        </x-table.cell>
                        <x-table.cell>
                            <div class="relative group">
                                <button class="text-red-600 hover:text-red-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                                <div class="absolute left-1/2 -translate-x-1/2 mt-2 w-max p-2 text-white text-sm bg-gray-900 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-95 group-hover:scale-100">
                                    {{ __('حذف پیام') }}
                                </div>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endforeach
                @foreach($this->taskUsers as $taskUser)
                    <x-table.row class="hover:bg-gray-50">
                        <x-table.cell>
                            <a href="{{route('tasks.create',$taskUser->task->meeting_id)}}" class="inline-block text-center w-full bg-pink-500 hover:bg-pink-600 text-white text-xs font-medium px-4 py-2 rounded-lg shadow transition duration-300 transform hover:scale-105 hover:shadow-lg cursor-pointer">
                                {{ __('صورتجلسه') }}
                            </a>
                        </x-table.cell>
                        <x-table.cell>{{ $taskUser->user->user_info->full_name ?? 'N/A' }}</x-table.cell>
                        <x-table.cell class="break-words max-w-xs">{{ $taskUser->task->body }}</x-table.cell>
                        <x-table.cell>{{ $taskUser->request_task ?? '---' }}</x-table.cell>
                        <x-table.cell>---</x-table.cell>
                    </x-table.row>
                @endforeach
            </x-slot>
        </x-table.table>
    </div>


    <x-modal name="deny-invitation">
        @if($meetingId)
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4" dir="rtl">
                {{--                 Header--}}
                <div class="sm:flex sm:items-center mb-4 border-b pb-3">
                    <div
                        class="mx-auto shrink-0 flex items-center justify-center size-12 rounded-full bg-red-100 sm:mx-0 sm:size-10">
                        <svg class="size-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ms-4 sm:text-start">
                        <h3 class="text-sm text-gray-900 dark:text-gray-100">
                            {{ __('آیا مطمئن هستید که می‌خواهید جلسه') }}
                            <span class="font-medium">{{ $meeting }}</span>
                            {{ __('را رد کنید؟') }}
                        </h3>
                    </div>
                </div>

                {{--                 Denial Reason--}}
                <div>
                    <x-input-label for="body" :value="__('دلیل رد درخواست')" class="mb-2"/>
                    <textarea wire:model="body"
                              class="w-full min-h-[80px] px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50"
                              placeholder="{{ __('دلیل خود را وارد کنید...') }}"></textarea>
                    <x-input-error :messages="$errors->get('body')" class="mt-2"/>
                </div>
            </div>

            {{--             Footer Buttons--}}
            <div class="flex justify-between items-center px-6 gap-x-3 py-4 bg-gray-100">
                <x-accept-button wire:click="deny({{ $meetingId }})">
                    {{ __('تایید') }}
                </x-accept-button>
                <x-cancel-button wire:click="close">
                    {{ __('لغو') }}
                </x-cancel-button>
            </div>

        @endif
    </x-modal>

    <x-modal name="accept-invitation">
        @if($meetingId)
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4" dir="rtl">
                {{--                 Header--}}
                <div class="sm:flex sm:items-center mb-4 border-b pb-3">
                    <div
                        class="mx-auto shrink-0 flex items-center justify-center size-12 rounded-full bg-red-100 sm:mx-0 sm:size-10">
                        <svg class="size-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ms-4 sm:text-start">
                        <h3 class="text-sm text-gray-900 dark:text-gray-100">
                            {{ __('آیا مطمئن هستید که می‌خواهید در جلسه') }}
                            <span class="font-medium">{{ $meeting }}</span>
                            {{ __('شرکت کنید؟') }}
                        </h3>
                    </div>
                </div>

            </div>

            {{--             Replacement Section--}}
            <div class="p-4 space-y-3">
                <input type="checkbox" wire:model="checkBox" class="mr-2">
                <span>در صورت انتخاب جانشین، فیلدهای زیر را پر کنید:</span>

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
                </div>

                {{--                 Error List--}}
                {{--                @if ($errors->any())--}}
                {{--                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">--}}
                {{--                        @foreach ($errors->all() as $error)--}}
                {{--                            <li>{{ $error }}</li>--}}
                {{--                        @endforeach--}}
                {{--                    </ul>--}}
                {{--                @endif--}}
            </div>

            {{--             Footer Buttons--}}
            <div class="flex justify-between items-center px-6 gap-x-3 py-4 bg-gray-100">
                <x-accept-button wire:click="accept({{ $meetingId }})">
                    {{ __('تایید') }}
                </x-accept-button>
                <x-cancel-button wire:click="close">
                    {{ __('لغو') }}
                </x-cancel-button>
            </div>

        @endif
    </x-modal>

</div>
