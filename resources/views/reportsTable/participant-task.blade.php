@php use App\Enums\UserPermission;use App\Enums\UserRole; @endphp
<x-app-layout>
    @can('has-permission',UserPermission::TASK_REPORT_TABLE)

    <nav class="flex justify-between mb-4 mt-16">
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
                <a href="{{route('task.report.table')}}"
                   class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                    <span>{{__('گزارش اقدامات شرکت')}}</span>
                </a>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                 stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            <li>
            <span
                class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                {{__('نمایش جزئیات')}}
            </span>
            </li>
        </ol>
    </nav>

        <div id="printable-meeting-content"
             class="p-6 text-gray-900 font-sans text-[14px] leading-6 print:bg-white print:text-black print:p-6 print:text-[12px]">


            {{-- Meeting Overview --}}
            <h2 class="text-2xl font-bold text-center mb-6 no-print">
                {{ __('عنوان جلسه:') }} {{ $meeting->title ?? '---' }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                {{-- Boss Info --}}
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-md">
                    <h4 class="text-base font-semibold mb-3 text-gray-900 dark:text-white">{{__('رئیس جلسه')}}</h4>
                    <div class="space-y-1 text-sm">
                        <p><span class="font-medium">{{__('نام:')}}</span>
                            <span
                                data-role="boss-name">{{ optional($meeting->boss->user_info)->full_name ?? '---' }}</span>
                        </p>
                        <p><span class="font-medium">{{__('واحد:')}}</span>
                            <span
                                data-role="boss-unit">{{ optional(optional($meeting->boss->user_info)->department)->department_name ?? '---' }}</span>
                        </p>
                        <p><span class="font-medium">{{__('سمت:')}}</span>
                            <span
                                data-role="boss-position">{{ optional($meeting->boss->user_info)->position ?? '---' }}</span>
                        </p>
                    </div>
                </div>

                {{-- Scriptorium --}}
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-md">
                    <h4 class="text-base font-semibold mb-3 text-gray-900 dark:text-white">{{__('دبیر جلسه')}}</h4>
                    <div class="space-y-1 text-sm">
                        <p><span class="font-medium">{{__('نام:')}}</span>
                            <span
                                data-role="scriptorium-name">{{ optional($meeting->scriptorium->user_info)->full_name ?? '---' }}</span>
                        </p>
                        <p><span class="font-medium">{{__('واحد:')}}</span>
                            <span
                                data-role="scriptorium-unit">{{ optional(optional($meeting->scriptorium->user_info)->department)->department_name ?? '---' }}</span>
                        </p>
                        <p><span class="font-medium">{{__('سمت:')}}</span>
                            <span
                                data-role="scriptorium-position">{{ optional($meeting->scriptorium->user_info)->position ?? '---' }}</span>
                        </p>
                    </div>
                </div>
                {{-- Time / Location --}}
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-md">
                    <h4 class="text-base font-semibold mb-3 text-gray-900 dark:text-white">{{__('زمان و مکان')}}</h4>
                    <div class="space-y-1 text-sm">
                        <p>
                            <span class="font-medium">{{__('تاریخ:')}}</span>
                            <span data-role="meeting-date">{{ $meeting->date }}</span>
                        </p>
                        <p>
                            <span class="font-medium">{{__('ساعت:')}}</span>
                            <span data-role="meeting-time">{{ $meeting->time }}</span>
                        </p>
                        <p>
                            <span class="font-medium">{{__('مکان:')}}</span>
                            <span data-role="meeting-location">{{ $meeting->location }}</span>
                        </p>
                    </div>
                </div>
                {{-- Committee / Treat --}}
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-md">
                    <h4 class="text-base font-semibold mb-3 text-gray-900 dark:text-white">{{__('اطلاعات تکمیلی')}}</h4>
                    <div class="space-y-1 text-sm">
                        <p>
                            <span class="font-medium">{{__('واحد برگزار کننده:')}}</span>
                            <span data-role="meeting-unit">{{ $meeting->unit_held }}</span>
                        </p>
                        <p>
                            <span class="font-medium">{{__('پذیرایی:')}}</span>
                            <span data-role="meeting-treat">{{ $meeting->treat ? 'دارد' : 'ندارد' }}</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Task Card --}}
            <section class="mt-12 grid grid-cols-1 gap-8">
                @php
                    $taskUser = $meeting->tasks
                        ->flatMap(fn($task) => $task->taskUsers)
                        ->first(fn($tu) => $tu->id == request()->route('taskUser_id'));
                    $task = $taskUser?->task;
                @endphp

                @if ($taskUser && $task)
                    <article
                        class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6 transition-all hover:shadow-xl print:break-inside-avoid">

                        <!-- Title -->
                        <header class="mb-4 border-b border-dashed pb-4">
                            <h3 class="text-xl font-semibold text-indigo-700 mb-2 inline-block">{{ __('بند مذاکره:') }}</h3>
                            <span class="text-gray-800 leading-relaxed">{{ $task->body }}</span>
                        </header>

                        <!-- Task Info -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-4 text-sm text-gray-700">
                            <div class="md:col-span-1 print-task-item">
                                <span class="font-semibold text-gray-800">{{ __('اقدام کننده:') }}</span>
                                <span class="mt-1">{{ $taskUser->user->user_info->full_name ?? '---' }}</span>
                            </div>
                            <div class="md:col-span-1 print-task-item">
                                <span class="font-semibold text-gray-800">{{ __('مهلت اقدام:') }}</span>
                                <span class="mt-1">{{ $taskUser->time_out ?? '---' }}</span>
                            </div>
                            <div class="md:col-span-1 print-task-item">
                                <span class="font-semibold text-gray-800">{{ __('تاریخ انجام اقدام:') }}</span>
                                <span class="mt-1">{{ $taskUser->sent_date ?? '---' }}</span>
                            </div>

                            <!-- Files -->
                            <div class="md:col-span-2">
                                <div class="print:flex print:items-center print:gap-2 file-print">
                                    <span class="font-semibold text-gray-800">{{ __('فایل‌ها:') }}</span>

                                    @if ($taskUser->taskUserFiles->isEmpty())
                                        <span class="text-gray-400 text-sm m-0 print:m-0">{{ __('بدون فایل') }}</span>
                                    @endif
                                </div>

                                @if ($taskUser->taskUserFiles->isNotEmpty())
                                    <ul class="mt-2 list-disc list-inside space-y-1 text-blue-600 text-sm">
                                        @foreach ($taskUser->taskUserFiles as $file)
                                            <li>
                                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="hover:underline">
                                                    {{ $file->original_name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <!-- Action Description -->
                            <div class="md:col-span-3">
                                <span class="font-semibold text-gray-800">{{ __('شرح اقدام:') }}</span>
                                @if ($taskUser->body_task)
                                    <p class="mt-2 text-gray-700 leading-relaxed bg-gray-50 p-3 rounded-md border border-gray-100">
                                        {{ $taskUser->body_task }}
                                    </p>
                                @else
                                    <p class="mt-2 text-red-500 font-medium">{{ __('هنوز اقدامی ثبت نشده') }}</p>
                                @endif
                            </div>
                        </div>

                    </article>
                @else
                    <div class="text-center text-gray-500 text-base mt-6 italic">
                        {{ __('اطلاعاتی برای این اقدام یافت نشد.') }}
                    </div>
                @endif
            </section>



            {{-- Controls --}}
            <div class="no-print flex flex-wrap items-center gap-4 mt-10">
                <a href="{{ route('task.report.table') }}">
                    <x-cancel-button>
                        {{ __('بازگشت') }}
                    </x-cancel-button>
                </a>

                <button onclick="printTask('{{ $meeting->title }}', '{{ $userInfo->full_name }}')"
                        class=" px-4 py-2 bg-blue-500 text-white border border-transparent rounded-md font-semibold text-xs uppercase shadow-sm hover:bg-blue-600 hover:outline-none hover:ring-2 hover:ring-blue-500 hover:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-300">
                    {{ __('چاپ اطلاعات') }}
                </button>
            </div>

            <script src="{{ asset('js/printParticipantTask.js') }}"></script>


        </div>
    @endcan

</x-app-layout>
