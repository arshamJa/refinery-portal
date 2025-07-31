<x-guest-layout>


    <!-- Login Form -->
    <div
        class="bg-gradient-to-br from-white via-blue-100 to-white border border-blue-100 backdrop-blur-sm shadow-2xl flex-1 flex items-center justify-center p-6 sm:p-12 bg-[#f0f4ff] relative z-10">
        <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
            <div class="space-y-6" dir="rtl">
                <!-- Logo -->
                <div class="flex justify-center">
                    <x-application-logo/>
                </div>

                <div class="relative w-full mt-4">
                    @if (session()->has('otp_sent'))
                        <div
                            x-data="{ showMessage: true }" x-show="showMessage" x-transition x-cloak
                            x-init="setTimeout(() => showMessage = false, 4000)"
                            dir="rtl"
                            class="fixed top-5 right-5 z-[99] max-w-xs bg-white border border-gray-200 rounded-xl shadow-lg dark:bg-neutral-800 dark:border-neutral-700">
                            <div class="flex p-4">
                                <div class="shrink-0">
                                    <svg class="shrink-0 size-4 text-teal-500 mt-0.5"
                                         xmlns="http://www.w3.org/2000/svg" width="16"
                                         height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"></path>
                                    </svg>
                                </div>
                                <div class="ms-3">
                                    <p class="text-sm text-gray-700 dark:text-neutral-400">
                                        {{ session('otp_sent') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Send OTP Form -->
                    <form id="otp-send-form" action="{{ route('otp.send') }}" method="POST" dir="rtl">
                        @csrf
                        <div>
                            <x-input-label for="phone" :value="__('شماره موبایل')"/>
                            <div class="relative mt-2">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                                    </svg>
                                </div>
                                <x-text-input name="phone" id="phone-input" class="pl-10 w-full" required
                                              value="{{ old('phone', session('otp_phone')) }}"/>
                            </div>
                            <x-input-error :messages="$errors->get('phone')" class="mt-1 text-red-600"/>
                        </div>
                        <button
                            type="submit"
                            id="send-otp-btn"
                            class="w-full mt-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                        >
                            {{ __('ارسال کد') }}
                        </button>

                        <div id="otp-timer" class="text-sm text-gray-600 mt-2"></div>


                    </form>
                    @if(session('otp_phone'))

                        <!-- Verify OTP Form -->
                        <form action="{{ route('otp.verify') }}" method="POST" class="mt-6 space-y-4" dir="rtl">
                            @csrf
                            <div>
                                <x-input-label for="otp" :value="__('کد تایید')"/>
                                <x-text-input name="otp" class="mt-2 w-full" required/>
                                <!-- Display OTP errors -->
                                <x-input-error :messages="$errors->get('otp')" class="mt-1 text-red-600"/>
                            </div>

                            <button type="submit"
                                    class="w-full py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                {{ __('تایید و ورود') }}
                            </button>
                        </form>
                    @endif
                    <!-- Separator with "or" -->
                    <div class="flex items-center justify-center gap-4 my-4 text-gray-500 text-sm">
                        <hr class="w-full border-gray-300">
                        <span class="whitespace-nowrap text-gray-500">{{ __('یا') }}</span>
                        <hr class="w-full border-gray-300">
                    </div>

                    <!-- OTP Login Link -->
                    <a href="{{ route('login') }}">
                        <x-primary-button type="button" class="inline-flex w-full justify-center px-4 py-3 mt-4">
                            {{ __('ورود با کدپرسنلی') }}
                        </x-primary-button>
                    </a>
                </div>


            </div>
        </div>
    </div>

    @if(session('otp_phone'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const phoneInput = document.getElementById('phone-input');
                const sendBtn = document.getElementById('send-otp-btn');
                const timerDiv = document.getElementById('otp-timer');

                let startTime = localStorage.getItem('otp_timer_start');
                let countdown;

                function startCountdown() {
                    phoneInput.readOnly = true;
                    phoneInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                    sendBtn.disabled = true;

                    const now = Date.now();
                    const duration = 60000; // 60 seconds
                    const endTime = parseInt(startTime) + duration;

                    countdown = setInterval(() => {
                        let remaining = Math.ceil((endTime - Date.now()) / 1000);

                        if (remaining <= 0) {
                            clearInterval(countdown);
                            localStorage.removeItem('otp_timer_start');
                            timerDiv.textContent = '';
                            phoneInput.readOnly = false;
                            phoneInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                            sendBtn.disabled = false;
                        } else {
                            timerDiv.textContent = `امکان ارسال مجدد تا ${remaining} ثانیه دیگر`;
                        }
                    }, 1000);
                }

                // If timer is active, start it
                if (startTime) {
                    startCountdown();
                }

                // Set localStorage when send button is clicked
                sendBtn.addEventListener('click', function () {
                    localStorage.setItem('otp_timer_start', Date.now().toString());
                });
            });
        </script>
    @endif
    @if(session('otp_verified'))
        <script>
            // Clear timer after successful verification
            localStorage.removeItem('otp_timer_start');
        </script>
    @endif

</x-guest-layout>
