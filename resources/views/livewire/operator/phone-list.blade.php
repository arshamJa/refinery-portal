<div>

    <x-sessionMessage name="status"/>



    @can('update-phone-list')
        <x-modal name="update">
            @if($userInfoId)
                <form wire:submit="updateInfos({{$userInfoId}})">
                    <div class="flex flex-row justify-end px-6 py-4 bg-gray-100" dir="rtl">
                        {{__('اطلاعات جدبد')}}
                    </div>
                    <div class="px-6 py-4">
                        <div class="my-2 text-sm text-gray-600">
                            <div class="w-full">
                                <x-input-label dir="rtl" for="phone" :value="__('تلفن همراه')"/>
                                <x-text-input wire:model="phone" id="phone" class="block my-2 w-full"
                                              type="text" maxlength="11" autofocus/>
                                <x-input-error :messages="$errors->get('phone')" class="my-2"/>

                                <x-input-label dir="rtl" for="house_phone" :value="__('تلفن منزل')"/>
                                <x-text-input wire:model="house_phone" id="house_phone"
                                              class="block my-2 w-full" type="text" autofocus/>
                                <x-input-error :messages="$errors->get('house_phone')" class="my-2"/>

                                <x-input-label dir="rtl" for="work_phone" :value="__('تلفن محل کار')"/>
                                <x-text-input wire:model="work_phone" id="work_phone"
                                              class="block my-2 w-full" type="text" autofocus/>
                                <x-input-error :messages="$errors->get('work_phone')" class="my-2"/>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-row justify-between px-6 py-4 bg-gray-100">
                        <x-primary-button type="submit">
                            {{ __('ثبت') }}
                        </x-primary-button>
                        <x-secondary-button wire:click="close">
                            {{ __('لفو') }}
                        </x-secondary-button>
                    </div>
                </form>
            @endif
        </x-modal>
    @endcan

