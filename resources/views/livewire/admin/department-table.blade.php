@php use App\Models\Department; @endphp
<div>
    <x-sessionMessage name="status"/>


    @can('create-department-organization')
        <x-modal name="create">
            <form wire:submit="createNewDepartment">
                <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-start">
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
    @endcan


    @can('update-department-organization')
        <x-modal name="update">
            @if($departmentId)
                <form wire:submit="updateDep({{$departmentId}})">
                    <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-start">
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
    @endcan

    @can('delete-department-organization')
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
    @endcan


    <x-template>
        <x-organizationDepartmentHeader/>


        <!-- Start coding here -->
        <div class="bg-white relative shadow-md sm:rounded-lg overflow-hidden">
            <div
                class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-3/6">
                    <form class="flex items-center">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500"
                                     fill="currentColor" viewbox="0 0 20 20"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <input wire:model.live.debounce.500ms="search" type="text" dir="rtl"
                                   placeholder="جست و جو ..."
                                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                    </form>
                </div>
                <div
                    class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <div class="flex items-center justify-center w-full md:w-auto gap-2">
                        @can('create-department-organization')
                            <a href="{{route('department.export')}}">
                                <x-secondary-button>
                                    Export
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-4 mr-1">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                                    </svg>
                                </x-secondary-button>
                            </a>
                            <x-secondary-button wire:click="openModalImport">
                                Import
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5"
                                     stroke="currentColor" class="size-4 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                </svg>
                            </x-secondary-button>

                            <x-primary-button wire:click="openModalCreate">
                                {{__('دپارتمان')}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-4 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            </x-primary-button>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto" dir="rtl">
                <x-table.table>
                    <x-slot name="head">
                        <th class="px-4 py-3">{{__('ردیف')}}</th>
                        <th class="px-4 py-3">{{__('دپارتمان')}}</th>
                        <th class="px-4 py-3">{{__('قابلیت')}}</th>
                    </x-slot>

                    <x-slot name="body">
                        @forelse($this->departments as $department)
                            <tr class="px-4 py-3 border-b text-center"
                                wire:key="{{$department->id}}">
                                <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">
                                    {{$loop->index+1}}
                                </td>
                                <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">
                                    {{$department->department_name}}
                                </td>
                                <td class="px-4 py-4 flex gap-x-3 justify-center whitespace-no-wrap text-sm leading-5 text-coll-gray-900">
                                    @can('update-department-organization')
                                        <x-primary-button class="flex gap-x-1"
                                                          wire:click="openModalEdit({{$department->id}})">
                                            {{__('ویرایش')}}
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                            </svg>
                                        </x-primary-button>
                                    @endcan
                                    @can('delete-department-organization')
                                        <x-danger-button wire:click="openModalDelete({{$department->id}})">
                                            {{__('حذف')}}
                                        </x-danger-button>
                                    @endcan
                                </td>
                            </tr>
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
                <nav
                    class="flex flex-col md:flex-row mt-8 justify-between items-start md:items-center space-y-3 md:space-y-0 p-4">
                    {{ $this->departments->withQueryString()->links(data:['scrollTo'=>false]) }}
                </nav>
            </div>
        </div>

    </x-template>


</div>
