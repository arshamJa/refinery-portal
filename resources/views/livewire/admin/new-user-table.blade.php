<div>
    @can('delete-user')
        <x-modal name="delete">
            @if($user_name)
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
                                    class="font-medium">{{$user_name}}</span> {{__('پاک شود ؟')}}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row justify-between px-6 gap-x-3 py-4 bg-gray-100">
                    <x-secondary-button wire:click="close">
                        {{ __('لغو') }}
                    </x-secondary-button>
                    <x-danger-button wire:click="delete({{$userInfo_id}})">
                        {{ __('حذف') }}
                    </x-danger-button>
                </div>
            @endif
        </x-modal>
    @endcan
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
                    @can('create-user')
                        <a href="{{Illuminate\Support\Facades\URL::signedRoute('newUser.create')}}">
                            <x-primary-button>
                                {{__('ساخت کاربر جدید')}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            </x-primary-button>
                        </a>
                    @endcan
                </div>
            </div>
        </div>


        <div class="pt-4 sm:px-10 sm:pt-6 border shadow-md rounded-md">
            <table wire:loading.class.delay="opacity-70"
                   class="w-full text-sm text-left mb-6 rtl:text-right text-gray-500 dark:text-gray-400">
                <thead
                    class="text-sm text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">{{__('ردیف')}}</th>
                    <th class="px-4 py-3">{{__('نقش')}}</th>
                    <th class="px-4 py-3">{{__('نام و نام خانوادگی')}}</th>
                    <th class="px-4 py-3">{{__('کد پرسنلی')}}</th>
                    <th class="px-4 py-3">{{__('کد ملی')}}</th>
                    <th class="px-4 py-3">{{__('سمت')}}</th>
                    <th class="px-4 py-3">{{__('دپارتمان')}}</th>
                    <th class="px-4 py-3">{{__('قابلیت')}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($this->userInfos as $userInfo)
                    <tr class="px-4 py-3 border-b text-center" wire:key="{{$userInfo->id}}">
                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$userInfo->id}}</td>
                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$userInfo->user->role}}</td>
                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$userInfo->full_name}}</td>
                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$userInfo->user->p_code}}</td>
                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$userInfo->n_code}}</td>
                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$userInfo->position}}</td>
                        <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">
                            @if($userInfo->department_id)
                                {{$userInfo->department->department_name}}
                            @else
                                {{__('دپارتمان وجود ندارد')}}
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-no-wrap flex flex-row gap-x-2 text-sm leading-5 text-coll-gray-900">
                            <x-dropdown>
                                <x-slot name="trigger">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                         class="size-6 hover:cursor-pointer">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                                    </svg>
                                </x-slot>
                                <x-slot name="content">
                                    @can('view-user')
                                        <x-dropdown-link
                                            href="{{Illuminate\Support\Facades\URL::signedRoute('newUser.show',$userInfo->id)}}">
                                            {{__('نمایش')}}
                                        </x-dropdown-link>

                                    @endcan
                                    @can('update-user')
                                        <x-dropdown-link
                                            href="{{Illuminate\Support\Facades\URL::signedRoute('newUser.edit',$userInfo->id)}}">
                                            {{__('ویرایش')}}
                                        </x-dropdown-link>
                                    @endcan
                                    @can('delete-user')
                                        <button wire:click="openModalDelete({{$userInfo->id}})"
                                                class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
                                            {{__('حذف')}}
                                        </button>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                    </tr>
                @empty
                    <tr class="px-4 py-3 border-b text-center">
                        <td colspan="8" class="py-6">
                            {{__('رکوردی یافت نشد...')}}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <span class="p-2 mx-2">
                {{ $this->userInfos->withQueryString()->links(data:['scrollTo'=>false]) }}
            </span>
        </div>
    </div>

</div>
