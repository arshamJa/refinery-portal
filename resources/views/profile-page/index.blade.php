<x-app-layout>

    <x-sessionMessage name="status"/>



{{--    <x-template>--}}
            <div class="space-y-6 mt-14">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        {{ __('انتخاب عکس پروفایل') }}
                        <div class="col-span-6 sm:col-span-4">
                            <form action="{{route('updateProfilePhoto')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <!-- Profile Photo File Input -->
                                @if(!auth()->user()->profile_photo_path)
                                    <input type="file" id="photo" name="photo"/>
                                    {{--                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>--}}
                                    {{--                                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file_input" type="file">--}}
                                    {{--                                {{auth()->user()->user_info->full_name}}--}}
                                @else
                                    <div class="mt-2">
                                        <img class="rounded-full h-20 w-20 object-cover"
                                             src="{{ auth()->user()->profilePhoto() }}" alt="">
                                    </div>
                                @endif
                                <br>
                                <x-primary-button class="mt-2 me-2" type="submit">
                                    {{ __('ذخیره') }}
                                </x-primary-button>
                            </form>
                            <form action="{{route('deleteProfilePhoto')}}" method="post">
                                @csrf
                                @if(auth()->user()->profile_photo_path)
                                    <x-secondary-button type="submit" class="mt-2">
                                        {{ __('حذف پروفایل') }}
                                    </x-secondary-button>
                                @endif
                                <x-input-error class="mt-2" :messages="$errors->get('photo')"/>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('اطلاعات شخصی') }}
                            </h2>
                        </header>
                        <form action="{{route('updateProfileInformation')}}" method="post" class="mt-6 space-y-6">
                            @csrf
                            <div>
                                <x-input-label for="role" :value="__('نقش')"/>
                                <p class="mt-1 block w-full">{{$users->role}}</p>
                                <x-input-label for="department" :value="__('دپارتمان')" class="mt-4"/>
                                <p class="mt-1 block w-full">{{$department}}</p>

                                <x-input-label for="position" :value="__('سمت')" class="mt-4"/>
                                <p class="mt-1 block w-full">{{$users->user_info->position}}</p>

                                <x-input-label for="full_name" :value="__('نام و نام خانوادگی')" class="mt-4"/>
                                <x-text-input name="full_name" value="{{$users->user_info->full_name}}" id="full_name"
                                              type="text" class="mt-2 block w-full" required autofocus/>
                                <x-input-error class="mt-2" :messages="$errors->get('full_name')"/>

                                <x-input-label for="n_code" :value="__('کد ملی')" class="mt-4"/>
                                <x-text-input name="n_code" value="{{$users->user_info->n_code}}" id="n_code" maxlength="10"
                                              name="n_code" type="text" class="mt-2 block w-full" required autofocus/>
                                <x-input-error class="mt-2" :messages="$errors->get('n_code')"/>

                                <x-input-label for="p_code" :value="__('کد پرسنلی')" class="mt-4"/>
                                <x-text-input name="p_code" value="{{$users->p_code}}" maxlength="6" id="p_code"
                                              name="p_code" type="text" class="mt-2 block w-full" required autofocus/>
                                <x-input-error class="mt-2" :messages="$errors->get('p_code')"/>

                                <x-input-label for="phone" :value="__('تلفن همراه')" class="mt-4"/>
                                <x-text-input name="phone" value="{{$users->user_info->phone}}" maxlength="11" id="phone"
                                              name="phone" type="text" class="mt-2 block w-full" required autofocus/>
                                <x-input-error class="mt-2" :messages="$errors->get('phone')"/>

                                <x-input-label for="house_phone" :value="__('تلفن منزل')" class="mt-4"/>
                                <x-text-input name="house_phone" value="{{$users->user_info->house_phone}}" id="house_phone"
                                              name="house_phone" type="text" class="mt-2 block w-full" required autofocus/>
                                <x-input-error class="mt-2" :messages="$errors->get('house_phone')"/>

                                <x-input-label for="work_phone" :value="__('تلفن محل کار')" class="mt-4"/>
                                <x-text-input name="work_phone" value="{{$users->user_info->work_phone}}" id="work_phone"
                                              name="work_phone" type="text" class="mt-2 block w-full" required autofocus/>
                                <x-input-error class="mt-2" :messages="$errors->get('work_phone')"/>

                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('ذخیره') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('تغییر رمز ورود') }}
                            </h2>
                        </header>

                        <form action="{{route('updatePassword')}}" method="post" class="mt-6 space-y-6">
                            @csrf
                            <div>
                                <x-input-label for="update_password_current_password" :value="__('رمز قبلی')" class="mt-4"/>
                                <x-text-input name="current_password" id="update_password_current_password"
                                              name="current_password" type="password" class="mt-2 block w-full"
                                              autocomplete="current-password"/>
                                <x-input-error :messages="$errors->get('current_password')" class="mt-2"/>
                            </div>

                            <div>
                                <x-input-label for="update_password_password" :value="__('رمز جدید')" class="mt-4"/>
                                <x-text-input name="password" id="update_password_password" name="password" type="password"
                                              class="mt-2 block w-full" autocomplete="new-password"/>
                                <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                            </div>

                            <div>
                                <x-input-label for="update_password_password_confirmation" :value="__('تکرار رمز جدید')"
                                               class="mt-4"/>
                                <x-text-input name="password_confirmation" id="update_password_password_confirmation"
                                              name="password_confirmation" type="password" class="mt-2 block w-full"
                                              autocomplete="new-password"/>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>
                                    {{ __('ذخیره') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
{{--    </x-template>--}}
</x-app-layout>
