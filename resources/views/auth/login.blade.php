<x-guest-layout>



    <!-- Login Form -->
    <div class="bg-gradient-to-br from-white via-blue-100 to-white border border-blue-100 backdrop-blur-sm shadow-2xl flex-1 flex items-center justify-center p-6 sm:p-12 bg-[#f0f4ff] relative z-10">
        <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
            <div class="space-y-6" dir="rtl">

                <!-- Logo -->
                <div class="flex justify-center">
                    <x-application-logo/>
                </div>

                <!-- Form Start -->
                <form action="{{ route('login.store') }}" method="POST" dir="rtl" class="space-y-4">
                    @csrf

                    <!-- Personal Code -->
                    <div>
                        <x-input-label for="p_code" :value="__('کد پرسنلی')" />
                        <div class="relative mt-2">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </div>
                            <x-text-input name="p_code" class="w-full pl-10 text-sm" autofocus/>
                        </div>
                        <x-input-error :messages="$errors->get('p_code')" class="mt-1" />
                    </div>

                    <!-- Password -->
                    <div x-data="{ show: true }">
                        <x-input-label for="p_code" class="mt-2" :value="__('رمز')"/>
                        <div class="relative mt-2">
                            <input name="password" autofocus
                                   class="w-full text-sm bg-white pl-10 border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50"
                                   :type="show ? 'password' : 'text'" type="password">
                            <div class="absolute top-1/2 left-4 cursor-pointer"
                                 style="transform: translateY(-50%);">
                                <svg class="h-4 text-gray-700 block" fill="none" @click="show = !show"
                                     :class="{'hidden': !show, 'block':show }" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 576 512">
                                    <path fill="currentColor"
                                          d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z">
                                    </path>
                                </svg>
                                <svg class="h-4 text-gray-700 hidden" fill="none" @click="show = !show"
                                     :class="{'block': !show, 'hidden':show }" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 640 512">
                                    <path fill="currentColor"
                                          d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                    </div>

                    <!-- Remember -->
                    <div class="flex items-center justify-between mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="remember"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"/>
                            <span class="mr-2 text-sm text-gray-600">{{__('بخاطر بسپار')}}</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                            class="inline-flex justify-center w-full px-4 py-3 bg-blue-500 text-white border border-transparent rounded-md font-semibold text-sm uppercase shadow-sm hover:bg-blue-600 hover:outline-none hover:ring-2 hover:ring-blue-500 hover:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-300">
                        {{ __('ورود') }}
                    </button>

                    <!-- Separator with "or" -->
                    <div class="flex items-center justify-center gap-4 my-4 text-gray-500 text-sm">
                        <hr class="w-full border-gray-300">
                        <span class="whitespace-nowrap text-gray-500">{{ __('یا') }}</span>
                        <hr class="w-full border-gray-300">
                    </div>

                    <!-- OTP Login Link -->
                    <a href="{{ route('otp.login') }}">
                        <x-primary-button type="button" class="inline-flex w-full justify-center px-4 py-3 mt-4 ">
                            {{ __('ورود با رمز یکبارمصرف') }}
                        </x-primary-button>
                    </a>
                </form>
            </div>
        </div>
    </div>


</x-guest-layout>
