<x-guest-layout>

    <!-- Login Form -->
    <div class="flex-1 flex items-center justify-center p-6 sm:p-12 bg-gray-100 relative z-10">
        <div class="w-full max-w-md bg-white shadow-xl rounded-xl p-8 border border-gray-100">
            <div class="space-y-6">

                <!-- Logo added here -->
                <div class="flex justify-center">
                    <x-application-logo/>
                </div>

                <!-- Tabs -->
                <div class="flex w-full bg-gray-100 rounded-full shadow-sm overflow-hidden text-sm font-medium">
                    <a href="{{ route('login') }}"
                       class="w-1/2 text-center py-2 transition-all text-gray-600 hover:bg-blue-100 hover:text-blue-700 rounded-l-full">
                        {{ __('ورود با کد پرسنلی') }}
                    </a>
                    <a href="{{ route('otp.login') }}"
                       class="w-1/2 text-center py-2 transition-all rounded-r-full bg-blue-500 text-white hover:bg-blue-600">
                        {{ __('ورود با رمز یکبارمصرف') }}
                    </a>
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
                            <x-text-input name="phone" id="phone-input" class="mt-2 w-full" required
                                          value="{{ old('phone', session('otp_phone')) }}"/>
                            <!-- Display phone errors (including throttle) -->
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
