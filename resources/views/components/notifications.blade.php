@php use App\Models\MeetingUser; @endphp
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-4 py-4 border-b">
    <a href="{{route('message')}}"
       class="flex justify-between items-center gap-x-4 hover:bg-[#3D3D3D] hover:text-[#FFFAEC] text-black border border-[#3D3D3D] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
        <h3 class="text-sm font-semibold flex items-center gap-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
            </svg>
            {{__('پیغام های دریافتی')}}</h3>
        <span class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5">{{$this->messages}}</span>
    </a>
    <a href="{{route('attended.meetings')}}"
       class="flex justify-between items-center gap-x-4 hover:bg-[#882042] hover:text-[#FFFAEC] text-black border border-[#882042] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
        <h3 class="text-sm font-semibold"> {{__('جلساتی که در آن شرکت کردم')}}</h3>
        <span
            class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5">{{MeetingUser::where('user_id',auth()->user()->id)->where('is_present',1)->count()}}</span>
    </a>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <a href="{{route('meetings.create')}}"
       class="flex justify-between items-center hover:bg-[#40A578] hover:text-[#FFFAEC] border border-[#40A578] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
        <h3 class="text-sm font-semibold flex items-center gap-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            {{__('ایجاد جلسه جدید')}}</h3>
    </a>
    <a href="{{route('meetingsList')}}"
       class="flex justify-between items-center gap-x-4 bg-transparent text-black hover:bg-[#9DDE8B] hover:text-[#FFFAEC] border border-[#9DDE8B] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
        <h3 class="text-sm font-semibold flex items-center gap-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
            </svg>
            {{__('جدول جلسات')}}</h3>
        {{--        <span class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5"></span>--}}
    </a>
    {{--    <a href="{{route('meetings.index')}}" class="flex justify-between items-center gap-x-4 bg-transparent text-black hover:bg-[#9DDE8B] hover:text-[#FFFAEC] border border-[#9DDE8B] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">--}}
    {{--        <h3 class="text-sm font-semibold"> {{__('لیست جلساتی که تشکیل می شود')}}</h3>--}}
    {{--        <span class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5">{{\App\Models\Meeting::where('scriptorium',auth()->user()->user_info->full_name)->count()}}</span>--}}
    {{--    </a>--}}
    <a href="{{route('scriptorium.report')}}"
       class="flex justify-between items-center gap-x-4 hover:bg-[#CD5555] hover:text-[#FFFAEC] text-black border border-[#CD5555] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
        <h3 class="text-sm font-semibold flex items-center gap-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
            </svg>
            {{__('گزارش جلسات تشکیل شده')}}</h3>
        {{--        <span class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5">4</span>--}}
    </a>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-4">
    <a href="{{route('meeting.report')}}"
       class="flex justify-between items-center hover:bg-[#3D3D3D] hover:text-[#FFFAEC] text-black border border-[#3D3D3D] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
        <h3 class="text-sm font-semibold flex items-center gap-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605" />
            </svg>
            {{__('داشبورد جلسات')}}
        </h3>
    </a>
</div>
