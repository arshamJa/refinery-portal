@php use App\Enums\UserRole; @endphp
<x-app-layout>

    <x-sessionMessage name="status"/>
    @can('profile-page')
        <nav class="flex justify-between mb-4 mt-20">
            <ol class="inline-flex items-center mb-3 space-x-1 text-xs text-neutral-500 [&_.active-breadcrumb]:text-neutral-600 [&_.active-breadcrumb]:font-medium sm:mb-0">
                <li class="flex items-center h-full">
                    <a href="{{route('dashboard')}}"
                       class="inline-flex items-center gap-1 px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
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
                    <a href="{{Illuminate\Support\Facades\URL::signedRoute('profile')}}"
                       class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                        <span>{{__('پروفایل')}}</span>
                    </a>
                </li>
            </ol>
        </nav>

        <div class="space-y-6">
            <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow-md sm:rounded-lg space-y-6">
                <header>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 border-b pb-2">
                        {{ __('انتخاب عکس پروفایل') }}
                    </h2>
                </header>

                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <!-- Preview -->
                    @if (auth()->user()->profile_photo_path)
                        <img class="rounded-full h-20 w-20 object-cover ring-2 ring-indigo-500 dark:ring-indigo-400"
                             src="{{ auth()->user()->profilePhoto() }}" alt="Profile Photo">
                    @else
                        <div
                            class="rounded-full h-20 w-20 bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-500 text-sm">
                            {{ __('بدون عکس') }}
                        </div>
                    @endif

                    <!-- Upload Form -->
                    <form action="{{ route('updateProfilePhoto') }}" method="POST" enctype="multipart/form-data"
                          class="flex flex-col gap-2">
                        @csrf
                        <input type="file" id="photo" name="photo"
                               class="block py-2 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 file:me-2 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"/>

                        <x-primary-button type="submit" class="w-fit">
                            {{ __('ذخیره عکس') }}
                        </x-primary-button>

                        <x-input-error class="text-sm text-red-500" :messages="$errors->get('photo')"/>
                    </form>
                </div>

                <!-- Delete Form -->
                @if(auth()->user()->profile_photo_path)
                    <form action="{{ route('deleteProfilePhoto') }}" method="POST" class="pt-2">
                        @csrf
                        @method('delete')
                        <x-cancel-button type="submit">
                            {{ __('حذف پروفایل') }}
                        </x-cancel-button>
                    </form>
                @endif

            </div>

            <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow-md sm:rounded-lg space-y-6">
                <header>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 border-b pb-2">
                        {{ __('اطلاعات شخصی') }}
                    </h2>
                </header>

                <!-- Static Info Section -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-700 dark:text-gray-300">
                    <p>
                        <span class="font-medium">{{ __('نقش :') }}</span>
                        @if (auth()->user()->hasRole('super_admin'))
                            {{ __('Samael') }}
                        @elseif (auth()->user()->hasRole('ادمین'))
                            {{ __('ادمین') }}
                        @elseif (auth()->user()->hasRole(UserRole::OPERATOR->value))
                            {{ UserRole::OPERATOR->value }}
                        @elseif (auth()->user()->hasRole(UserRole::USER->value))
                            {{ UserRole::USER->value }}
                        @endif
                    </p>
                    <p>
                        <span class="font-medium">{{ __('دپارتمان :') }}</span>
                        {{ $department }}
                    </p>
                    <p>
                        <span class="font-medium">{{ __('سمت :') }}</span>
                        {{ $users->user_info->position }}
                    </p>
                </div>

                <!-- Form -->
                <form action="{{ route('updateProfileInformation') }}" method="post" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Full Name -->
                        <div>
                            <x-input-label for="full_name" :value="__('نام و نام خانوادگی')"/>
                            <x-text-input id="full_name" name="full_name" type="text"
                                          class="mt-2 block w-full"
                                          :value="$users->user_info->full_name" required autofocus/>
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('full_name')"/>
                        </div>
                        <!-- National Code -->
                        <div>
                            <x-input-label for="n_code" :value="__('کد ملی')"/>
                            <x-text-input id="n_code" name="n_code" type="text" maxlength="10"
                                          class="mt-2 block w-full"
                                          :value="$users->user_info->n_code" required/>
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('n_code')"/>
                        </div>
                        <!-- Personnel Code -->
                        <div>
                            <x-input-label for="p_code" :value="__('کد پرسنلی')"/>
                            <x-text-input id="p_code" name="p_code" type="text" maxlength="6"
                                          class="mt-2 block w-full"
                                          :value="$users->p_code" required/>
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('p_code')"/>
                        </div>
                        <!-- Mobile Phone -->
                        <div>
                            <x-input-label for="phone" :value="__('تلفن همراه')"/>
                            <x-text-input id="phone" name="phone" type="text" maxlength="11"
                                          class="mt-2 block w-full"
                                          :value="$users->user_info->phone" required/>
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('phone')"/>
                        </div>
                        <!-- Home Phone -->
                        <div>
                            <x-input-label for="house_phone" :value="__('تلفن منزل')"/>
                            <x-text-input id="house_phone" name="house_phone" type="text"
                                          class="mt-2 block w-full"
                                          :value="$users->user_info->house_phone" required/>
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('house_phone')"/>
                        </div>
                        <!-- Work Phone -->
                        <div>
                            <x-input-label for="work_phone" :value="__('تلفن محل کار')"/>
                            <x-text-input id="work_phone" name="work_phone" type="text"
                                          class="mt-2 block w-full"
                                          :value="$users->user_info->work_phone" required/>
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('work_phone')"/>
                        </div>
                    </div>

                    <div class="pt-4">
                        <x-primary-button>
                            {{ __('ذخیره اطلاعات') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>


            <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow-md sm:rounded-lg space-y-6">
                <header>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 border-b pb-2">
                        {{ __('تغییر رمز ورود') }}
                    </h2>
                </header>

                <form action="{{ route('updatePassword') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Current Password -->
                        <div>
                            <x-input-label for="current_password" :value="__('رمز قبلی')"/>
                            <x-text-input id="current_password" name="current_password" type="password"
                                          class="mt-2 block w-full" autocomplete="current-password"/>
                            <x-input-error :messages="$errors->get('current_password')"
                                           class="mt-2 text-sm text-red-500"/>
                        </div>

                        <!-- New Password -->
                        <div>
                            <x-input-label for="password" :value="__('رمز جدید')"/>
                            <x-text-input id="password" name="password" type="password"
                                          class="mt-2 block w-full" autocomplete="new-password"/>
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500"/>
                        </div>

                        <!-- Confirm New Password -->
                        <div>
                            <x-input-label for="password_confirmation" :value="__('تکرار رمز جدید')"/>
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                                          class="mt-2 block w-full" autocomplete="new-password"/>
                            <x-input-error :messages="$errors->get('password_confirmation')"
                                           class="mt-2 text-sm text-red-500"/>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="pt-4">
                        <x-primary-button>
                            {{ __('ذخیره اطلاعات') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    @endcan

</x-app-layout>
