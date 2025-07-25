@php use App\Enums\UserPermission; @endphp
@php use App\Enums\UserRole; @endphp
<x-app-layout>
    <div dir="rtl">
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
                    <a href="{{route('phone-list.index')}}"
                       class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13.6986 3.68267C12.7492 2.77246 11.2512 2.77244 10.3018 3.68263L4.20402 9.52838C3.43486 10.2658 3 11.2852 3 12.3507V19C3 20.1046 3.89543 21 5 21H8.04559C8.59787 21 9.04559 20.5523 9.04559 20V13.4547C9.04559 13.2034 9.24925 13 9.5 13H14.5456C14.7963 13 15 13.2034 15 13.4547V20C15 20.5523 15.4477 21 16 21H19C20.1046 21 21 20.1046 21 19V12.3507C21 11.2851 20.5652 10.2658 19.796 9.52838L13.6986 3.68267Z"
                                fill="currentColor"></path>
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
                {{__('ویرایش')}}
                </span>
                </li>
            </ol>
        </nav>
        @can('has-permission-and-role',[UserPermission::PHONE_PERMISSIONS->value,UserRole::ADMIN->value])
            <form action="{{ route('phone-list.update', ['source' => $source, 'id' => $record->id]) }}" method="POST">
                @csrf
                @method('put')
                <div class="p-4 mb-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <section>
                            <div>
                                <p class="text-gray-700 dark:text-gray-300">
                                    {{ __('نام و نام خانوادگی :') }}
                                    <span class="font-medium">{{ $record->full_name }}</span>
                                </p>

                                @if($source === 'user_info')
                                    <p class="text-gray-700 dark:text-gray-300 mt-1 mb-3">
                                        {{ __('دپارتمان :') }}
                                        <span class="font-medium">
                                {{ $record->department?->department_name ?? '—' }}
                            </span>
                                    </p>
                                @endif

                                <x-input-label for="phone" :value="__('شماره همراه')"/>
                                <x-text-input name="phone" id="phone" maxlength="11"
                                              value="{{ old('phone', $record->phone) }}" class="block my-2 w-full"
                                              type="text" autofocus/>
                                <x-input-error :messages="$errors->get('phone')" class="my-2"/>

                                <x-input-label for="house_phone" :value="__('شماره منزل')"/>
                                <x-text-input name="house_phone" id="house_phone"
                                              value="{{ old('house_phone', $record->house_phone) }}"
                                              class="block my-2 w-full" type="text"/>
                                <x-input-error :messages="$errors->get('house_phone')" class="my-2"/>

                                <x-input-label for="work_phone" :value="__('شماره محل کار')"/>
                                <x-text-input name="work_phone" id="work_phone"
                                              value="{{ old('work_phone', $record->work_phone) }}"
                                              class="block my-2 w-full" type="text"/>
                                <x-input-error :messages="$errors->get('work_phone')" class="my-2"/>
                            </div>
                        </section>
                    </div>

                    <div class="mt-8">
                        <x-primary-button type="submit" class="ml-4">
                            {{ __('ذخیره') }}
                        </x-primary-button>
                        <a href="{{ route('phone-list.index') }}">
                            <x-cancel-button>{{ __('لغو') }}</x-cancel-button>
                        </a>
                    </div>
                </div>
            </form>
        @endcan
    </div>
</x-app-layout>
