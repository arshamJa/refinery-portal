@php use App\Models\Department; @endphp
<div>
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
                {{__('جدول دپارتمان')}}
            </span>
            </li>
        </x-breadcrumb>
        <x-sessionMessage name="status"/>

        <x-modal name="create">
            <form wire:submit="createNewDepartment">
                <div class="flex flex-row px-6 py-4 bg-gray-100 text-start">
                    {{__('ایجاد دپارتمان جدید')}}
                </div>
                <div class="px-6 py-4" dir="rtl">
                    <div class="mt-4 text-sm text-gray-600">
                        <div class="w-full">
                            <x-input-label for="department" :value="__('دپارتمان')"/>
                            <x-text-input wire:model="department" id="department" class="block my-2 w-full" type="text"
                                          autofocus required/>
                            <x-input-error :messages="$errors->get('department')" class="mt-2"/>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row justify-between px-6 py-4 bg-gray-100">
                    <x-secondary-button wire:click="close">
                        {{ __('لفو') }}
                    </x-secondary-button>
                    <x-primary-button type="submit">
                        {{ __('ثبت') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>
        <x-modal name="update">
            @if($departmentId)
                <form wire:submit="updateDep({{$departmentId}})">
                    <div class="flex flex-row px-6 py-4 bg-gray-100 text-start">
                        {{__('ویرایش دپارتمان')}}
                    </div>
                    <div class="px-6 py-4" dir="rtl">
                        <div class="mt-4 text-sm text-gray-600">
                            <div class="w-full">
                                <x-input-label for="department" :value="__('دپارتمان')"/>
                                <x-text-input wire:model="department" id="department" class="block my-2 w-full"
                                              type="text"
                                              autofocus/>
                                <x-input-error :messages="$errors->get('department')" class="mt-2"/>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-row justify-between px-6 py-4 bg-gray-100">
                        <x-secondary-button wire:click="close">
                            {{ __('لفو') }}
                        </x-secondary-button>
                        <x-primary-button type="submit">
                            {{ __('ثبت') }}
                        </x-primary-button>
                    </div>
                </form>
            @endif
        </x-modal>
        <x-modal name="delete">
            @if($departmentId)
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4" dir="rtl">
                    <div class="sm:flex sm:items-center">
                        <div
                            class="mx-auto shrink-0 flex items-center justify-center size-12 rounded-full bg-red-100 sm:mx-0 sm:size-10">
                            <svg class="size-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ms-4 sm:text-start">
                            <h3 class="text-sm text-gray-900 dark:text-gray-100">
                                {{ __('آیا مطمئن هستید که ') }} <span
                                    class="font-medium">{{$department}}</span> {{__('پاک شود ؟')}}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row justify-between px-6 gap-x-3 py-4 bg-gray-100">
                    <x-secondary-button wire:click="close">
                        {{ __('لغو') }}
                    </x-secondary-button>
                    <x-danger-button wire:click="delete({{$departmentId}})">
                        {{ __('حذف') }}
                    </x-danger-button>
                </div>
            @endif
        </x-modal>


        <form wire:submit="filterDepartments"
              class="flex flex-col sm:flex-row items-center justify-between gap-4 py-4 bg-white border-b border-gray-200 rounded-t-xl">
            <div class="grid gap-4 px-3 w-full sm:px-0 lg:grid-cols-6 items-end">
                <!-- Search Input -->
                <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                    <x-input-label for="search" value="{{ __('جست و جو') }}"/>
                    <x-search-input>
                        <x-text-input type="text" id="search" wire:model.debounce="search" class="block ps-10"
                                      placeholder="{{ __('عبارت مورد نظر را وارد کنید...') }}"/>
                    </x-search-input>
                </div>

                <!-- Status Filter -->
                <div class="col-span-6 sm:col-span-1 ">
                    <x-search-button>{{ __('جست و جو') }}</x-search-button>
                    @if($search !== '')
                        <x-view-all-link href="{{ route('organizations') }}">
                            {{ __('نمایش همه') }}
                        </x-view-all-link>
                    @endif

                </div>
                <!-- Search + Show All Buttons -->
                <div class="col-span-6 lg:col-span-3 flex justify-start md:justify-end flex-row gap-4 mt-4 lg:mt-0">
                    <x-dropdown>
                        <x-slot name="trigger">
                            <x-secondary-button class="flex items-center gap-2">
                                {{ __('عملیات') }}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5"
                                     stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                                </svg>
                            </x-secondary-button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link wire:click="openModalCreate"> {{__('افزودن دپارتمان')}}</x-dropdown-link>
                            <x-dropdown-link href="{{route('department.export')}}">Export</x-dropdown-link>
                            <x-dropdown-link wire:click="openModalImport">Import</x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </form>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-12">
            <x-table.table>
                <x-slot name="head">
                    <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                        @foreach (['#', 'دپارتمان', 'قابلیت'] as $th)
                            <x-table.heading
                                class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}">
                                {{ __($th) }}
                            </x-table.heading>
                        @endforeach
                    </x-table.row>
                </x-slot>
                <x-slot name="body">
                    @forelse($this->departments as $department)
                        <x-table.row wire:key="{{$department->id}}"
                                     class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-50">
                            <x-table.cell>{{ ($this->departments->currentPage() - 1) * $this->departments->perPage() + $loop->iteration }}</x-table.cell>
                            <x-table.cell>{{$department->department_name}}</x-table.cell>
                            <x-table.cell>
                                <x-primary-button class="ml-2" wire:click="openModalEdit({{$department->id}})">
                                    {{__('ویرایش')}}
                                </x-primary-button>
                                <x-danger-button wire:click="openModalDelete({{$department->id}})">
                                    {{__('حذف')}}
                                </x-danger-button>
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <tr class="px-4 py-3 border-b text-center">
                            <td colspan="6"
                                class="py-6 px-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">
                                {{__('رکوردی یافت نشد...')}}
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-table.table>
        </div>
        <div class="mt-2">
            {{ $this->departments->withQueryString()->links(data:['scrollTo'=>false]) }}
        </div>

    @endcan

</div>