{{--    <x-template>--}}
            <div class="max-w-screen-2xl mt-14">
                <div class="bg-white relative shadow-md sm:rounded-lg overflow-hidden">
                    <!-- Table Header -->
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">

                        <!-- Search Bar -->
                        <div class="w-full md:w-1/2">
                            <form class="flex items-center">
                                <label for="simple-search" class="sr-only">Search</label>
                                <div class="relative w-full">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                             fill="currentColor" viewbox="0 0 20 20"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                  d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <input type="text" wire:model.live="search" id="simple-search" dir="rtl"
                                           class="bg-gray-50  border border-gray-300 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full"
                                           placeholder="جست و جو" required="">
                                </div>
                            </form>
                        </div>

                        <!-- Filter -->
                        @can('view-phone-list')
                            <div
                                class="flex flex-col items-stretch justify-center px-2 flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3">
                                <div
                                    x-data="{ openFilter: false }" @click.outside="openFilter=false"
                                    class="relative text-sm">

                                    <button @click="openFilter=!openFilter"
                                            class="bg-white px-4 py-2 flex items-center justify-center w-full md:w-auto border hover:bg-gray-800 hover:text-white border-gray-800 transition rounded-md shadow-sm cursor-pointer">
                                                        <span class="flex justify-between items-center gap-1">
                                                               <svg xmlns="http://www.w3.org/2000/svg"
                                                                    aria-hidden="true"
                                                                    class="h-4 w-4 text-gray-400" viewbox="0 0 20 20"
                                                                    fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                              d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                                              clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ __('فیلتر') }}
                                                    </span>
                                    </button>

                                    <!-- Filter Options -->
                                    <ul x-show="openFilter" dir="rtl"
                                        class="absolute top-0 right-0 w-48 p-4 mt-10 bg-green-100 z-30 rounded-md shadow-sm"
                                        x-transition x-cloak>
                                        @if(auth()->user()->role === 'admin')
                                            <li class="flex items-center gap-x-1">
                                                <x-checkbox-input x-on:click="$wire.filter_roles('admin')"
                                                                  checked
                                                                  id="admin"
                                                                  value="admin"/>
                                                <x-input-label for="admin"/>{{ __('ادمین') }}
                                            </li>
                                        @endif
                                        <li class="flex items-center gap-x-1">
                                            <x-checkbox-input x-on:click="$wire.filter_roles('operator_phones')"
                                                              checked
                                                              id="operator"
                                                              value="operator"/>
                                            <x-input-label for="operator"/>{{ __('اپراتور') }}
                                        </li>
                                        <li class="flex items-center gap-x-1">
                                            <x-checkbox-input x-on:click="$wire.filter_roles('employee')"
                                                              checked
                                                              id="employee"
                                                              value="employee"/>
                                            <x-input-label for="employee"/>{{ __('کارمند') }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endcan
                    </div>
                    <!-- Table Body -->
                    <div class="overflow-x-auto" dir="ltr">
                        <!-- Table -->
                        <x-table.table>
                            <x-slot name="head">
                                <th class="py-3"></th>
                                @if(auth()->user()->role === 'admin')
                                    @if(in_array('phone', $infos))
                                        <th class="py-3">{{ __('تلفن همراه') }}</th>
                                    @endif
                                    @if(in_array('house_phone', $infos))
                                        <th class="py-3">{{ __('تلفن منزل') }}</th>
                                    @endif
                                @endif

                                @if(in_array('work_phone', $infos))
                                    <th class="py-3">{{ __('تلفن محل کار') }}</th>
                                @endif

                                @if(in_array('full_name', $infos))
                                    <th class="py-3">{{ __('نام و نام خانوادگی') }}</th>
                                @endif

                                @if(in_array('department_id', $infos))
                                    <th class="py-3">{{ __('دپارتمان') }}</th>
                                @endif

                                <th class="py-3">{{ __('ردیف') }}</th>
                            </x-slot>
                            <x-slot name="body">
                                @if($this->userInfos != null)
                                    @forelse($this->userInfos as $userInfo)
                                        <tr class="py-3 border-b text-center" wire:key="{{$userInfo->id}}">
                                            <td>
                                                @can('update-phone-list', $userInfo)
                                                    <x-primary-button
                                                        wire:click="openModalEdit({{$userInfo->id}})"
                                                        class="flex gap-x-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                             viewBox="0 0 24 24"
                                                             stroke-width="1.5" stroke="currentColor"
                                                             class="size-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                                        </svg>
                                                        {{__('ویرایش')}}
                                                    </x-primary-button>
                                                @endcan
                                            </td>
                                            @if(auth()->user()->role === 'admin')
                                                @if(in_array('phone', $infos))
                                                    <td class="py-4 whitespace-no-wrap text-sm leading-5">
                                                        {{ $userInfo->phone }}
                                                    </td>
                                                @endif
                                                @if(in_array('house_phone', $infos))
                                                    <td class="py-4 whitespace-no-wrap text-sm leading-5">
                                                        {{ $userInfo->house_phone }}
                                                    </td>
                                                @endif
                                            @endif
                                            @if(in_array('work_phone', $infos))
                                                <td class="py-4 whitespace-no-wrap text-sm leading-5">
                                                    {{ $userInfo->work_phone }}
                                                </td>
                                            @endif
                                            @if(in_array('full_name', $infos))
                                                <td class="py-4 whitespace-no-wrap text-sm leading-5">
                                                    {{ $userInfo->full_name }}
                                                </td>
                                            @endif
                                            @if(in_array('department_id', $infos))
                                                <td class="py-4 whitespace-no-wrap text-sm leading-5">
                                                    {{ $userInfo->department->department_name ?? 'بدون واحد'}}
                                                </td>
                                            @endif
                                            <td class="py-4 whitespace-no-wrap text-sm leading-5">
                                                {{ $userInfo->id }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="py-3 border-b text-center">
                                            <td colspan="7"
                                                class="py-4 whitespace-no-wrap text-sm leading-5">
                                                {{__('... رکوردی یافت نشد')}}
                                            </td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr class="py-3 border-b text-center">
                                        <td colspan="7"
                                            class="py-4 whitespace-no-wrap text-sm leading-5">
                                            {{__('... رکوردی یافت نشد')}}
                                        </td>
                                    </tr>
                                @endif
                            </x-slot>
                        </x-table.table>

                        <!-- Pagination -->
                        <nav dir="rtl"
                             class="flex flex-col md:flex-row mt-8 justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
                             aria-label="Table navigation">
                            @if($this->userinfos != null)
                                {{ $this->userInfos->withQueryString()->links(data: ['scrollTo' => false]) }}
                            @endif
                        </nav>

                    </div>

                </div>
        </div>
{{--    </x-template>--}}

</div>
