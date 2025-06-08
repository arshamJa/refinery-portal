@php use App\Enums\MeetingStatus;use App\Enums\TaskStatus;use App\Enums\UserRole;use Illuminate\Support\Str; @endphp
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
                    <span>{{__('جلسات')}}</span>
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

    <div class="p-6 max-w-6xl bg-white rounded-2xl shadow-md space-y-6 mb-6">
        <div class="w-full text-center">
            @switch($this->meetings->status)
                @case(MeetingStatus::IS_IN_PROGRESS)
                    <span
                        class="block w-full bg-blue-500 text-md text-white font-medium px-3 py-4 rounded-lg m-1">
                                                    {{ __('جلسه درحال برگزاری است') }}
                                                </span>
                    @break
                @case(MeetingStatus::IS_FINISHED)
                    <span
                        class="block w-full bg-green-100 text-green-700 text-md font-medium px-3 py-4 rounded-lg shadow-sm m-1">
                                            {{ __('جلسه خاتمه یافت') }}
                                        </span>
                    @break
            @endswitch
        </div>

        <div id="meeting-info"
             class="grid grid-cols-2 gap-x-6 gap-y-2 bg-gray-50 border border-gray-300 rounded-md p-4 text-gray-700 text-sm print:text-[16px] print:leading-[1.4] print:grid print:grid-cols-2 print:gap-2 print:border print:border-gray-400">
            <div><strong>{{ __('واحد/کمیته: ') }}</strong><span>{{ $this->meetings->unit_held }}</span></div>
            <div><strong>{{ __('تهیه کننده(دبیرجلسه): ') }}</strong><span>{{ $this->meetings->scriptorium }}</span>
            </div>
            <div><strong>{{ __('رئیس جلسه: ') }}</strong><span>{{ $this->meetings->boss }}</span></div>
            <div>
                <strong>{{ __('پیوست: ') }}</strong><span>{{ $this->meetings->tasks->flatMap->taskUsers->flatMap->taskUserFiles->count() > 0 ? 'دارد' : 'ندارد' }}</span>
            </div>
            <div><strong>{{ __('تاریخ جلسه: ') }}</strong><span>{{ $this->meetings->date }}</span></div>
            <div><strong>{{ __('زمان جلسه: ') }}</strong><span>{{ $this->meetings->time }}@if($this->meetings->end_time)
                        - {{ $this->meetings->end_time }}
                    @endif</span></div>
            <div><strong>{{ __('مکان جلسه: ') }}</strong><span>{{ $this->meetings->location }}</span></div>
            <div><strong>{{ __('موضوع جلسه: ') }}</strong><span>{{ $this->meetings->title }}</span></div>
            <div class="col-span-2 print:col-span-2"><strong>{{ __('حاضرین: ') }}</strong>
                <span>
                    @foreach ($this->employees as $employee)
                        {{ $employee->user->user_info->full_name }}{{ !$loop->last ? ' -' : '' }}
                    @endforeach
                </span>
            </div>
        </div>
        @if(auth()->user()->user_info->full_name === $this->meetings->scriptorium && auth()->user()->user_info->position === $this->meetings->scriptorium_position)
            <div class="flex justify-end items-center gap-4">
                @if( $this->meetings->status == MeetingStatus::IS_IN_PROGRESS)
                    @if (!$this->allTasksLocked)
                        <x-accept-button wire:click="showFinalCheck({{ $this->meetings->id}})"
                                         class="flex gap-2 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z"/>
                            </svg>
                            {{ __('خاتمه جلسه') }}
                        </x-accept-button>
                    @endif
                @endif
                <!-- Print Button -->
                <button onclick="printTable()"
                        class=" px-4 py-2 bg-blue-500 text-white border border-transparent rounded-md font-semibold text-xs uppercase shadow-sm hover:bg-blue-600 hover:outline-none hover:ring-2 hover:ring-blue-500 hover:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-300">
                    {{__('چاپ صوتجلسه')}}
                </button>
            </div>
            @if (!$this->allTasksLocked)
                <form action="{{route('tasks.store', $this->meetings->id)}}" method="post"
                      class="border-t border-b py-3"
                      enctype="multipart/form-data">
                    @csrf
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 mt-4 pb-2">
                        {{__('درج بند جدید')}}
                    </h2>
                    <div class=" space-y-4">
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
                            <x-input-label for="body" :value="__('تصمیم اتخاذ شده')" class="mb-2"/>
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
                            <a href="{{ route('view.task.page', ['meeting' => $this->meetings->id]) }}">
                                <x-cancel-button>
                                    {{ __('انصراف') }}
                                </x-cancel-button>
                            </a>
                        </div>
                    </div>
                </form>
            @endif
            <div class="flex justify-end items-center gap-4">
                @if ($this->allTasksLocked)
                    <x-secondary-button disabled>
                        {{ __('این صورتجلسه قبلاً قفل شده است') }}
                    </x-secondary-button>
                @else
                    <x-secondary-button wire:click="openAddParticipantModal">
                        {{ __('افزودن اقدام کننده') }}
                    </x-secondary-button>
                    <x-danger-button wire:click="openDeleteTaskModal">
                        {{ __('حذف بند مذاکره') }}
                    </x-danger-button>
                    <x-modal name="add-participant" maxWidth="4xl" :closable="false">
                        <form wire:submit.prevent="submitParticipantForm">
                            <!-- Header -->
                            <div
                                class="flex justify-between items-center px-6 py-4 bg-gray-100 border-b border-gray-200">
                                <h2 class="text-2xl font-bold text-gray-800">{{ __('درج مشارکت‌کننده جدید') }}</h2>
                                <button type="button" x-on:click="$dispatch('close')"
                                        class="text-gray-400 hover:text-red-500 transition duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <!-- Body -->
                            <div
                                class="px-6 py-4 space-y-6 text-sm text-gray-800 dark:text-gray-200 max-h-[70vh] overflow-y-auto">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Select Task -->
                                    <div>
                                        <x-input-label :value="__('انتخاب بند مذاکره')" class="mb-2"/>
                                        <x-select-input wire:model.defer="task_id">
                                            <option value="">{{ __('انتخاب کنید') }}</option>
                                            @foreach($this->tasks as $task)
                                                <option value="{{ $task->id }}">
                                                    {{$loop->index+1}} - {{ Str::words($task->body,10, '...') }}
                                                </option>
                                            @endforeach
                                        </x-select-input>
                                        <x-input-error :messages="$errors->get('task_id')" class="mt-2"/>
                                    </div>

                                    <!-- Select Employee -->
                                    <div>
                                        <x-input-label :value="__('اقدام‌کننده')" class="mb-2"/>
                                        <x-select-input wire:model.defer="employee_id">
                                            <option value="">{{ __('انتخاب کنید') }}</option>
                                            @foreach($this->employees as $employee)
                                                <option
                                                    value="{{ $employee->user_id }}">{{ $employee->user->user_info->full_name }}</option>
                                            @endforeach
                                        </x-select-input>
                                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2"/>
                                    </div>
                                </div>

                                <!-- Due Date -->
                                <div>
                                    <x-input-label for="time_out" :value="__('مهلت اقدام')" class="mb-2"/>
                                    <div class="flex gap-2">
                                        <div class="w-full">
                                            <x-select-input wire:model.defer="year">
                                                <option value="">{{ __('سال') }}</option>
                                                @for($i = 1404; $i <= 1430; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </x-select-input>
                                            <x-input-error :messages="$errors->get('year')" class="mt-2"/>
                                        </div>
                                        <div class="w-full">
                                            <x-select-input wire:model.defer="month">
                                                <option value="">{{ __('ماه') }}</option>
                                                @foreach(["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور", "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"] as $index => $monthName)
                                                    <option value="{{ $index + 1 }}">{{ $monthName }}</option>
                                                @endforeach
                                            </x-select-input>
                                            <x-input-error :messages="$errors->get('month')" class="mt-2"/>
                                        </div>
                                        <div class="w-full">
                                            <x-select-input wire:model.defer="day">
                                                <option value="">{{ __('روز') }}</option>
                                                @for($i = 1; $i <= 31; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </x-select-input>
                                            <x-input-error :messages="$errors->get('day')" class="mt-2"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Footer -->
                            <div class="flex justify-between px-6 py-4 bg-gray-100 border-t border-gray-200">
                                <x-primary-button type="submit"
                                                  wire:target="submitParticipantForm"
                                                  wire:loading.attr="disabled"
                                                  wire:loading.class="opacity-50">
                                    <span wire:loading.remove
                                          wire:target="submitParticipantForm">{{ __('ذخیره') }}</span>
                                    <span wire:loading
                                          wire:target="submitParticipantForm">{{ __('در حال ثبت...') }}</span>
                                </x-primary-button>
                                <x-cancel-button x-on:click="$dispatch('close')">
                                    {{ __('انصراف') }}
                                </x-cancel-button>
                            </div>
                        </form>
                    </x-modal>
                    <x-modal name="delete-task" maxWidth="4xl" :closable="false">
                        <form wire:submit.prevent="deleteTask">
                            <!-- Header -->
                            <div
                                class="flex justify-between items-center px-6 py-4 bg-gray-100 border-b border-gray-200">
                                <h2 class="text-2xl font-bold text-gray-800">{{ __('حذف بند مذاکره') }}</h2>
                                <button type="button" x-on:click="$dispatch('close')"
                                        class="text-gray-400 hover:text-red-500 transition duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Body -->
                            <div
                                class="px-6 py-4 space-y-6 text-sm text-gray-800 dark:text-gray-200 max-h-[70vh] overflow-y-auto">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Warning Message -->
                                    <div class="md:col-span-2">
                                        <div
                                            class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded">
                                            <p class="font-semibold">{{ __('هشدار:') }}</p>
                                            <p>{{ __('با حذف این بند مذاکره، تمام اقدام کنندگان تخصیص داده‌شده به آن نیز حذف خواهند شد.') }}</p>
                                        </div>
                                    </div>
                                    <!-- Select Task -->
                                    <div>
                                        <x-input-label :value="__('انتخاب بند مذاکره')" class="mb-2"/>
                                        <x-select-input wire:model.defer="task_id">
                                            <option value="">{{ __('انتخاب کنید') }}</option>
                                            @foreach($this->tasks as $task)
                                                <option value="{{ $task->id }}">
                                                    {{$loop->index+1}} -{{ Str::words($task->body,10, '...') }}
                                                </option>
                                            @endforeach
                                        </x-select-input>
                                        <x-input-error :messages="$errors->get('task_id')" class="mt-2"/>
                                    </div>
                                </div>
                            </div>
                            <!-- Footer -->
                            <div class="flex justify-between px-6 py-4 bg-gray-100 border-t border-gray-200">
                                <x-primary-button type="submit"
                                                  wire:target="submitParticipantForm"
                                                  wire:loading.attr="disabled"
                                                  wire:loading.class="opacity-50">
                                    <span wire:loading.remove wire:target="submitParticipantForm">{{ __('حذف') }}</span>
                                    <span wire:loading
                                          wire:target="submitParticipantForm">{{ __('در حال حذف...') }}</span>
                                </x-primary-button>
                                <x-cancel-button x-on:click="$dispatch('close')">
                                    {{ __('انصراف') }}
                                </x-cancel-button>
                            </div>
                        </form>
                    </x-modal>
                    @can('lock-task', $this->meetings)
                        <x-edit-button wire:click="openLockTaskModal">
                            {{ __('بستن صورتجلسه') }}
                        </x-edit-button>
                        <x-modal name="lock-task" maxWidth="4xl" :closable="false">
                            <form method="POST" action="{{ route('meeting.lockTasks',$this->meetings->id) }}">
                                @csrf
                                <!-- Header -->
                                <div
                                    class="flex justify-between items-center px-6 py-4 bg-gray-100 border-b border-gray-200">
                                    <h2 class="text-2xl font-bold text-gray-800">{{ __('بستن صورتجلسه') }}</h2>
                                    <a href="{{route('view.task.page',$this->meetings->id)}}"
                                       class="text-gray-400 hover:text-red-500 transition duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M6 18 18 6M6 6l12 12"/>
                                        </svg>
                                    </a>
                                </div>
                                <!-- Body -->
                                <div
                                    class="px-6 py-4 space-y-6 text-sm text-gray-800 dark:text-gray-200 max-h-[70vh] overflow-y-auto">
                                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                                            <p class="font-semibold">{{ __('هشدار:') }}</p>
                                            <p>{{ __('با قفل کردن این بند مذاکره، امکان ویرایش یا تغییر توسط دبیر غیرفعال می‌شود.') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Footer -->
                                <div class="flex justify-between px-6 py-4 bg-gray-100 border-t border-gray-200">
                                    <x-primary-button type="submit">
                                        {{ __('قفل کردن') }}
                                    </x-primary-button>
                                    <x-cancel-button x-on:click="$dispatch('close')">
                                        {{ __('انصراف') }}
                                    </x-cancel-button>
                                </div>
                            </form>
                        </x-modal>
                    @endcan
                @endif
            </div>
        @endif

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-12" id="task-table">
            <x-table.table wire:poll.visible.60s>
                <x-slot name="head">
                    <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                        @foreach (['#', 'خلاصه مذاکرات و تصمیمات اتخاذ شده', 'مهلت اقدام', 'اقدام کننده', 'شرح اقدام', 'تاریخ انجام اقدام','فایل های آپلود شده','عملیات'] as $th)
                            <x-table.heading
                                class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}  {{ in_array($loop->index, [4, 7,8]) ? 'no-print' : '' }}">
                                {{ __($th) }}
                            </x-table.heading>
                        @endforeach
                    </x-table.row>
                </x-slot>
                <x-slot name="body">
                    @foreach ($this->tasks as $task)
                        @php
                            $taskUsers = $task->taskUsers;
                            $rowspan = $taskUsers->count();
                        @endphp

                        @foreach ($taskUsers as $userIndex => $taskUser)
                            <tr class="bg-white hover:bg-gray-50 transition"
                                wire:key="task-{{ $task->id }}-taskUser-{{ $taskUser->id }}">

                                @if ($userIndex === 0)
                                    <td class="px-4 py-4" rowspan="{{ $rowspan }}">
                                        {{ $loop->parent->iteration }}
                                    </td>
                                    {{-- Task Body --}}
                                    <td class="px-4 py-2 border-r border-gray-300 relative" rowspan="{{ $rowspan }}">
                                        <span class="block text-gray-700">{{ $task->body }}</span>
                                        @if(auth()->user()->user_info->full_name === $task->meeting->scriptorium && auth()->user()->user_info->position === $task->meeting->scriptorium_position)
                                            @if (!$this->allTasksLocked)
                                                <x-secondary-button class="absolute bottom-2 left-2"
                                                                    wire:click="openEditTaskModal({{ $task->id }})">
                                                    {{ __('ویرایش') }}
                                                </x-secondary-button>
                                            @endif
                                        @endif
                                    </td>
                                @endif

                                {{-- View mode: timeout --}}
                                <td class="px-2 py-4 border-r border-gray-300 w-48">
                                    {{ $taskUser->time_out ?? '---' }}
                                </td>

                                {{-- View mode: user name --}}
                                <td class="px-4 py-4 border-r border-gray-300 w-48" data-username="true">
                                    {{ $taskUser->user->user_info->full_name ?? '---' }}
                                </td>
                                {{--                                @endif--}}
                                @php
                                    list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
                                    $todayDate = sprintf("%04d/%02d/%02d", $ja_year, $ja_month, $ja_day);
                                    $isAfterTimeOut = $todayDate >= $taskUser->time_out;
                                @endphp
                                {{--                                    Removed the column for شرح اقدام in print version--}}
                                <td class="px-4 py-4 border-r border-gray-300 no-print screen-only">
                                    @if(!$isAfterTimeOut)
                                        @if($taskUser->body_task)
                                            <!-- Content only for printing (always visible in print) -->
                                            <div class="task-card print-only" style="display: none;">
                                                {{ $taskUser->body_task }}
                                            </div>
                                            <x-secondary-button
                                                wire:click="openParticipantTaskBodyModal({{$taskUser->id}})">
                                                {{__('نمایش')}}
                                            </x-secondary-button>
                                        @else
                                            <span>---</span>
                                        @endif

                                    @else
                                        <div class="mt-2 text-sm text-gray-400">
                                            {{ __('مهلت اقدام به پایان رسیده است') }}
                                        </div>
                                        <div class="task-card print-only" style="display: none;">
                                            {{ __('پاسخی از سمت اقدام کننده در مهلت مقرر صورت نگرفت') }}
                                        </div>
                                    @endif
                                </td>

                                {{-- تاریخ انجام اقدام --}}
                                <td class="px-4 py-4 border-r border-gray-300">
                                    {{ $taskUser->sent_date ?? '---' }}
                                </td>
                                {{-- فایل‌ها --}}
                                <td class="px-4 py-4 border-r border-gray-300">
                                    @if ($taskUser->taskUserFiles->isNotEmpty())
                                        <div class="flex flex-col gap-2 screen-only">
                                            @foreach ($taskUser->taskUserFiles as $file)
                                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                                   class="text-blue-600 hover:underline text-xs truncate">
                                                    📄 {{ $file->original_name }}
                                                </a>
                                            @endforeach
                                        </div>
                                        <div class="print-only hidden">{{ __('دارای فایل') }}</div>
                                    @else
                                        <span class="text-gray-400 print-only text-xs">{{__('بدون فایل')}}</span>
                                    @endif
                                </td>

                                {{-- عملیات --}}
                                <td class="px-4 py-4 border-r  border-gray-300 text-center screen-only no-print">
                                    @if($taskUser->task_status === TaskStatus::SENT_TO_SCRIPTORIUM)
                                        <span>
                                                {{__('اقدام تکمیل شد')}}
                                        </span>
                                    @else

                                        @can('participantTask',$taskUser)
                                            @if($taskUser->task_status === TaskStatus::PENDING)
                                                <x-accept-button class="inline-flex justify-center w-full mb-2"
                                                                 wire:click="acceptTask({{ $taskUser->id }})">
                                                    {{ __('تایید') }}
                                                </x-accept-button>
                                                <x-cancel-button class="inline-flex justify-center w-full"
                                                                 wire:click="openDenyModal({{ $taskUser->id }})">
                                                    {{ __('رد') }}
                                                </x-cancel-button>
                                            @elseif($taskUser->task_status === TaskStatus::ACCEPTED)
                                                <x-secondary-button class="whitespace-nowrap" wire:click="showTaskForm({{ $taskUser->id }})">
                                                    {{ __('انجام اقدام') }}
                                                </x-secondary-button>
                                            @elseif($taskUser->task_status === TaskStatus::IS_COMPLETED)
                                                <x-edit-button class="w-full mb-2"
                                                               wire:click="openUpdateModal({{$taskUser->id}})">
                                                    {{ __('ویرایش') }}
                                                </x-edit-button>
                                                <x-secondary-button class="w-full "
                                                                    wire:click="sendToScriptorium({{$task->id}})">
                                                    {{ __('ارسال') }}
                                                </x-secondary-button>
                                            @endif
                                        @endcan
                                        @if (!$this->allTasksLocked)
                                            @can('scriptoriumTask',$taskUser)
                                                @if(!$isAfterTimeOut)
                                                    <x-edit-button class="w-full mb-2"
                                                                   wire:click="opedEditModal({{ $taskUser->id }})">
                                                        {{ __('ویرایش') }}
                                                    </x-edit-button>
                                                    <x-cancel-button class="w-full "
                                                                     wire:click="delete({{$taskUser->id}})"
                                                                     wire:confirm="آیا مطمئن هستید که این اقدام کننده پاک شود؟">
                                                        {{ __('حذف') }}
                                                    </x-cancel-button>
                                                @endif
                                            @endcan
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </x-slot>
            </x-table.table>
            <script src="{{ asset('js/printTable.js') }}"></script>
        </div>


        {{--  Table to show the participants task rejection , this is gate --}}
        @if($this->taskUsers->whereNotNull('request_task')->count() > 0)
            {{--            @can('view-denied-tasks')--}}
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-12">
                <x-table.table>
                    <x-slot name="head">
                        <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                            @foreach (['نام', 'بند مذاکره','دلیل رد', ] as $th)
                                <x-table.heading
                                    class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}">
                                    {{ __($th) }}
                                </x-table.heading>
                            @endforeach
                        </x-table.row>
                    </x-slot>
                    <x-slot name="body">
                        @foreach($this->taskUsers->where('task_status', TaskStatus::DENIED->value) as $taskRequest)
                            <x-table.row wire:key="taskRequest-{{ $taskRequest->id }}"
                                         class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-50">
                                <x-table.cell>{{ $taskRequest->user->user_info->full_name }}</x-table.cell>
                                <x-table.cell>{{ $taskRequest->task->body }}</x-table.cell>
                                <x-table.cell>{{ $taskRequest->request_task }}</x-table.cell>
                            </x-table.row>
                        @endforeach
                    </x-slot>
                </x-table.table>
            </div>
            {{--            @endcan--}}
        @endif


        {{--  this is for showing the signatures --}}
        @if($this->presentUsers->isNotEmpty())
            <div id="signature-section">
                <div style="margin-top: 40px;">
                    <h3 style="font-size: 1.2rem; font-weight: bold; margin-bottom: 16px; border-bottom: 1px solid #ccc; padding-bottom: 8px;">
                        امضا حاضرین
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
                        @foreach($this->presentUsers as $user)
                            <div
                                style="display: flex; align-items: center; gap: 12px; background: #fff; padding: 12px; border-radius: 12px; box-shadow: 0 0 5px rgba(0,0,0,0.1);">
                                <img src="{{ asset('storage/' . $user->user_info->signature) }}"
                                     alt="{{ $user->user_info->full_name }}"
                                     style="width: 48px; height: 48px; object-fit: contain;"/>
                                <div>
                                    <div style="font-weight: bold;">{{ $user->user_info->full_name }}</div>
                                    <div style="font-size: 0.875rem; color: green;">امضا شده</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <x-modal name="participant-body-task" maxWidth="4xl" :closable="false">
        @if ($selectedTaskUser)
            <div class="flex justify-between items-center px-6 py-4 bg-gray-100 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800">{{ __('شرح اقدام') }}</h2>
                <button type="button" x-on:click="$dispatch('close')"
                        class="text-gray-400 hover:text-red-500 transition duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="px-6 py-6 space-y-6 text-sm text-gray-800 dark:text-gray-200 max-h-[70vh] overflow-y-auto">
                <div class="grid grid-cols-2 gap-4">
                    <x-meeting-info label="{{ __('اقدام کننده') }}"
                                    :value="$selectedTaskUser->user->user_info->full_name"/>
                    <x-meeting-info label="{{ __('سمت') }}" :value="$selectedTaskUser->user->user_info->position"/>
                </div>

                <div>
                    <x-input-label :value="__('شرح اقدام')" class="mb-2"/>
                    <p class="p-4 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md min-h-[100px]">
                        {{ $selectedTaskUser->body_task ?: __('هیچ اقدامی ثبت نشده است.') }}
                    </p>
                </div>
            </div>
            <div class="flex justify-end px-6 py-4 bg-gray-100 border-t border-gray-200">
                <x-cancel-button x-on:click="$dispatch('close')">
                    {{ __('بستن') }}
                </x-cancel-button>
            </div>
        @endif
    </x-modal>

    {{--  Modal for participant to type their task--}}
    <x-modal name="view-task-details-modal" maxWidth="4xl" :closable="false">
        @if ($selectedTaskUser)
            @can('participantTask', $selectedTaskUser)
                <form wire:submit="submitTaskForm" enctype="multipart/form-data">
                    <!-- Header -->
                    <div class="flex justify-between items-center px-6 py-4 bg-gray-100 border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-800">{{ __('جزئیات') }}</h2>
                        <button type="button" x-on:click="$dispatch('close')"
                                class="text-gray-400 hover:text-red-500 transition duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div
                        class="px-6 py-4 space-y-6 text-sm text-gray-800 dark:text-gray-200 max-h-[70vh] overflow-y-auto">
                        <div class="grid grid-cols-2 gap-4">
                            <x-meeting-info label="{{ __('اقدام کننده') }}"
                                            :value="$selectedTaskUser->user->user_info->full_name "/>
                            <x-meeting-info label="{{ __('سمت') }}"
                                            :value="$selectedTaskUser->user->user_info->position"/>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <x-meeting-info label="{{ __('خلاصه مذاکره') }}"
                                            :value="$selectedTaskUser->task->body"/>
                        </div>

                        <div>
                            <x-input-label for="participantTaskBody" :value="__('شرح اقدام شما')" class="mb-2"/>
                            <textarea wire:model.defer="participantTaskBody" id="participantTaskBody" rows="4"
                                      class="w-full min-h-[100px] p-3 text-sm bg-white dark:bg-gray-800 border rounded-md border-gray-300 dark:border-gray-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 focus:outline-none"></textarea>
                            <x-input-error :messages="$errors->get('participantTaskBody')"/>
                        </div>

                        <div x-data="{ progress: 0 }"
                             x-on:livewire-upload-start="progress = 0"
                             x-on:livewire-upload-progress="progress = $event.detail.progress"
                             x-on:livewire-upload-finish="progress = 100"
                             x-on:livewire-upload-error="progress = 0"
                             class="space-y-2">

                            <x-input-label for="files" :value="__('آپلود فایل مرتبط')"/>
                            <input type="file" wire:model="files" multiple
                                   class="w-full text-sm text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none"/>

                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                                <div class="bg-green-500 h-2 transition-all duration-300"
                                     :style="`width: ${progress}%`"></div>
                            </div>
                            <div x-text="progress + '%'"
                                 class="text-xs text-right text-gray-600 dark:text-gray-400"></div>

                            <div wire:loading wire:target="files" class="text-blue-500 text-sm">
                                {{ __('در حال آپلود فایل...') }}
                            </div>
                            <div wire:loading.remove wire:target="files" class="text-green-600 text-sm" x-cloak>
                                @if (!empty($files))
                                    {{ __('فایل‌(ها) با موفقیت آپلود شدند.') }}
                                @endif
                            </div>

                            <x-input-error :messages="$errors->get('files')"/>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-between px-6 py-4 bg-gray-100 border-t border-gray-200">
                        <x-primary-button type="submit"
                                          wire:target="submitTaskForm"
                                          wire:loading.attr="disabled"
                                          wire:loading.class="opacity-50">
                            <span wire:loading.remove wire:target="submitTaskForm">{{ __('ذخیره') }}</span>
                            <span wire:loading wire:target="submitTaskForm"
                                  class="ml-2">{{ __('در حال ثبت...') }}</span>
                        </x-primary-button>

                        <x-cancel-button x-on:click="$dispatch('close')">
                            {{ __('انصراف') }}
                        </x-cancel-button>
                    </div>
                </form>
            @endcan
        @endif
    </x-modal>

    {{--  Modal for participant to update their task--}}
    <x-modal name="edit-task-details-modal" maxWidth="4xl" :closable="false">
        @if ($selectedTaskUser)
            @can('participantTask',$selectedTaskUser)
                <form wire:submit="updateTaskForm" enctype="multipart/form-data" class="text-sm text-gray-800">
                    <!-- Header -->
                    <div class="flex justify-between items-center px-6 py-4 bg-gray-100 border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-800">{{ __('ویرایش اقدام') }}</h2>
                        <button type="button" x-on:click="$dispatch('close')"
                                class="text-gray-400 hover:text-red-500 transition duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-4 space-y-6 text-gray-800 dark:text-gray-200 max-h-[70vh] overflow-y-auto">
                        <div class="grid grid-cols-2 gap-4">
                            <x-meeting-info label="{{ __('خلاصه مذاکره') }}"
                                            :value="$selectedTaskUser->task->body"/>
                            <x-meeting-info label="{{ __('اقدام کننده') }}"
                                            :value="$selectedTaskUser->user->user_info->full_name"/>
                        </div>

                        <div>
                            <x-input-label for="updateTaskBody" :value="__('شرح اقدام شما')" class="mb-2"/>
                            <textarea id="updateTaskBody" rows="4" wire:model.defer="updateTaskBody"
                                      class="w-full min-h-[100px] p-3 text-sm bg-white dark:bg-gray-800 border rounded-md border-gray-300 dark:border-gray-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 focus:outline-none resize-none"></textarea>
                            <x-input-error :messages="$errors->get('participantTaskBody')"/>
                        </div>

                        <div x-data="{ progress: 0 }"
                             x-on:livewire-upload-start="progress = 0"
                             x-on:livewire-upload-progress="progress = $event.detail.progress"
                             x-on:livewire-upload-finish="progress = 100"
                             x-on:livewire-upload-error="progress = 0"
                             class="space-y-2">

                            <x-input-label for="newFiles" :value="__('آپلود فایل جدید (در صورت نیاز)')"
                                           class="mb-2"/>
                            <input type="file" id="newFiles" wire:model="newFiles" multiple
                                   class="w-full text-sm text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none"/>

                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                                <div class="bg-green-500 h-2 transition-all duration-300"
                                     :style="`width: ${progress}%`"></div>
                            </div>
                            <div x-text="progress + '%'"
                                 class="text-xs text-right text-gray-600 dark:text-gray-400"></div>

                            <div wire:loading wire:target="newFiles" class="text-blue-500 text-sm">
                                {{ __('در حال آپلود فایل...') }}
                            </div>
                            <div wire:loading.remove wire:target="newFiles" class="text-green-600 text-sm" x-cloak>
                                @if (!empty($newFiles))
                                    {{ __('فایل‌(ها) با موفقیت آپلود شدند.') }}
                                @endif
                            </div>

                            <x-input-error :messages="$errors->get('newFiles')"/>
                        </div>

                        @if (!empty($selectedTaskFiles))
                            <div>
                                <x-input-label :value="__('فایل‌های آپلود شده قبلی')" class="mb-2"/>
                                <div class="grid gap-2">
                                    @foreach ($selectedTaskFiles as $file)
                                        <div id="file-{{ $file->id }}"
                                             class="flex items-center justify-between bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-md shadow-sm">
                                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                               class="text-blue-600 dark:text-blue-400 text-xs hover:underline truncate w-4/5">
                                                📄 {{ $file->original_name }}
                                            </a>
                                            <button type="button" wire:click="deleteUploadedFile({{ $file->id }})"
                                                    wire:confirm="{{__('آیا مطمئن هستید این فایل پاک شود؟')}}"
                                                    class="text-red-500 text-xs hover:underline">
                                                {{ __('حذف') }}
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- Footer -->
                    <div class="flex justify-between px-6 py-4 bg-gray-100 border-t border-gray-200">
                        <x-primary-button type="submit"
                                          wire:loading.attr="disabled"
                                          wire:target="updateTaskForm"
                                          wire:loading.class="opacity-50">
                            <span wire:loading.remove wire:target="updateTaskForm">{{ __('به‌روزرسانی') }}</span>
                            <span wire:loading wire:target="updateTaskForm"
                                  class="ml-2">{{ __('در حال ثبت...') }}</span>
                        </x-primary-button>

                        <x-cancel-button x-on:click="$dispatch('close')">
                            {{ __('انصراف') }}
                        </x-cancel-button>
                    </div>
                </form>
            @endcan
        @endif
    </x-modal>


    @if (!$this->allTasksLocked)
        {{--          Modal for scriptorium to finish the meeting --}}
        @if($this->meetings->status === MeetingStatus::IS_IN_PROGRESS)
            @if ($meeting)
                {{--        @can('scriptoriumTask')--}}
                <x-modal name="final-check" :closable="false">
                    <form wire:submit="finishMeeting" class="text-sm text-gray-800">
                        <!-- Header -->
                        <div class="flex justify-between items-center px-6 py-4 bg-gray-100 border-b border-gray-200">
                            <h2 class="text-2xl font-bold text-gray-800">{{ __('آیا از خاتمه این جلسه مطمئن هستید؟') }}</h2>
                            <button type="button" x-on:click="$dispatch('close')"
                                    class="text-gray-400 hover:text-red-500 transition duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <!-- Body -->
                        <div class="px-6 py-4 text-red-500 text-md">
                            {{ __('این اقدام قابل بازگشت نیست!') }}
                        </div>

                        <!-- Footer -->
                        <div class="flex justify-between px-6 py-4 bg-gray-100 border-t border-gray-200">
                            <x-accept-button type="submit">
                                {{ __('تایید') }}
                            </x-accept-button>
                            <x-cancel-button x-on:click="$dispatch('close')">
                                {{ __('انصراف') }}
                            </x-cancel-button>
                        </div>
                    </form>
                </x-modal>
                {{--        @endcan--}}
            @endif
        @endif
        <x-modal name="edit-by-scriptorium" :closable="false">
            @if ($selectedTaskUser)
                {{--                @can('participantTask', $selectedTaskUser)--}}
                <form wire:submit="editForm" class="text-sm text-gray-800">
                    <!-- Header -->
                    <div class="flex justify-between items-center px-6 py-4 bg-gray-100 border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-800">{{ __('ویرایش اقدام') }}</h2>
                        <button type="button" x-on:click="$dispatch('close')"
                                class="text-gray-400 hover:text-red-500 transition duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-4 space-y-6 text-gray-800 dark:text-gray-200 max-h-[70vh] overflow-y-auto">
                        <div class="grid grid-cols-2 gap-4">
                            <x-meeting-info label="{{ __('اقدام کننده') }}"
                                            :value="$selectedTaskUser->user->user_info->full_name"/>
                            <x-meeting-info label="{{ __('مهلت فعلی اقدام') }}" :value="$selectedTaskUser->time_out"/>
                        </div>

                        <div>
                            <x-input-label for="time_out" :value="__('مهلت جدید اقدام')" class="mb-2"/>
                            <div class="flex gap-2">
                                <div class="w-full">
                                    <x-select-input wire:model.defer="year">
                                        <option value="">{{ __('سال') }}</option>
                                        @for($i = 1404; $i <= 1430; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </x-select-input>
                                    <x-input-error :messages="$errors->get('year')" class="mt-2"/>
                                </div>
                                <div class="w-full">
                                    <x-select-input wire:model.defer="month">
                                        <option value="">{{ __('ماه') }}</option>
                                        @foreach(["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور", "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"] as $index => $monthName)
                                            <option value="{{ $index + 1 }}">{{ $monthName }}</option>
                                        @endforeach
                                    </x-select-input>
                                    <x-input-error :messages="$errors->get('month')" class="mt-2"/>
                                </div>
                                <div class="w-full">
                                    <x-select-input wire:model.defer="day">
                                        <option value="">{{ __('روز') }}</option>
                                        @for($i = 1; $i <= 31; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </x-select-input>
                                    <x-input-error :messages="$errors->get('day')" class="mt-2"/>
                                </div>
                            </div>
                        </div>

                        <!-- Editable Body -->
                        <div>
                            <x-input-label for="taskBody" :value="__('خلاصه مذاکره')" class="mb-2"/>
                            <textarea id="taskBody" rows="4" wire:model.defer="taskBody"
                                      class="w-full min-h-[100px] p-3 text-sm bg-white dark:bg-gray-800 border rounded-md border-gray-300 dark:border-gray-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 focus:outline-none resize-none">
                            {{$taskBody}}
                        </textarea>
                            <x-input-error :messages="$errors->get('taskBody')"/>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-between px-6 py-4 bg-gray-100 border-t border-gray-200">
                        <x-primary-button type="submit"
                                          wire:loading.attr="disabled"
                                          wire:target="updateTaskForm"
                                          wire:loading.class="opacity-50">
                            <span wire:loading.remove wire:target="updateTaskForm">{{ __('به‌روزرسانی') }}</span>
                            <span wire:loading wire:target="updateTaskForm"
                                  class="ml-2">{{ __('در حال ثبت...') }}</span>
                        </x-primary-button>

                        <x-cancel-button x-on:click="$dispatch('close')">
                            {{ __('انصراف') }}
                        </x-cancel-button>
                    </div>
                </form>
                {{--                @endcan--}}
            @endif
        </x-modal>
        <x-modal name="edit-task-body" maxWidth="4xl" :closable="false">
            @if($task_id)
                <form wire:submit.prevent="editTaskBody">
                    <!-- Header -->
                    <div class="flex justify-between items-center px-6 py-4 bg-gray-100 border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-800">{{ __('ویرایش بند مذاکره') }}</h2>
                        <a href="{{route('view.task.page',$meeting)}}"
                           class="text-gray-400 hover:text-red-500 transition duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-4 space-y-6 text-sm text-gray-800 max-h-[70vh] overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Textarea -->
                            <div class="col-span-2">
                                <x-input-label :value="__('متن بند مذاکره')" class="mb-1"/>
                                <textarea wire:model="task_body" rows="6"
                                          class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            {{$task_body}}
                        </textarea>
                                <x-input-error :messages="$errors->get('task_body')" class="mt-2"/>
                            </div>

                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-between px-6 py-4 bg-gray-100 border-t border-gray-200">
                        <x-primary-button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50">
                            <span wire:loading.remove>{{ __('ذخیره تغییرات') }}</span>
                            <span wire:loading>{{ __('در حال ذخیره...') }}</span>
                        </x-primary-button>

                        <a href="{{route('view.task.page',$meeting)}}">
                            <x-cancel-button>
                                {{ __('انصراف') }}
                            </x-cancel-button>
                        </a>
                    </div>
                </form>
            @endif
        </x-modal>
    @endif

    {{--          Modal for participants to type the task rejection --}}
    <x-modal name="deny-task" :closable="false">
        @if ($selectedTaskUser)
            @can('participantTask',$selectedTaskUser)
                <form wire:submit="denyTask" class="text-sm text-gray-800">
                    <!-- Header with Close Button -->
                    <div class="flex justify-between items-center px-6 py-4 bg-gray-100 border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-800">{{ __('رد خلاصه مذاکره') }}</h2>
                        <button type="button" x-on:click="$dispatch('close')"
                                class="text-gray-400 hover:text-red-500 transition duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div
                        class="p-6 max-h-[85vh] overflow-y-auto text-sm text-gray-800 dark:text-gray-200 space-y-6">
                        <div class="grid grid-cols-1 gap-4">
                            <x-meeting-info label="{{ __('مهلت انجام') }}" :value="$selectedTaskUser->time_out"/>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            <x-meeting-info label="{{ __('خلاصه مذاکره') }}"
                                            :value="$selectedTaskUser->task->body"/>
                        </div>
                        <div>
                            <label for="request_task" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('دلیل رد بند خلاصه مصوب') }}
                            </label>
                            <textarea wire:model.defer="request_task" id="request_task" rows="4"
                                      class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
            </textarea>
                            <x-input-error :messages="$errors->get('request_task')"/>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex flex-row justify-between px-6 py-4 bg-gray-100 border-t border-gray-200">
                        <x-primary-button type="submit">
                            {{ __('ثبت') }}
                        </x-primary-button>
                        <x-cancel-button x-on:click="$dispatch('close')">
                            {{ __('انصراف') }}
                        </x-cancel-button>
                    </div>
                </form>
            @endcan
        @endif
    </x-modal>


</div>
