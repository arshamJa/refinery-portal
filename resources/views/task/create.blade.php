@php use App\Models\UserInfo;use Carbon\Carbon; @endphp
<x-app-layout>
    <x-sessionMessage name="status"/>
    <nav class="flex justify-between mb-4 mt-20">
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
                <a href="{{route('dashboard.meeting')}}"
                   class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                    <span>{{__('جدول جلسات')}}</span>
                </a>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                 stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            <li>
                    <span
                        class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                       {{__('صفحه صورتجلسه')}}
                    </span>
            </li>
        </ol>
    </nav>

    <div class="p-6 max-w-6xl bg-white rounded-2xl shadow-md space-y-6">
        <form action="{{route('tasks.store', $meetings->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                <div><strong>{{__('واحد/کمیته:')}}</strong>{{$meetings->unit_held}}</div>
                <div><strong>{{__('تهیه کننده(دبیرجلسه):')}}</strong>{{$meetings->scriptorium}}</div>
                <div><strong>{{__('پیوست:')}}</strong> {{__('پیوست')}}</div>
                <div><strong>{{__('تاریخ جلسه:')}}</strong>{{$meetings->date}}</div>
                <div><strong>{{__('زمان جلسه:')}}</strong>{{$meetings->time}}</div>
                <div><strong>{{__('مکان جلسه:')}}</strong>{{$meetings->location}}</div>
                <div class="col-span-2"><strong>{{__('موضوع جلسه:')}}</strong> {{$meetings->title}}</div>
                <div class="col-span-2 mb-2"><strong>{{__('حاضرین:')}}</strong>
                    @foreach ($employees as $employee)
                        {{ $employee->user->user_info->full_name }} -
                    @endforeach
                </div>
            </div>
            <div class="border-t pt-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="holders" class="mb-2"
                                       :value="__('اقدام کننده')"/>
                        <div class="custom-select">
                            <div class="select-box">
                                <input type="text" class="tags_input" multiple name="holders" hidden>
                                <div class="selected-options"></div>
                                <div class="arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5"
                                         stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="options">
                                <div class="option-search-tags">
                                    <input type="text" class="search-tags" placeholder="جست و جو ...">
                                    <button type="button" class="clear">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5"
                                             stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M6 18 18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="option all-tags" data-value="All">{{__('انتخاب همه')}}</div>
                                @foreach($employees as $employee)
                                    <div class="option" data-value="{{$employee->user_id}}">
                                        {{ $employee->user->user_info->full_name }}
                                    </div>
                                @endforeach
                                <div class="no-result-message" style="display:none;">No result match</div>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('holders')" class="my-2"/>
                    </div>

                    <div>
                        <x-input-label for="time_out" :value="__('مهلت اقدام')" class="mb-2"/>
                        <div class="flex gap-2">
                            <div class="w-full">
                                <div class="flex items-center gap-1">
                                    <select name="year" id="year" dir="ltr"
                                            class="w-full text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                                        <option value="">{{__(':سال')}}</option>
                                        @for($i = 1404; $i <= 1430; $i++)
                                            <option value="{{$i}}" @if (old('year') == $i) selected @endif>
                                                {{$i}}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <x-input-error :messages="$errors->get('year')" class="my-2"/>
                            </div>
                            <div class="w-full">
                                <div class="flex items-center gap-1">
                                    @php
                                        $persian_months = ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور","مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"];
                                    @endphp
                                    <select name="month" id="month" dir="ltr"
                                            class="w-full text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                                        <option value="">{{__(':ماه')}}</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" @if (old('month') == $i) selected @endif>
                                                {{ $persian_months[$i - 1] }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <x-input-error :messages="$errors->get('month')" class="my-2"/>
                            </div>
                            <div class="w-full">
                                <div class="flex items-center gap-1">
                                    <select name="day" id="day" dir="ltr"
                                            class="w-full text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                                        <option value="">{{__(':روز')}}</option>
                                        @for($i = 1; $i <= 31; $i++)
                                            <option value="{{$i}}" @if (old('day') == $i) selected @endif>
                                                {{$i}}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <x-input-error :messages="$errors->get('day')" class="my-2"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <x-input-label for="body" :value="__('خلاصه مذاکرات و تصمیمات اتخاذ شده')" class="mb-2"/>
                    <textarea type="text" name="body" rows="4"
                              class="w-full h-auto min-h-[80px] p-2 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                            {{old('body')}}
                        </textarea>
                    <x-input-error :messages="$errors->get('body')" class="mt-2"/>
                </div>

                <div class="flex space-x-2 rtl:space-x-reverse">
                    <x-primary-button type="submit">
                        {{ __('ارسال') }}
                    </x-primary-button>
                    <a href="{{route('dashboard.meeting')}}">
                        <x-secondary-button>
                            {{__('لغو')}}
                        </x-secondary-button>
                    </a>
                </div>
            </div>

        </form>

        <table class="w-full text-right text-sm">
            <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th class="px-4 py-3">ردیف</th>
                <th class="px-4 py-3">خلاصه مذاکرات و تصمیمات اتخاذ شده</th>
                <th class="px-4 py-3">مهلت اقدام</th>
                <th class="px-4 py-3">اقدام کننده</th>
                <th class="px-4 py-3">شرح اقدام</th>
                <th class="px-4 py-3">تاریخ انجام اقدام</th>
                <th class="px-4 py-3"></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($tasks as $index => $task)
                @foreach ($task->taskUsers as $userIndex => $taskUser)
                    <tr class="border-t">
                        @if ($userIndex === 0)
                            <td class="px-4 py-4" rowspan="{{ $task->taskUsers->count() }}">{{ $index + 1 }}</td>
                            <td class="px-4 py-4" rowspan="{{ $task->taskUsers->count() }}">{{ $task->body }}</td>
                            <td class="px-4 py-4" rowspan="{{ $task->taskUsers->count() }}">{{ $task->time_out }}</td>
                        @endif
                        <td class="px-4 py-4">{{ $taskUser->user->user_info->full_name ?? '---' }}</td>
                        <td class="px-4 py-4">{{ $taskUser->request_task ?? '---' }}</td>
                        <td class="px-4 py-4">
                            {{ $taskUser->sent_date ? \Carbon\Carbon::parse($taskUser->sent_date)->format('Y-m-d') : '---' }}
                        </td>
                        <td class="px-4 py-4">
                            <x-primary-button>
                                نمایش
                            </x-primary-button>
                        </td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>




    </div>

{{--        <table class="w-full text-right text-sm">--}}
{{--            <thead>--}}
{{--            <tr class="bg-gray-100 text-gray-700">--}}
{{--                <th class="px-4 py-3">ردیف</th>--}}
{{--                <th class="px-4 py-3">خلاصه مذاکرات و تصمیمات اتخاذ شده</th>--}}
{{--                <th class="px-4 py-3">مهلت اقدام</th>--}}
{{--                <th class="px-4 py-3">اقدام کننده</th>--}}
{{--                <th class="px-4 py-3">شرح اقدام</th>--}}
{{--                <th class="px-4 py-3">تاریخ انجام اقدام</th>--}}
{{--                <th class="px-4 py-3"></th>--}}
{{--            </tr>--}}
{{--            </thead>--}}
{{--            <tbody>--}}
{{--            @foreach ($tasks as $index => $task)--}}
{{--                @foreach ($task['users'] as $userIndex => $user)--}}
{{--                    <tr class="border-t">--}}
{{--                        @if ($userIndex === 0)--}}
{{--                            <td class="px-4 py-4" rowspan="{{ count($task['users']) }}">{{ $index + 1 }}</td>--}}
{{--                            <td class="px-4 py-4" rowspan="{{ count($task['users']) }}">{{ $task['body'] }}</td>--}}
{{--                            <td class="px-4 py-4" rowspan="{{ count($task['users']) }}">{{ $task['time_out'] }}</td>--}}
{{--                        @endif--}}
{{--                        <td class="px-4 py-4">{{ $user['name'] }}</td>--}}
{{--                        <td class="px-4 py-4">{{ $user['action_description'] }}</td>--}}
{{--                        <td class="px-4 py-4">{{ $user['sent_date'] }}</td>--}}
{{--                        <td class="px-4 py-4">--}}
{{--                            <x-primary-button>--}}
{{--                                نمایش--}}
{{--                            </x-primary-button>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--            @endforeach--}}
{{--            </tbody>--}}
{{--        </table>--}}





    {{--        <div class="mt-6 border-t pt-4">--}}
    {{--            <table class="w-full text-right text-sm">--}}
    {{--                <thead>--}}
    {{--                <tr class="bg-gray-100 text-gray-700">--}}
    {{--                    @foreach (['ردیف', 'خلاصه مذاکرات و تصمیمات اتخاذ شده', 'مهلت اقدام', 'اقدام کننده','شرح اقدام','تاریخ اقدام',''] as $th)--}}
    {{--                        <th class="px-4 py-3">{{ __($th) }}</th>--}}
    {{--                    @endforeach--}}
    {{--                </tr>--}}
    {{--                </thead>--}}
    {{--                <tbody>--}}
    {{--                <!-- Sample row -->--}}
    {{--                @foreach($tasks as $task)--}}
    {{--                    <tr class="border-t">--}}
    {{--                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$loop->index+1}}</td>--}}
    {{--                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->body}}</td>--}}
    {{--                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->time_out}}</td>--}}
    {{--                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->user->user_info->full_name}}</td>--}}
    {{--                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">--}}
    {{--                            this is the text that each one has done.--}}
    {{--                        </td>--}}
    {{--                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">--}}
    {{--                            the sent date--}}
    {{--                        </td>--}}
    {{--                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">--}}
    {{--                            view button--}}
    {{--                        </td>--}}
    {{--                        --}}{{--                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">--}}
    {{--                        --}}{{--                               <span--}}
    {{--                        --}}{{--                                   class="{{$task->is_completed ? 'bg-green-600' : 'bg-red-600'}} text-gray-100 rounded-md p-2">--}}
    {{--                        --}}{{--                                   {{$task->is_completed ? 'انجام شد' : 'انجام نشده'}}--}}
    {{--                        --}}{{--                               </span>--}}
    {{--                        --}}{{--                        </td>--}}
    {{--                    </tr>--}}
    {{--                @endforeach--}}
    {{--                </tbody>--}}
    {{--            </table>--}}
    {{--        </div>--}}
    {{--    </div>--}}

</x-app-layout>
