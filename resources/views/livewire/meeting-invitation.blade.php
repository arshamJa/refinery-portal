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
            <a href="{{route('message')}}"
               class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                <span>{{__('پیغام های دریافتی')}}</span>
            </a>
        </li>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
             stroke="currentColor" class="w-3 h-3 text-gray-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
        </svg>
        <li>
            <span
                class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                {{__('لیست دعوتنامه های دریافت شده')}}
            </span>
        </li>
    </x-breadcrumb>
    <x-modal name="deny">
        @if($meetingId)
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4" dir="rtl">
                {{-- Header --}}
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

                {{-- Denial Reason --}}
                <div>
                    <x-input-label for="body" :value="__('دلیل رد درخواست')" class="mb-2"/>
                    <textarea wire:model="body"
                              class="w-full min-h-[80px] px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50"
                              placeholder="{{ __('دلیل خود را وارد کنید...') }}"></textarea>
                    <x-input-error :messages="$errors->get('body')" class="mt-2"/>
                </div>
            </div>

            {{-- Replacement Section --}}
            <div class="border-t p-4 space-y-3">
                <p>{{ __('در صورت انتخاب جانشین، فیلدهای زیر را پر کنید:') }}</p>

                <div class="flex items-center gap-2">
                    <input type="checkbox" wire:model="checkBox">
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

                {{-- Error List --}}
                @if ($errors->any())
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Footer Buttons --}}
            <div class="flex justify-between items-center px-6 gap-x-3 py-4 bg-gray-100">
                <x-primary-button wire:click="deny({{ $meetingId }})">
                    {{ __('تایید') }}
                </x-primary-button>
                <x-secondary-button wire:click="close">
                    {{ __('لغو') }}
                </x-secondary-button>
            </div>
        @endif
    </x-modal>

    @foreach($this->meetingUsers as $meetingUser)
        <div class="bg-green-50 mb-2 border border-green-100 rounded-2xl p-4 shadow-sm space-y-4 max-w-4xl">
            <div class="flex flex-col md:flex-row items-start justify-between text-right">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 text-lg font-semibold text-gray-800">
                        <div class="shrink-0">
                                <span
                                    class="inline-flex justify-center items-center size-8 rounded-full border-4 border-teal-100 bg-teal-200 text-teal-800 dark:border-teal-900 dark:bg-teal-800 dark:text-teal-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-4"><path
                                            stroke-linecap="round" stroke-linejoin="round"
                                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                </span>
                        </div>
                        <span>{{ $meetingUser->meeting->title }}</span>
                    </div>
                    <p class="text-sm text-gray-600">
                        {{ __('شما در تاریخ') }} <span class="underline">{{ $meetingUser->meeting->date }}</span>
                        {{ __('و در ساعت') }} <span class="underline">{{ $meetingUser->meeting->time }}</span>
                        {{ __('توسط آقا/خانم') }} <span
                            class="font-bold">{{ $meetingUser->meeting->scriptorium }}</span>
                        {{ __('دعوت شده‌اید') }}
                    </p>
                </div>
                @if(! $meetingUser->is_present() && ! $meetingUser->is_absent())
                    <div class="flex gap-2 mt-4 md:mt-0">
                        <button wire:click="accept({{$meetingUser->meeting_id}})"
                                class="px-3 py-1.5 mb-2 bg-gray-800 border border-transparent rounded-md text-sm text-white">
                            {{('قبول')}}
                        </button>
                        <button wire:click="openModalDeny({{$meetingUser->meeting_id}})"
                                class="px-3 py-1.5 mb-2 bg-red-600 border border-transparent rounded-md text-sm text-white">
                            {{('رد')}}
                        </button>
                    </div>
                @endif
            </div>
            <div class="text-sm">
                @if($meetingUser->is_present())
                    {{ __('شما دعوت به این جلسه را') }} <span class="text-green-700">{{ __('پذیرفتید') }}</span>
                @elseif($meetingUser->is_absent())
                    {{ __('شما دعوت به این جلسه را') }} <span
                        class="text-red-600 font-bold">{{ __('نپذیرفتید') }}</span>
                    @if($meetingUser->replacement)
                        {{ __('و آقا/خانم') }} <span
                            class="text-green-700">{{ $meetingUser?->replacementName() }}</span>
                        {{ __('به عنوان جانشین خود انتخاب کردید') }}
                    @endif
                @endif
            </div>
        </div>
    @endforeach
    <span class="p-2 mx-2">
            {{ $this->meetingUsers->withQueryString()->links(data: ['scrollTo' => false]) }}
        </span>

</div>
