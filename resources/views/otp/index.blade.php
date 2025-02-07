<x-guest-layout>
    <img id="background" class="absolute -z-50 -left-20 top-0 max-w-[877px]"
         src="https://laravel.com/assets/img/welcome/background.svg"/>
    <form action="{{route('otp.generate')}}" method="post">
        @csrf
        <x-input-label dir="rtl" for="p_code" :value="__('شماره تلفن')"/>
        <x-text-input name="phone" maxlength="11" value="{{old('phone')}}" type="text" id="phone" required class="mt-2"
                      autofocus/>
        <x-input-error :messages="$errors->get('phone')" class="mt-2" dir="rtl"/>
        <x-auth-session-status :status="session('guest')" dir="rtl"/>

        <div class="mt-16 text-right flex justify-between items-center">
            <a href="{{route('login')}}">
                <x-primary-button type="button">
                    {{__('بازگشت')}}
                </x-primary-button>
            </a>
            <x-secondary-button type="submit">
                {{__('دریافت رمز یکبار مصرف')}}
            </x-secondary-button>
        </div>
    </form>
</x-guest-layout>
