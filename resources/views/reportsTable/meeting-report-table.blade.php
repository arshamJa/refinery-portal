@php use App\Enums\MeetingStatus; @endphp
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
            <li>
            <span
                class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
               <span> {{__('گزارش جلسات شرکت')}}</span>
            </span>
            </li>
        </ol>
    </nav>




    <form method="GET" action="{{ route('meeting.report.table') }}">
        <div class="grid gap-4 px-3 sm:px-0 lg:grid-cols-6 items-end">

            {{-- Search Input --}}
            <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                <x-input-label for="search" value="{{ __('جست و جو') }}"/>
                <x-search-input>
                    <x-text-input type="text" id="search" name="search" value="{{ request('search') }}"
                                  class="block ps-10"
                                  placeholder="{{ __('عبارت مورد نظر را وارد کنید...') }}"/>
                </x-search-input>
            </div>

            {{-- Status Filter --}}
            <div class="col-span-6 sm:col-span-1">
                <x-input-label for="statusFilter" value="{{ __('وضعیت جلسه') }}"/>
                <x-select-input id="statusFilter" wire:model="statusFilter">
                    <option value="">{{__('همه وضعیت‌ها')}}</option>
                    <option value="{{MeetingStatus::PENDING->value}}">{{__('در حال بررسی دعوتنامه')}}</option>
                    <option value="{{MeetingStatus::IS_CANCELLED->value}}">{{__('لغو شد')}}</option>
                    <option value="{{MeetingStatus::IS_NOT_CANCELLED->value}}">{{__('تشکیل می‌شود')}}</option>
                    <option value="{{MeetingStatus::IS_IN_PROGRESS->value}}">{{__('در حال برگزاری')}}</option>
                    <option value="{{MeetingStatus::IS_FINISHED->value}}">{{__('خاتمه یافت')}}</option>
                </x-select-input>
            </div>


            {{-- Date Inputs (Start & End) --}}
            <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <x-input-label for="start_date" value="{{ __('تاریخ شروع') }}"/>
                        <x-date-input>
                            <x-text-input type="text" id="start_date" name="start_date"
                                value="{{ request('start_date') }}" class="block ps-10"/>
                        </x-date-input>
                    </div>
                    <div class="flex-1">
                        <x-input-label for="end_date" value="{{ __('تاریخ پایان') }}"/>
                        <x-date-input>
                            <x-text-input type="text" id="end_date" name="end_date"
                                value="{{ request('end_date') }}" class="block ps-10"/>
                        </x-date-input>
                    </div>
                </div>
            </div>

            {{-- Search and Show All --}}
            <div class="col-span-6 lg:col-span-2 flex justify-start flex-row gap-4 mt-4 lg:mt-0">
                <x-search-button>{{ __('جست و جو') }}</x-search-button>
                @if(request()->hasAny(['search', 'start_date', 'end_date', 'statusFilter']))
                    <x-view-all-link href="{{ route('meeting.report.table') }}">
                        {{ __('نمایش همه') }}
                    </x-view-all-link>
                @endif
            </div>

        </div>
    </form>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-12 mt-4">
        <x-table.table>
            <x-slot name="head">
                <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                    @foreach (['#','موضوع جلسه','رییس جلسه','دبیر جلسه','تاریخ','ساعت','وضعیت جلسه','قابلیت'] as $th)
                        <x-table.heading
                            class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}">
                            {{ __($th) }}
                        </x-table.heading>
                    @endforeach
                </x-table.row>
            </x-slot>
            <x-slot name="body">
                @forelse($meetings as $meeting)
                    <x-table.row
                        class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-50">
                        <x-table.cell>{{ ($meetings->currentPage() - 1) * $meetings->perPage() + $loop->iteration }}</x-table.cell>
                        <x-table.cell>{{ $meeting->title }}</x-table.cell>
                        <x-table.cell>{{ $meeting->boss }}</x-table.cell>
                        <x-table.cell>{{ $meeting->scriptorium }}</x-table.cell>
                        <x-table.cell>{{ $meeting->date }}</x-table.cell>
                        <x-table.cell class="whitespace-nowrap">
                            {{ $meeting->time }}{{ $meeting->end_time ? ' - '.$meeting->end_time : '' }}
                        </x-table.cell>
                        <x-table.cell class="whitespace-nowrap">
                            <span class="{{ $meeting->status->badgeColor() }} text-xs font-medium px-3 py-1 rounded-lg">
                                {{ $meeting->status->label() }}
                            </span>
                        </x-table.cell>
                        <x-table.cell>
                            <a href="{{route('meeting.details.show',$meeting->id)}}">
                                <x-secondary-button>
                                    {{__('نمایش جزئیات')}}
                                </x-secondary-button>
                            </a>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="7" class="py-6">
                            {{ __('رکوردی یافت نشد...') }}
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-table.table>
    </div>
    <div class="mt-4">
        {{ $meetings->withQueryString()->links() }}
    </div>

</x-app-layout>
