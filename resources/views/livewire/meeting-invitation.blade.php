<div>



    <x-modal name="delete">
        @if($meeting_id)
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4" dir="rtl">
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
                            {{ __('آیا مطمئن هستید که میخواهید جلسه ') }} <span
                                class="font-medium">{{$meeting}}</span> {{__('را رد کنید ؟')}}
                        </h3>
                    </div>
                </div>
                <div>
                    <x-input-label for="body" :value="__('دلیل رد درخواست')" class="mb-2"/>
                    <textarea type="text" wire:model="body"
                              class="flex w-full h-auto min-h-[80px] px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50"></textarea>
                    <x-input-error :messages="$errors->get('body')" class="mt-2"/>
                </div>
            </div>
            <div class="flex flex-row justify-between px-6 gap-x-3 py-4 bg-gray-100">
                <x-primary-button wire:click="deny({{$meeting_id}})">
                    {{ __('تایید') }}
                </x-primary-button>
                <x-secondary-button wire:click="close">
                    {{ __('لغو') }}
                </x-secondary-button>
            </div>
        @endif
    </x-modal>

    <x-template>

        <nav class="flex justify-between mb-4">
            <ol class="inline-flex items-center mb-3 space-x-1 text-xs text-neutral-500 [&_.active-breadcrumb]:text-neutral-600 [&_.active-breadcrumb]:font-medium sm:mb-0">
                <li class="flex items-center h-full">
                    <a href="{{route('dashboard')}}" class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.6986 3.68267C12.7492 2.77246 11.2512 2.77244 10.3018 3.68263L4.20402 9.52838C3.43486 10.2658 3 11.2852 3 12.3507V19C3 20.1046 3.89543 21 5 21H8.04559C8.59787 21 9.04559 20.5523 9.04559 20V13.4547C9.04559 13.2034 9.24925 13 9.5 13H14.5456C14.7963 13 15 13.2034 15 13.4547V20C15 20.5523 15.4477 21 16 21H19C20.1046 21 21 20.1046 21 19V12.3507C21 11.2851 20.5652 10.2658 19.796 9.52838L13.6986 3.68267Z" fill="currentColor"></path></svg>
                        <span>{{__('داشبورد')}}</span>
                    </a>
                </li>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3 h-3 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                <li>
                    <a href="{{route('message')}}" class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                        <span>{{__('پیغام های دریافتی')}}</span>
                    </a>
                </li>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3 h-3 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                <li>
                    <span class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                        {{__('لیست دعوتنامه')}}
                    </span>
                </li>
            </ol>
        </nav>
        <div wire:poll.visible.60s>
            @foreach($this->meetingUsers as $meetingUser)
            <div class="space-y-5 mb-4">
                <div class="bg-teal-50 border-t-2 border-teal-500 rounded-lg p-4 dark:bg-teal-800/30">
                    <div class="flex">
                        <div class="shrink-0">
                            <span
                                class="inline-flex justify-center items-center size-8 rounded-full border-4 border-teal-100 bg-teal-200 text-teal-800 dark:border-teal-900 dark:bg-teal-800 dark:text-teal-400">
                              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </span>
                        </div>
                        <div class="ms-3 w-full">
                                <h3 class="text-gray-800 font-semibold dark:text-white">
                                    {{$meetingUser->meeting->title}}
                                </h3>
                                <p class="text-sm text-gray-700 pb-4 dark:text-neutral-400">
                                    <span>{{__('شما در تاریخ ')}}
                                        <span class="underline">{{$meetingUser->meeting->date}}</span>
                                        {{__('و در ساعت ')}}
                                        <span class="underline">{{$meetingUser->meeting->time}}</span>
                                        {{__(' توسط آقا/خانم ')}}
                                        <span class="font-bold">{{$meetingUser->meeting->scriptorium}}</span>
                                        {{__(' دعوت شده اید ')}}
                                    </span>
                                </p>
                            <div class="flex justify-between items-center">
{{--                                <a href="#" class="hover:underline">{{__('نمایش جزئیات')}}</a>--}}
                                <div>
                                    @if($meetingUser->where('meeting_id',$meetingUser->meeting->id)->where('user_id',auth()->user()->id)->value('is_present') == '1')
                                        {{__('شما دعوت به این جلسه را پذیرفتید')}}
                                    @elseif($meetingUser->where('meeting_id',$meetingUser->meeting->id)->where('user_id',auth()->user()->id)->value('is_present') == '-1')
                                        {{__('شما دعوت به این جلسه را نپذیرفتید')}}
                                    @else
                                        <button wire:click="accept({{$meetingUser->id}})"
                                                class="px-3 py-1.5 mb-2 bg-gray-800 border border-transparent rounded-md text-sm text-white">
                                            {{('قبول')}}
                                        </button>
                                        <button wire:click="openModalDeny({{$meetingUser->id}})"
                                                class="px-3 py-1.5 mb-2 bg-red-600 border border-transparent rounded-md text-sm text-white">
                                            {{('رد')}}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

