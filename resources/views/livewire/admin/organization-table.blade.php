<div>


    <x-organizationDepartmentHeader/>


    <form wire:submit="filterOrganizations"
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
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                            </svg>
                        </x-secondary-button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link
                            wire:click.prevent="openModalCreate">{{ __('افزودن سامانه') }}</x-dropdown-link>
                        <x-dropdown-link href="{{ route('organization.export') }}">Export</x-dropdown-link>
                        <x-dropdown-link wire:click.prevent="openImportModal">Import</x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>


        </div>
    </form>


    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-12">
        <x-table.table>
            <x-slot name="head">
                <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                    @foreach (['#','سامانه','لینک سامانه','قابلیت'] as $th)
                        <x-table.heading
                            class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}">
                            {{ __($th) }}
                        </x-table.heading>
                    @endforeach
                </x-table.row>
            </x-slot>
            <x-slot name="body">
                @forelse($this->organizations as $organization)
                    <x-table.row wire:key="organization-{{ $organization->id }}"
                                 class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-50">
                        <x-table.cell>{{ ($this->organizations->currentPage() - 1) * $this->organizations->perPage() + $loop->iteration }}</x-table.cell>
                        <x-table.cell>{{$organization->organization_name}}</x-table.cell>
                        <x-table.cell> {{$organization->url}}</x-table.cell>
                        <x-table.cell>
                            <x-edit-button class="ml-2" wire:click="openModalEdit({{$organization->id}})">
                                {{__('ویرایش')}}
                            </x-edit-button>
                            <x-danger-button wire:click="openModalDelete({{$organization->id}})">
                                {{__('حذف')}}
                            </x-danger-button>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <tr class="px-4 py-3 border-b text-center">
                        <td colspan="6" class="py-6">
                            {{__('رکوردی یافت نشد...')}}
                        </td>
                    </tr>
                @endforelse
            </x-slot>
        </x-table.table>
    </div>
    <div class="mt-2">
        {{ $this->organizations->withQueryString()->links(data:['scrollTo'=>false]) }}
    </div>


    {{--    @can('create-department-organization')--}}
    <x-modal name="create" maxWidth="2xl" :closable="false">
        <form wire:submit="createOrg" enctype="multipart/form-data">
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 bg-gray-100 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800">{{ __('ایجاد سامانه جدید') }}</h2>
                <a href="{{route('organizations')}}"
                   class="text-gray-400 hover:text-red-500 transition duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </a>
            </div>

            <!-- Body -->
            <div class="px-6 py-4 space-y-6 text-sm text-gray-800 dark:text-gray-200" dir="rtl">
                <div>
                    <x-input-label for="organization" :value="__('سامانه')"/>
                    <x-text-input wire:model="organization" id="organization" class="block my-2 w-full" type="text"
                                  autofocus/>
                    <x-input-error :messages="$errors->get('organization')" class="my-2"/>
                </div>

                <div>
                    <x-input-label for="url" :value="__('لینک سامانه')"/>
                    <x-text-input wire:model="url" id="url" value="{{ old('url') }}" dir="ltr"
                                  class="block my-2 w-full" type="text"/>
                    <x-input-error :messages="$errors->get('url')" class="my-2"/>
                </div>

                <!-- File Upload Section with progress -->
                <div x-data="{ progress: 0 }"
                     x-on:livewire-upload-start="progress = 0"
                     x-on:livewire-upload-progress="progress = $event.detail.progress"
                     x-on:livewire-upload-finish="progress = 100"
                     x-on:livewire-upload-error="progress = 0"
                     class="space-y-2">

                    <x-input-label for="image" :value="__('آپلود لوگو')"/>
                    <input type="file" wire:model="image"
                           id="image"
                           class="w-full text-sm text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none"/>

                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                        <div class="bg-green-500 h-2 transition-all duration-300"
                             :style="`width: ${progress}%`"></div>
                    </div>
                    <div x-text="progress + '%'" class="text-xs text-right text-gray-600 dark:text-gray-400"></div>

                    <div wire:loading wire:target="image" class="text-blue-500 text-sm">
                        {{ __('در حال آپلود فایل...') }}
                    </div>
                    <div wire:loading.remove wire:target="image" class="text-green-600 text-sm" x-cloak>
                        @if (!empty($image))
                            {{ __('فایل با موفقیت آپلود شد.') }}
                        @endif
                    </div>

                    <x-input-error :messages="$errors->get('image')"/>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-between px-6 py-4 bg-gray-100 border-t border-gray-200">
                <x-primary-button type="submit"
                                  wire:target="createOrg"
                                  wire:loading.attr="disabled"
                                  wire:loading.class="opacity-50">
                    <span wire:loading.remove wire:target="createOrg">{{ __('ثبت') }}</span>
                    <span wire:loading wire:target="createOrg">{{ __('در حال ثبت...') }}</span>
                </x-primary-button>
                <a href="{{route('organizations')}}">
                    <x-cancel-button>
                        {{ __('لغو') }}
                    </x-cancel-button>
                </a>
            </div>

        </form>
    </x-modal>

    <x-modal name="import">
        <form action="/organization_import" method="post" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-start">
                Excel {{__('انتخاب فایل')}}
            </div>
            <div class="px-6 py-4" dir="rtl">
                <div class="mt-4 text-sm text-gray-600">
                    <div class="w-full">
                        <x-input-label value="{{__('فایل اکسل')}}"/>
                        <x-text-input type="file" class="p-2 my-2" name="excel_file"/>
                        <x-input-error :messages="$errors->get('excel_file')" class="my-2"/>
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
    {{--    @endcan--}}

    {{--    @can('delete-department-organization')--}}
    <x-modal name="delete">
        @if($organizationId)
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
                                class="font-medium">{{$organization}}</span> {{__('پاک شود ؟')}}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="flex flex-row justify-between px-6 gap-x-3 py-4 bg-gray-100">
                <x-secondary-button wire:click="close">
                    {{ __('لغو') }}
                </x-secondary-button>
                <x-danger-button wire:click="delete({{$organizationId}})">
                    {{ __('حذف') }}
                </x-danger-button>
            </div>
        @endif
    </x-modal>
    {{--    @endcan--}}

    {{--    @can('update-department-organization')--}}
    <x-modal name="update">
        @if($organizationId)
            <form wire:submit="updateOrg({{$organizationId}})" enctype="multipart/form-data">
                <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-start">
                    {{__('ویرایش سامانه')}}
                </div>
                <div class="px-6 py-4" dir="rtl">
                    <div class="mt-4 text-sm text-gray-600">
                        <div class="w-full">
                            <x-input-label for="organization" :value="__('سامانه')"/>
                            <x-text-input wire:model="organization" id="organization" class="block my-2 w-full"
                                          type="text" autofocus/>
                            <x-input-error :messages="$errors->get('organization')" class="mt-2"/>

                            <x-input-label for="url" :value="__('لینک سامانه')"/>
                            <x-text-input wire:model="url" id="url" dir="ltr" class="block my-2 w-full"
                                          type="text" autofocus/>
                            <x-input-error :messages="$errors->get('url')" class="my-2"/>

                            <x-input-label for="newImage" :value="__('آپلود عکس')"/>
                            <x-text-input wire:model="newImage" id="newImage" class="block my-2 p-2 w-full"
                                          type="file" autofocus/>
                            <x-input-error :messages="$errors->get('newImage')" class="my-2"/>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row justify-between px-6 py-4 bg-gray-100">
                    <x-secondary-button wire:click="close">
                        {{ __('لفو') }}
                    </x-secondary-button>
                    <x-primary-button type="submit" class="px-6">
                        {{ __('ثبت') }}
                    </x-primary-button>
                </div>
            </form>
        @endif
    </x-modal>
    {{--    @endcan--}}


</div>
