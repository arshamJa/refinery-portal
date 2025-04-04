<x-app-layout>
    <x-breadcrumb>
        <li class="flex items-center h-full">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.6986 3.68267C12.7492 2.77246 11.2512 2.77244 10.3018 3.68263L4.20402 9.52838C3.43486 10.2658 3 11.2852 3 12.3507V19C3 20.1046 3.89543 21 5 21H8.04559C8.59787 21 9.04559 20.5523 9.04559 20V13.4547C9.04559 13.2034 9.24925 13 9.5 13H14.5456C14.7963 13 15 13.2034 15 13.4547V20C15 20.5523 15.4477 21 16 21H19C20.1046 21 21 20.1046 21 19V12.3507C21 11.2851 20.5652 10.2658 19.796 9.52838L13.6986 3.68267Z"
                          fill="currentColor"></path>
                </svg>
                <span>{{ __('داشبورد') }}</span>
            </a>
        </li>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
             stroke="currentColor" class="w-3 h-3 text-gray-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
        </svg>
        <li>
            <a href="{{ route('meeting.table') }}"
               class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                <span>{{ __('جدول جلسات') }}</span>
            </a>
        </li>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
             stroke="currentColor" class="w-3 h-3 text-gray-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
        </svg>
        <li>
            <span class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
               {{ __('جزئیات جلسه ') }}{{ $meetings->title }}
            </span>
        </li>
    </x-breadcrumb>

    <div class="bg-gray-100 border-2 p-4 rounded-md" dir="rtl">

        <div class="grid grid-cols-3 gap-4 max-w-full">
            <p class="mb-2">{{ __('موضوع جلسه') }} : {{ $meetings->title }}</p>
            <p class="mb-2">{{ __('واحد سازمانی') }} : {{ $meetings->unit_organization }}</p>
            <p class="mb-2">{{ __('نام دبیر جلسه') }} : {{ $meetings->scriptorium }}</p>

            <div class="col-span-3">
                {{ __('اعضای جلسه') }} :
                @foreach($userInfos as $userId => $fullName)
                    {{ $fullName }} -
                    @if(isset($tasks[$userId]) && $tasks[$userId]->request_task)
                        <a href="{{ route('editUserTask', $tasks[$userId]->id) }}">View Edit</a>
                    @endif
                @endforeach
            </div>

            <p class="mb-2">{{ __('محل برگزاری جلسه') }} : {{ $meetings->location }}</p>
            <p class="mb-2">{{ __('تاریخ جلسه') }} : {{ $meetings->date }}</p>
            <p class="mb-2">{{ __('ساعت جلسه') }} : {{ $meetings->time }}</p>
            <p class="mb-2">{{ __('کمیته یا واحد برگزار کننده جلسه') }} : {{ $meetings->unit_held }}</p>
            <p class="mb-2">{{ __('پذیرایی') }} : {{ $meetings->treat ? __('دارد') : __('ندارد') }}</p>
            <p class="mb-2">
                {{ __('مهمان') }} :
                @if($meetings->guest)
                    @foreach($meetings->guest as $guest)
                        - {{ $guest }}
                    @endforeach
                @else
                    {{ __('مهمانی وجود ندارد') }}
                @endif
            </p>
            <p class="mb-2">{{ __('نام درخواست دهنده جلسه') }} : {{ $meetings->applicant }}</p>
            <p class="mb-2">{{ __('سمت سازمانی') }} : {{ $meetings->position_organization }}</p>
            <p class="mb-2">{{ __('امضا') }} : {{ $meetings->signature ? __('دارد') : __('ندارد') }}</p>
            <p class="mb-6">{{ __('زمان جهت یادآوری') }} : {{ $meetings->reminder }} {{ __('دقیقه') }}</p>
        </div>

    </div>
</x-app-layout>



{{--@php use App\Models\UserInfo; @endphp--}}
{{--<x-app-layout>--}}

