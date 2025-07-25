@php use App\Enums\UserPermission; @endphp
@php use App\Enums\UserRole; @endphp
<x-app-layout>

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
                <a href="{{route('phone-list.index')}}"
                   class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M14.25 9.75v-4.5m0 4.5h4.5m-4.5 0 6-6m-3 18c-8.284 0-15-6.716-15-15V4.5A2.25 2.25 0 0 1 4.5 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44-.054.902-.417 1.173l-1.293.97a1.062 1.062 0 0 0-.38 1.21 12.035 12.035 0 0 0 7.143 7.143c.441.162.928-.004 1.21-.38l.97-1.293a1.125 1.125 0 0 1 1.173-.417l4.423 1.106c.5.125.852.575.852 1.091V19.5a2.25 2.25 0 0 1-2.25 2.25h-2.25Z"/>
                    </svg>
                    <span>{{__('دفترچه تلفنی')}}</span>
                </a>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                 stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            <li>
            <span
                class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                {{__('ایجاد شماره جدید')}}
            </span>
            </li>
        </ol>
    </nav>

    @can('has-permission-and-role',[UserPermission::PHONE_PERMISSIONS->value,UserRole::ADMIN->value])

        <form action="{{route('phone-list.store')}}" method="POST">
            @csrf
            <div class="p-4 mb-2 sm:p-8 bg-white dark:bg-gray-800 drop-shadow-xl sm:rounded-lg">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 border-b pb-2">
                    {{ __('افزودن شماره عموم ') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2">
                    <div>
                        <x-input-label for="full_name" :value="__('نام و نام خانوادگی')"/>
                        <x-text-input name="full_name" id="full_name"
                                      value="{{old('full_name')}}" class="block "
                                      type="text" autofocus/>
                        <x-input-error :messages="$errors->get('full_name')"/>
                    </div>
                    <div>
                        <x-input-label for="phone" :value="__('شماره همراه')"/>
                        <x-text-input name="phone" id="phone"
                                      value="{{old('phone')}}" class="block "
                                      type="text" autofocus/>
                        <x-input-error :messages="$errors->get('phone')"/>
                    </div>
                    <div>
                        <x-input-label for="house_phone" :value="__('شماره منزل')"/>
                        <x-text-input name="house_phone" id="house_phone"
                                      value="{{old('house_phone')}}" class="block "
                                      type="text" autofocus/>
                        <x-input-error :messages="$errors->get('house_phone')"/>
                    </div>
                    <div>
                        <x-input-label for="work_phone" :value="__('شماره محل کار')"/>
                        <x-text-input name="work_phone" id="work_phone"
                                      value="{{old('work_phone')}}" class="block "
                                      type="text" autofocus/>
                        <x-input-error :messages="$errors->get('work_phone')"/>
                    </div>
                </div>
                <div class="mt-6">
                    <x-primary-button type="submit" class="ml-4">
                        {{ __('ذخیره') }}
                    </x-primary-button>
                    <a href="{{route('phone-list.index')}}">
                        <x-cancel-button>
                            {{__('لغو')}}
                        </x-cancel-button>
                    </a>
                </div>
            </div>
        </form>
    @endcan

</x-app-layout>