{{--            <label for="simple-search" class="sr-only">Search</label>--}}
{{--            <div class="relative w-full">--}}
{{--                <div--}}
{{--                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">--}}
{{--                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500"--}}
{{--                         fill="currentColor" viewbox="0 0 20 20"--}}
{{--                         xmlns="http://www.w3.org/2000/svg">--}}
{{--                        <path fill-rule="evenodd"--}}
{{--                              d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"--}}
{{--                              clip-rule="evenodd"/>--}}
{{--                    </svg>--}}
{{--                </div>--}}
{{--                <input type="text" wire:model.live="search" id="simple-search" dir="rtl"--}}
{{--                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full"--}}
{{--                       placeholder="جست و جو وظایف ..." required="">--}}
{{--            </div>--}}
{{--            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">--}}
{{--                <thead--}}
{{--                    class="text-sm text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">--}}
{{--                <tr>--}}
{{--                    <th scope="col" class="px-6 py-3">--}}
{{--                        {{__('جلسات')}}--}}
{{--                    </th>--}}
{{--                    <th scope="col" class="px-6 py-3">--}}
{{--                        {{__('دبیرجلسه')}}--}}
{{--                    </th>--}}
{{--                    <th scope="col" class="px-6 py-3">--}}
{{--                        {{__('تاریخ')}}--}}
{{--                    </th>--}}
{{--                    <th scope="col" class="px-6 py-3">--}}
{{--                        {{__('ساعت')}}--}}
{{--                    </th>--}}
{{--                    <th scope="col" class="px-6 py-3">--}}
{{--                        {{__('حاضرین')}}--}}
{{--                    </th>--}}
{{--                    <th scope="col" class="px-6 py-3">--}}
{{--                        {{__('وضعیت جلسه')}}--}}
{{--                    </th>--}}
{{--                    <th scope="col" class="px-6 py-3"></th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}
{{--                @forelse($this->meetingUsers as $meetingUser)--}}
{{--                    <tr class="hover:bg-gray-100 relative text-center border-b dark:border-gray-700">--}}
{{--                        <th scope="row"--}}
{{--                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">--}}
{{--                            <a href="{{Illuminate\Support\Facades\URL::signedRoute('meetings.show',$meetingUser->meeting->id)}}"--}}
{{--                               class="p-2 mb-2 hover:underline underline-offset-2 w-full transition ease-in-out">--}}
{{--                                {{$meetingUser->meeting->title}}--}}
{{--                            </a>--}}
{{--                            @if($meetingUser->meeting->tasks->where('request_task',!null)->value('request_task'))--}}
{{--                                <span class="absolute right-2 top-1/2 -translate-y-1/2 flex h-3 w-3"><span--}}
{{--                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span><span--}}
{{--                                        class="relative inline-flex rounded-full h-3 w-3 bg-sky-500"></span></span>--}}
{{--                            @endif--}}
{{--                        </th>--}}
{{--                        <td class="px-6 py-4">--}}
{{--                            {{$meetingUser->meeting->scriptorium}}--}}
{{--                        </td>--}}
{{--                        <td class="px-6 py-4">--}}
{{--                            {{$meetingUser->meeting->date}}--}}
{{--                        </td>--}}
{{--                        <td class="px-6 py-4">--}}
{{--                            {{$meetingUser->meeting->time}}--}}
{{--                        </td>--}}
{{--                        <td class="px-6 py-4">--}}
{{--                            {{$meetingUser->where('meeting_id',$meetingUser->meeting->id)->where('is_present','1')->count()}}--}}
{{--                        </td>--}}
{{--                        <td class="px-6 py-4">--}}
{{--                            @if($meetingUser->meeting->is_cancelled == '0')--}}
{{--                                {{__('درحال بررسی...')}}--}}
{{--                            @elseif($meetingUser->meeting->is_cancelled == '1')--}}
{{--                                {{__('جلسه لغو شد')}}--}}
{{--                            @elseif($meetingUser->meeting->is_cancelled == '-1')--}}
{{--                                {{__('جلسه تشکیل میشود')}}--}}
{{--                            @endif--}}
{{--                        </td>--}}
{{--                        <td class="px-6 py-4">--}}
{{--                            @if($meetingUser->where('meeting_id',$meetingUser->meeting->id)->where('user_id',auth()->user()->id)->value('is_present') == '1')--}}
{{--                                {{__('شما دعوت به این جلسه را پذیرفتید')}}--}}
{{--                            @elseif($meetingUser->where('meeting_id',$meetingUser->meeting->id)->where('user_id',auth()->user()->id)->value('is_present') == '-1')--}}
{{--                                {{__('شما دعوت به این جلسه را نپذیرفتید')}}--}}
{{--                            @else--}}
{{--                                <button wire:click="accept({{$meetingUser->id}})"--}}
{{--                                        class="px-3 py-1.5 mb-2 bg-gray-800 border border-transparent rounded-md text-sm text-white">--}}
{{--                                    {{('قبول')}}--}}
{{--                                </button>--}}
{{--                                <button wire:click="openModalDeny({{$meetingUser->id}})"--}}
{{--                                        class="px-3 py-1.5 mb-2 bg-red-600 border border-transparent rounded-md text-sm text-white">--}}
{{--                                    {{('رد')}}--}}
{{--                                </button>--}}
{{--                            @endif--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                @empty--}}
{{--                    <tr class="border-b dark:border-gray-700">--}}
{{--                        <th colspan="8"--}}
{{--                            class="text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">--}}
{{--                            {{__('رکوردی یافت نشد ...')}}--}}
{{--                        </th>--}}
{{--                    </tr>--}}
{{--                @endforelse--}}
{{--                </tbody>--}}
{{--            </table>--}}
            <nav
                class="flex flex-col md:flex-row mt-4 justify-between items-start md:items-center space-y-3 md:space-y-0 p-4">
                {{ $this->meetingUsers->withQueryString()->links(data:['scrollTo'=>false]) }}
            </nav>
        </div>
    </x-template>
</div>
