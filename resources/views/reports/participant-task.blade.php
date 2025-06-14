@php use App\Enums\UserPermission; @endphp
<x-app-layout>

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

    @can('refinery-report')
        {{-- Meeting Info --}}
        <div id="meeting-info"
             class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white border border-gray-300 rounded-xl p-6 shadow-sm text-gray-800 text-sm">

            @foreach([
                'واحد/کمیته' => $meeting->unit_held,
                'تهیه کننده (دبیر جلسه)' => $meeting->scriptorium,
                'رئیس جلسه' => $meeting->boss,
                'پیوست' => $meeting->tasks->flatMap->taskUsers->flatMap->taskUserFiles->count() > 0 ? 'دارد' : 'ندارد',
                'تاریخ جلسه' => $meeting->date,
                'زمان جلسه' => $meeting->end_time ? "{$meeting->time} - {$meeting->end_time}" : $meeting->time,
                'مکان جلسه' => $meeting->location,
                'موضوع جلسه' => $meeting->title,
                'سمت' => $userInfo->position ?? 'نامشخص',
                'دپارتمان/واحد' => $userInfo->department->department_name ?? 'نامشخص'
            ] as $label => $value)
                <div class="flex items-start gap-2">
                    <span class="font-semibold">{{ __($label) }}:</span>
                    <span class="text-gray-700">{{ $value }}</span>
                </div>
            @endforeach
        </div>

        {{-- Task Cards --}}
        <div class="mt-8 grid grid-cols-1 gap-6 mb-8">
            @forelse ($meeting->tasks as $task)
                @php
                    $taskUsers = $task->taskUsers->where('user_id', $userInfo->user_id);
                @endphp

                @foreach ($taskUsers as $taskUser)
                    <div class="bg-white border border-gray-200 rounded-xl shadow p-6 space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-indigo-700 mb-2">{{ __('خلاصه مذاکرات') }}</h3>
                            <p class="text-sm text-gray-800">{{ $task->body }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div><span
                                    class="font-medium text-gray-700">{{ __('اقدام کننده:') }}</span> {{ $taskUser->user->user_info->full_name ?? '---' }}
                            </div>
                            <div><span
                                    class="font-medium text-gray-700">{{ __('مهلت اقدام:') }}</span> {{ $taskUser->time_out ?? '---' }}
                            </div>
                            <div><span
                                    class="font-medium text-gray-700">{{ __('تاریخ انجام اقدام:') }}</span> {{ $taskUser->sent_date ?? '---' }}
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">{{ __('شرح اقدام:') }}</span>
                                @if ($taskUser->body_task)
                                    <p class="mt-1 text-gray-700 leading-relaxed">{{ $taskUser->body_task }}</p>
                                @else
                                    <p class="mt-1 text-red-500">{{ __('هنوز اقدامی ثبت نشده') }}</p>
                                @endif
                            </div>
                            <div class="md:col-span-2">
                                <span class="font-medium text-gray-700">{{ __('فایل‌ها:') }}</span>
                                @if ($taskUser->taskUserFiles->isNotEmpty())
                                    <ul class="list-disc list-inside mt-1 text-blue-600 text-xs space-y-1">
                                        @foreach ($taskUser->taskUserFiles as $file)
                                            <li>
                                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                                   class="hover:underline">
                                                    {{ $file->original_name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-400 text-xs mt-1">{{ __('بدون فایل') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @empty
                <div class="text-center text-gray-500 text-sm mt-6">
                    {{ __('هیچ اقدامی برای این کاربر در این جلسه ثبت نشده است.') }}
                </div>
            @endforelse
            <a href="{{route('task.report.table')}}">
                <x-secondary-button>
                    {{__('بازگشت')}}
                </x-secondary-button>
            </a>
        </div>
    @endcan

</x-app-layout>
