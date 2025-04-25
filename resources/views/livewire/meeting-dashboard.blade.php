<div>
    <x-breadcrumb>
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
                {{__('جلسات')}}
            </span>
        </li>
    </x-breadcrumb>
    @can('create-meeting')
        <div class="mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-create-link href="{{route('meeting.create')}}">{{__('ایجاد جلسه جدید')}}</x-create-link>
                {{--                <a href="{{route('meetingsList')}}"--}}
                {{--                   class="bg-[#FCF7F8]  hover:ring-2 hover:ring-[#4332BD] hover:ring-offset-2 text-black shadow  flex gap-2 items-start transition duration-300 ease-in-out p-4 rounded-lg">--}}
                {{--                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
                {{--                         stroke="currentColor" class="size-5">--}}
                {{--                        <path stroke-linecap="round" stroke-linejoin="round"--}}
                {{--                              d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5"/>--}}
                {{--                    </svg>--}}
                {{--                    <h3 class="text-sm font-semibold"> {{__('لیست جلسات در حال برگزاری')}}</h3>--}}
                {{--                </a>--}}
                {{--                <a href="{{route('scriptorium.report')}}"--}}
                {{--                   class="bg-[#FCF7F8] hover:ring-2 hover:ring-[#4332BD] hover:ring-offset-2 text-black shadow  flex gap-2 items-start transition duration-300 ease-in-out p-4 rounded-lg">--}}
                {{--                    <span class="flex items-center gap-x-2">--}}
                {{--                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
                {{--                             stroke="currentColor" class="size-5">--}}
                {{--                            <path stroke-linecap="round" stroke-linejoin="round"--}}
                {{--                                  d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>--}}
                {{--                        </svg>--}}
                {{--                        <h3 class="text-sm font-semibold"> {{__('گزارش جلسات تشکیل شده')}}</h3>--}}
                {{--                    </span>--}}
                {{--                </a>--}}


                {{--                <a href="{{route('attended.meetings')}}"--}}
                {{--                   class="bg-[#FCF7F8] flex justify-between gap-2 hover:ring-2 hover:ring-[#4332BD] hover:ring-offset-2 text-black shadow  transition duration-300 ease-in-out p-4 rounded-lg">--}}
                {{--                    <span class="flex items-center gap-x-2">--}}
                {{--                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
                {{--                             stroke="currentColor" class="size-5">--}}
                {{--                            <path stroke-linecap="round" stroke-linejoin="round"--}}
                {{--                                  d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z"/>--}}
                {{--                        </svg>--}}
                {{--                        <h3 class="text-sm font-semibold"> {{__('جلساتی که در آن شرکت کردم')}}</h3>--}}
                {{--                    </span>--}}
                {{--                    --}}{{--                <span class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5">--}}
                {{--                    --}}{{--                    {{\App\Models\Task::where('user_id',auth()->user()->id)->where('is_completed',false)->count()}}--}}
                {{--                    --}}{{--                </span>--}}
                {{--                </a>--}}
            </div>
        </div>
    @endcan

    <div class="pt-4 px-10 sm:pt-6 border shadow-md rounded-md">

        <form wire:submit="filterMeetings"
              class="flex flex-col sm:flex-row items-center justify-between gap-4 px-4 py-4 bg-white border-b border-gray-200 rounded-t-xl">
            <div class="grid gap-4 px-3 sm:px-0 lg:grid-cols-6 items-end">
                <!-- Search Input -->
                <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                    <x-input-label for="search" value="{{ __('جست و جو') }}"/>
                    <x-search-input>
                        <x-text-input type="text" id="search" wire:model="search" class="block ps-10"
                                      placeholder="{{ __('عبارت مورد نظر را وارد کنید...') }}"/>
                    </x-search-input>
                </div>

                <!-- Status Filter -->
                <div class="col-span-6 sm:col-span-1">
                    <x-input-label for="statusFilter" value="{{ __('وضعیت جلسه') }}"/>
                    <x-select-input id="statusFilter" wire:model="statusFilter">
                        <option value="">{{__('همه وضعیت‌ها')}}</option>
                        <option value="0">{{__('در حال بررسی')}}</option>
                        <option value="1">{{__('جلسه لغو شد')}}</option>
                        <option value="-1">{{__('جلسه تشکیل می‌شود')}}</option>
                    </x-select-input>
                </div>

                <!-- Date Inputs (side-by-side) -->
                <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <x-input-label for="start_date" value="{{ __('تاریخ شروع') }}"/>
                            <x-date-input>
                                <x-text-input id="start_date" wire:model="start_date" class="block ps-10"/>
                            </x-date-input>
                        </div>
                        <div class="flex-1">
                            <x-input-label for="end_date" value="{{ __('تاریخ پایان') }}"/>
                            <x-date-input>
                                <x-text-input id="end_date" wire:model="end_date" class="block ps-10"/>
                            </x-date-input>
                        </div>
                    </div>
                </div>

                <!-- Search + Show All Buttons -->
                <div class="col-span-6 lg:col-span-2 flex justify-start flex-row gap-4 mt-4 lg:mt-0">
                    <x-search-button>{{ __('جست و جو') }}</x-search-button>
                    @if($search || $start_date || $statusFilter !== '')
                        <x-view-all-link href="{{ route('dashboard.meeting') }}">
                            {{ __('نمایش همه') }}
                        </x-view-all-link>
                    @endif
                </div>

                <!-- Export Button under the right group -->
                <div class="col-span-6 lg:col-start-5 lg:col-span-2 flex justify-start lg:justify-end mt-2">
                    <x-export-link wire:click.prevent="exportExcel">
                        {{ __('خروجی Excel') }}
                    </x-export-link>
                </div>
            </div>
        </form>
        <div class="pt-4 w-full overflow-x-auto overflow-y-hidden sm:pt-6 mb-4 bg-white">
            <x-table.table>
                <x-slot name="head">
                    <x-table.row>
                        @foreach ([
                                'ردیف','موضوع جلسه','دبیر جلسه','واحد سازمانی','تاریخ','ساعت','مکان','مشاهده اعضا','وضعیت جلسه','رویت صورتجلسه',''
                            ] as $th)
                            <x-table.heading>{{ __($th) }}</x-table.heading>
                        @endforeach
                    </x-table.row>
                </x-slot>
                <x-slot name="body">
                    @forelse($this->meetings as $meeting)
                        <x-table.row wire:key="meeting-{{ $meeting->id }}">
                            <x-table.cell>{{ ($this->meetings->currentPage() - 1) * $this->meetings->perPage() + $loop->iteration }}</x-table.cell>
                            <x-table.cell>{{$meeting->title}}</x-table.cell>
                            <x-table.cell>{{$meeting->scriptorium}}</x-table.cell>
                            <x-table.cell>{{$meeting->unit_organization}}</x-table.cell>
                            <x-table.cell>{{$meeting->date}}</x-table.cell>
                            <x-table.cell>{{$meeting->time}}</x-table.cell>
                            <x-table.cell>{{$meeting->location}}</x-table.cell>
                            @if(auth()->user()->user_info->full_name === $meeting->scriptorium)
                                <x-table.cell>
                                    <a href="{{route('presentUsers',$meeting->id)}}">
                                        <x-secondary-button>
                                            {{__('نمایش')}}
                                        </x-secondary-button>
                                    </a>
                                </x-table.cell>
                            @else
                                <x-table.cell>
                                    {{__('---')}}
                                </x-table.cell>
                            @endif
                            <x-table.cell>
                                @if($meeting->is_cancelled == '0')
                                    <span
                                        class="block w-full bg-yellow-400 text-xs text-black font-bold  px-4 py-1 rounded-xl m-0.5">
                                    {{__('درحال بررسی...')}}
                                    </span>
                                @elseif($meeting->is_cancelled == '1')
                                    <span
                                        class="block w-full bg-[#E96742] text-xs text-white font-bold  px-4 py-1 rounded-xl m-0.5">
                                    {{__('جلسه لغو شد')}}
                                </span>
                                @elseif($meeting->is_cancelled == '-1')
                                    <span
                                        class="block w-full bg-green-500 text-xs text-white font-bold  px-4 py-1 rounded-xl m-0.5">
                                    {{__('جلسه تشکیل میشود')}}
                                </span>
                                @endif
                            </x-table.cell>
                            <x-table.cell>
                                @if($meeting->is_cancelled == '-1')
                                    <a href="{{route('tasks.create',$meeting->id)}}">
                                        <x-primary-button>
                                            {{ __('نمایش') }}
                                        </x-primary-button>
                                    </a>
                                @endif
                            </x-table.cell>
                            <x-table.cell>
                                <x-dropdown>
                                    <x-slot name="trigger">
                                        <button class="hover:bg-gray-200 rounded-full p-1 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                 class="w-5 h-5 text-gray-600">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                                            </svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link wire:click.prevent="view({{$meeting->id}})">
                                            {{ __('نمایش') }}
                                        </x-dropdown-link>
                                        @if($meeting->is_cancelled != '1' and $meeting->is_cancelled != '-1')
                                            <x-dropdown-link href="#">
                                                {{ __('ویرایش') }}
                                            </x-dropdown-link>
                                        @endif
                                        {{--                                        @if( $meeting->is_cancelled == '0' or $meeting->is_cancelled == '1')--}}
                                        <x-dropdown-link wire:click.prevent="deleteMeeting({{$meeting->id}})"
                                                         class="text-red-600 ">
                                            {{ __('حذف') }}
                                        </x-dropdown-link>
                                        {{--                                        @endif--}}
                                    </x-slot>
                                </x-dropdown>
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.row>
                            <x-table.cell colspan="10" class="py-6">
                                {{__('رکوردی یافت نشد...')}}
                            </x-table.cell>
                        </x-table.row>
                    @endforelse
                </x-slot>
            </x-table.table>
            <span class="p-2 mx-2">
                    {{ $this->meetings->withQueryString()->links(data: ['scrollTo' => false]) }}
            </span>
        </div>
    </div>

    <x-modal name="view-meeting-modal" maxWidth="4xl">
        @if ($selectedMeeting)
            @php
                $guests = is_string($selectedMeeting->guest)
                    ? json_decode($selectedMeeting->guest, true)
                    : (is_array($selectedMeeting->guest) ? $selectedMeeting->guest : []);
                $innerGuests = $selectedMeeting->meetingUsers->where('is_guest', true);
                $participants = $selectedMeeting->meetingUsers->where('is_guest', false);
            @endphp

            <div class="p-6 max-h-[85vh] overflow-y-auto text-sm text-gray-800 dark:text-gray-200 space-y-8">
                {{-- Meeting Title --}}
                <div class="border-b pb-4">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $selectedMeeting->title }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ __('جزئیات جلسه') }}</p>
                </div>
                {{-- Meeting Info --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-[15px]">
                    <x-meeting-info label="{{ __('دبیرجلسه') }}" :value="$selectedMeeting->scriptorium"/>
                    <x-meeting-info label="{{ __('رئیس جلسه') }}" :value="$selectedMeeting->boss"/>
                    <x-meeting-info label="{{ __('واحد سازمانی') }}" :value="$selectedMeeting->unit_organization"/>
                    <x-meeting-info label="{{ __('سمت دبیرجلسه') }}" :value="$selectedMeeting->position_organization"/>
                    <x-meeting-info label="{{ __('مکان') }}" :value="$selectedMeeting->location"/>
                    <x-meeting-info label="{{ __('تاریخ') }}" :value="$selectedMeeting->date"/>
                    <x-meeting-info label="{{ __('زمان') }}" :value="$selectedMeeting->time"/>
                    <x-meeting-info label="{{ __('کمیته یا واحد برگزار کننده') }}"
                                    :value="$selectedMeeting->unit_held"/>
                    <x-meeting-info label="{{ __('پذیرایی') }}" :value="$selectedMeeting->treat ? 'دارد' : 'ندارد'"/>
                    <x-meeting-info label="{{ __('درخواست دهنده') }}" :value="$selectedMeeting->applicant"/>
                </div>

                {{-- Participants --}}
                <div>
                    <h3 class="text-xl font-bold border-b pb-2 mb-4">{{ __('اعضای جلسه') }}</h3>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($participants as $user)
                            <x-meeting-card
                                :name="$user->user->user_info->full_name ?? 'N/A'"
                                :unit="$user->user->user_info->department->department_name ?? 'N/A'"
                                :position="$user->user->user_info->position ?? 'N/A'"/>
                        @endforeach
                    </div>
                </div>

                {{-- Outer Guests --}}
                <div>
                    <h3 class="text-xl font-bold border-b pb-2 mb-4">{{ __('مهمانان برون سازمانی') }}</h3>
                    @if (!empty($guests))
                        <ul class="space-y-2">
                            @foreach ($guests as $guest)
                                <li class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg text-sm shadow">
                                    <div class="font-medium">
                                        {{ __('نام:') }} {{ $guest['name'] ?? 'نام ندارد' }}
                                    </div>
                                    @if (!empty($guest['companyName']))
                                        <div class="text-gray-500">{{ __('شرکت:') }} {{ $guest['companyName'] }}</div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">{{ __('مهمان برون سازمانی وجود ندارد') }}</p>
                    @endif
                </div>

                {{-- Inner Guests --}}
                <div>
                    <h3 class="text-xl font-bold border-b pb-2 mb-4">{{ __('مهمانان درون سازمانی') }}</h3>
                    @if ($innerGuests->isNotEmpty())
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($innerGuests as $user)
                                <x-meeting-card
                                    :name="$user->user->user_info->full_name ?? 'N/A'"
                                    :unit="$user->user->user_info->department->department_name ?? 'N/A'"
                                    :position="$user->user->user_info->position ?? 'N/A'"/>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">{{ __('مهمان درون سازمانی وجود ندارد') }}</p>
                    @endif
                </div>

            </div>
        @endif
    </x-modal>

    <x-modal name="delete-meeting-modal">
        @if ($selectedMeeting)
            <form method="POST" action="{{ route('meeting.destroy', $selectedMeeting->id) }}">
                @csrf
                @method('DELETE')
                <div class="flex flex-row px-6 py-4 bg-gray-100 text-start">
                    <p class="text-xl font-bold text-red-600 dark:text-red-400">
                        {{ __('آیا از حذف جلسه زیر اطمینان دارید؟') }}
                    </p>
                </div>
                <div class="px-6 py-4" dir="rtl">
                    <div class="mt-4 text-sm text-gray-600">
                        {{--                         Show a bit of meeting info for confirmation--}}
                        <ul class="list-disc list-inside text-sm space-y-2">
                            <li><strong>{{ __('عنوان جلسه:') }}</strong> {{ $selectedMeeting->title }}</li>
                            <li><strong>{{ __('تاریخ:') }}</strong> {{ $selectedMeeting->date }}</li>
                            <li><strong>{{ __('زمان:') }}</strong> {{ $selectedMeeting->time }}</li>
                        </ul>

                        <p class="text-xs text-red-500 dark:text-red-300 mt-2">
                            {{ __('توجه: این عمل غیرقابل بازگشت است و تمام اطلاعات مرتبط با این جلسه (شامل اعضا و مهمانان) حذف خواهند شد.') }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-row justify-between px-6 py-4 bg-gray-100">
                    <x-danger-button type="submit">
                        {{ __('حذف جلسه') }}
                    </x-danger-button>
                    <x-secondary-button x-on:click="$dispatch('close')" class="text-gray-700 dark:text-gray-300">
                        {{ __('لغو') }}
                    </x-secondary-button>
                </div>
            </form>
        @endif
    </x-modal>


</div>
