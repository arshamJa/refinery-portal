{{--<div>--}}




{{--    <div class="py-12 bg-gray-100" dir="rtl">--}}
{{--        <div class="max-w-7xl sm:px-6 lg:px-8 space-y-6">--}}

{{--            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">--}}
{{--                <div class="max-w-xl">--}}
{{--                    {{ __('انتخاب عکس پروفایل') }}--}}
{{--                    <form wire:submit="updateProfilePhoto">--}}
{{--                        <div class="col-span-6 sm:col-span-4">--}}
{{--                            <!-- Profile Photo File Input -->--}}
{{--                            @if(!auth()->user()->profile_photo_path)--}}
{{--                                <input type="file" id="photo" wire:model="photo"/>--}}
{{--                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>--}}
{{--                                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file_input" type="file">--}}
{{--                                {{auth()->user()->user_info->full_name}}--}}
{{--                            @else--}}
{{--                                <div class="mt-2">--}}
{{--                                    <img class="rounded-full h-20 w-20 object-cover" src="{{ auth()->user()->profilePhoto() }}" alt="">--}}
{{--                                </div>--}}
{{--                            @endif--}}
{{--                            <br>--}}
{{--                            <x-primary-button class="mt-2 me-2" type="submit">--}}
{{--                                {{ __('ذخیره') }}--}}
{{--                            </x-primary-button>--}}

{{--                            @if(auth()->user()->profile_photo_path)--}}
{{--                                <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">--}}
{{--                                    {{ __('حذف پروفایل') }}--}}
{{--                                </x-secondary-button>--}}
{{--                            @endif--}}
{{--                            <x-input-error class="mt-2" :messages="$errors->get('photo')" />--}}
{{--                            <x-action-message class="me-3" on="profilePhoto-updated">--}}
{{--                                {{ __('عکس ذخیره شد') }}--}}
{{--                            </x-action-message>--}}
{{--                            <x-action-message class="me-3" on="profilePhoto-deleted">--}}
{{--                                {{ __('عکس حذف شد') }}--}}
{{--                            </x-action-message>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">--}}
{{--                <div class="max-w-xl">--}}
{{--                    <header>--}}
{{--                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">--}}
{{--                            {{ __('اطلاعات شخصی') }}--}}
{{--                        </h2>--}}
{{--                    </header>--}}

{{--                    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">--}}
{{--                        <div>--}}
{{--                            <x-input-label for="role" :value="__('نقش')" />--}}
{{--                            <p class="mt-1 block w-full">{{$role}}</p>--}}

{{--                            <x-input-label for="department" :value="__('دپارتمان')" class="mt-4" />--}}
{{--                            <p class="mt-1 block w-full">{{$department}}</p>--}}

{{--                            <x-input-label for="position" :value="__('سمت')" class="mt-4" />--}}
{{--                            <p class="mt-1 block w-full">{{$position}}</p>--}}

{{--                            <x-input-label for="full_name" :value="__('نام و نام خانوادگی')" class="mt-4" />--}}
{{--                            <x-text-input wire:model="full_name" id="full_name" name="full_name" type="text" class="mt-2 block w-full" required autofocus />--}}
{{--                            <x-input-error class="mt-2" :messages="$errors->get('full_name')" />--}}

{{--                            <x-input-label for="n_code" :value="__('کد ملی')" class="mt-4" />--}}
{{--                            <x-text-input wire:model="n_code" id="n_code" maxlength="10" name="n_code" type="text" class="mt-2 block w-full" required autofocus />--}}
{{--                            <x-input-error class="mt-2" :messages="$errors->get('n_code')" />--}}

{{--                            <x-input-label for="p_code" :value="__('کد پرسنلی')" class="mt-4" />--}}
{{--                            <x-text-input wire:model="p_code"  maxlength="6" id="p_code" name="p_code" type="text" class="mt-2 block w-full" required autofocus />--}}
{{--                            <x-input-error class="mt-2" :messages="$errors->get('p_code')" />--}}

{{--                            <x-input-label for="phone" :value="__('تلفن همراه')" class="mt-4" />--}}
{{--                            <x-text-input wire:model="phone" maxlength="11" id="phone" name="phone" type="text" class="mt-2 block w-full" required autofocus />--}}
{{--                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />--}}

{{--                            <x-input-label for="house_phone" :value="__('تلفن منزل')" class="mt-4" />--}}
{{--                            <x-text-input wire:model="house_phone" id="house_phone" name="house_phone" type="text" class="mt-2 block w-full" required autofocus />--}}
{{--                            <x-input-error class="mt-2" :messages="$errors->get('house_phone')" />--}}

{{--                            <x-input-label for="work_phone" :value="__('تلفن محل کار')" class="mt-4" />--}}
{{--                            <x-text-input wire:model="work_phone" id="work_phone" name="work_phone" type="text" class="mt-2 block w-full" required autofocus />--}}
{{--                            <x-input-error class="mt-2" :messages="$errors->get('work_phone')" />--}}

{{--                        </div>--}}

{{--                        <div class="flex items-center gap-4">--}}
{{--                            <x-primary-button>{{ __('ذخیره') }}</x-primary-button>--}}

{{--                            <x-action-message class="me-3" on="profile-updated">--}}
{{--                                {{ __('اطلاعات ذخیره شد') }}--}}
{{--                            </x-action-message>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">--}}
{{--                <div class="max-w-xl">--}}
{{--                    <header>--}}
{{--                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">--}}
{{--                            {{ __('تغییر رمز ورود') }}--}}
{{--                        </h2>--}}
{{--                    </header>--}}

{{--                    <form wire:submit="updatePassword" class="mt-6 space-y-6">--}}
{{--                        <div>--}}
{{--                            <x-input-label for="update_password_current_password" :value="__('رمز قبلی')" class="mt-4" />--}}
{{--                            <x-text-input wire:model="current_password" id="update_password_current_password" name="current_password" type="password" class="mt-2 block w-full" autocomplete="current-password" />--}}
{{--                            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />--}}
{{--                        </div>--}}

{{--                        <div>--}}
{{--                            <x-input-label for="update_password_password" :value="__('رمز جدید')" class="mt-4" />--}}
{{--                            <x-text-input wire:model="password" id="update_password_password" name="password" type="password" class="mt-2 block w-full" autocomplete="new-password" />--}}
{{--                            <x-input-error :messages="$errors->get('password')" class="mt-2" />--}}
{{--                        </div>--}}

{{--                        <div>--}}
{{--                            <x-input-label for="update_password_password_confirmation" :value="__('تکرار رمز جدید')" class="mt-4" />--}}
{{--                            <x-text-input wire:model="password_confirmation" id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-2 block w-full" autocomplete="new-password" />--}}
{{--                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />--}}
{{--                        </div>--}}

{{--                        <div class="flex items-center gap-4">--}}
{{--                            <x-primary-button>--}}
{{--                                {{ __('ذخیره') }}--}}
{{--                            </x-primary-button>--}}

{{--                            <x-action-message class="me-3" on="password-updated">--}}
{{--                                {{ __('رمز جدید ثبت شد.') }}--}}
{{--                            </x-action-message>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
