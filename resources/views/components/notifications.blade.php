@php use App\Models\MeetingUser; @endphp
{{--<div class="col-span-3 space-y-10 place-items-center">--}}
@if(auth()->user()->user_info->create_meeting)

    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">{{__('مراحل جلسات')}}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{route('meeting.create')}}"
               class="bg-[#FCF7F8] hover:ring-2 hover:ring-[#4332BD] hover:ring-offset-2 text-black shadow  flex gap-2 items-start transition duration-300 ease-in-out p-4 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <h3 class="text-sm font-semibold"> {{__('ایجاد جلسه جدید')}}</h3>
            </a>
            <a href="{{route('meetingsList')}}"
               class="bg-[#FCF7F8]  hover:ring-2 hover:ring-[#4332BD] hover:ring-offset-2 text-black shadow  flex gap-2 items-start transition duration-300 ease-in-out p-4 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5"/>
                </svg>
                <h3 class="text-sm font-semibold"> {{__('لیست جلسات در حال برگزاری')}}</h3>
            </a>
            <a href="{{route('scriptorium.report')}}"
               class="bg-[#FCF7F8] hover:ring-2 hover:ring-[#4332BD] hover:ring-offset-2 text-black shadow  flex gap-2 items-start transition duration-300 ease-in-out p-4 rounded-lg">
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
    </div>
@endif
<div class="mb-8">
    <h2 class="text-lg font-semibold mb-4 text-gray-800">{{__('پیام ها و اقدامات')}}</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{route('message')}}"
           class="bg-[#FCF7F8] flex justify-between gap-2 hover:ring-2 hover:ring-[#4332BD] hover:ring-offset-2 text-black shadow  transition duration-300 ease-in-out p-4 rounded-lg">
                        <span class="flex items-center gap-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5"><path stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                            </svg>
                            <span class="text-sm font-semibold">{{__('پیغام های دریافتی')}}</span>
                        </span>
            <p class="text-2xl text-blue-600 font-bold">{{$this->messages}}</p>
        </a>
        <a href="{{route('attended.meetings')}}"
           class="bg-[#FCF7F8]  flex justify-between gap-2 hover:ring-2 hover:ring-[#4332BD] hover:ring-offset-2 text-black shadow  transition duration-300 ease-in-out p-4 rounded-lg">
                <span class="flex items-center gap-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z"/>
                </svg>
                <h3 class="text-sm font-semibold"> {{__('جلساتی که در آن شرکت کردم')}}</h3>
                </span>
{{--                <span class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5">--}}
{{--                    {{\App\Models\Task::where('user_id',auth()->user()->id)->where('is_completed',false)->count()}}--}}
{{--                </span>--}}
        </a>
        <a href="#"
           class="bg-[#FCF7F8] flex justify-between gap-2 hover:ring-2 hover:ring-[#4332BD] hover:ring-offset-2 text-black shadow  transition duration-300 ease-in-out p-4 rounded-lg">
                <span class="flex items-center gap-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.98.626-1.813 1.5-2.122"/>
                </svg>
                <h3 class="text-sm font-semibold"> {{__('اقداماتی که انجام دادم')}}</h3>
                </span>
            {{--                <span--}}
            {{--                    class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5">{{MeetingUser::where('user_id',auth()->user()->id)->where('is_present',1)->count()}}</span>--}}
        </a>
    </div>
</div>

    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">{{__('گزارش کل جلسات')}}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{route('meeting.report')}}"
               class="bg-[#FCF7F8] flex gap-2 hover:ring-2 hover:ring-[#4332BD] hover:ring-offset-2 text-black shadow  transition duration-300 ease-in-out p-4 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z"/>
                </svg>
                <h3 class="text-sm font-semibold"> {{__('داشبورد جلسات')}}</h3>
            </a>
        </div>
    </div>


{{--</div>--}}
