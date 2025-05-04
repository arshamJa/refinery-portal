@php use App\Models\MeetingUser; @endphp
@php use App\Models\Meeting; @endphp
<div wire:poll.visible.60s>
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



    <div x-data="{ selectedType: 'all' }" class="overflow-x-auto rounded-xl shadow-sm border border-gray-200 bg-white p-4">
        <!-- Dropdown for message type filter -->
        <div class="mb-4">
            <label for="messageType" class="font-semibold text-gray-700">فیلتر نوع پیام:</label>
            <select id="messageType" x-model="selectedType" class="ml-2 border rounded-md p-1">
                <option value="all">همه</option>
                <option value="invitation">دعوت‌نامه</option>
                <option value="assigned_task">وظیفه محول‌شده</option>
            </select>
        </div>

        <table class="w-full text-sm text-gray-700 text-right rtl:text-right">
            <thead class="bg-gray-50 text-gray-600 border-b text-sm font-semibold">
            <tr>
                <th class="px-4 py-3">نوع</th>
                <th class="px-4 py-3">فرستنده</th>
                <th class="px-4 py-3">عنوان</th>
                <th class="px-4 py-3">تاریخ</th>
                <th class="px-4 py-3">ساعت</th>
                <th class="px-4 py-3">متن</th>
                <th class="px-4 py-3">محول به</th>
                <th class="px-4 py-3">اقدام شما</th>
                <th class="px-4 py-3">وضعیت</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            <!-- Invitation Message -->
            <tr x-show="selectedType === 'all' || selectedType === 'invitation'" x-data="{ status: '', reason: '' }" class="hover:bg-gray-50">
                <td class="px-4 py-3">دعوت‌نامه</td>
                <td class="px-4 py-3">Scriptorium</td>
                <td class="px-4 py-3">جلسه تیم توسعه</td>
                <td class="px-4 py-3">1403/02/05</td>
                <td class="px-4 py-3">13:00</td>
                <td class="px-4 py-3">دعوت به جلسه برنامه‌ریزی محصول</td>
                <td class="px-4 py-3">سارا رحیمی</td>
                <td class="px-4 py-3">
                    <button @click="status = 'accepted'; reason=''" class="text-green-600 hover:underline mr-2">قبول</button>
                    <button @click="status = 'denied'" class="text-red-600 hover:underline">رد</button>
                </td>
                <td class="px-4 py-3">
                    <template x-if="status === 'accepted'">
                        <span class="text-green-600 font-semibold">پذیرفته شد</span>
                    </template>
                    <template x-if="status === 'denied'">
                        <div class="flex flex-col gap-1">
                            <input x-model="reason" type="text" placeholder="دلیل رد" class="w-full text-sm border rounded px-2 py-1" />
                            <span class="text-red-600 font-semibold">رد شد</span>
                        </div>
                    </template>
                </td>
            </tr>

            <!-- Assigned Task -->
            <tr x-show="selectedType === 'all' || selectedType === 'assigned_task'" x-data="{ status: '', reason: '' }" class="hover:bg-gray-50">
                <td class="px-4 py-3">وظیفه محول‌شده</td>
                <td class="px-4 py-3">Scriptorium</td>
                <td class="px-4 py-3">تهیه گزارش</td>
                <td class="px-4 py-3">1403/02/03</td>
                <td class="px-4 py-3">16:00</td>
                <td class="px-4 py-3">ارسال گزارش عملکرد ماهانه</td>
                <td class="px-4 py-3">رضا خالقی</td>
                <td class="px-4 py-3">
                    <button @click="status = 'accepted'; reason=''" class="text-green-600 hover:underline mr-2">قبول</button>
                    <button @click="status = 'denied'" class="text-red-600 hover:underline">رد</button>
                </td>
                <td class="px-4 py-3">
                    <template x-if="status === 'accepted'">
                        <span class="text-green-600 font-semibold">پذیرفته شد</span>
                    </template>
                    <template x-if="status === 'denied'">
                        <div class="flex flex-col gap-1">
                            <input x-model="reason" type="text" placeholder="دلیل رد" class="w-full text-sm border rounded px-2 py-1" />
                            <span class="text-red-600 font-semibold">رد شد</span>
                        </div>
                    </template>
                </td>
            </tr>
            </tbody>
        </table>
    </div>







