@php use App\Enums\MeetingStatus;use App\Enums\TaskStatus;use Illuminate\Support\Str; @endphp
<div>


    @if (session('status'))
        <div
            x-data="{ showMessage: true }" x-show="showMessage" x-transition x-cloak
            x-init="setTimeout(() => showMessage = false, 4000)"
            dir="rtl"
            class="fixed top-5 right-5 z-[99] max-w-xs bg-white border border-gray-200 rounded-xl shadow-lg dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex p-4">
                <div class="shrink-0">
                    <svg class="shrink-0 size-4 text-teal-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16"
                         height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"></path>
                    </svg>
                </div>
                <div class="ms-3">
                    <p class="text-sm text-gray-700 dark:text-neutral-400">
                        {{ session('status') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

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
            <div><strong>{{ __('زمان جلسه: ') }}</strong><span>{{ $this->meetings->time }}</span></div>
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
        {{--        @if (!$this->allUsersHaveTasks )--}}
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
                            <x-secondary-button>
                                {{__('لغو')}}
                            </x-secondary-button>
                        </a>
                    </div>
                </div>
            </form>
        @endif
        {{--        @endif--}}


        <div class="overflow-x-auto rounded-xl border border-gray-300 shadow-sm">
            <table id="task-table" class="w-full text-right text-sm border-collapse">
                <thead class="bg-gray-100 text-gray-700">
                @foreach (['#', 'خلاصه مذاکرات و تصمیمات اتخاذ شده', 'مهلت اقدام', 'اقدام کننده', 'شرح اقدام', 'تاریخ انجام اقدام','فایل های آپلود شده','عملیات'] as $th)
                    <th class="px-4 py-3 border-b border-gray-400 @if ($loop->last) screen-only @endif @if ($loop->index === 4) no-print @endif">
                        {{ __($th) }}
                    </th>
                @endforeach
                </thead>
                <tbody class="divide-y divide-gray-300">
                @foreach ($this->tasks as $index => $task)
                    @foreach ($task->taskUsers as $userIndex => $taskUser)
                        <tr class="bg-white hover:bg-gray-50 transition">
                            @if ($userIndex === 0)
                                {{-- Render only once for the first user of this task --}}
                                <td class="px-4 py-4 align-top"
                                    rowspan="{{ $task->taskUsers->count() }}">{{ $index + 1 }}</td>
                                <td class="px-4 py-4 border-r border-gray-300 align-top"
                                    rowspan="{{ $task->taskUsers->count() }}">{{ $task->body }}</td>
                            @endif

                            {{-- time_out is specific per user, so render without rowspan --}}
                            <td class="px-4 py-4 border-r border-gray-300 align-top">
                                {{ $taskUser->time_out }}
                            </td>

                            <td class="px-4 py-4 border-r border-gray-300" data-username="true">
                                {{ $taskUser->user->user_info->full_name ?? '---' }}
                            </td>

                            {{-- Action description --}}
                            @php
                                list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
                                $todayDate = sprintf("%04d/%02d/%02d", $ja_year, $ja_month, $ja_day);
                                $isAfterTimeOut = $todayDate >= $taskUser->time_out;
                            @endphp

                            {{-- Removed the column for شرح اقدام in print version --}}
                            <td class="px-4 py-4 border-r border-gray-300 no-print">
                                @if(!$isAfterTimeOut)
                                    @if($taskUser->body_task && $taskUser->body_task !== '---')
                                        <!-- Content visible only on the page (screen-only) -->
                                        <div x-data="{ expanded: false }" class="screen-only">
                                            <div x-show="!expanded" class="truncate">
                                                {{ Str::words($taskUser->body_task, 5, '...') }}
                                            </div>
                                            <div x-show="expanded"
                                                 class="overflow-auto mt-2 text-sm text-gray-800 max-h-40">
                                                {{ $taskUser->body_task }}
                                            </div>
                                            <button @click="expanded = !expanded"
                                                    class="mt-2 inline-flex items-center gap-1 text-xs font-semibold px-3 py-1 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition duration-200">
                                                <template x-if="!expanded">
                                                    <span class="no-print flex items-center">
                                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                             stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                        {{__('نمایش بیشتر')}}
                                                    </span>
                                                </template>
                                                <template x-if="expanded">
                                                    <span class="no-print flex items-center">
                                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                             stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M5 15l7-7 7 7"></path>
                                                        </svg>
                                                        {{__('نمایش کمتر')}}
                                                    </span>
                                                </template>
                                            </button>
                                        </div>
                                        <!-- Content only for printing (always visible in print) -->
                                        <div class="task-card print-only" style="display: none;">
                                            {{ $taskUser->body_task }}
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
                                {{ $taskUser->sent_date ?? '---' }}
                            </td>

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

                            <td class="px-4 py-4 border-r border-gray-300 text-center screen-only">
                                {{--  @can('acceptOrDeny',$taskUser)--}}
                                @if (auth()->id() === $taskUser->user_id && $taskUser->task_status === TaskStatus::PENDING)
                                    <div class="flex gap-2 justify-center">
                                        <x-primary-button wire:click="acceptTask({{ $task->id }})">
                                            {{ __('تایید') }}
                                        </x-primary-button>
                                        <x-danger-button wire:click="openDenyModal({{ $taskUser->id }})">
                                            {{ __('رد') }}
                                        </x-danger-button>
                                    </div>
                                @endif
                                {{--  @endcan--}}
                                @can('scriptoriumCanEdit', $taskUser)
                                    <x-secondary-button wire:click="openModalScriptorium({{$taskUser->id}})">
                                        {{ __('ویرایش') }}
                                    </x-secondary-button>
                                @endcan
                                @can('writeTask', $taskUser)
                                    <x-primary-button class="px-3 py-2"
                                                      wire:click="showTaskDetails({{ $taskUser->id }})">
                                        {{ __('انجام اقدام') }}
                                    </x-primary-button>
                                @endcan
                                @can('updateTask', $taskUser)
                                    <x-secondary-button wire:click="openUpdateModal({{$taskUser->id}})">
                                        {{ __('ویرایش اقدام') }}
                                    </x-secondary-button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
            <script src="{{ asset('js/printTable.js') }}"></script>
        </div>


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

    {{--    this one has wire:target--}}
    <x-modal name="view-task-details-modal" maxWidth="4xl" :closable="false">
        @if ($selectedTask)
            <form wire:submit.prevent="submitTaskForm({{ $selectedTask->id}})" enctype="multipart/form-data">
                <div class="p-6 max-h-[85vh] overflow-y-auto text-sm text-gray-800 dark:text-gray-200 space-y-6">
                    <div class="border-b pb-4">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('جزئیات') }}</h2>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <x-meeting-info label="{{ __('خلاصه مذاکره') }}" :value="$selectedTask->task->body"/>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="taskBody" :value="__('شرح اقدام شما')" class="mb-2"/>
                        <textarea wire:model.defer="taskBody" id="taskBody" rows="4"
                                  class="w-full h-auto min-h-[80px] p-2 text-sm bg-white border rounded-md border-neutral-300"></textarea>
                        <x-input-error :messages="$errors->get('taskBody')"/>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="files" :value="__('آپلود فایل مرتبط')" class="mb-2"/>
                        <input type="file" wire:model="files" multiple class="w-full text-sm text-gray-500"/>
                        <div wire:loading wire:target="files" class="text-blue-500 text-sm mt-2">{{ __('در حال آپلود فایل...') }}</div>
                        <x-input-error :messages="$errors->get('files')"/>
                    </div>

                    <div class="pt-4 flex justify-between gap-2">
                        <x-primary-button type="submit"
                                          wire:target="submitTaskForm"
                                          wire:loading.attr="disabled"
                                          wire:loading.class="opacity-50">
                        <span wire:loading.remove wire:target="submitTaskForm">
                            {{ __('ثبت') }}
                        </span>
                        <span wire:loading wire:target="submitTaskForm" class="ml-2">
                            {{ __('در حال ثبت...') }}
                        </span>
                        </x-primary-button>

                        <button type="button" x-on:click="$dispatch('close')"
                                class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                            {{ __('لغو') }}
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </x-modal>

    <x-modal name="edit-task-details-modal" maxWidth="4xl" :closable="false">
        @if ($selectedTask)
            <form method="POST" action="{{ route('tasks.update', $selectedTask->id) }}" enctype="multipart/form-data"
                  class="text-sm text-gray-800">
                @csrf
                @method('PUT')

                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">{{ __('ویرایش اقدام') }}</h2>
                    <button type="button"
                            x-on:click="show = false"
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
                            <p class="font-medium">{{ $selectedTask->task->body }}</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-xs text-gray-500 mb-1">{{ __('اقدام کننده') }}</p>
                            <p class="font-medium">{{ $selectedTask->user->user_info->full_name }}</p>
                        </div>
                    </div>

                    <!-- Task Body -->
                    <div>
                        <x-input-label for="taskBody" :value="__('شرح اقدام شما')" class="mb-1"/>
                        <textarea name="taskBody" id="taskBody" rows="6"
                                  class="w-full p-4 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm resize-none"
                                  placeholder="شرح اقدام خود را وارد کنید...">{{ old('taskBody', $selectedTask->body_task) }}</textarea>
                        @error('taskBody')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Upload -->
                    <div>
                        <x-input-label for="fileUpload" :value="__('آپلود فایل جدید (در صورت نیاز)')" class="mb-1"/>
                        <input type="file" name="fileUpload[]" id="fileUpload" multiple
                               class="block w-full text-sm file:px-4 file:py-2 file:rounded-md file:border-0 file:bg-blue-50 file:text-blue-700 file:font-semibold hover:file:bg-blue-100"/>
                        @error('fileUpload.*')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
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
                                        <button type="button"
                                                class="text-red-500 text-xs hover:underline delete-file"
                                                data-file-id="{{ $file->id }}">
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
                    <x-primary-button type="submit" class="px-5 py-2 text-sm">
                        {{ __('به‌روزرسانی') }}
                    </x-primary-button>
                    <a href="{{route('tasks.create',$selectedTask->task->meeting_id)}}">
                        <x-cancel-button>
                            {{ __('انصراف') }}
                        </x-cancel-button>
                    </a>
                </div>
            </form>
        @endif
    </x-modal>


    <script>
        $(document).ready(function () {
            // Function to load the files dynamically
            function loadTaskFiles(taskUserId) {
                $.ajax({
                    url: '/tasks/files/' + taskUserId, // URL to fetch files
                    method: 'GET',
                    success: function (response) {
                        if (response.files.length > 0) {
                            let filesHtml = '';
                            response.files.forEach(function (file) {
                                filesHtml += `
                                <li id="file-${file.id}" class="flex items-center justify-between bg-gray-100 p-2 rounded">
                                    <a href="{{ asset('storage/') }}/${file.file_path}" target="_blank" class="text-blue-600 hover:underline truncate">
                                        📄 ${file.original_name}
                                    </a>
                                    <button class="text-red-500 text-xs hover:underline delete-file" data-file-id="${file.id}">
                                        حذف
                                    </button>
                                </li>
                            `;
                            });
                            $('#task-files-list').html(filesHtml);
                        } else {
                            $('#task-files-list').html('<p>هیچ فایلی آپلود نشده است.</p>');
                        }
                    }
                });
            }

            // Handle file deletion
            $('body').on('click', '.delete-file', function (e) {
                e.preventDefault();
                var fileId = $(this).data('file-id');
                var fileRow = $('#file-' + fileId);  // The row to remove

                if (confirm('آیا مطمئن هستید که می‌خواهید این فایل را حذف کنید؟')) {
                    $.ajax({
                        url: '/tasks/delete-file/' + fileId, // URL to delete file
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}', // CSRF token
                        },
                        success: function (response) {
                            if (response.success) {
                                fileRow.fadeOut();  // Remove the file row
                                alert(response.message);  // Show success message
                            } else {
                                alert('عملیات حذف ناموفق بود.');
                            }
                        },
                        error: function (xhr, status, error) {
                            alert('خطا در حذف فایل');
                        }
                    });
                }
            });
        });
    </script>


    @if($this->meetings->status === MeetingStatus::IS_IN_PROGRESS)
        <x-modal name="final-check" :closable="false">
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

    <x-modal name="deny-task" :closable="false">
        @if ($selectedTask)
            <form wire:submit="denyTask">
                <div class="p-6 max-h-[85vh] overflow-y-auto text-sm text-gray-800 dark:text-gray-200 space-y-6">
                    <div class="grid grid-cols-1 gap-4">
                        <x-meeting-info label="{{ __('خلاصه مذاکره') }}" :value="$selectedTask->task->body"/>
                    </div>
                    <div>
                        <label for="request_task" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('دلیل رد خلاصه مذاکره') }}
                        </label>
                        <textarea wire:model.defer="request_task" id="request_task" rows="4"
                                  class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
            </textarea>
                        <x-input-error :messages="$errors->get('request_task')"/>
                    </div>

                </div>
                <div class="flex flex-row justify-between px-6 py-4 bg-gray-100">
                    <x-primary-button type="submit">
                        {{ __('ثبت') }}
                    </x-primary-button>
                    <x-cancel-button x-on:click="$dispatch('close')">
                        {{ __('انصراف') }}
                    </x-cancel-button>
                </div>
            </form>
        @endif
    </x-modal>

    <x-modal name="edit-by-scriptorium" :closable="false">
        <form wire:submit="updateTask">
            <div class="flex flex-row px-6 py-4 bg-gray-100 text-start">
                <h2 class="text-2xl font-bold text-gray-800">ویرایش وظیفه: {{ $userName }}</h2>
            </div>
            <div class="px-6 py-4">
                <x-input-label for="time_out" :value="__('مهلت اقدام')" class="mb-2"/>
                <div class="flex gap-2">
                    <div class="w-full">
                        <div class="flex items-center gap-1">
                            <select wire:model.defer="year" id="year" dir="ltr"
                                    class="w-full text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                                <option value="">{{__(':سال')}}</option>
                                @for($i = 1404; $i <= 1430; $i++)
                                    <option value="{{$i}}" @if (old('year') == $i) selected @endif>
                                        {{$i}}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="flex items-center gap-1">
                            @php
                                $persian_months = ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور","مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"];
                            @endphp
                            <select wire:model.defer="month" id="month" dir="ltr"
                                    class="w-full text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                                <option value="">{{__(':ماه')}}</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" @if (old('month') == $i) selected @endif>
                                        {{ $persian_months[$i - 1] }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="flex items-center gap-1">
                            <select wire:model.defer="day" id="day" dir="ltr"
                                    class="w-full text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                                <option value="">{{__(':روز')}}</option>
                                @for($i = 1; $i <= 31; $i++)
                                    <option value="{{$i}}" @if (old('day') == $i) selected @endif>
                                        {{$i}}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <x-input-label for="body" :value="__('خلاصه مذاکرات و تصمیمات اتخاذ شده')" class="mb-2"/>
                <textarea type="text" wire:model.defer="body" rows="4"
                          class="w-full h-auto min-h-[80px] p-2 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                            {{old('body')}}
                </textarea>
                <x-input-error :messages="$errors->get('year')" class="my-2"/>
                <x-input-error :messages="$errors->get('month')" class="my-2"/>
                <x-input-error :messages="$errors->get('day')" class="my-2"/>
                <x-input-error :messages="$errors->get('body')" class="mt-2"/>
            </div>

            <div class="flex flex-row justify-between px-6 py-4 bg-gray-100">
                <x-primary-button type="submit">
                    {{ __('بروزرسانی') }}
                </x-primary-button>
                <x-cancel-button x-on:click="$dispatch('close')">
                    {{ __('انصراف') }}
                </x-cancel-button>
            </div>
        </form>
    </x-modal>

</div>
