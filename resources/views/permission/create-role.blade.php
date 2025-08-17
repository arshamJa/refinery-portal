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
                <li class="flex items-center h-full">
                    <a href="{{route('role.permission.table')}}"
                       class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                         <span>
                    {{__('جدول مدیریت نقش و تعیین سطح دسترسی')}}
                </span>
                    </a>
                </li>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                     stroke="currentColor" class="w-3 h-3 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
                <li>
                <span
                    class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                    {{__('ایجاد نقش جدید')}}
                </span>
                </li>
            </ol>
        </nav>
        <div class="max-w-3xl p-4 bg-white shadow-lg rounded-2xl space-y-8">
            <!-- Role Creation Form -->
            <form action="{{ route('roles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <h2 class="text-2xl font-bold text-gray-800 border-b pb-2">{{__('ایجاد نقش جدید')}}</h2>

                <!-- Role Name -->
                <div>
                    <x-input-label for="role" :value="'نام نقش'"/>
                    <x-text-input id="role" name="role" type="text" class="mt-2 w-full"/>
                    <x-input-error :messages="$errors->get('role')" class="mt-1 text-red-500 text-sm"/>
                </div>

                <!-- Permissions Checkbox Grid -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">{{__('سطوح دسترسی')}}</label>
                    {{--                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">--}}
                    @foreach($permissions as $permission)
                        <label class="flex items-center space-x-2 text-sm text-gray-600">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                   class="accent-indigo-600">
                            <span>{{ $permission->name }}</span>
                        </label>
                    @endforeach
                    {{--                </div>--}}
                    <x-input-error :messages="$errors->get('permissions')" class="mt-2 text-red-500 text-sm"/>
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center">
                    <x-primary-button>
                        {{__('ثبت نقش')}}
                    </x-primary-button>
                    <a href="{{ route('role.permission.table') }}">
                        <x-secondary-button>
                            {{__('لغو')}}
                        </x-secondary-button>
                    </a>

                </div>
            </form>

        </div>
    @endcan
</x-app-layout>

