<x-app-layout>
{{--    @if (session('time'))--}}
{{--        <div class="alert alert-success">--}}
{{--            {{ session('time') }}--}}
{{--        </div>--}}
{{--    @endif--}}
{{--    @if (session('otp'))--}}
{{--        <div class="alert alert-success">--}}
{{--            {{ session('otp') }}--}}
{{--        </div>--}}
{{--    @endif--}}

{{--    <div id="clock"></div>--}}

{{--    <a href="{{route('resend',['id'=>$id])}}" id="otp"></a>--}}

{{--    <script>--}}
{{--        let count = 120;--}}
{{--        function updateClock() {--}}
{{--            // Format the countdown display--}}
{{--            const minutes = Math.floor(count / 60);--}}
{{--            const seconds = count % 60;--}}
{{--            const formattedTime = minutes.toString().padStart(2, '0')--}}
{{--                + ':'--}}
{{--                + seconds.toString().padStart(2, '0');--}}
{{--            // Display the formatted time--}}
{{--            document.getElementById('clock').textContent = formattedTime;--}}
{{--            // Decrement the count--}}
{{--            count--;--}}
{{--            // Check if the countdown has reached zero--}}
{{--            if (count < 0) {--}}
{{--                clearInterval(timer);--}}
{{--                document.getElementById('otp').textContent = "دریافت مجدد کد";--}}
{{--            }--}}
{{--        }--}}
{{--        // Update the clock every second--}}
{{--        const timer = setInterval(updateClock, 1000);--}}
{{--        // Initialize the clock display--}}
{{--        updateClock();--}}
{{--    </script>--}}



{{--    <form action="{{route('resend', [ 'id' => $id] ) }}" method="post">--}}
{{--        @csrf--}}
{{--        <button>Resend OTP</button>--}}
{{--    </form>--}}

{{--    <a href="/otp">{{__('ویرایش شماره تلفن')}}</a>--}}






    <section class="ant dark:bg-gray-900 ">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white text-center">
                {{__('کد تایید برای شماره ')}}
                <span class="mx-1 font-bold">
            {{\App\Models\User::where('id',$id)->value('phone')}}
        </span>
                {{__('ارسال شد')}}</h2>
            <form method="post" action="{{ route('otp.getLogin', [ 'id' => $id] ) }}">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6" dir="rtl">
                    <div class="w-full">
                        <label for="otp"
                               class="block mb-2 text-sm text-right font-medium text-gray-900 dark:text-white">
                            {{__('کد یکبار مصرف')}}
                        </label>
                        <input id="otp" type="text" name="two_factor_code" value="{{ old('two_factor_code') }}" maxlength="6" required
                               class="w-full text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                        <x-input-error :messages="$errors->get('two_factor_code')" class="mt-2"/>
                    </div>

                    <div dir="ltr" class="flex gap-x-3" data-hs-pin-input='{
                                    "availableCharsRE": "^[0-9]+$"
                                }'>
                        <input name="one" type="text" class="block w-[38px] text-center border-gray-200 rounded-md text-sm [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="⚬" data-hs-pin-input-item="">
                        <input name="two" type="text" class="block w-[38px] text-center border-gray-200 rounded-md text-sm [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="⚬" data-hs-pin-input-item="">
                        <input name="three" type="text" class="block w-[38px] text-center border-gray-200 rounded-md text-sm [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="⚬" data-hs-pin-input-item="">
                        <input name="four" type="text" class="block w-[38px] text-center border-gray-200 rounded-md text-sm [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="⚬" data-hs-pin-input-item="">
                        <input name="five" type="text" class="block w-[38px] text-center border-gray-200 rounded-md text-sm [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="⚬" data-hs-pin-input-item="">
                        <input name="six" type="text" class="block w-[38px] text-center border-gray-200 rounded-md text-sm [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="⚬" data-hs-pin-input-item="">
                    </div>



                </div>
                <div class="mt-16 text-right">
                    <button type="submit"
                            class="inline-flex items-center text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                        {{__('ثبت')}}
                    </button>
                </div>
            </form>
        </div>
    </section>


{{--    <p id="demo">2:00</p>--}}
{{--    <script>--}}
{{--       const startingMinutes = 2;--}}
{{--       let time = startingMinutes * 60;--}}
{{--       const countDown = document.getElementById('demo');--}}
{{--       setInterval(update,1000);--}}
{{--       function update(){--}}
{{--           const minutes = Math.floor(time/60);--}}
{{--           let seconds = time % 60 ;--}}
{{--           seconds = seconds < 2 ? '0' + seconds : seconds;--}}
{{--           countDown.innerHTML = `${minutes}:${seconds}`;--}}
{{--           time--;--}}
{{--       }--}}
{{--    </script>--}}


</x-app-layout>
