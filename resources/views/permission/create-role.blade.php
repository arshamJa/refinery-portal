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

        <form action="{{route('role.store')}}" method="post">
            @csrf
            <div class="flex flex-row px-6 py-4 bg-gray-100 text-start">
                {{__('ایجاد نقش جدید')}}
            </div>
            <div class="px-6 py-4" dir="rtl">
                <div class="mt-4 text-sm text-gray-600">
                    <div class="w-full">
                        <x-input-label for="role" :value="__('نقش')"/>
                        <x-text-input name="role" id="role"
                                      class="block my-2 w-full" type="text" autofocus/>
                        <x-input-error :messages="$errors->get('role')" class="my-2"/>
                    </div>
                </div>
            </div>
            @foreach($permissions as $permission)
                <input type="checkbox" name="permissions[{{$permission->name}}]" value="{{$permission->name}}">{{$permission->name}}
            @endforeach
            <div class="flex flex-row justify-between px-6 py-4 bg-gray-100">
                <a href="{{route('role.permission.table')}}">
                    <x-secondary-button>
                        {{ __('لفو') }}
                    </x-secondary-button>
                </a>
                <x-primary-button type="submit">
                    {{ __('ثبت') }}
                </x-primary-button>
            </div>
        </form>




    </div>
</x-app-layout>

