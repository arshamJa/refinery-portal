@php use App\Models\UserInfo; @endphp
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
                            {{__('جدول کاربران')}}
                        </span>
            </li>
        </ol>
    </nav>

    <!-- Start coding here -->

    <div class="bg-white px-3 relative shadow-md sm:rounded-lg overflow-hidden">
        <form method="GET" action="{{ route('users.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 px-3 pt-3">
                <div>
                    <x-input-label for="full_name">{{ __('نام و نام حانوادگی') }}</x-input-label>
                    <x-text-input type="text" name="full_name" id="full_name" value="{{ request('full_name') }}"/>
                </div>
                <div>
                    <x-input-label for="p_code">{{ __('کد پرسنلی') }}</x-input-label>
                    <x-text-input type="text" name="p_code" id="p_code" value="{{ request('p_code') }}"/>
                </div>
                <div>
                    <x-input-label for="n_code">{{ __('کد ملی') }}</x-input-label>
                    <x-text-input type="text" name="n_code" id="n_code" value="{{ request('n_code') }}"/>
                </div>
                <div>
                    <x-input-label for="position">{{ __('سمت') }}</x-input-label>
                    <x-text-input type="text" name="position" id="position" value="{{ request('position') }}"/>
                </div>
                <div>
                    <x-input-label for="department_name">{{ __('دپارتمان') }}</x-input-label>
                    <x-text-input type="text" name="department_name" id="department_name" value="{{ request('department_name') }}"/>
                </div>
                <div>
                    <x-input-label for="permission_name">{{ __('نام دسترسی') }}</x-input-label>
                    <x-text-input type="text" name="permission_name" id="permission_name" value="{{ request('permission_name') }}"/>
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

            </div>
            <div class="w-full flex gap-4 items-center px-3 pb-3">
                <x-search-button>{{__('جست و جو')}}</x-search-button>
                @if ($originalUsersCount != $filteredUsersCount)
                    <x-view-all-link href="{{route('users.index')}}">{{__('نمایش همه')}}</x-view-all-link>
                @endif
            </div>
        </form>

        <div class="pt-4 overflow-x-auto overflow-y-hidden sm:pt-6 bg-white pb-10">
            <x-table.table>
                <x-slot name="head">
                    @foreach (['ردیف', 'نقش', 'نام و نام خانوادگی', 'سطح دسترسی', 'کد پرسنلی', 'کد ملی', 'سمت', 'دپارتمان', 'قابلیت'] as $th)
                        <x-table.heading>{{ __($th) }}</x-table.heading>
                    @endforeach
                </x-slot>
                <x-slot name="body">
                    @forelse($userInfos as $userInfo)
                        <x-table.row>
                            <x-table.cell>{{ ($userInfos->currentPage() - 1) * $userInfos->perPage() + $loop->iteration }}</x-table.cell>
                            <x-table.cell>
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                           @if($userInfo->user->roles && $userInfo->user->roles->isNotEmpty())
                                    {{ $userInfo->user->roles->pluck('name')->implode(', ') }}
                                @else
                                    {{ __('بدون نقش') }}
                                @endif
                          </span>
                            </x-table.cell>
                            <x-table.cell>{{ $userInfo->full_name }}</x-table.cell>
                            <x-table.cell>
                                @if ($userInfo->display_permission)
                                    <span
                                        class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full m-0.5">
                                        {{ $userInfo->display_permission }}
                                    </span>
                                    @if ($userInfo->more_permissions_count > 0)
                                        <span
                                            class="inline-block bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full m-0.5">
                                        +{{ $userInfo->more_permissions_count }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400">ندارد</span>
                                @endif
                            </x-table.cell>
                            <x-table.cell>{{ $userInfo->user->p_code }}</x-table.cell>
                            <x-table.cell>{{ $userInfo->n_code }}</x-table.cell>
                            <x-table.cell>{{ $userInfo->position }}</x-table.cell>
                            <x-table.cell>{{ $userInfo->department->department_name ?? __('دپارتمان وجود ندارد') }}</x-table.cell>
                            <x-table.cell>
                                @can('viewUserTable', UserInfo::class)
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
                                            <x-dropdown-link href="{{ route('users.show', $userInfo->id) }}">
                                                {{ __('نمایش') }}
                                            </x-dropdown-link>
                                            <x-dropdown-link href="{{ route('users.edit', $userInfo->id) }}">
                                                {{ __('ویرایش') }}
                                            </x-dropdown-link>
                                            <button wire:click="openModalDelete({{ $userInfo->id }})"
                                                    class="block w-full px-4 py-2 text-start text-sm text-red-600 hover:bg-red-100">
                                                {{ __('حذف') }}
                                            </button>
                                        </x-slot>
                                    </x-dropdown>
                                @endcan
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.row>
                            <x-table.cell colspan="9" class="py-6">
                                {{__('رکوردی یافت نشد...')}}
                            </x-table.cell>
                        </x-table.row>
                    @endforelse
                </x-slot>
            </x-table.table>

            <div class="p-2 mx-2">
                {{ $userInfos->withQueryString()->links(data: ['scrollTo' => false]) }}
            </div>

        </div>
    </div>
</x-app-layout>