{{--        <table class="w-full text-sm text-gray-700 text-center rtl:text-right">--}}
{{--        <thead class="bg-gray-50 text-gray-600 border-b text-sm font-semibold">--}}
{{--        <tr>--}}
{{--            <th class="px-4 py-3">نوع پیام</th>--}}
{{--            <th class="px-4 py-3">ارسال‌کننده</th>--}}
{{--            <th class="px-4 py-3">عنوان</th>--}}
{{--            <th class="px-4 py-3">تاریخ ارسال</th>--}}
{{--            <th class="px-4 py-3">زمان ارسال</th>--}}
{{--            <th class="px-4 py-3">وضعیت جلسه</th>--}}
{{--        </tr>--}}
{{--        </thead>--}}
{{--        <tbody class="divide-y divide-gray-100">--}}
{{--         Loop for Invitations--}}
{{--        @foreach($this->meetingUsers as $meetingUser)--}}
{{--            <tr class="hover:bg-gray-50 odd:bg-gray-50">--}}
{{--                <td class="px-4 py-3">دعوت به جلسه</td>--}}
{{--                <td class="px-4 py-3">{{ $meetingUser->meeting->scriptorium }}</td>--}}
{{--                <td class="px-4 py-3">{{ $meetingUser->meeting->title }}</td>--}}
{{--                <td class="px-4 py-3">{{ $meetingUser->meeting->date }}</td>--}}
{{--                <td class="px-4 py-3">{{ $meetingUser->meeting->time }}</td>--}}
{{--                <td class="px-4 py-3">--}}
{{--                    @if($meetingUser->is_present())--}}
{{--                        پذیرفته شد--}}
{{--                    @elseif($meetingUser->is_absent())--}}
{{--                        رد شد--}}
{{--                        @if($meetingUser->replacement)--}}
{{--                            - جانشین: {{ $meetingUser?->replacementName() }}--}}
{{--                        @endif--}}
{{--                    @else--}}
{{--                        در انتظار پاسخ--}}
{{--                    @endif--}}
{{--                </td>--}}
{{--            </tr>--}}
{{--        @endforeach--}}

{{--         Loop for Notifications --}}
{{--        @foreach($this->meetingUsers as $meetingUser)--}}
{{--            <tr class="hover:bg-gray-50 odd:bg-gray-50">--}}
{{--                <td class="px-4 py-3">وضعیت جلسه</td>--}}
{{--                <td class="px-4 py-3">{{ $meetingUser->meeting->scriptorium }}</td>--}}
{{--                <td class="px-4 py-3">{{ $meetingUser->meeting->title }}</td>--}}
{{--                <td class="px-4 py-3">{{ $meetingUser->meeting->date }}</td>--}}
{{--                <td class="px-4 py-3">{{ $meetingUser->meeting->time }}</td>--}}
{{--                <td class="px-4 py-3">--}}
{{--                    @if($meetingUser->meeting->status == '1')--}}
{{--                        لغو شده--}}
{{--                    @elseif($meetingUser->meeting->status == '-1')--}}
{{--                        برگزار می‌شود--}}
{{--                    @else--}}
{{--                        در حال بررسی--}}
{{--                    @endif--}}
{{--                </td>--}}
{{--            </tr>--}}
{{--        @endforeach--}}

