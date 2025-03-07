@php use App\Models\MeetingUser; @endphp
<div class="col-span-3 space-y-6">
    @if(auth()->user()->user_info->create_meeting)
        <div class="flex justify-center items-center w-full">
            <div class="border-t border-gray-200 w-1/4"></div>
            <span class="mx-4 text-sm text-gray-700">first one</span>
            <div class="border-t border-gray-200 w-1/4"></div>
        </div>
        <div class="grid grid-cols-3 content-evenly gap-4">
            <a href="{{route('meeting.create')}}"
               class="flex items-center gap-x-2 hover:bg-[#40A578] hover:text-[#FFFAEC] border border-[#9DDE8B] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <h3 class="text-sm font-semibold"> {{__('ایجاد جلسه جدید')}}</h3>
            </a>
            <a href="{{route('meetingsList')}}"
               class="flex items-center gap-x-2 bg-transparent text-black hover:bg-[#9DDE8B] hover:text-[#FFFAEC] border border-[#9DDE8B] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5"/>
                </svg>
                <h3 class="text-sm font-semibold"> {{__('لیست جلسات در حال برگزاری')}}</h3>
            </a>
            <a href="{{route('scriptorium.report')}}"
               class="flex justify-between items-center gap-x-4 hover:bg-[#CD5555] hover:text-[#FFFAEC] text-black border border-[#9DDE8B] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
                <span class="flex items-center gap-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>
                </svg>
                <h3 class="text-sm font-semibold"> {{__('گزارش جلسات تشکیل شده')}}</h3>
                </span>
            </a>
        </div>
        <div class="flex justify-center items-center w-full">
            <div class="border-t border-gray-200 w-1/4"></div>
            <span class="mx-4 text-sm text-gray-700">Admin</span>
            <div class="border-t border-gray-200 w-1/4"></div>
        </div>
    @endif
        <div class="grid grid-cols-3 content-evenly gap-4">
            <a href="{{route('message')}}"
               class="flex justify-between items-center gap-x-2 hover:bg-[#3D3D3D] hover:text-[#FFFAEC] text-black border border-[#9DDE8B] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
                        <span class="flex items-center gap-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                        </svg>
                        <span class="text-sm font-semibold">{{__('پیغام های دریافتی')}}</span>
                        </span>
                <span class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5">{{$this->messages}}</span>
            </a>
            <a href="{{route('attended.meetings')}}"
               class="flex justify-between items-center gap-x-4 hover:bg-[#882042] hover:text-[#FFFAEC] text-black border border-[#9DDE8B] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
                <span class="flex items-center gap-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z"/>
                </svg>
                <h3 class="text-sm font-semibold"> {{__('جلساتی که در آن شرکت کردم')}}</h3>
                </span>
                <span
                    class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5">{{MeetingUser::where('user_id',auth()->user()->id)->where('is_present',1)->count()}}</span>
            </a>
        </div>
        <div class="flex justify-center items-center w-full">
            <div class="border-t border-gray-200 w-1/4"></div>
            <span class="mx-4 text-sm text-gray-700">Admin</span>
            <div class="border-t border-gray-200 w-1/4"></div>
        </div>
        <div class="grid grid-cols-3 content-evenly gap-4">
            <a href="{{route('meeting.report')}}"
               class="flex h-full items-center gap-x-2 hover:bg-[#3D3D3D] hover:text-[#FFFAEC] text-black border border-[#9DDE8B] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z"/>
                </svg>
                <h3 class="text-sm font-semibold"> {{__('داشبورد جلسات')}}</h3>
            </a>
        </div>
</div>

