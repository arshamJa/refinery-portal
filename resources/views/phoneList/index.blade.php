@php use App\Enums\UserRole;use App\Models\User;use App\Models\UserInfo; @endphp
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
                <a href="{{route('dashboard')}}"
                   class="inline-flex items-center px-2 py-1.5 cursor-default active-breadcrumb space-x-1.5 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75v-4.5m0 4.5h4.5m-4.5 0 6-6m-3 18c-8.284 0-15-6.716-15-15V4.5A2.25 2.25 0 0 1 4.5 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44-.054.902-.417 1.173l-1.293.97a1.062 1.062 0 0 0-.38 1.21 12.035 12.035 0 0 0 7.143 7.143c.441.162.928-.004 1.21-.38l.97-1.293a1.125 1.125 0 0 1 1.173-.417l4.423 1.106c.5.125.852.575.852 1.091V19.5a2.25 2.25 0 0 1-2.25 2.25h-2.25Z" />
                    </svg>
                    <span>{{__('دفترچه تلفنی')}}</span>
                </a>
            </li>
        </ol>
    </nav>
    <div class="bg-white px-3 relative shadow-md sm:rounded-lg overflow-hidden">
        <form method="GET" action="{{ route('phone-list.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 px-3 pt-3">
                <div>
                    <x-input-label for="department">{{ __('دپارتمان') }}</x-input-label>
                    <x-text-input type="text" name="department" id="department" value="{{ request('department') }}"/>
                </div>
                <div>
                    <x-input-label for="full_name">{{ __('نام و نام حانوادگی') }}</x-input-label>
                    <x-text-input type="text" name="full_name" id="full_name" value="{{ request('full_name') }}"/>
                </div>
                <div>
                    <x-input-label for="work_phone">{{ __('تلفن محل کار') }}</x-input-label>
                    <x-text-input type="text" name="work_phone" id="work_phone" value="{{ request('work_phone') }}"/>
                </div>
                @can('viewAny',User::class)
                    <div>
                        <x-input-label for="phone">{{ __('تلفن همراه') }}</x-input-label>
                        <x-text-input type="text" name="phone" id="phone" value="{{ request('phone') }}"/>
                    </div>
                    <div>
                        <x-input-label for="house_phone">{{ __('تلفن منزل') }}</x-input-label>
                        <x-text-input type="text" name="house_phone" id="house_phone"
                                      value="{{ request('house_phone') }}"/>
                    </div>
                    <div>
                        <x-label for="role">{{ __('نقش') }}</x-label>
                        <select name="role" id="role"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                            <option value="">{{ __('همه نقش ها') }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endcan

            </div>
            <div class="w-full flex gap-4 items-center px-3 pb-3">
                <x-search-button>{{__('جست و جو')}}</x-search-button>
                @if ($originalUsersCount != $filteredUsersCount)
                    <x-view-all-link href="{{route('phone-list.index')}}">{{__('نمایش همه')}}</x-view-all-link>
                @endif
            </div>
        </form>
        <div class="pt-4 overflow-x-auto overflow-y-hidden sm:pt-6 bg-white pb-10">
            <x-table.table>
                <x-slot name="head">
                    @foreach (['ردیف', 'دپارتمان', 'نام و نام خانوادگی','تلفن محل کار'] as $th)
                        <x-table.heading>{{ __($th) }}</x-table.heading>
                    @endforeach
                    @can('viewAny',User::class)
                        <x-table.heading>{{ __('تلفن همراه') }}</x-table.heading>
                        <x-table.heading>{{ __('تلفن منزل') }}</x-table.heading>
                        <x-table.heading>{{ __('قابلیت') }}</x-table.heading>
                    @endcan
                </x-slot>
                <x-slot name="body">
                    @forelse($userInfos as $userInfo)
                        <x-table.row>
                            <x-table.cell>{{ ($userInfos->currentPage() - 1) * $userInfos->perPage() + $loop->iteration }}</x-table.cell>
                            <x-table.cell>{{ $userInfo->department->department_name ?? 'بدون واحد'}}</x-table.cell>
                            <x-table.cell> {{ $userInfo->full_name }}</x-table.cell>
                            <x-table.cell>{{ $userInfo->work_phone }}</x-table.cell>
                            @can('viewAny',User::class)
                                <x-table.cell> {{ $userInfo->phone }}</x-table.cell>
                                <x-table.cell>{{ $userInfo->house_phone }}</x-table.cell>
                                <x-table.cell>
                                    <a href="{{route('phone-list.edit',$userInfo->id)}}">
                                        <x-primary-button>
                                            {{__('ویرایش')}}
                                        </x-primary-button>
                                    </a>
                                </x-table.cell>
                                {{--                                @endcan--}}
                                {{--                                <x-dropdown>--}}
                                {{--                                    <x-slot name="trigger">--}}
                                {{--                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"--}}
                                {{--                                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"--}}
                                {{--                                             class="size-6 hover:cursor-pointer">--}}
                                {{--                                            <path stroke-linecap="round" stroke-linejoin="round"--}}
                                {{--                                                  d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>--}}
                                {{--                                        </svg>--}}
                                {{--                                    </x-slot>--}}
                                {{--                                    <x-slot name="content">--}}
                                {{--                                        <x-dropdown-link--}}
                                {{--                                            href="{{route('users.show',$userInfo->id)}}">--}}
                                {{--                                            {{__('نمایش')}}--}}
                                {{--                                        </x-dropdown-link>--}}
                                {{--                                        <x-dropdown-link--}}
                                {{--                                            href="{{route('users.edit',$userInfo->id)}}">--}}
                                {{--                                            {{__('ویرایش')}}--}}
                                {{--                                        </x-dropdown-link>--}}
                                {{--                                    </x-slot>--}}
                                {{--                                </x-dropdown>--}}
                            @endcan
                        </x-table.row>

                    @empty
                        <x-table.row>
                            <x-table.cell colspan="7" class="py-6">
                                {{__('رکوردی یافت نشد...')}}
                            </x-table.cell>
                        </x-table.row>
                    @endforelse
                </x-slot>
            </x-table.table>
            <span class="p-2 mx-2">
                {{ $userInfos->withQueryString()->links(data: ['scrollTo' => false]) }}
            </span>
        </div>
    </div>


</x-app-layout>
