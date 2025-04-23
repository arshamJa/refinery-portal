<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <a href="{{route('dashboard.meeting')}}" wire:navigate
       class="bg-[#FCF7F8] hover:ring-2 hover:ring-[#4332BD] hover:ring-offset-2 text-black shadow  flex gap-2 items-start transition duration-300 ease-in-out p-4 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
        </svg>
        <h3 class="text-sm font-semibold"> {{__('جلسات')}}</h3>
    </a>
    <a href="{{route('employee.organization')}}" wire:navigate
       class="bg-[#FCF7F8] hover:ring-2 hover:ring-[#4332BD] hover:ring-offset-2 text-black shadow  flex gap-2 items-start transition duration-300 ease-in-out p-4 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="m7.875 14.25 1.214 1.942a2.25 2.25 0 0 0 1.908 1.058h2.006c.776 0 1.497-.4 1.908-1.058l1.214-1.942M2.41 9h4.636a2.25 2.25 0 0 1 1.872 1.002l.164.246a2.25 2.25 0 0 0 1.872 1.002h2.092a2.25 2.25 0 0 0 1.872-1.002l.164-.246A2.25 2.25 0 0 1 16.954 9h4.636M2.41 9a2.25 2.25 0 0 0-.16.832V12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 12V9.832c0-.287-.055-.57-.16-.832M2.41 9a2.25 2.25 0 0 1 .382-.632l3.285-3.832a2.25 2.25 0 0 1 1.708-.786h8.43c.657 0 1.281.287 1.709.786l3.284 3.832c.163.19.291.404.382.632M4.5 20.25h15A2.25 2.25 0 0 0 21.75 18v-2.625c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125V18a2.25 2.25 0 0 0 2.25 2.25Z" />
        </svg>
        <h3 class="text-sm font-semibold"> {{__('سامانه ها')}}</h3>
    </a>
    <a  href="{{route('blogs.index')}}" wire:navigate
        class="bg-[#FCF7F8] hover:ring-2 hover:ring-[#4332BD] hover:ring-offset-2 text-black shadow  flex gap-2 items-start transition duration-300 ease-in-out p-4 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
        </svg>
        <h3 class="text-sm font-semibold"> {{__('اخبار و اطلاعیه')}}</h3>
    </a>
    <a href="{{route('phone-list.index')}}" wire:navigate
       class="bg-[#FCF7F8] hover:ring-2 hover:ring-[#4332BD] hover:ring-offset-2 text-black shadow  flex gap-2 items-start transition duration-300 ease-in-out p-4 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75v-4.5m0 4.5h4.5m-4.5 0 6-6m-3 18c-8.284 0-15-6.716-15-15V4.5A2.25 2.25 0 0 1 4.5 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44-.054.902-.417 1.173l-1.293.97a1.062 1.062 0 0 0-.38 1.21 12.035 12.035 0 0 0 7.143 7.143c.441.162.928-.004 1.21-.38l.97-1.293a1.125 1.125 0 0 1 1.173-.417l4.423 1.106c.5.125.852.575.852 1.091V19.5a2.25 2.25 0 0 1-2.25 2.25h-2.25Z" />
        </svg>
        <h3 class="text-sm font-semibold">  {{__('دفترچه تلفنی')}}</h3>
    </a>
</div>
<div class="my-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{route('message')}}" wire:navigate
           class="bg-[#FCF7F8] flex justify-between gap-2 hover:ring-2 hover:ring-[#4332BD] hover:ring-offset-2 text-black shadow  transition duration-300 ease-in-out p-4 rounded-lg">
                        <span class="flex items-center gap-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5"><path stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                            </svg>
                            <span class="text-sm font-semibold">{{__('پیغام های دریافتی')}}</span>
                        </span>
            <p wire:poll.visible.60s class="text-2xl text-blue-600 font-bold">{{$this->messages()}}</p>
        </a>
{{--        <a href="{{route('participants.task')}}"--}}
{{--           class="bg-[#FCF7F8] flex justify-between gap-2 hover:ring-2 hover:ring-[#4332BD] hover:ring-offset-2 text-black shadow  transition duration-300 ease-in-out p-4 rounded-lg">--}}
{{--                <span class="flex items-center gap-x-2">--}}
{{--                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
{{--                     stroke="currentColor" class="size-5">--}}
{{--                    <path stroke-linecap="round" stroke-linejoin="round"--}}
{{--                          d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.98.626-1.813 1.5-2.122"/>--}}
{{--                </svg>--}}
{{--                <h3 class="text-sm font-semibold"> {{__('اقداماتی که انجام دادم')}}</h3>--}}
{{--                </span>--}}
{{--        </a>--}}
    </div>
</div>

<div class="mb-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{route('meeting.report')}}" wire:navigate
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
