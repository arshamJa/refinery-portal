<x-app-layout>
    <div>
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
                    {{__('جدول مدیریت نقش / تعیین سطح دسترسی')}}
                </span>
                </li>
            </ol>
        </nav>

        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">{{__('مدیریت نقش')}}</h2>
            <div class="bg-white relative shadow-md sm:rounded-lg overflow-hidden">
                <div
                    class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="relative w-full md:w-3/6">
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
                </div>
                <a href="{{route('role.create')}}">
                    <x-primary-button>
                    {{__('افزودن نقش')}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="size-4 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    </x-primary-button>
                </a>

                <div class="pt-4 sm:px-10 sm:pt-6 shadow-md rounded-md">
                    <table
                        class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead
                            class="text-sm text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">{{__('ردیف')}}</th>
                            <th class="px-4 py-3">{{__('نقش')}}</th>
                            <th class="px-4 py-3">{{__('لیست دسترسی')}}</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($roles as $role)
                            <tr class="px-4 py-3 border-b text-center">
                                <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$loop->index+1}}</td>
                                <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$role->name}}</td>
                                <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">
                                    @foreach($role->permissions as $permission)
                                        {{$permission->name}}
                                    @endforeach
                                </td>
                                <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">
{{--                                    <a href="{{route('role.show',$role->id)}}">--}}
{{--                                        <x-primary-button>{{__('نمایش')}}</x-primary-button>--}}
{{--                                    </a>--}}
                                    <a href="{{route('role.edit',$role->id)}}">
                                        <x-secondary-button>{{__('ویرایش')}}</x-secondary-button>
                                    </a>
                                    <form action="{{route('role.destroy',$role->id)}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <x-danger-button type="submit">{{__('حذف')}}</x-danger-button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr class="px-4 py-3 border-b text-center">
                                <td colspan="7" class="py-6">
                                    {{__('رکوردی یافت نشد...')}}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <span class="p-2 mx-2">
                {{$roles->withQueryString()->links(data:['scrollTo'=>false]) }}
            </span>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-2xl font-semibold mb-4">{{__('مدیریت سطح دسترسی')}}</h2>
            <div class="bg-white relative shadow-md sm:rounded-lg overflow-hidden">
                <div
                    class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="relative w-full md:w-3/6">
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
                </div>
                <a href="{{route('permission.create')}}">
                    <x-primary-button>
                        {{__('افزودن سطح دسترسی')}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="size-4 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </x-primary-button>
                </a>

                <div class="pt-4 sm:px-10 sm:pt-6  shadow-md rounded-md">
                    <table
                        class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead
                            class="text-sm text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">{{__('ردیف')}}</th>
                            <th class="px-4 py-3">{{__('دسترسی')}}</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($permissions as $permission)
                            <tr class="px-4 py-3 border-b text-center">
                                <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$loop->index+1}}</td>
                                <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$permission->name}}</td>
                                <td class="px-4 py-4 whitespace-no-wrap flex flex-row gap-x-2 text-sm leading-5 text-coll-gray-900">
{{--                                    <a href="{{route('permission.show',$permission->id)}}">--}}
{{--                                        <x-primary-button>{{__('نمایش')}}</x-primary-button>--}}
{{--                                    </a>--}}
                                    <a href="{{route('permission.edit',$permission->id)}}">
                                        <x-secondary-button>{{__('ویرایش')}}</x-secondary-button>
                                    </a>
                                    <form action="{{route('permission.destroy',$permission->id)}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <x-danger-button type="submit">{{__('حذف')}}</x-danger-button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr class="px-4 py-3 border-b text-center">
                                <td colspan="7" class="py-6">
                                    {{__('رکوردی یافت نشد...')}}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <span class="p-2 mx-2">
                    {{$permissions->withQueryString()->links(data:['scrollTo'=>false]) }}
                    </span>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>

