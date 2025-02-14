@php use App\Models\MeetingUser; @endphp
@php use App\Models\Meeting; @endphp
<div wire:poll.visible.60s>

    <x-template>
        <nav class="flex justify-between mb-4">
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
                <li>
                        <span
                            class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                            {{__('پیغام های دریافتی')}}
                        </span>
                </li>
            </ol>
        </nav>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <p class="col-span-4">{{__('نقش دبیرجلسه')}}</p>
            <a href="{{route('meetings.request')}}"
               class="flex justify-between items-center hover:bg-[#40A578] hover:text-[#FFFAEC] border border-[#40A578] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
                <h3 class="text-sm font-semibold"> {{__('پاسخ دعوتنامه')}}</h3>
                @if(Meeting::where('scriptorium',auth()->user()->user_info->full_name)->exists())
                <span class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5">
                        {{MeetingUser::where('is_present','!=' , '0')->where('read_by_scriptorium',false)->count()}}
                </span>
                @endif
            </a>
        </div>


        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-4 pt-4 border-t-2">
            <p class="col-span-4">{{__('نقش عضو جلسه')}}</p>
            {{--        @if($this->invitation)--}}
            <a href="{{route('meeting.invitation')}}"
               class="flex justify-between items-center hover:bg-[#006769] hover:text-[#FFFAEC] text-black border border-[#006769] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
                <span class="text-sm font-semibold">{{__('دعوتنامه')}}</span>
                <span class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5">{{$this->invitation}}</span>
            </a>
            {{--        @endif--}}
            <a href="{{route('meeting.notification')}}"
               class="flex justify-between items-center gap-x-4 bg-transparent text-black hover:bg-[#9DDE8B] hover:text-[#FFFAEC] border border-[#9DDE8B] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
                <h3 class="text-sm font-semibold"> {{__('نتیجه نهایی جلسات')}}</h3>
                <span
                    class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5">{{Meeting::where('is_cancelled','!=','0')->where('scriptorium','!=',auth()->user()->user_info->full_name)->count()}}</span>
            </a>
        </div>

        {{--        <div class="relative w-full rounded-lg border border-transparent bg-blue-600 p-4 [&>svg]:absolute [&>svg]:text-foreground [&>svg]:right-4 [&>svg]:top-4 [&>svg+div]:translate-y-[-3px] [&:has(svg)]:pr-11 text-white">--}}
        {{--            <svg class="w-5 h-5 -translate-y-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>--}}
        {{--            <h5 class="mb-1 font-medium leading-none tracking-tight">{{__('وضعیت نهایی جلسه')}}</h5>--}}
        {{--            <div class="text-sm opacity-80">{{__('این جلسه در این تاریخ و ساعت برگزار می شود')}}</div>--}}
        {{--        </div>--}}


    </x-template>
</div>
