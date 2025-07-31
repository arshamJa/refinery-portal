<x-app-layout>

    @can('admin-role')
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
                {{__('تنظیمات راهبری')}}
            </span>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                 stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            <li>
            <span
                class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                {{__('مدیریت دپارتمان/سامانه')}}
            </span>
            </li>
        </x-breadcrumb>


        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <!-- Form on the left or right -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg p-6 col-span-1">
                <form action="{{route('departments.organizations.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <header>
                        <h2 class="text-lg mb-4 font-medium text-gray-900 dark:text-gray-100">
                            {{ __('ارتباط دپارتمان با سامانه') }}
                        </h2>
                    </header>
                    <div class="my-2">
                        <x-input-label for="department" :value="__('دپارتمان')"/>
                       <x-select-input name="departmentId">
                           <option value="">...</option>
                           @foreach($departments as $department)
                               <option value="{{ $department->id }}" @selected(old('departmentId') == $department->id)>
                                   {{ $department->department_name }}
                               </option>
                           @endforeach
                       </x-select-input>
                        <x-input-error :messages="$errors->get('department')" class="my-2"/>
                    </div>
                    <div id="organizations_dropdown" data-users='@json($orgs)' class="relative w-full mt-2 mb-4" style="direction: rtl;">
                        <x-input-label class="mb-2" :value="__('سامانه')" />
                        <button id="organizations-dropdown-btn" type="button"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-right text-gray-800 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center">
                            <span id="organizations-selected-text" class="truncate">انتخاب سامانه‌ها</span>
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="organizations-dropdown-menu"
                             class="hidden absolute mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-lg z-10">
                            <div class="px-4 py-2">
                                <input id="organizations-dropdown-search" type="text" placeholder="جست و جو"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                            </div>
                            <ul id="organizations-dropdown-list" class="max-h-48 overflow-auto"></ul>
                            <div id="organizations-no-result" class="px-4 py-2 text-gray-500 hidden">موردی یافت نشد</div>
                        </div>
                        <div id="organizations-selected-container" class="mt-2 flex flex-wrap gap-2"></div>
                        <input type="hidden" name="organization_ids" id="organizations-hidden-input"
                               value='{{ json_encode(old("organization_ids") ?? []) }}'>
                        <x-input-error :messages="$errors->get('organization_ids')" class="mt-2"/>
                    </div>
                    <x-primary-button type="submit">
                        {{ __('ذخیره') }}
                    </x-primary-button>
                </form>
            </div>

            <!-- Table and Search on the right -->
            <div class="col-span-1 lg:col-span-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg p-4">
                <form method="GET" action="{{ route('organization.department.manage') }}"
                      class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white border-gray-200 rounded-t-xl">
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
                            <x-search-button>{{__('جست و جو')}}</x-search-button>
                            @if ($originalOrganizationsCount != $filteredOrganizationsCount)
                                <x-view-all-link
                                    href="{{route('organization.department.manage')}}">  {{__('نمایش همه')}}</x-view-all-link>
                            @endif
                        </div>
                    </div>
                </form>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-12 mt-4">
                    <x-table.table>
                        <x-slot name="head">
                            <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                                @foreach (['ردیف', 'دپارتمان','سامانه'] as $th)
                                    <x-table.heading
                                        class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}">
                                        {{ __($th) }}
                                    </x-table.heading>
                                @endforeach
                            </x-table.row>
                        </x-slot>
                        <x-slot name="body">
                            @php
                                // Group organizations by department_id
                                $groupedByDepartment = $organizations->groupBy('department_id');
                            @endphp

                            @forelse($groupedByDepartment as $departmentId => $orgs)
                                <x-table.row
                                    class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-50">
                                    <x-table.cell>{{ $loop->iteration }}</x-table.cell>
                                    <x-table.cell>{{ $orgs->first()->department->department_name ?? '---' }}</x-table.cell>
                                    <x-table.cell>
                                        @foreach($orgs as $org)
                                            {{ $org->organization_name }}{{ !$loop->last ? '، ' : '' }}
                                        @endforeach
                                    </x-table.cell>
                                </x-table.row>
                            @empty
                                <x-table.row>
                                    <x-table.cell colspan="4" class="py-6">
                                        {{ __('رکوردی یافت نشد...') }}
                                    </x-table.cell>
                                </x-table.row>
                            @endforelse

                        </x-slot>
                    </x-table.table>
                </div>
                <div class="mt-2">
                    {{ $organizations->withQueryString()->links(data:['scrollTo'=>false]) }}
                </div>
            </div>
        </div>

    @endcan

</x-app-layout>
