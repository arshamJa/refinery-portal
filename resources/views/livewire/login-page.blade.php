<div>

    <img id="background" class="absolute -z-50 -left-20 top-0 max-w-[877px]" src="https://laravel.com/assets/img/welcome/background.svg" />

{{--    <div--}}
{{--        x-data="{--}}
{{--        tabSelected: 1,--}}
{{--        tabId: $id('tabs'),--}}
{{--        tabButtonClicked(tabButton){--}}
{{--            this.tabSelected = tabButton.id.replace(this.tabId + '-', '');--}}
{{--            this.tabRepositionMarker(tabButton);--}}
{{--        },--}}
{{--        tabRepositionMarker(tabButton){--}}
{{--            this.$refs.tabMarker.style.width=tabButton.offsetWidth + 'px';--}}
{{--            this.$refs.tabMarker.style.height=tabButton.offsetHeight + 'px';--}}
{{--            this.$refs.tabMarker.style.left=tabButton.offsetLeft + 'px';--}}
{{--        },--}}
{{--        tabContentActive(tabContent){--}}
{{--            return this.tabSelected == tabContent.id.replace(this.tabId + '-content-', '');--}}
{{--        }--}}
{{--    }"--}}

{{--        x-init="tabRepositionMarker($refs.tabButtons.firstElementChild);" class="relative w-full max-w-sm"--}}
{{--        dir="rtl">--}}

{{--        <div x-ref="tabButtons" class="relative inline-grid items-center justify-center w-full h-10 grid-cols-2 p-1 text-gray-500 bg-gray-100 rounded-lg select-none">--}}
{{--            <button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button" class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">{{__('ورود')}}</button>--}}
{{--            <button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button" class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">{{__('ثبت نام')}}</button>--}}
{{--            <div x-ref="tabMarker" class="absolute left-0 z-10 w-1/2 h-full duration-300 ease-out" x-cloak><div class="w-full h-full bg-white rounded-md shadow-sm"></div></div>--}}
{{--        </div>--}}
{{--        <div class="relative w-full mt-2 content">--}}
{{--            <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative">--}}
{{--                <!-- Tab Content 1 - Replace with your content -->--}}
{{--                <div class="border rounded-lg shadow-sm bg-card text-neutral-900">--}}
{{--                    <div class="flex flex-col space-y-1.5 p-6">--}}
{{--                        <h3 class="text-lg font-semibold leading-none tracking-tight">Account</h3>--}}
{{--                        <p class="text-sm text-neutral-500">Make changes to your account here. Click save when you're done.</p>--}}
{{--                    </div>--}}
{{--                    <div class="p-6 pt-0 space-y-2">--}}
{{--                        <div class="space-y-1"><label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="name">Name</label><input type="text" placeholder="Name" id="name" value="Adam Wathan" class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md peer border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50" /></div>--}}
{{--                        <div class="space-y-1"><label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="username">Username</label><input type="text" placeholder="Username" id="username" value="@adamwathan" class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md peer border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50" /></div>--}}
{{--                    </div>--}}
{{--                    <div class="flex items-center p-6 pt-0"><button type="button" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium tracking-wide text-white transition-colors duration-200 rounded-md bg-neutral-950 hover:bg-neutral-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none">Save changes</button></div>--}}
{{--                </div>--}}
{{--                <!-- End Tab Content 1 -->--}}
{{--            </div>--}}

{{--            <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative" x-cloak>--}}
{{--                <!-- Tab Content 2 - Replace with your content -->--}}
{{--                <div class="border rounded-lg shadow-sm bg-card text-neutral-900">--}}
{{--                    <div class="flex flex-col space-y-1.5 p-6">--}}
{{--                        <h3 class="text-lg font-semibold leading-none tracking-tight">Password</h3>--}}
{{--                        <p class="text-sm text-neutral-500">Change your password here. After saving, you'll be logged out.</p>--}}
{{--                    </div>--}}
{{--                    <div class="p-6 pt-0 space-y-2">--}}
{{--                        <div class="space-y-1"><label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="password">Current Password</label><input type="password" placeholder="Current Password" id="password" class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md peer border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50" /></div>--}}
{{--                        <div class="space-y-1"><label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="password_new">New Password</label><input type="password" placeholder="New Password" id="password_new" class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50" /></div>--}}
{{--                    </div>--}}
{{--                    <div class="flex items-center p-6 pt-0"><button type="button" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium tracking-wide text-white transition-colors duration-200 rounded-md bg-neutral-950 hover:bg-neutral-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none">Save password</button></div>--}}
{{--                </div>--}}
{{--                <!-- End Tab Content 2 -->--}}
{{--            </div>--}}

{{--        </div>--}}
{{--    </div>--}}


    <form wire:submit="login" dir="rtl">

        <!-- Email Address -->
        <div>
            <x-input-label for="p_code" :value="__('کدپرسنلی')" />
            <x-text-input wire:model="form.p_code" class="mt-2" maxlength="6" required autofocus/>
            <x-input-error :messages="$errors->get('form.p_code')" class="mt-2" />
        </div>

        <!-- Password -->
        <div x-data="{ show: true }">
            <x-input-label for="p_code" class="mt-2" :value="__('رمز')" />
            <div class="relative mt-2">
                <input wire:model="form.password" maxlength="8" required autofocus
                       class="w-full text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50"
                       :type="show ? 'password' : 'text'" type="password">
                <div class="absolute top-1/2 left-4 cursor-pointer" style="transform: translateY(-50%);">
                    <svg class="h-4 text-gray-700 block" fill="none" @click="show = !show" :class="{'hidden': !show, 'block':show }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                        <path fill="currentColor" d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z">
                        </path>
                    </svg>
                    <svg class="h-4 text-gray-700 hidden" fill="none" @click="show = !show" :class="{'block': !show, 'hidden':show }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                        <path fill="currentColor" d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z">
                        </path>
                    </svg>
                </div>
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>
        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('بخاطر بسپار') }}</span>
            </label>
        </div>
        <div class="flex items-center justify-between mt-4">
            <x-primary-button>
                {{ __('ورود') }}
            </x-primary-button>
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
               href="{{route('otp')}}" wire:navigate.hover>
                {{ __('فراموشی رمز؟') }}
            </a>
        </div>
    </form>
</div>
