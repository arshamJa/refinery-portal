@php use App\Enums\MeetingUserStatus;use App\Enums\UserPermission;use App\Enums\UserRole; @endphp
<x-app-layout>

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
            <li>
                <a href="{{route('meeting.report.table')}}"
                   class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">

                    <span> {{__('گزارش جلسات شرکت')}}</span>
                </a>
                </span>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                 stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            <li>
                <span
                    class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
               <span>{{__('جزئیات جلسه')}}</span>
            </span>
            </li>
        </ol>
    </nav>

    @can('has-permission-and-role', [UserPermission::TASK_REPORT_TABLE,UserRole::ADMIN])
        <div id="meeting-summary"
             class="py-6 text-gray-900 font-sans text-[14px] leading-6 print:bg-white print:text-black print:p-6 print:text-[12px]">
            {{-- Meeting Overview --}}
            <h2 class="text-2xl font-bold text-center mb-6">
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

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg my-12">
                <x-table.table>
                    <caption class="caption-top">{{__('اعضای جلسه')}}</caption>
                    <x-slot name="head">
                        <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                            @foreach (['#','نام','واحد','سمت','وضعیت','جایگزین','دلیل غیبت'] as $th)
                                <x-table.heading
                                    class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}">
                                    {{ __($th) }}
                                </x-table.heading>
                            @endforeach
                        </x-table.row>
                    </x-slot>
                    <x-slot name="body">
                        @php
                            $replacementUserIds = $participants->pluck('replacement')->filter()->unique();
                            $rowNumber = 1;
                        @endphp

                        @foreach($participants as $user)
                            @php
                                $userInfo = optional($user->user->user_info);
                                $currentUserId = $userInfo?->user_id;
                            @endphp

                            @if ($replacementUserIds->contains($currentUserId))
                                @continue
                            @endif

                            <x-table.row
                                class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-50">
                                <x-table.cell>{{ $rowNumber++ }}</x-table.cell>
                                <x-table.cell>{{ $userInfo->full_name ?? 'N/A' }}</x-table.cell>
                                <x-table.cell>{{ $userInfo->department?->department_name ?? '---' }}</x-table.cell>
                                <x-table.cell>{{ $userInfo->position ?? '---' }}</x-table.cell>
                                <x-table.cell>
                                    @php
                                        $status = $user->is_present;
                                        $statusText = match($status) {
                                            MeetingUserStatus::IS_PRESENT->value => 'حاضر',
                                            MeetingUserStatus::IS_NOT_PRESENT->value => 'غایب',
                                            MeetingUserStatus::PENDING->value => 'نامشخص',
                                        };
                                    @endphp
                                    {{ $statusText }}
                                </x-table.cell>
                                <x-table.cell>{{ $user->replacementName() ?? '-' }}</x-table.cell>
                                <x-table.cell>{{ $status == MeetingUserStatus::IS_NOT_PRESENT->value ? $user->reason_for_absent : '-' }}</x-table.cell>
                            </x-table.row>
                        @endforeach
                    </x-slot>
                </x-table.table>
            </div>

            {{-- Inner Guests --}}
            <div class="mb-10 no-page-break">
                <h3 class="text-lg font-semibold border-b pb-1 mb-3">{{__('مهمانان درون سازمانی')}}</h3>
                @if ($innerGuests->isNotEmpty())
                    <ul class="space-y-1 list-disc mr-6">
                        @foreach($innerGuests as $user)
                            <li>
                                {{ optional($user->user->user_info)->full_name ?? 'N/A' }} -
                                {{ optional(optional($user->user->user_info)->department)->department_name ?? '---' }} -
                                {{ optional($user->user->user_info)->position ?? '---' }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">{{__('مهمان درون سازمانی وجود ندارد')}}</p>
                @endif
            </div>

            {{-- Outer Guests --}}
            <div class="mb-10 no-page-break">
                <h3 class="text-lg font-semibold border-b pb-1 mb-3">{{__('مهمانان برون سازمانی')}}</h3>
                @if (!empty($guests))
                    <ul class="space-y-1 list-disc mr-6">
                        @foreach ($guests as $guest)
                            <li>
                                {{ $guest['name'] ?? 'نام ندارد' }}
                                @if (!empty($guest['companyName']))
                                    - شرکت: {{ $guest['companyName'] }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">{{__('مهمان برون سازمانی وجود ندارد')}}</p>
                @endif
            </div>

            <button onclick="printMeetingSummary(`{{ $meeting->title }}`)"
                    class="px-4 py-2 mt-4 bg-blue-500 text-white border border-transparent rounded-md font-semibold text-xs uppercase shadow-sm hover:bg-blue-600 hover:outline-none hover:ring-2 hover:ring-blue-500 hover:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-300">
                {{__('چاپ اطلاعات جلسه')}}
            </button>

            <script src="{{ asset('js/printMeeting.js') }}"></script>
        </div>
    @endcan
</x-app-layout>
