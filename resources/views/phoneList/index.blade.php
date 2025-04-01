<x-app-layout>
    <div class="max-w-screen-2xl mt-16">
        <div class="bg-white relative shadow-md sm:rounded-lg overflow-hidden">

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
                        <x-input-label for="phone">{{ __('تلفن همراه') }}</x-input-label>
                        <x-text-input type="text" name="phone" id="phone"/>
                    </div>
                    <div>
                        <x-input-label for="house_phone">{{ __('تلفن منزل') }}</x-input-label>
                        <x-text-input type="text" name="house_phone" id="house_phone"/>
                    </div>
                    <div>
                        <x-input-label for="work_phone">{{ __('تلفن محل کار') }}</x-input-label>
                        <x-text-input type="text" name="work_phone" id="work_phone"/>
                    </div>
                    @if($showAllColumns)
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
                    @endif

                </div>
                <div class="w-full flex gap-4 items-center px-3 pb-3">
                    <x-search-button>{{__('جست و جو')}}</x-search-button>
                    @if ($originalUsersCount != $filteredUsersCount)
                        <x-view-all-link href="{{route('phone-list.index')}}">{{__('نمایش همه')}}</x-view-all-link>
                    @endif
                </div>
            </form>
            <div class="pt-4 sm:px-10 sm:pt-6 shadow-md rounded-md">
                <table class="w-full text-sm text-gray-500 dark:text-gray-400">
                    <thead
                        class="text-sm text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="py-3">{{ __('ردیف') }}</th>
                        <th class="py-3">{{ __('دپارتمان') }}</th>
                        <th class="py-3">{{ __('نام و نام خانوادگی') }}</th>
                        <th class="py-3">{{ __('تلفن محل کار') }}</th>
                        @if($showAllColumns)
                            <th class="py-3">{{ __('تلفن همراه') }}</th>
                            <th class="py-3">{{ __('تلفن منزل') }}</th>
                            <th class="py-3"></th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($userInfos as $userInfo)
                        <tr class="py-3 border-b text-center">
                            <td class="py-4 whitespace-no-wrap text-sm leading-5">
                                {{ $loop->index+1 }}
                            </td>
                            <td class="py-4 whitespace-no-wrap text-sm leading-5">
                                {{ $userInfo->department->department_name ?? 'بدون واحد'}}
                            </td>
                            <td class="py-4 whitespace-no-wrap text-sm leading-5">
                                {{ $userInfo->full_name }}
                            </td>
                            <td class="py-4 whitespace-no-wrap text-sm leading-5">
                                {{ $userInfo->phone }}
                            </td>
                            @if($showAllColumns)
                                <td class="py-4 whitespace-no-wrap text-sm leading-5">
                                    {{ $userInfo->house_phone }}
                                </td>
                                <td class="py-4 whitespace-no-wrap text-sm leading-5">
                                    {{ $userInfo->work_phone }}
                                </td>
                            <td>
                                @can('update',$userInfo)
                                <a href="{{route('phone-list.edit',$userInfo->id)}}">
                                    <x-primary-button>
                                        {{__('ویرایش')}}
                                    </x-primary-button>
                                </a>
                                @endcan
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
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr class="py-3 border-b text-center">
                            <td colspan="7" class="py-4 whitespace-no-wrap text-sm leading-5">
                                {{ __('... رکوردی یافت نشد') }}
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <span class="p-2 mx-2">
                        {{ $userInfos->withQueryString()->links(data: ['scrollTo' => false]) }}
                </span>
            </div>
        </div>
    </div>

</x-app-layout>