{{--    <x-breadcrumb>--}}
{{--                <li class="flex items-center h-full">--}}
{{--                    <a href="{{route('dashboard')}}"--}}
{{--                       class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">--}}
{{--                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                            <path--}}
{{--                                d="M13.6986 3.68267C12.7492 2.77246 11.2512 2.77244 10.3018 3.68263L4.20402 9.52838C3.43486 10.2658 3 11.2852 3 12.3507V19C3 20.1046 3.89543 21 5 21H8.04559C8.59787 21 9.04559 20.5523 9.04559 20V13.4547C9.04559 13.2034 9.24925 13 9.5 13H14.5456C14.7963 13 15 13.2034 15 13.4547V20C15 20.5523 15.4477 21 16 21H19C20.1046 21 21 20.1046 21 19V12.3507C21 11.2851 20.5652 10.2658 19.796 9.52838L13.6986 3.68267Z"--}}
{{--                                fill="currentColor"></path>--}}
{{--                        </svg>--}}
{{--                        <span>{{__('داشبورد')}}</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"--}}
{{--                     stroke="currentColor" class="w-3 h-3 text-gray-400">--}}
{{--                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>--}}
{{--                </svg>--}}
{{--                <li>--}}
{{--                    <a href="{{route('meeting.table')}}"--}}
{{--                       class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">--}}
{{--                        <span>{{__('جدول جلسات')}}</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"--}}
{{--                     stroke="currentColor" class="w-3 h-3 text-gray-400">--}}
{{--                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>--}}
{{--                </svg>--}}
{{--                <li>--}}
{{--                    <span--}}
{{--                        class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">--}}
{{--                       {{__('جزئیات جلسه ')}}{{$meetings->title}}--}}
{{--                    </span>--}}
{{--                </li>--}}
{{--    </x-breadcrumb>--}}

{{--        <div class="bg-gray-100 border-2 p-4 rounded-md" dir="rtl">--}}

{{--            <div class="grid grid-cols-3 gap-4 max-w-full">--}}
{{--                <p class="mb-2">--}}
{{--                    {{__('موضوع جلسه')}} : {{$meetings->title}}--}}
{{--                </p>--}}
{{--                <p class="mb-2">--}}
{{--                    {{__('واحد سازمانی')}} : {{$meetings->unit_organization}}--}}
{{--                </p>--}}
{{--                <p class="mb-2">--}}
{{--                    {{__('نام دبیر جلسه')}} : {{$meetings->scriptorium}}--}}
{{--                </p>--}}
{{--                <div class="col-span-3">--}}
{{--                    {{__('اعضای جلسه')}} :--}}
{{--                    @foreach($userIds as $userId)--}}
{{--                        {{UserInfo::where('user_id',$userId->user_id)->value('full_name')}} ---}}
{{--                        --}}{{--                                    @if(!$meetings->is_cancelled)--}}
{{--                        @if($tasks->where('user_id',$userId->user_id)->value('request_task'))--}}
{{--                            <a href="{{route('editUserTask',$tasks->where('user_id',$userId->user_id)->value('id'))}}">View--}}
{{--                                Edit</a>--}}
{{--                            --}}{{-- @endif--}}
{{--                        @endif--}}
{{--                    @endforeach--}}
{{--                </div>--}}

{{--                <p class="mb-2">--}}
{{--                    {{__('محل برگزاری جلسه')}} : {{$meetings->location}}--}}
{{--                </p>--}}
{{--                <p class="mb-2">--}}
{{--                    {{__('تاریخ جلسه')}} : {{$meetings->date}}--}}
{{--                </p>--}}
{{--                <p class="mb-2">--}}
{{--                    {{__('ساعت جلسه')}} : {{$meetings->time}}--}}
{{--                </p>--}}
{{--                <p class="mb-2">--}}
{{--                    {{__('کمیته یا واحد برگزار کننده جلسه')}} : {{$meetings->unit_held}}--}}
{{--                </p>--}}
{{--                <p class="mb-2">--}}
{{--                    {{__('پذیرایی')}} :--}}
{{--                    @if($meetings->treat)--}}
{{--                        {{__('دارد')}}--}}
{{--                    @else--}}
{{--                        {{__('ندارد')}}--}}
{{--                    @endif--}}
{{--                </p>--}}
{{--                <p class="mb-2">--}}
{{--                    {{__('مهمان')}} :--}}
{{--                    @if($meetings->guest)--}}
{{--                        @foreach($meetings->guest as $guest)--}}
{{--                            - {{$guest}}--}}
{{--                        @endforeach--}}
{{--                    @else--}}
{{--                        {{__('مهمانی وجود ندارد')}}--}}
{{--                    @endif--}}
{{--                </p>--}}
{{--                <p class="mb-2">--}}
{{--                    {{__('نام درخواست دهنده جلسه')}} : {{$meetings->applicant}}--}}
{{--                </p>--}}
{{--                <p class="mb-2">--}}
{{--                    {{__('سمت سازمانی')}} : {{$meetings->position_organization}}--}}
{{--                </p>--}}
{{--                <p class="mb-2">--}}
{{--                    {{__('امضا')}} :--}}
{{--                    @if($meetings->signature)--}}
{{--                        {{__('دارد')}}--}}
{{--                    @else--}}
{{--                        {{__('ندارد')}}--}}
{{--                    @endif--}}
{{--                </p>--}}
{{--                <p class="mb-6">--}}
{{--                    {{__('زمان جهت یادآوری')}} : {{$meetings->reminder}} {{__('دقیقه')}}--}}
{{--                </p>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--</x-app-layout>--}}


