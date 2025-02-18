@php use App\Models\UserInfo; @endphp
<div wire:poll.visible.60s>
    <x-sessionMessage name="status"/>


    <x-modal name="delete">
        @if($meetingId)
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4" dir="rtl">
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
            </div>
            <div class="flex flex-row justify-between px-6 gap-x-3 py-4 bg-gray-100">
                <x-secondary-button wire:click="close">
                    {{ __('لغو') }}
                </x-secondary-button>
                <x-primary-button wire:click="denyMeeting({{$meetingId}})">
                    {{ __('تایید') }}
                </x-primary-button>
            </div>
        @endif
    </x-modal>

{{--    <x-template>--}}
        <nav class="flex justify-between mb-4 mt-14">
            <ol class="inline-flex items-center mb-3 space-x-1 text-xs text-neutral-500 [&_.active-breadcrumb]:text-neutral-600 [&_.active-breadcrumb]:font-medium sm:mb-0">
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
                    <a href="{{route('meetingsList')}}"
                       class="inline-flex items-center px-2 py-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
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
                       {{__('اسامی اعضای جلسه')}}
                    </span>
                </li>
            </ol>
        </nav>


        <div class="p-4 mb-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg" dir="rtl">
            <div class="max-w-xl">
                <section>
                    <div class="flex justify-between items-center">
                        <p class="bg-gray-800 text-white py-2.5 px-4 rounded-md">{{__('تعداد ثبت نشده')}}
                            : {{$this->not_sent}}</p>
                        <p class="bg-green-600 text-white py-2.5 px-4 rounded-md">{{__('تعداد حاضرین')}}
                            : {{$this->present}}</p>
                        <p class="bg-red-600 text-white py-2.5 px-4 rounded-md">{{__('تعداد غایبین')}}
                            : {{$this->absent}}</p>
                    </div>
                    <div class="border-b my-4 py-2">
                        {{__('اسامی ثبت نشده')}} :
                        <br>
                        @foreach($this->meetingUsers->where('is_present',0) as $meetingUser)
                            {{UserInfo::where('user_id',$meetingUser->user_id)->value('full_name')}}
                            -
                        @endforeach
                    </div>
                    <div class="border-b my-4 py-2">
                        {{__('اسامی حاضرین')}} :
                        <br>
                        @foreach($this->meetingUsers->where('is_present',1) as $meetingUser)
                            {{UserInfo::where('user_id',$meetingUser->user_id)->value('full_name')}}
                            -
                        @endforeach
                    </div>
                    <div class="my-4 py-2">
                        {{__('اسامی غایبین به همراه دلیل')}} :
                        <br>
                        @foreach($this->meetingUsers->where('is_present',-1) as $meetingUser)
                            {{UserInfo::where('user_id',$meetingUser->user_id)->value('full_name')}}
                            :
                            {{$meetingUser->reason_for_absent}}
                            <br>
                        @endforeach
                    </div>
                </section>
            </div>
{{--            <a href="{{route('meetingsList')}}">--}}
{{--                <x-secondary-button>--}}
{{--                    {{__('بازگشت')}}--}}
{{--                </x-secondary-button>--}}
{{--            </a>--}}


            @if($this->meeting == '0')
                <button wire:click="acceptMeeting({{$meetingId}})"
                        class="px-3 py-1.5 mb-2 bg-gray-800 border border-transparent rounded-md text-sm text-white">
                    {{('تایید جلسه')}}
                </button>
                <button wire:click="openModalDeny({{$meetingId}})"
                        class="px-3 py-1.5 mb-2 bg-red-600 border border-transparent rounded-md text-sm text-white">
                    {{('لغو جلسه')}}
                </button>
            @endif
        </div>

{{--    </x-template>--}}


</div>


