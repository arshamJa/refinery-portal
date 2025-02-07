<x-app-layout>
    <section class="ant dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white text-center">{{__('فرم فراموشی رمز')}}</h2>
            <form action="{{route('resetPassword',['id'=>$id])}}" method="post">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6" dir="rtl">
                    <div class="w-full">
                        <label for="password"
                               class="block mb-2 text-sm text-right font-medium text-gray-900 dark:text-white">
                            {{__('رمز جدید')}}
                        </label>
                        <input type="password" name="password" maxlength="8" id="password" required
                               class="w-full text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                        <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                    </div>
                    <div class="w-full">
                        <label for="conPass"
                               class="block mb-2 text-sm text-right font-medium text-gray-900 dark:text-white">
                            {{__('تکرار رمز جدید')}}
                        </label>
                        <input type="password" name="password_confirmation" maxlength="8" id="conPass" required
                               class="w-full text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
                    </div>
                </div>
                <div class="mt-16 text-right">
                    <button type="submit"
                            class="inline-flex items-center text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                        {{__('تایید رمز جدید')}}
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-app-layout>
