<x-app-layout>
    {{--    advance search filter--}}
    {{--    <form action="{{ route('your.search.route') }}" method="GET">--}}
    {{--        <input type="text" name="name" placeholder="Name">--}}
    {{--        <input type="email" name="email" placeholder="Email">--}}
    {{--        <input type="date" name="date" placeholder="Date">--}}
    {{--        <select name="status">--}}
    {{--            <option value="">All Statuses</option>--}}
    {{--            <option value="active">Active</option>--}}
    {{--            <option value="inactive">Inactive</option>--}}
    {{--        </select>--}}
    {{--        <button type="submit">Search</button>--}}
    {{--    </form>--}}

    {{--    <table>--}}
    {{--        <thead>--}}
    {{--        <tr>--}}
    {{--            <th>Name</th>--}}
    {{--            <th>Email</th>--}}
    {{--            <th>Date</th>--}}
    {{--            <th>Status</th>--}}
    {{--        </tr>--}}
    {{--        </thead>--}}
    {{--        <tbody>--}}
    {{--        @foreach ($results as $result)--}}
    {{--            <tr>--}}
    {{--                <td>{{ $result->name }}</td>--}}
    {{--                <td>{{ $result->email }}</td>--}}
    {{--                <td>{{ $result->date }}</td>--}}
    {{--                <td>{{ $result->status }}</td>--}}
    {{--            </tr>--}}
    {{--        @endforeach--}}
    {{--        </tbody>--}}
    {{--    </table>--}}

    {{--    advance search filter function --}}
    {{--    public function search(Request $request)--}}
    {{--    {--}}
    {{--    $query = YourModel::query(); // Start with a base query--}}
    {{--    // Apply filters based on input fields--}}
    {{--    if ($request->has('name') && $request->input('name') !== null) {--}}
    {{--    $query->where('name', 'like', '%' . $request->input('name') . '%');--}}
    {{--    }--}}
    {{--    if ($request->has('email') && $request->input('email') !== null) {--}}
    {{--    $query->where('email', 'like', '%' . $request->input('email') . '%');--}}
    {{--    }--}}
    {{--    if ($request->has('date') && $request->input('date') !== null) {--}}
    {{--    $query->where('date', $request->input('date'));--}}
    {{--    }--}}
    {{--    if ($request->has('status') && $request->input('status') !== null) {--}}
    {{--    if ($request->input('status') !== "") {--}}
    {{--    $query->where('status', $request->input('status'));--}}
    {{--    }--}}
    {{--    }--}}
    {{--    $results = $query->get();--}}
    {{--    return view('your.view', compact('results'));--}}
    {{--    }--}}

    <nav class="flex justify-between mb-4 mt-20">
        <ol class="inline-flex items-center mb-3 space-x-1 text-xs text-neutral-500 [&_.active-breadcrumb]:text-neutral-600 [&_.active-breadcrumb]:font-medium sm:mb-0">
            <li class="flex items-center h-full">
                <a href="{{route('dashboard')}}"
                   class="inline-flex items-center px-2 gap-1 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
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
                    class="inline-flex items-center gap-1 px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                          stroke="currentColor" class="w-3.5 h-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5"/>
                </svg>
                    {{__('لیست جلسات در حال برگزاری')}}
                </span>
            </li>
        </ol>
    </nav>
    <div class="pt-4 sm:px-10 sm:pt-6 border shadow-md rounded-md">

        <form method="GET" action="{{ route('meetingsList') }}">
            @csrf
            <div class="grid grid-cols-2 items-end gap-4">
                <div class="relative">
                    <div
                        class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500"
                             fill="currentColor" viewbox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <x-text-input type="text" name="search" placeholder="جست و جو..."/>
                </div>
                <select name="is_cancelled"
                        class="w-1/3 text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                    <option>{{__('وضعیت جلسه')}}</option>
                    <option value="0">{{__('در حال بررسی ...')}}</option>
                    <option value="-1">{{__('جلساتی که تشکیل میشود')}}</option>
                    <option value="1">{{__('جلساتی که لفو شد')}}</option>
                </select>
            </div>
            <div class="w-full flex gap-4 items-center pl-4 py-2 mt-1">

                <button type="submit"
                        class="inline-flex gap-1 items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"/>
                    </svg>
                    {{__('فیلتر')}}
                </button>
                <a href="{{route('meetingsList')}}"
                   class="px-4 py-2 bg-[#A31621] border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{__('نمایش همه')}}
                </a>
            </div>
        </form>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead
                class="text-sm text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    {{__('جلسات')}}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{__('دبیرجلسه')}}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{__('تاریخ')}}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{__('مکان')}}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{__('ساعت')}}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{__('حاضرین')}}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{__('مشاهده اعضا')}}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{__('وضعیت جلسه')}}
                </th>
                <th scope="col" class="px-6 py-3"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($meetings as $meeting)
                <tr class="hover:bg-gray-100 relative text-center border-b dark:border-gray-700">
                    <th scope="row"
                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <a href="{{route('meeting.show',$meeting->id)}}"
                           class="p-2 mb-2 hover:underline underline-offset-2 w-full transition ease-in-out">
                            {{$meeting->title}}
                        </a>
                        @if($meeting->tasks->where('request_task',!null)->value('request_task'))
                            <span class="absolute right-2 top-1/2 -translate-y-1/2 flex h-3 w-3"><span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span><span
                                    class="relative inline-flex rounded-full h-3 w-3 bg-sky-500"></span></span>
                        @endif
                    </th>
                    <td class="px-6 py-4">
                        {{$meeting->scriptorium}}
                    </td>
                    <td class="px-6 py-4">
                        {{$meeting->date}}
                    </td>
                    <td class="px-6 py-4">
                        {{$meeting->location}}
                    </td>
                    <td class="px-6 py-4">
                        {{$meeting->time}}
                    </td>
                    <td class="px-6 py-4">
                        {{$meeting->meetingUsers->where('meeting_id',$meeting->id)->where('is_present','1')->count()}}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{route('presentUsers',$meeting->id)}}"
                           class="hover:underline font-bold text-black"> {{__('نمایش')}}</a>
                    </td>
                    <td class="px-6 py-4">
                        @if($meeting->is_cancelled == '0')
                            {{__('درحال بررسی...')}}
                        @elseif($meeting->is_cancelled == '1')
                            {{__('جلسه لغو شد')}}
                        @elseif($meeting->is_cancelled == '-1')
                            {{__('جلسه تشکیل میشود')}}
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        {{-- Display the "Add Tasks" button based on the condition --}}
                        @if($meeting->is_cancelled == -1 && (!isset($allUsersHaveTasks[$meeting->id]) || $allUsersHaveTasks[$meeting->id] === false))
                            <a href="{{ route('tasks.create', $meeting->id) }}">
                                <x-primary-button>
                                    {{ __('افزودن اقدامات') }}
                                </x-primary-button>
                            </a>
                        @elseif((isset($allUsersHaveTasks[$meeting->id]) && $allUsersHaveTasks[$meeting->id]))
                            <p>{{__('اقدامات برای تمامی اعضا ارسال شد')}}</p>
                        @endif
                    </td>
                </tr>
            @empty
                <tr class="border-b dark:border-gray-700">
                    <th colspan="8"
                        class="text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                        {{__('رکوردی یافت نشد ...')}}
                    </th>
                </tr>
            @endforelse
            </tbody>
        </table>
        <span class="p-2 mx-2">
           {{ $meetings->withQueryString()->links(data:['scrollTo'=>false]) }}
       </span>
    </div>
</x-app-layout>
