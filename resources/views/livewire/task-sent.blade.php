<div>
    <nav class="flex justify-between mb-4 mt-14">
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
            <li class="flex items-center h-full">
                <a href="{{route('message')}}"
                   class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                    <span>{{__('پیغام های دریافتی')}}</span>
                </a>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                 stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            <li class="flex items-center h-full">
                <span class="active-breadcrumb">{{__('لیست اقدامات ارسال شده')}}</span>
            </li>
        </ol>
    </nav>
    <div class="mx-auto p-4">
        <table class="min-w-full bg-lime-300 divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('جلسات')}}</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('اقدام کننده')}}</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('اقدامات')}}</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('مهلت اقدام')}}</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('تاریخ ارسال')}}</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach($this->meetings as $meeting)
                @foreach($this->tasks->where('meeting_id', $meeting->id) as $task)
                    <tr>
                        @if ($loop->first)
                            <td class="px-6 py-4 whitespace-nowrap text-center" rowspan="{{ $this->tasks->where('meeting_id', $meeting->id)->count() }}">
                                {{ $meeting->title }}
                            </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $task->full_name()}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $task->body }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $task->time_out }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $task->sent_date }}</td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>
</div>
