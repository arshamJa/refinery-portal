<x-app-layout>
    @can('has-role',UserRole::SUPER_ADMIN)

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
                    {{__('جدول مدیریت نقش و تعیین سطح دسترسی')}}
                </span>
                </li>
            </ol>
        </nav>
        <a href="{{route('connect-permissions')}}">Create the Permissions</a>
        <div class="bg-white px-3 relative shadow-md sm:rounded-lg overflow-hidden mb-4">
            <h2 class="text-2xl font-semibold mb-4">{{__('مدیریت نقش')}}</h2>
            <form method="GET" action="{{route('role.permission.table')}}">
                <div class="grid gap-4 px-3 sm:px-0 lg:grid-cols-6 items-end">
                    <!-- Search Input -->
                    <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                        <x-input-label for="role">{{ __('نقش') }}</x-input-label>
                        <x-text-input type="text" name="role" id="role" value="{{ request('role') }}"/>
                    </div>

                    <div class="col-span-6 lg:col-span-4 flex justify-start lg:justify-end flex-row gap-4 mt-4 lg:mt-0">
                        <x-search-button>{{__('جست و جو')}}</x-search-button>
                        @if (request()->has('role'))
                            <x-view-all-link
                                href="{{route('role.permission.table')}}">{{__('نمایش همه')}}</x-view-all-link>
                        @endif
                    </div>

                    <div class="col-span-6 lg:col-start-5 lg:col-span-2 flex justify-start lg:justify-end mt-2">
                        {{--            @can('create',App\Models\Role::class)--}}
                        <a href="{{route('roles.create')}}">
                            <x-primary-button type="button">
                                {{__('افزودن نقش')}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-4 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            </x-primary-button>
                        </a>
                        {{--            @endcan--}}
                    </div>
                </div>
            </form>

            <div class="pt-4 overflow-x-auto overflow-y-hidden sm:pt-6 bg-white pb-10">
                <x-table.table>
                    <x-slot name="head">
                        @foreach (['ردیف', 'نقش','سطح دسترسی','قابلیت'] as $th)
                            <x-table.heading>{{ __($th) }}</x-table.heading>
                        @endforeach
                    </x-slot>
                    <x-slot name="body">
                        @forelse($roles as $role)
                            <x-table.row>
                                <x-table.cell>{{ ($roles->currentPage() - 1) * $roles->perPage() + $loop->iteration }}</x-table.cell>
                                <x-table.cell>
                                 <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                {{ $role->name}}
                                 </span>
                                </x-table.cell>
                                <x-table.cell>
                                    @if($role->permissions->isNotEmpty())
                                        @foreach($role->permissions as $permission)
                                            <span
                                                class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full m-0.5">
                                            {{ $permission->name }}
                                        </span>
                                        @endforeach
                                    @else
                                        <span
                                            class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full m-0.5">
                                        {{__('بدون سطح دسترسی')}}
                                    </span>
                                    @endif
                                </x-table.cell>
                                {{--@can('viewUserTable', UserInfo::class)--}}
                                <x-table.cell class="flex gap-2 justify-center">
                                    <x-primary-button>
                                        <a href="{{ route('roles.edit', $role->id) }}">
                                            {{ __('ویرایش') }}
                                        </a>
                                    </x-primary-button>
                                    <form action="{{route('roles.destroy',$role->id)}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <x-danger-button type="submit">
                                            {{ __('حذف') }}
                                        </x-danger-button>
                                    </form>
                                </x-table.cell>
                                {{--@endcan--}}
                            </x-table.row>
                        @empty
                            <x-table.row>
                                <x-table.cell colspan="3" class="py-6">
                                    {{__('رکوردی یافت نشد...')}}
                                </x-table.cell>
                            </x-table.row>
                        @endforelse
                    </x-slot>
                </x-table.table>
                <div class="p-2 mx-2">
                    {{ $roles->withQueryString()->links(data: ['scrollTo' => false]) }}
                </div>
            </div>
        </div>
        <div class="bg-white px-3 relative shadow-md sm:rounded-lg overflow-hidden">
            <h2 class="text-2xl font-semibold mb-4">{{__('مدیریت سطح دسترسی')}}</h2>
            <form method="GET" action="{{route('role.permission.table')}}">
                <div class="grid gap-4 px-3 sm:px-0 lg:grid-cols-6 items-end">
                    <!-- Search Input -->
                    <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                        <x-input-label for="permission">{{ __('دسترسی') }}</x-input-label>
                        <x-text-input type="text" name="permission" id="permission"
                                      value="{{ request('permission') }}"/>
                    </div>

                    <div class="col-span-6 lg:col-span-4 flex justify-start lg:justify-end flex-row gap-4 mt-4 lg:mt-0">
                        <x-search-button>{{__('جست و جو')}}</x-search-button>
                        @if (request()->has('permission'))
                            <x-view-all-link
                                href="{{route('role.permission.table')}}">{{__('نمایش همه')}}</x-view-all-link>
                        @endif
                    </div>

                    <div class="col-span-6 lg:col-start-5 lg:col-span-2 flex justify-start lg:justify-end mt-2">
                        {{--            @can('create',App\Models\Role::class)--}}
                        <a href="{{route('permissions.create')}}">
                            <x-primary-button type="button">
                                {{__('افزودن دسترسی')}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-4 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            </x-primary-button>
                        </a>
                        {{--            @endcan--}}
                    </div>
                </div>
            </form>

            <div class="pt-4 overflow-x-auto overflow-y-hidden sm:pt-6 bg-white pb-10">
                <x-table.table>
                    <x-slot name="head">
                        @foreach (['ردیف', 'دسترسی', 'قابلیت'] as $th)
                            <x-table.heading>{{ __($th) }}</x-table.heading>
                        @endforeach
                    </x-slot>
                    <x-slot name="body">
                        @forelse($permissions as $permission)
                            <x-table.row>
                                <x-table.cell>{{ ($permissions->currentPage() - 1) * $permissions->perPage() + $loop->iteration }}</x-table.cell>
                                <x-table.cell>
                                <span
                                    class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full m-0.5">
                                {{ $permission->name}}
                                </span>
                                </x-table.cell>
                                {{--@can('viewUserTable', UserInfo::class)--}}
                                <x-table.cell class="flex gap-2 justify-center">
                                    <x-primary-button>
                                        <a href="{{ route('permissions.edit', $permission->id) }}">
                                            {{ __('ویرایش') }}
                                        </a>
                                    </x-primary-button>
                                    <form action="{{route('permissions.destroy',$permission->id)}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <x-danger-button type="submit">
                                            {{ __('حذف') }}
                                        </x-danger-button>
                                    </form>
                                </x-table.cell>
                                {{--@endcan--}}
                            </x-table.row>
                        @empty
                            <x-table.row>
                                <x-table.cell colspan="3" class="py-6">
                                    {{__('رکوردی یافت نشد...')}}
                                </x-table.cell>
                            </x-table.row>
                        @endforelse
                    </x-slot>
                </x-table.table>
                <div class="p-2 mx-2">
                    {{$permissions->withQueryString()->links(data: ['scrollTo' => false]) }}
                </div>
            </div>
        </div>

    @endcan
</x-app-layout>

