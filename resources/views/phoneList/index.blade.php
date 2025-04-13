@php use App\Enums\UserRole;use App\Models\User;use App\Models\UserInfo; @endphp
<x-app-layout>

    <div class="bg-white px-3 relative shadow-md sm:rounded-lg overflow-hidden">
        <form method="GET" action="{{ route('phone-list.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 px-3 pt-3">
                <div>
                    <x-input-label for="department">{{ __('دپارتمان') }}</x-input-label>
                    <x-text-input type="text" name="department" id="department"/>
                </div>
                <div>
                    <x-input-label for="full_name">{{ __('نام و نام حانوادگی') }}</x-input-label>
                    <x-text-input type="text" name="full_name" id="full_name"/>
                </div>
                <div>
                    <x-input-label for="work_phone">{{ __('تلفن محل کار') }}</x-input-label>
                    <x-text-input type="text" name="work_phone" id="work_phone"/>
                </div>
                @can('viewAny',User::class)
                    <div>
                        <x-input-label for="phone">{{ __('تلفن همراه') }}</x-input-label>
                        <x-text-input type="text" name="phone" id="phone"/>
                    </div>
                    <div>
                        <x-input-label for="house_phone">{{ __('تلفن منزل') }}</x-input-label>
                        <x-text-input type="text" name="house_phone" id="house_phone"/>
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
                            <x-table.cell colspan="6" class="py-6">
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
