@php use App\Models\MeetingUser; @endphp
<div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 mt-14 lg:grid-cols-4 gap-6 mb-4 py-4 border-b">
        <a href="{{route('message')}}"
           class="flex justify-between items-center gap-x-4 hover:bg-[#3D3D3D] hover:text-[#FFFAEC] text-black border border-[#3D3D3D] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
            <h3 class="text-sm font-semibold"> {{__('پیغام های دریافتی')}}</h3>
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
            <h3 class="text-sm font-semibold"> {{__('ایجاد جلسه جدید')}}</h3>
        </a>
        <a href="{{route('meetingsList')}}"
           class="flex justify-between items-center gap-x-4 bg-transparent text-black hover:bg-[#9DDE8B] hover:text-[#FFFAEC] border border-[#9DDE8B] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
            <h3 class="text-sm font-semibold"> {{__('جدول جلسات')}}</h3>
            {{--        <span class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5"></span>--}}
        </a>
        {{--    <a href="{{route('meetings.index')}}" class="flex justify-between items-center gap-x-4 bg-transparent text-black hover:bg-[#9DDE8B] hover:text-[#FFFAEC] border border-[#9DDE8B] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">--}}
        {{--        <h3 class="text-sm font-semibold"> {{__('لیست جلساتی که تشکیل می شود')}}</h3>--}}
        {{--        <span class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5">{{\App\Models\Meeting::where('scriptorium',auth()->user()->user_info->full_name)->count()}}</span>--}}
        {{--    </a>--}}
        <a href="{{route('scriptorium.report')}}"
           class="flex justify-between items-center gap-x-4 hover:bg-[#CD5555] hover:text-[#FFFAEC] text-black border border-[#CD5555] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
            <h3 class="text-sm font-semibold"> {{__('گزارش جلسات تشکیل شده')}}</h3>
            {{--        <span class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5">4</span>--}}
        </a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-4">
        <a href="{{route('meeting.report')}}"
           class="flex justify-between items-center hover:bg-[#3D3D3D] hover:text-[#FFFAEC] text-black border border-[#3D3D3D] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
            <h3 class="text-sm font-semibold"> {{__('داشبورد جلسات')}}</h3>
        </a>
    </div>
</div>
