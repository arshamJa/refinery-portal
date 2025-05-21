@php use App\Enums\MeetingStatus;use App\Enums\TaskStatus;use Illuminate\Support\Str; @endphp
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
                <strong>{{ __('پیوست: ') }}</strong><span>{{ $this->meetings->tasks->flatMap->taskUsers->flatMap->taskUserFiles->count() === 1 ? 'دارد' : 'ندارد' }}</span>
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

        @if(auth()->user()->user_info->full_name === $this->meetings->scriptorium )
            <form action="{{route('tasks.store', $this->meetings->id)}}" method="post" enctype="multipart/form-data">
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
                            <x-cancel-button>
                                {{__('لغو')}}
                            </x-cancel-button>
                        </a>
                    </div>
                </div>
            </form>
        @endif

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-12" id="task-table">
            <x-table.table>
                <x-slot name="head">
                    <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                        @foreach (['#', 'خلاصه مذاکرات و تصمیمات اتخاذ شده', 'مهلت اقدام', 'اقدام کننده', 'شرح اقدام', 'تاریخ انجام اقدام','فایل های آپلود شده','عملیات'] as $th)
                            <x-table.heading
                                class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}  {{ in_array($loop->index, [4, 7]) ? 'no-print' : '' }}">
                                {{ __($th) }}
                            </x-table.heading>
                        @endforeach
                    </x-table.row>
                </x-slot>
                <x-slot name="body">
                    @foreach ($this->tasks as $task)
                        <tr class="bg-white hover:bg-gray-50 transition" wire:key="task-{{ $task->id }}">
                            <td class="px-4 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-2 border-r border-gray-300 whitespace-pre-wrap ">
                                @if($editingTaskId === $task->id)
                                    <textarea wire:model.defer="editingBodyTask"
                                              class="mt-1 bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50 block w-full p-2.5 resize-y"
                                              rows="3"
                                    ></textarea>
                                    <x-input-error :messages="$errors->get('editingBodyTask')"/>
                                @else
                                    <span>{{$task->body}}</span>
                                @endif
                            </td>
                            {{-- time_out is specific per user, so render without rowspan --}}
                            <td class="px-2 py-4 border-r border-gray-300">
                                @if($editingTaskId === $task->id)
                                    <input id="timeout"
                                           wire:model.defer="editingTimeOut"
                                           class="w-full text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                                    <x-input-error :messages="$errors->get('editingTimeOut')"/>
                                @else
                                    <span>{{ $task->time_out }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 border-r border-gray-300" data-username="true">
                                {{ $task->user->user_info->full_name ?? '---' }}
                            </td>
                            {{-- Action description --}}
                            @php
                                list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
                                $todayDate = sprintf("%04d/%02d/%02d", $ja_year, $ja_month, $ja_day);
                                $isAfterTimeOut = $todayDate >= $task->time_out;
                            @endphp
                            {{-- Removed the column for شرح اقدام in print version --}}
                            <td class="px-4 py-4 border-r border-gray-300 no-print">
                                @if(!$isAfterTimeOut)
                                    @if($task->body_task && $task->body_task !== '---')
                                        <!-- Content visible only on the page (screen-only) -->
                                        <div x-data="{ expanded: false }" class="screen-only">
                                            <div x-show="!expanded" class="truncate">
                                                {{ Str::words($task->body_task, 5, '...') }}
                                            </div>
                                            <div x-show="expanded"
                                                 class="overflow-auto mt-2 text-sm text-gray-800 max-h-40">
                                                {{ $task->body_task }}
                                            </div>
                                            <button @click="expanded = !expanded"
                                                    class="mt-2 inline-flex items-center gap-1 text-xs font-semibold px-3 py-1 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition duration-200">
                                                <template x-if="!expanded">
                                                                <span class="no-print flex items-center">
                                                                    <svg class="w-4 h-4 ml-1" fill="none"
                                                                         stroke="currentColor"
                                                                         stroke-width="2" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                              stroke-linejoin="round"
                                                                              d="M19 9l-7 7-7-7"></path>
                                                                    </svg>
                                                                    {{__('نمایش بیشتر')}}
                                                                </span>
                                                </template>
                                                <template x-if="expanded">
                                                                <span class="no-print flex items-center">
                                                                    <svg class="w-4 h-4 ml-1" fill="none"
                                                                         stroke="currentColor"
                                                                         stroke-width="2" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                              stroke-linejoin="round"
                                                                              d="M5 15l7-7 7 7"></path>
                                                                    </svg>
                                                                    {{__('نمایش کمتر')}}
                                                                </span>
                                                </template>
                                            </button>
                                        </div>
                                        <!-- Content only for printing (always visible in print) -->
                                        <div class="task-card print-only" style="display: none;">
                                            {{ $task->body_task }}
                                        </div>
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
                            <td class="px-4 py-4 border-r border-gray-300">
                                {{ $task->sent_date ?? '---' }}
                            </td>

                            <td class="px-4 py-4 border-r border-gray-300">
                                @if ($task->taskUserFiles->isNotEmpty())
                                    <div class="flex flex-col gap-2 screen-only">
                                        @foreach ($task->taskUserFiles as $file)
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


                            <td class="px-4 py-4 border-r border-gray-300 text-center screen-only no-print">
                                @can('acceptOrDenyByParticipant',$task)
                                    <div class="flex gap-2 flex-col">
                                        <x-primary-button wire:click="acceptTask({{ $task->id }})">
                                            {{ __('تایید') }}
                                        </x-primary-button>
                                        <x-danger-button wire:click="openDenyModal({{ $task->id }})">
                                            {{ __('رد') }}
                                        </x-danger-button>
                                    </div>
                                @endcan
                                @can('scriptoriumCanEdit', $task)
                                    @if($editingTaskId !== $task->id)
                                        <div class="flex gap-2 flex-col">
                                            <x-edit-button wire:click="edit({{ $task->id }})">
                                                {{ __('ویرایش') }}
                                            </x-edit-button>
                                            <x-danger-button wire:click="delete({{$task->id}})"
                                                             wire:confirm="Are you sure you want to delete this todo?">
                                                {{ __('حذف') }}
                                            </x-danger-button>
                                        </div>
                                    @endif
                                    @if($editingTaskId === $task->id)
                                        <div class="flex gap-2 flex-col">
                                            <x-accept-button wire:click="update">
                                                {{ __('بروزرسانی') }}
                                            </x-accept-button>
                                            <x-danger-button wire:click="cancel">
                                                {{ __('لغو') }}
                                            </x-danger-button>
                                        </div>
                                    @endif
                                @endcan
                                @can('participantCanWriteTask', $task)
                                    <x-primary-button class="px-3 py-2 whitespace-nowrap"
                                                      wire:click="showTaskForm({{ $task->id }})">
                                        {{ __('انجام اقدام') }}
                                    </x-primary-button>
                                @endcan
                                @can('participantCanUpdateTask', $task)
                                    <div class="flex flex-col gap-2">
                                        <x-edit-button wire:click="openUpdateModal({{$task->id}})">
                                            {{ __('ویرایش') }}
                                        </x-edit-button>
                                        <x-secondary-button wire:click="sendToScriptorium({{$task->id}})">
                                            {{ __('ارسال') }}
                                        </x-secondary-button>
                                    </div>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-table.table>
            <script src="{{ asset('js/printTable.js') }}"></script>
        </div>

        {{-- شرح اقدام Cards below the table --}}
        <h2 class="text-lg font-semibold mb-4">{{ __('شرح اقدامات:') }}</h2>
        <div class="mt-8 grid grid-cols-1 gap-6">
            @foreach ($this->tasks as $task)
                <div wire:key="task-{{$task->id}}">
                    @if($task->body_task && $task->body_task !== '---')
                        <div class="p-4 bg-white rounded-lg shadow-md border border-gray-200">
                            <h3 class="text-md font-semibold mb-2">
                                {{ $task->user->user_info->full_name ?? 'ناشناس' }}
                            </h3>
                            <p class="text-gray-700 text-sm whitespace-pre-line">
                                {{ $task->body_task }}
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>


        {{--        <div class="relative mb-12" id="task-table">--}}
        {{--            --}}{{-- Search/filter bar --}}
        {{--            <div class="mb-4 flex justify-between items-center">--}}
        {{--                <input type="search" placeholder="جستجو..." wire:model.debounce.300ms="search"--}}
        {{--                       class="px-4 py-2 border rounded-md w-full max-w-xs focus:outline-none focus:ring-2 focus:ring-blue-400" />--}}
        {{--                --}}{{-- You can add sorting dropdown here --}}
        {{--            </div>--}}


        {{--            --}}{{-- Mobile Cards --}}
        {{--            <div class="space-y-4">--}}
        {{--                @foreach ($this->tasks as $task)--}}
        {{--                    <div class="bg-white p-4 rounded-lg shadow border border-gray-200">--}}
        {{--                        <div class="flex justify-between items-center mb-2">--}}
        {{--                            <div class="font-semibold text-gray-800">--}}
        {{--                                {{ $task->user->user_info->full_name ?? 'ناشناس' }}--}}
        {{--                            </div>--}}
        {{--                            <div class="text-xs text-gray-500">{{ $task->time_out }}</div>--}}
        {{--                        </div>--}}
        {{--                        <div class="mb-2 whitespace-pre-wrap text-gray-700">{{ $task->body }}</div>--}}
        {{--                        <div class="mb-2 text-sm text-gray-600 truncate" title="{{ $task->body_task }}">--}}
        {{--                            <strong>شرح اقدام:</strong> {{ $task->body_task }}--}}
        {{--                        </div>--}}
        {{--                        <div class="flex space-x-2">--}}
        {{--                            <button wire:click="edit({{ $task->id }})" class="text-blue-600 hover:text-blue-800" aria-label="ویرایش">--}}
        {{--                                ✏️--}}
        {{--                            </button>--}}
        {{--                            <button wire:click="delete({{ $task->id }})" class="text-red-600 hover:text-red-800" aria-label="حذف">--}}
        {{--                                🗑️--}}
        {{--                            </button>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                @endforeach--}}
        {{--            </div>--}}
        {{--        </div>--}}







        @if($this->tasks()->where('task_status',TaskStatus::DENIED)->isNotEmpty())
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-12">
                <x-table.table>
                    <x-slot name="head">
                        <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                            @foreach (['#', 'نام', 'دلیل رد', ] as $th)
                                <x-table.heading
                                    class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}">
                                    {{ __($th) }}
                                </x-table.heading>
                            @endforeach
                        </x-table.row>
                    </x-slot>
                    <x-slot name="body">
                        @foreach($this->tasks()->where('task_status',TaskStatus::DENIED) as $taskRequest)
                            <x-table.row wire:key="taskRequest-{{ $taskRequest->id }}"
                                         class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-50">
                                <x-table.cell>{{ $loop->iteration }}</x-table.cell>
                                <x-table.cell>{{ $taskRequest->user->user_info->full_name }}</x-table.cell>
                                <x-table.cell
                                    class="whitespace-pre-wrap wrap-anywhere">{{ $taskRequest->request_task }}</x-table.cell>
                            </x-table.row>
                        @endforeach
                    </x-slot>
                </x-table.table>
            </div>
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

        @if (auth()->user()->user_info->full_name === $this->meetings->scriptorium)
            @if( $this->meetings->status == MeetingStatus::IS_IN_PROGRESS)
                <div class="flex justify-between">
                    <button wire:click="showFinalCheck({{ $this->meetings->id}})"
                            class="flex justify-center gap-3 items-center bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-medium py-3 px-6 rounded-xl shadow-lg transition-all duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z"/>
                        </svg>
                        {{ __('خاتمه جلسه') }}
                    </button>
                </div>
            @endif
            <!-- Print Button -->
            <button onclick="printTable()"
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                {{__('چاپ صوتجلسه')}}
            </button>

        @endif

    </div>

    <x-modal name="view-task-details-modal" maxWidth="4xl" :closable="false">
        @if ($selectedTask)
            @can('participantCanWriteTask',$selectedTask)
                <form wire:submit="submitTaskForm" enctype="multipart/form-data">
                    <div class="p-6 max-h-[85vh] overflow-y-auto text-sm text-gray-800 dark:text-gray-200 space-y-6">
                        <div class="border-b pb-4">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('جزئیات') }}</h2>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <x-meeting-info label="{{ __('خلاصه مذاکره') }}" :value="$selectedTask->body"/>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="participantTaskBody" :value="__('شرح اقدام شما')" class="mb-2"/>
                            <textarea wire:model.defer="participantTaskBody" id="participantTaskBody" rows="4"
                                      class="w-full h-auto min-h-[80px] p-2 text-sm bg-white border rounded-md border-neutral-300"></textarea>
                            <x-input-error :messages="$errors->get('participantTaskBody')"/>
                        </div>

                        <div class="mt-4" x-data="{ progress: 0 }"
                             x-on:livewire-upload-start="progress = 0"
                             x-on:livewire-upload-progress="progress = $event.detail.progress"
                             x-on:livewire-upload-finish="progress = 100"
                             x-on:livewire-upload-error="progress = 0">

                            <x-input-label for="files" :value="__('آپلود فایل مرتبط')" class="mb-2"/>
                            <input type="file" wire:model="files" multiple class="w-full text-sm text-gray-500"/>

                            <div class="w-full bg-gray-200 rounded h-2 mt-2 overflow-hidden">
                                <div class="bg-green-500 h-2 transition-all duration-300"
                                     :style="`width: ${progress}%`"></div>
                            </div>

                            <div x-text="progress + '%'" class="text-xs text-right text-gray-700 mt-1"></div>

                            <div wire:loading wire:target="files" class="text-blue-500 text-sm mt-2">
                                {{ __('در حال آپلود فایل...') }}
                            </div>

                            {{-- Upload complete message --}}
                            <div wire:loading.remove wire:target="files" class="text-green-600 text-sm mt-2" x-cloak>
                                @if (!empty($files))
                                    {{ __('فایل‌(ها) با موفقیت آپلود شدند.') }}
                                @endif
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('files')"/>


                        <div class="pt-4 flex justify-between gap-2">
                            <x-primary-button type="submit"
                                              wire:target="submitTaskForm"
                                              wire:loading.attr="disabled"
                                              wire:loading.class="opacity-50">
                            <span wire:loading.remove wire:target="submitTaskForm">
                                {{ __('ذخیره') }}
                            </span>
                                <span wire:loading wire:target="submitTaskForm" class="ml-2">
                                {{ __('در حال ثبت...') }}
                            </span>
                            </x-primary-button>
                            <x-cancel-button x-on:click="$dispatch('close')">
                                {{ __('انصراف') }}
                            </x-cancel-button>
                        </div>
                    </div>
                </form>
            @endcan
        @endif
    </x-modal>


    <x-modal name="edit-task-details-modal" maxWidth="4xl" :closable="false">
        @if ($selectedTask)
            @can('participantCanUpdateTask', $selectedTask)
                <form wire:submit="updateTaskForm" class="text-sm text-gray-800">
                    <!-- Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">{{ __('ویرایش اقدام') }}</h2>
                        <button type="button" x-on:click="$dispatch('close')"
                                class="text-gray-500 hover:text-gray-700 text-xl font-bold focus:outline-none">
                            X
                        </button>
                    </div>
                    <!-- Body -->
                    <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">

                        <!-- Info Summary -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <p class="text-xs text-gray-500 mb-1">{{ __('خلاصه مذاکره') }}</p>
                                <p class="font-medium">{{ $selectedTask->body }}</p>
                            </div>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <p class="text-xs text-gray-500 mb-1">{{ __('اقدام کننده') }}</p>
                                <p class="font-medium">{{ $selectedTask->user->user_info->full_name }}</p>
                            </div>
                        </div>

                        <!-- Task Body -->
                        <div>
                            <x-input-label for="updateTaskBody" :value="__('شرح اقدام شما')" class="mb-1"/>
                            <textarea id="updateTaskBody" rows="6" wire:model.defer="updateTaskBody"
                                      class="w-full p-4 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm resize-none"></textarea>
                            <x-input-error :messages="$errors->get('participantTaskBody')"/>
                        </div>

                        <!-- File Upload with Progress Bar -->
                        <div class="mt-4" x-data="{ progress: 0 }"
                             x-on:livewire-upload-start="progress = 0"
                             x-on:livewire-upload-progress="progress = $event.detail.progress"
                             x-on:livewire-upload-finish="progress = 100";
                             x-on:livewire-upload-error="progress = 0">

                            <x-input-label for="newFiles" :value="__('آپلود فایل جدید (در صورت نیاز)')" class="mb-2"/>
                            <input type="file" id="newFiles" wire:model="newFiles" multiple class="w-full text-sm text-gray-500"/>

                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 rounded h-2 mt-2 overflow-hidden">
                                <div class="bg-green-500 h-2 transition-all duration-300"
                                     :style="`width: ${progress}%`"></div>
                            </div>

                            <!-- Percentage Text -->
                            <div x-text="progress + '%'" class="text-xs text-right text-gray-700 mt-1"></div>

                            <!-- Uploading Text -->
                            <div wire:loading wire:target="newFiles" class="text-blue-500 text-sm mt-2">
                                {{ __('در حال آپلود فایل...') }}
                            </div>

                            <!-- Upload Done Text -->
                            <div wire:loading.remove wire:target="newFiles" class="text-green-600 text-sm mt-2" x-cloak>
                                @if (!empty($newFiles))
                                    {{ __('فایل‌(ها) با موفقیت آپلود شدند.') }}
                                @endif
                            </div>

                            <x-input-error :messages="$errors->get('newFiles')"/>
                        </div>


                        <!-- Existing Files -->
                        @if (!empty($selectedTaskFiles))
                            <div>
                                <x-input-label :value="__('فایل‌های آپلود شده قبلی')" class="mb-2"/>
                                <div class="grid gap-2">
                                    @foreach ($selectedTaskFiles as $file)
                                        <div id="file-{{ $file->id }}"
                                             class="flex items-center justify-between bg-gray-100 px-4 py-2 rounded-md shadow-sm">
                                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                               class="text-blue-600 text-xs hover:underline truncate w-4/5">
                                                📄 {{ $file->original_name }}
                                            </a>
                                            <button type="button" wire:click="deleteUploadedFile({{$file->id}})"
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
                    <div class="flex justify-between items-center px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <x-primary-button type="submit"
                                          wire:loading.attr="disabled"
                                          wire:target="updateTaskForm"
                                          wire:loading.class="opacity-50">
                    <span wire:loading.remove wire:target="updateTaskForm">
                        {{ __('به‌روزرسانی') }}
                    </span>
                            <span wire:loading wire:target="updateTaskForm" class="ml-2">
                        {{ __('در حال ثبت...') }}
                    </span>
                        </x-primary-button>
                        <x-cancel-button wire:click="close({{$selectedTask->meeting_id}})">
                            {{ __('انصراف') }}
                        </x-cancel-button>
                    </div>
                </form>
            @endcan
        @endif
    </x-modal>


    {{--    <script>--}}
    {{--        $(document).ready(function () {--}}
    {{--            // Function to load the files dynamically--}}
    {{--            function loadTaskFiles(taskUserId) {--}}
    {{--                $.ajax({--}}
    {{--                    url: '/tasks/files/' + taskUserId, // URL to fetch files--}}
    {{--                    method: 'GET',--}}
    {{--                    success: function (response) {--}}
    {{--                        if (response.files.length > 0) {--}}
    {{--                            let filesHtml = '';--}}
    {{--                            response.files.forEach(function (file) {--}}
    {{--                                filesHtml += `--}}
    {{--                                        <li id="file-${file.id}" class="flex items-center justify-between bg-gray-100 p-2 rounded">--}}
    {{--                                            <a href="{{ asset('storage/') }}/${file.file_path}" target="_blank" class="text-blue-600 hover:underline truncate">--}}
    {{--                                                📄 ${file.original_name}--}}
    {{--                                            </a>--}}
    {{--                                            <button class="text-red-500 text-xs hover:underline delete-file" data-file-id="${file.id}">--}}
    {{--                                                حذف--}}
    {{--                                            </button>--}}
    {{--                                        </li>--}}
    {{--                                    `;--}}
    {{--                            });--}}
    {{--                            $('#task-files-list').html(filesHtml);--}}
    {{--                        } else {--}}
    {{--                            $('#task-files-list').html('<p>هیچ فایلی آپلود نشده است.</p>');--}}
    {{--                        }--}}
    {{--                    }--}}
    {{--                });--}}
    {{--            }--}}

    {{--            // Handle file deletion--}}
    {{--            $('body').on('click', '.delete-file', function (e) {--}}
    {{--                e.preventDefault();--}}
    {{--                var fileId = $(this).data('file-id');--}}
    {{--                var fileRow = $('#file-' + fileId);  // The row to remove--}}

    {{--                if (confirm('آیا مطمئن هستید که می‌خواهید این فایل را حذف کنید؟')) {--}}
    {{--                    $.ajax({--}}
    {{--                        url: '/tasks/delete-file/' + fileId, // URL to delete file--}}
    {{--                        method: 'DELETE',--}}
    {{--                        data: {--}}
    {{--                            _token: '{{ csrf_token() }}', // CSRF token--}}
    {{--                        },--}}
    {{--                        success: function (response) {--}}
    {{--                            if (response.success) {--}}
    {{--                                fileRow.fadeOut();  // Remove the file row--}}
    {{--                                alert(response.message);  // Show success message--}}
    {{--                            } else {--}}
    {{--                                alert('عملیات حذف ناموفق بود.');--}}
    {{--                            }--}}
    {{--                        },--}}
    {{--                        error: function (xhr, status, error) {--}}
    {{--                            alert('خطا در حذف فایل');--}}
    {{--                        }--}}
    {{--                    });--}}
    {{--                }--}}
    {{--            });--}}
    {{--        });--}}
    {{--    </script>--}}


    {{--        @if($this->meetings->status === MeetingStatus::IS_IN_PROGRESS)--}}
    {{--            <x-modal name="final-check" :closable="false">--}}
    {{--                <form wire:submit="finishMeeting({{$this->meetings->id}})">--}}
    {{--                    <div class="flex flex-row px-6 py-4 bg-gray-100 text-start">--}}
    {{--                        <h2 class="text-2xl font-bold text-gray-800">{{ __('آیا تایید نهایی این جلسه مطمئن هستید؟') }}</h2>--}}
    {{--                    </div>--}}
    {{--                    <div class="px-6 py-4">--}}
    {{--                        <p class="text-md text-red-500">{{ __('این اقدام قابل بازگشت نیست!') }}</p>--}}
    {{--                    </div>--}}
    {{--                    <div class="flex flex-row justify-between px-6 py-4 bg-gray-100">--}}
    {{--                        <x-primary-button type="submit">--}}
    {{--                            {{ __('تایید') }}--}}
    {{--                        </x-primary-button>--}}
    {{--                        <x-cancel-button x-on:click="$dispatch('close')">--}}
    {{--                            {{ __('انصراف') }}--}}
    {{--                        </x-cancel-button>--}}
    {{--                    </div>--}}
    {{--                </form>--}}
    {{--            </x-modal>--}}
    {{--        @endif--}}

    {{--        <x-modal name="deny-task" :closable="false">--}}
    {{--            @if ($selectedTask)--}}
    {{--                <form wire:submit="denyTask({{$selectedTask->id}})">--}}
    {{--                    <div class="p-6 max-h-[85vh] overflow-y-auto text-sm text-gray-800 dark:text-gray-200 space-y-6">--}}
    {{--                        <div class="grid grid-cols-1 gap-4">--}}
    {{--                            <x-meeting-info label="{{ __('مهلت انجام') }}" :value="$selectedTask->time_out"/>--}}
    {{--                        </div>--}}
    {{--                        <div class="grid grid-cols-1 gap-4">--}}
    {{--                            <x-meeting-info label="{{ __('خلاصه مذاکره') }}" :value="$selectedTask->body"/>--}}
    {{--                        </div>--}}
    {{--                        <div>--}}
    {{--                            <label for="request_task" class="block text-sm font-medium text-gray-700 mb-2">--}}
    {{--                                {{ __('دلیل رد خلاصه مذاکره') }}--}}
    {{--                            </label>--}}
    {{--                            <textarea wire:model.defer="request_task" id="request_task" rows="4"--}}
    {{--                                      class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">--}}
    {{--                </textarea>--}}
    {{--                            <x-input-error :messages="$errors->get('request_task')"/>--}}
    {{--                        </div>--}}

    {{--                    </div>--}}
    {{--                    <div class="flex flex-row justify-between px-6 py-4 bg-gray-100">--}}
    {{--                        <x-primary-button type="submit">--}}
    {{--                            {{ __('ثبت') }}--}}
    {{--                        </x-primary-button>--}}
    {{--                        <x-cancel-button x-on:click="$dispatch('close')">--}}
    {{--                            {{ __('انصراف') }}--}}
    {{--                        </x-cancel-button>--}}
    {{--                    </div>--}}
    {{--                </form>--}}
    {{--            @endif--}}
    {{--        </x-modal>--}}


</div>
