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



    <div class="overflow-x-auto rounded-xl shadow-sm border border-gray-200 bg-white">
        <table class="w-full text-sm text-gray-700 text-right rtl:text-right">
            <thead class="bg-gray-50 text-gray-600 border-b text-sm font-semibold">
            <tr>
                <th class="px-4 py-3">نوع پیام</th>
                <th class="px-4 py-3">فرستنده</th>
                <th class="px-4 py-3">عنوان</th>
                <th class="px-4 py-3">تاریخ ارسال</th>
                <th class="px-4 py-3">ساعت ارسال</th>
                <th class="px-4 py-3">وضعیت جلسه</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">دعوت به جلسه</td>
                <td class="px-4 py-3">نسرین محمدی</td>
                <td class="px-4 py-3">جلسه کمیته انضباطی</td>
                <td class="px-4 py-3">1403/01/20</td>
                <td class="px-4 py-3">09:15</td>
                <td class="px-4 py-3 text-yellow-600 font-semibold">در حال بررسی</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">پاسخ (قبول)</td>
                <td class="px-4 py-3">رضا خالقی</td>
                <td class="px-4 py-3">جلسه هیئت‌مدیره</td>
                <td class="px-4 py-3">1403/01/18</td>
                <td class="px-4 py-3">13:10</td>
                <td class="px-4 py-3 text-green-600 font-semibold">برگزار میشود</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">پاسخ (رد)</td>
                <td class="px-4 py-3">سارا رحیمی</td>
                <td class="px-4 py-3">جلسه بودجه‌ریزی</td>
                <td class="px-4 py-3">1403/01/19</td>
                <td class="px-4 py-3">10:45</td>
                <td class="px-4 py-3 text-green-600 font-semibold">برگزار میشود</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">تعیین جانشین</td>
                <td class="px-4 py-3">سارا رحیمی</td>
                <td class="px-4 py-3">جلسه بودجه‌ریزی</td>
                <td class="px-4 py-3">1403/01/19</td>
                <td class="px-4 py-3">10:46</td>
                <td class="px-4 py-3 text-green-600 font-semibold">برگزار میشود</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">ارسال اقدام</td>
                <td class="px-4 py-3">امیر صفری</td>
                <td class="px-4 py-3">جلسه بودجه‌ریزی</td>
                <td class="px-4 py-3">1403/01/20</td>
                <td class="px-4 py-3">14:00</td>
                <td class="px-4 py-3 text-green-600 font-semibold">برگزار میشود</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">ارسال اقدام</td>
                <td class="px-4 py-3">الهه نادری</td>
                <td class="px-4 py-3">جلسه کمیته انضباطی</td>
                <td class="px-4 py-3">1403/01/20</td>
                <td class="px-4 py-3">15:30</td>
                <td class="px-4 py-3 text-yellow-600 font-semibold">در حال بررسی</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">دعوت به جلسه</td>
                <td class="px-4 py-3">محمدرضا احمدی</td>
                <td class="px-4 py-3">جلسه فناوری اطلاعات</td>
                <td class="px-4 py-3">1403/01/22</td>
                <td class="px-4 py-3">08:30</td>
                <td class="px-4 py-3 text-red-600 font-semibold">لغو شد</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">پاسخ (قبول)</td>
                <td class="px-4 py-3">الهام حسینی</td>
                <td class="px-4 py-3">جلسه فناوری اطلاعات</td>
                <td class="px-4 py-3">1403/01/22</td>
                <td class="px-4 py-3">09:00</td>
                <td class="px-4 py-3 text-red-600 font-semibold">لغو شد</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">ارسال اقدام</td>
                <td class="px-4 py-3">ناصر کاظمی</td>
                <td class="px-4 py-3">جلسه هیئت‌مدیره</td>
                <td class="px-4 py-3">1403/01/18</td>
                <td class="px-4 py-3">14:10</td>
                <td class="px-4 py-3 text-green-600 font-semibold">برگزار میشود</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">پاسخ (رد)</td>
                <td class="px-4 py-3">امین قادری</td>
                <td class="px-4 py-3">جلسه توسعه منابع انسانی</td>
                <td class="px-4 py-3">1403/01/21</td>
                <td class="px-4 py-3">11:00</td>
                <td class="px-4 py-3 text-yellow-600 font-semibold">در حال بررسی</td>
            </tr>
            </tbody>
        </table>
    </div>



    {{--    <table class="w-full text-sm text-gray-700 text-center rtl:text-right">--}}
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
{{--        --}}{{-- Loop for Invitations --}}
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

{{--        --}}{{-- Loop for Notifications --}}
{{--        @foreach($this->meetingUsers as $meetingUser)--}}
{{--            <tr class="hover:bg-gray-50 odd:bg-gray-50">--}}
{{--                <td class="px-4 py-3">وضعیت جلسه</td>--}}
{{--                <td class="px-4 py-3">{{ $meetingUser->meeting->scriptorium }}</td>--}}
{{--                <td class="px-4 py-3">{{ $meetingUser->meeting->title }}</td>--}}
{{--                <td class="px-4 py-3">{{ $meetingUser->meeting->date }}</td>--}}
{{--                <td class="px-4 py-3">{{ $meetingUser->meeting->time }}</td>--}}
{{--                <td class="px-4 py-3">--}}
{{--                    @if($meetingUser->meeting->is_cancelled == '1')--}}
{{--                        لغو شده--}}
{{--                    @elseif($meetingUser->meeting->is_cancelled == '-1')--}}
{{--                        برگزار می‌شود--}}
{{--                    @else--}}
{{--                        در حال بررسی--}}
{{--                    @endif--}}
{{--                </td>--}}
{{--            </tr>--}}
{{--        @endforeach--}}

{{--        --}}{{-- Loop for Tasks --}}
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


</div>
