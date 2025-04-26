@php use Illuminate\Support\Str; @endphp
<div>
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
            <div><strong>{{__('واحد/کمیته:')}}</strong>{{$this->meetings->unit_held}}</div>
            <div><strong>{{__('تهیه کننده(دبیرجلسه):')}}</strong>{{$this->meetings->scriptorium}}</div>
            <div><strong>{{__('رئیس جلسه:')}}</strong>{{$this->meetings->boss}}</div>
            <div><strong>{{__('پیوست:')}}</strong> {{__('پیوست')}}</div>
            <div><strong>{{__('تاریخ جلسه:')}}</strong>{{$this->meetings->date}}</div>
            <div><strong>{{__('زمان جلسه:')}}</strong>{{$this->meetings->time}}</div>
            <div><strong>{{__('مکان جلسه:')}}</strong>{{$this->meetings->location}}</div>
            <div><strong>{{__('موضوع جلسه:')}}</strong> {{$this->meetings->title}}</div>
            <div class="col-span-2 mb-2"><strong>{{__('حاضرین:')}}</strong>
                @foreach ($this->employees as $employee)
                    {{ $employee->user->user_info->full_name }} -
                @endforeach
            </div>
        </div>

        @if (auth()->user()->user_info->full_name === $this->meetings->scriptorium)
            @if (!$this->allUsersHaveTasks )
                <form action="{{route('tasks.store', $this->meetings->id)}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
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
                                        @foreach($this->employees as $employee)
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
            @else
                <p class="border-t pt-2 text-center text-lg font-semibold text-gray-700 bg-gray-100 p-4 rounded-lg shadow-md">
                    <span class="text-red-600">تمامی اقدامات برای اعضای جلسه ثبت شده است.</span>
                </p>
            @endif
        @endif

        <div class="overflow-x-auto rounded-xl border border-gray-300 shadow-sm">
            <table class="w-full text-right text-sm border-collapse">
                <thead class="bg-gray-100 text-gray-700">
                @foreach (['ردیف', 'خلاصه مذاکرات و تصمیمات اتخاذ شده', 'مهلت اقدام', 'اقدام کننده', 'شرح اقدام', 'تاریخ انجام اقدام',''] as $th)
                    <th class="px-4 py-3 border-b border-gray-400">{{ __($th) }}</th>
                @endforeach
                </thead>
                <tbody class="divide-y divide-gray-300">
                @foreach ($this->tasks as $index => $task)
                    @foreach ($task->taskUsers as $userIndex => $taskUser)
                        <tr class="bg-white hover:bg-gray-50 transition">
                            @if ($userIndex === 0)
                                <td class="px-4 py-4 align-top"
                                    rowspan="{{ $task->taskUsers->count() }}">{{ $index + 1 }}</td>
                                <td class="px-4 py-4 border-r border-gray-300 align-top"
                                    rowspan="{{ $task->taskUsers->count() }}">{{ $task->body }}</td>
                                <td class="px-4 py-4 border-r border-gray-300 align-top"
                                    rowspan="{{ $task->taskUsers->count() }}">{{ $task->time_out }}</td>
                            @endif
                            <td class="px-4 py-4 border-r border-gray-300">{{ $taskUser->user->user_info->full_name ?? '---' }}</td>
                            <td class="px-4 py-4 border-r border-gray-300 truncate">
                                {{ Str::words($taskUser->body_task ?? '---' , 10 , '...')}}
                            </td>
                            <td class="px-4 py-4 border-r border-gray-300">
                                {{ $taskUser->sent_date ?? '---' }}
                            </td>
                            @if($taskUser->user->user_info->full_name === auth()->user()->user_info->full_name && !$taskUser->is_completed)
                                <td class="px-4 py-4 border-r border-gray-300">
                                    <x-primary-button wire:click="showTaskDetails({{ $taskUser->id }})">
                                        نمایش
                                    </x-primary-button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>

        </div>
        @if (auth()->user()->user_info->full_name === $this->meetings->scriptorium && $this->meetings->is_cancelled != '2')
            <button wire:click="showFinalCheck({{ $this->meetings->id}})"
                    class="flex justify-center gap-3 items-center bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-medium py-3 px-6 rounded-xl shadow-lg transition-all duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z"/>
                </svg>
                {{ __('تایید نهایی') }}
            </button>
        @endif
    </div>


    <x-modal name="view-task-details-modal" maxWidth="4xl">
        @if ($selectedTask)
            <form wire:submit="submitTaskForm({{$selectedTask->id}})">
                <div class="p-6 max-h-[85vh] overflow-y-auto text-sm text-gray-800 dark:text-gray-200 space-y-6">

                    {{-- Title --}}
                    <div class="border-b pb-4">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ __('جزئیات') }}
                        </h2>
                    </div>

                    {{-- Task Information --}}
                    <div class="grid grid-cols-1 gap-4">
                        <x-meeting-info label="{{ __('خلاصه مذاکره') }}" :value="$selectedTask->task->body"/>
                    </div>

                    {{-- Meeting Information --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-meeting-info label="{{ __('اقدام کننده') }}" :value="$taskName"/>
                    </div>

                    {{-- Task Body Textarea --}}
                    <div>
                        <x-input-label for="taskBody" :value="__('شرح اقدام شما')" class="mb-2"/>
                        <textarea wire:model="taskBody" id="taskBody" rows="4"
                                  class="w-full h-auto min-h-[80px] p-2 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400">
                    </textarea>
                        <x-input-error :messages="$errors->get('taskBody')"/>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-4 flex justify-between gap-2">
                        <x-primary-button type="submit">
                            {{ __('ثبت') }}
                        </x-primary-button>
                        <x-secondary-button x-on:click="$dispatch('close')">
                            {{ __('لغو') }}
                        </x-secondary-button>
                    </div>

                </div>
            </form>
        @endif
    </x-modal>

    @if($this->meetings->is_cancelled != '2')
        <x-modal name="final-check">
            <form wire:submit="finishMeeting({{$this->meetings->id}})">
                <div class="flex flex-row px-6 py-4 bg-gray-100 text-start">
                    <h2 class="text-2xl font-bold text-gray-800">{{ __('آیا تایید نهایی این جلسه مطمئن هستید؟') }}</h2>
                </div>
                <div class="px-6 py-4">
                    <p class="text-md text-red-500">{{ __('این اقدام قابل بازگشت نیست!') }}</p>
                </div>
                <div class="flex flex-row justify-between px-6 py-4 bg-gray-100">
                    <x-primary-button type="submit">
                        {{ __('تایید') }}
                    </x-primary-button>
                    <x-cancel-button x-on:click="$dispatch('close')">
                        {{ __('انصراف') }}
                    </x-cancel-button>
                </div>
            </form>
        </x-modal>
    @endif


</div>
