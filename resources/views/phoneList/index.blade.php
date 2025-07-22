@php use App\Enums\UserPermission;use App\Enums\UserRole;use App\Models\User;use App\Models\UserInfo; @endphp
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
            {{__('دفترچه تلفنی')}}
            </span>
            </li>
        </ol>
    </nav>

    <form method="GET" action="{{ route('phone-list.index') }}"
          class="flex flex-col sm:flex-row items-center justify-between gap-4 py-4 bg-white border-gray-200 rounded-t-xl">
        <div class="grid gap-4 px-3 w-full sm:px-0 lg:grid-cols-6 items-end">
            <!-- Search Input -->
            <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                <x-input-label for="search" value="{{ __('جست و جو') }}"/>
                <x-search-input>
                    <x-text-input type="text" id="search" name="search" class="block ps-10"
                                  placeholder="{{ __('عبارت مورد نظر را وارد کنید...') }}"/>
                </x-search-input>
            </div>
            <!-- Search + Show All Buttons -->
            <div class="lg:col-span-2 flex justify-start flex-row gap-4 mt-4 lg:mt-0">
                <x-search-button>{{ __('جست و جو') }}</x-search-button>
                @if ($originalUsersCount != $filteredUsersCount)
                    <x-view-all-link href="{{route('phone-list.index')}}">{{__('نمایش همه')}}</x-view-all-link>
                @endif
            </div>
            <div class="col-span-2 flex justify-end">
                <a href="{{route('phone-list.create')}}">
                    <x-primary-button type="button">
                        {{__('اقزودن ')}}
                    </x-primary-button>
                </a>
            </div>
        </div>
    </form>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-12">
        <x-table.table>
            <x-slot name="head">
                <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                    @foreach (['ردیف', 'نام و نام خانوادگی', 'دپارتمان','تلفن محل کار'] as $th)
                        <x-table.heading
                            class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}">
                            {{ __($th) }}
                        </x-table.heading>
                    @endforeach
                    @can('has-permission-and-role',[UserPermission::PHONE_PERMISSIONS->value,UserRole::ADMIN->value])
                        <x-table.heading
                            class="px-6 py-3 border-r border-gray-200 dark:border-gray-700">{{ __('تلفن همراه') }}</x-table.heading>
                        <x-table.heading
                            class="px-6 py-3 border-r border-gray-200 dark:border-gray-700">{{ __('تلفن منزل') }}</x-table.heading>
                        <x-table.heading
                            class="px-6 py-3 border-r border-gray-200 dark:border-gray-700">{{ __('قابلیت') }}</x-table.heading>
                    @endcan
                </x-table.row>
            </x-slot>
            <x-slot name="body">
                @forelse($userInfos as $userInfo)
                    <x-table.row
                        class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-50">
                        <x-table.cell>{{ ($userInfos->currentPage() - 1) * $userInfos->perPage() + $loop->iteration }}</x-table.cell>
                        <x-table.cell> {{ $userInfo->full_name }}</x-table.cell>
                        <x-table.cell>{{ $userInfo->department->department_name ?? 'بدون واحد'}}</x-table.cell>
                        <x-table.cell>{{ $userInfo->work_phone }}</x-table.cell>
                        @can('has-permission-and-role',[UserPermission::PHONE_PERMISSIONS->value,UserRole::ADMIN->value])
                            <x-table.cell> {{ $userInfo->phone }}</x-table.cell>
                            <x-table.cell>{{ $userInfo->house_phone }}</x-table.cell>
                            <x-table.cell>
                                <a href="{{route('phone-list.edit',$userInfo->id)}}">
                                    <x-secondary-button>
                                        {{__('ویرایش')}}
                                    </x-secondary-button>
                                </a>
                            </x-table.cell>
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
    </div>
    <div class="mt-2 mb-12">
        {{ $userInfos->withQueryString()->links(data: ['scrollTo' => false]) }}
    </div>




</x-app-layout>
