@php use App\Enums\MeetingStatus;use App\Models\UserInfo; @endphp

<div>

    <x-sessionMessage name="status"/>

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
               class="inline-flex items-center px-2 py-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
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
                       {{__('تایید/رد جلسه')}}
                    </span>
        </li>
    </x-breadcrumb>

    <div wire:poll.visible.60s class="bg-white p-8 rounded-2xl shadow-lg space-y-8 max-w-4xl">
        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
            <div class="bg-gray-900 text-white p-6 rounded-xl space-y-2">
                <span class="text-sm font-medium">{{ __('تعداد ثبت نشده:') }}</span>
                <div class="text-2xl font-bold">{{$this->not_sent}}</div>
            </div>
            <div class="bg-emerald-600 text-white p-6 rounded-xl space-y-2">
                <span class="text-sm font-medium">{{ __('تعداد قبول کنندگان:') }}</span>
                <div class="text-2xl font-bold">{{$this->present}}</div>
            </div>
            <div class="bg-rose-600 text-white p-6 rounded-xl space-y-2">
                <span class="text-sm font-medium">{{ __('تعداد رد کنندگان:') }}</span>
                <div class="text-2xl font-bold">{{$this->absent}}</div>
            </div>
        </div>

        <!-- Names Section -->
        <div class="text-sm text-gray-700 rtl:text-right space-y-6">
            <!-- Not Sent -->
            <div>
                <p class="font-semibold text-base mb-2">{{ __('اسامی ثبت نشده:') }}</p>
                <div class="flex flex-wrap gap-2 text-gray-600">
                    @foreach($this->meetingUsers->where('is_present',0)->where('is_guest',false) as $user)
                        <span class="bg-gray-100 rounded-full px-3 py-1">
                            {{ UserInfo::where('user_id', $user->user_id)->value('full_name') }}
                        </span>
                    @endforeach
                </div>
            </div>

            <!-- Present -->
            <div>
                <p class="font-semibold text-base mb-2">{{ __('اسامی قبول کنندگان:') }}</p>
                <div class="flex flex-wrap gap-2 text-green-700">
                    @foreach($this->meetingUsers->where('is_guest','==',false)->where('is_present', 1) as $user)
                        <div class="bg-red-50 border border-red-200 p-4 rounded-lg">
                            <p class="text-red-700 font-medium">
                                {{ $user->user->user_info->full_name ?? '---' }}
                            </p>
                            @if ($user->replacementName())
                                <p class="text-sm text-indigo-600 mt-2">
                                    {{ __('جانشین اینجانب = ') }}
                                    <strong>{{ $user->replacementName() }}</strong>
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Absent with Reason -->
            <div>
                <p class="font-semibold text-base mb-2">{{ __('اسامی رد کنندگان به همراه دلیل:') }}</p>
                <div class="space-y-4">
                    @foreach($this->meetingUsers->where('is_guest','==',false)->where('is_present', -1) as $user)
                        <div class="bg-red-50 border border-red-200 p-4 rounded-lg">
                            <p class="text-red-700 font-medium">
                                {{ UserInfo::where('user_id', $user->user_id)->value('full_name') }}
                            </p>
                            <p class="text-sm text-gray-700 mt-1">{{ $user->reason_for_absent }}</p>
                        </div>
                    @endforeach
                </div>
            </div>


            <!-- Guests -->
            <div>
                <p class="font-semibold text-base mb-2">{{ __('اسامی مهمان :') }}</p>
                <div class="space-y-4">
                    @foreach($this->meetingUsers->where('is_guest',true) as $user)
                        <div class="bg-red-50 border border-red-200 p-4 rounded-lg">
                            <p class="text-red-700 font-medium">
                                {{ UserInfo::where('user_id', $user->user_id)->value('full_name') }}
                            </p>
                            @if($user->reason_for_absent != null)
                                <p class="text-sm text-gray-700 mt-1">
                                    {{ __('علت رد = ') }}
                                    {{ $user->reason_for_absent }}
                                </p>
                            @endif
                            @if ($user->replacementName())
                                <p class="text-sm text-indigo-600 mt-2">
                                    {{ __('جانشین اینجانب = ') }}
                                    <strong>{{ $user->replacementName() }}</strong>
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Buttons -->
        @if($this->meetingStatus == MeetingStatus::PENDING)
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                <x-primary-button wire:click="acceptMeeting({{ $meetingId }})">
                    {{ __('تایید جلسه') }}
                </x-primary-button>

                <x-danger-button wire:click="openModalDeny({{ $meetingId }})">
                    {{ __('لغو جلسه') }}
                </x-danger-button>
            </div>
        @endif
    </div>

    <x-modal name="delete" maxWidth="4xl" :closable="false">
        @if($meetingId)
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 bg-gray-100 border-b border-gray-200">
                <div class="sm:flex sm:items-center">
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
                            {{ __('آیا مطمئن هستید که جلسه ') }} <span
                                class="font-medium">{{$meetingTitle}}</span> {{__('لغو شود ؟')}}
                        </h3>
                    </div>
                </div>
                <button type="button" x-on:click="$dispatch('close')"
                        class="text-gray-400 hover:text-red-500 transition duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <!-- Body -->
            <div
                class="px-6 py-4 space-y-6 text-sm text-gray-800 dark:text-gray-200 max-h-[70vh] overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Warning Message -->
                    <div class="md:col-span-2">
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded">
                            <p class="font-semibold">{{ __('هشدار:') }}</p>
                            <p>{{ __('این اقدام قابل بازگشت نیست و با لغو این جلسه تمامی اعضای این جلسه و مهمان حذف می شوند') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-row justify-between px-6 gap-x-3 py-4 bg-gray-100">
                <x-primary-button wire:click="denyMeeting({{$meetingId}})">
                    {{ __('تایید') }}
                </x-primary-button>
                <x-cancel-button wire:click="close">
                    {{ __('لغو') }}
                </x-cancel-button>
            </div>
        @endif
    </x-modal>


</div>