{{--         Loop for Tasks --}}
{{--        @foreach($this->meetings as $meeting)--}}
{{--            @foreach($meeting->tasks as $task)--}}
{{--                <tr class="hover:bg-gray-50 odd:bg-gray-50">--}}
{{--                    <td class="px-4 py-3">اقدام</td>--}}
{{--                    <td class="px-4 py-3">{{ $task->full_name() }}</td>--}}
{{--                    <td class="px-4 py-3">{{ $meeting->title }}</td>--}}
{{--                    <td class="px-4 py-3">{{ $task->sent_date }}</td>--}}
{{--                    <td class="px-4 py-3">{{ \Illuminate\Support\Str::limit($task->time_out, 5, '') }}</td>--}}
{{--                    <td class="px-4 py-3">ارسال شد</td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}
{{--        @endforeach--}}
{{--        </tbody>--}}
{{--    </table>--}}




{{--    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-white rounded-xl shadow-md max-w-4xl">--}}

{{--        @can('create-meeting')--}}
{{--            <div class="col-span-full mb-8">--}}
{{--                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">--}}
{{--                    <a href="{{route('invitations.result')}}"--}}
{{--                       class="bg-[#FCF7F8] hover:ring-2 hover:ring-blue-500 hover:ring-offset-2 text-black shadow flex flex-col gap-2 items-start transition duration-300 ease-in-out p-4 rounded-lg">--}}
{{--                        <p class="text-sm "> {{__('پاسخ اعضای جلسه به دعوتنامه های ارسالی')}}</p>--}}
{{--                        @if($this->meetingCount > 0)--}}
{{--                            <p class="text-2xl text-blue-600 font-bold">--}}
{{--                                {{ $this->unreadMeetingUsersCount }}--}}
{{--                            </p>--}}
{{--                        @endif--}}
{{--                    </a>--}}
{{--                    <a href="{{route('task.sent')}}"--}}
{{--                       class="bg-[#FCF7F8] shadow hover:ring-2 hover:ring-blue-500 hover:ring-offset-2 flex flex-col items-start transition duration-300 ease-in-out p-4 rounded-lg">--}}
{{--                        <p class="text-sm "> {{__('اقدامات ارسال شده به دبیرجلسه')}}</p>--}}
{{--                        <p class="text-2xl text-blue-600 font-bold">{{$this->sentTaskCount}}</p>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endcan--}}
{{--        <div class="col-span-full mt-8">--}}
{{--            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">--}}
{{--                <a href="{{route('meeting.invitation')}}"--}}
{{--                   class="bg-[#FCF7F8] shadow hover:ring-2 hover:ring-blue-500 hover:ring-offset-2  flex flex-col items-start transition duration-300 ease-in-out p-4 rounded-lg">--}}
{{--                    <p class="text-sm ">{{__('لیست جلساتی که دعوت شده اید')}}</p>--}}
{{--                    <p class="text-2xl text-blue-600 font-bold">{{$this->invitation}}</p>--}}
{{--                </a>--}}
{{--                <a href="{{route('meeting.notification')}}"--}}
{{--                   class="bg-[#FCF7F8] shadow hover:ring-2 hover:ring-blue-500 hover:ring-offset-2  flex flex-col items-start transition duration-300 ease-in-out p-4 rounded-lg">--}}
{{--                    <p class="text-sm "> {{__('وضعیت تشکیل جلسات')}}</p>--}}
{{--                    <p class="text-2xl text-blue-600 font-bold">{{$this->read_by_user}}</p>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}


{{--    <div class="overflow-x-auto rounded-xl shadow-sm border border-gray-200 bg-white p-4">--}}
{{--        <table class="w-full text-sm text-gray-700 text-right rtl:text-right">--}}
{{--            <thead class="bg-gray-50 text-gray-600 border-b text-sm font-semibold">--}}
{{--            <tr>--}}
{{--                <th class="px-4 py-3">عنوان جلسه</th>--}}
{{--                <th class="px-4 py-3">تاریخ</th>--}}
{{--                <th class="px-4 py-3">زمان</th>--}}
{{--                <th class="px-4 py-3">فرستنده</th>--}}
{{--                <th class="px-4 py-3">اقدام شما</th>--}}
{{--                <th class="px-4 py-3">وضعیت</th>--}}
{{--                <th class="px-4 py-3">جانشین</th>--}}
{{--                <th class="px-4 py-3">اعلان</th>--}}
{{--            </tr>--}}
{{--            </thead>--}}
{{--            <tbody class="divide-y divide-gray-100">--}}
{{--            @foreach($this->meetingUsers as $meetingUser)--}}
{{--                <tr class="hover:bg-gray-50">--}}
{{--                    <td class="px-4 py-3">--}}
{{--                        <div class="flex items-center gap-2">--}}
{{--                            <div class="shrink-0">--}}
{{--                                <span class="inline-flex justify-center items-center size-8 rounded-full border-4 border-teal-100 bg-teal-200 text-teal-800 dark:border-teal-900 dark:bg-teal-800 dark:text-teal-400">--}}
{{--                                    @if($meetingUser->meeting->is_present == 1)--}}
{{--                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-green-600">--}}
{{--                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>--}}
{{--                                        </svg>--}}
{{--                                    @else--}}
{{--                                        <svg class="shrink-0 size-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">--}}
{{--                                            <circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path>--}}
{{--                                        </svg>--}}
{{--                                    @endif--}}
{{--                                </span>--}}
{{--                            </div>--}}
{{--                            <span>{{ $meetingUser->meeting->title }}</span>--}}
{{--                        </div>--}}
{{--                    </td>--}}
{{--                    <td class="px-4 py-3">{{ $meetingUser->meeting->date }}</td>--}}
{{--                    <td class="px-4 py-3">{{ $meetingUser->meeting->time }}</td>--}}
{{--                    <td class="px-4 py-3 font-bold">{{ $meetingUser->meeting->scriptorium }}</td>--}}
{{--                    <td class="px-4 py-3">--}}
{{--                        @if(!$meetingUser->is_present() && !$meetingUser->is_absent())--}}
{{--                            <div class="flex gap-2">--}}
{{--                                <button wire:click="accept({{ $meetingUser->meeting_id }})" class="px-3 py-1.5 mb-2 bg-gray-800 border border-transparent rounded-md text-sm text-white">--}}
{{--                                    {{ ('قبول') }}--}}
{{--                                </button>--}}
{{--                                <button wire:click="openModalDeny({{ $meetingUser->meeting_id }})" class="px-3 py-1.5 mb-2 bg-red-600 border border-transparent rounded-md text-sm text-white">--}}
{{--                                    {{ ('رد') }}--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    </td>--}}
{{--                    <td class="px-4 py-3 text-sm">--}}
{{--                        @if($meetingUser->is_present())--}}
{{--                            <span class="text-green-700">{{ __('پذیرفتید') }}</span>--}}
{{--                        @elseif($meetingUser->is_absent())--}}
{{--                            <span class="text-red-600 font-bold">{{ __('نپذیرفتید') }}</span>--}}
{{--                        @endif--}}
{{--                    </td>--}}
{{--                    <td class="px-4 py-3 text-sm">--}}
{{--                        @if($meetingUser->replacement)--}}
{{--                            <span class="text-green-700">{{ $meetingUser->replacementName() }}</span>--}}
{{--                        @endif--}}
{{--                    </td>--}}
{{--                    <!-- Notification Column -->--}}
{{--                    <td class="px-4 py-3">--}}
{{--                        <div class="bg-teal-50 dark:bg-teal-800/30 mb-4 rounded-lg p-4 shadow-sm max-w-4xl">--}}
{{--                            <div class="flex items-start gap-3">--}}
{{--                                <div>--}}
{{--                                    <span class="inline-flex items-center justify-center size-10 rounded-full border-4 border-teal-100 bg-teal-200 text-teal-800 dark:border-teal-900 dark:bg-teal-800 dark:text-teal-400">--}}
{{--                                        @if($meetingUser->meeting->is_present == 1)--}}
{{--                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="shrink-0 size-4 text-green-600">--}}
{{--                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>--}}
{{--                                            </svg>--}}
{{--                                        @else--}}
{{--                                            <svg class="shrink-0 size-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">--}}
{{--                                                <circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path>--}}
{{--                                            </svg>--}}
{{--                                        @endif--}}
{{--                                    </span>--}}
{{--                                </div>--}}
{{--                                <div class="w-full">--}}
{{--                                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $meetingUser->meeting->title }}</h3>--}}
{{--                                    <div class="mt-1 text-sm text-gray-700 dark:text-neutral-300 flex justify-between items-center">--}}
{{--                                        <span>--}}
{{--                                            {{ 'این جلسه که در تاریخ ' }}--}}
{{--                                            <span class="font-bold">{{ $meetingUser->meeting->date }}</span>--}}
{{--                                            {{ __('و در ساعت') }}--}}
{{--                                            <span class="font-bold">{{ $meetingUser->meeting->time }}</span>--}}
{{--                                            @if($meetingUser->meeting->is_present == '1')--}}
{{--                                                {{ __('که اینجانب قبول کردید، ') }}--}}
{{--                                            @elseif($meetingUser->meeting->is_present == '-1')--}}
{{--                                                {{ __('که اینجانب رد کردید، ') }}--}}
{{--                                            @endif--}}
{{--                                            @if($meetingUser->meeting->status == \App\Enums\MeetingStatus::IS_CANCELLED->value)--}}
{{--                                                <span class="font-bold">{{ __('لغو شد') }}</span>--}}
{{--                                            @elseif($meetingUser->meeting->status == \App\Enums\MeetingStatus::IS_NOT_CANCELLED->value)--}}
{{--                                                <span class="font-bold">{{ __('برگزار میشود') }}</span>--}}
{{--                                            @elseif($meetingUser->meeting->status == \App\Enums\MeetingStatus::PENDING->value)--}}
{{--                                                <span class="font-bold">{{ __('در حال بررسی است') }}</span>--}}
{{--                                            @elseif($meetingUser->meeting->status == \App\Enums\MeetingStatus::IS_IN_PROGRESS->value)--}}
{{--                                                <span class="font-bold">{{ __('در حال برگزاری است') }}</span>--}}
{{--                                            @elseif($meetingUser->meeting->status == \App\Enums\MeetingStatus::IS_FINISHED->value)--}}
{{--                                                <span class="font-bold">{{ __('جلسه خاتمه یافت') }}</span>--}}
{{--                                            @endif--}}
{{--                                        </span>--}}
{{--                                        <x-primary-button wire:click="markNotification({{ $meetingUser->meeting->id }})">--}}
{{--                                            {{ __('متوجه شدم') }}--}}
{{--                                        </x-primary-button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}
{{--            </tbody>--}}
{{--        </table>--}}

{{--        <span class="p-2 mx-2">--}}
{{--        {{ $this->meetingUsers->withQueryString()->links(data: ['scrollTo' => false]) }}--}}
{{--    </span>--}}
{{--    </div>--}}

    <div class="overflow-x-auto rounded-xl shadow-sm border border-gray-200 bg-white p-4">
        <table class="w-full text-sm text-gray-700 text-right rtl:text-right">
            <thead class="bg-gray-50 text-gray-600 border-b text-sm font-semibold">
            <tr>
                <th class="px-4 py-3">نوع پیام</th>
                <th class="px-4 py-3">فرستنده</th>
                <th class="px-4 py-3">متن</th>
                <th class="px-4 py-3">گیرنده</th>
                <th class="px-4 py-3">اقدام شما</th>
                <th class="px-4 py-3">وضعیت</th>
                <th class="px-4 py-3">عملیات</th>

            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">دعوت به جلسه</td>
                    <td class="px-4 py-3">scriptorium</td>
                    <td class="px-4 py-3">شما به این جلسه در تاریخ و ساعت دعوت شده اید</td>
                    <td class="px-4 py-3">user 5</td>
                    <td class="px-4 py-3">
                        <button>Accept</button>/
                        <button>Deny</button>
                    </td>
                    <td class="px-4 py-3">در حال بررسی دعوتنامه / لغو شد / برگزار میشود / در حال برگزاری</td>
                    <td class="px-4 py-3">
                        <button>X</button>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 py-3">ارسال خلاصه مذاکره</td>
                    <td class="px-4 py-3">scriptorium</td>
                    <td class="px-4 py-3">آپدیت پرگار و مهلت انجام آن</td>
                    <td class="px-4 py-3">user 5</td>
                    <td class="px-4 py-3">
                        <button>Accept</button>/
                        <button>Deny</button>
                    </td>
                    <td class="px-4 py-3">در حال بررسی درخواست شما / خلاسه مذاگره برای شما ویرایش شد</td>
                    <td class="px-4 py-3">
                        <button>X</button>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>



</div>
