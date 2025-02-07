<div class="min-h-full" x-data="{openNav:false}">
    <nav class="bg-white dark:bg-gray-800 border-b shadow-md border-gray-300 dark:border-gray-700">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <x-application-logo class="size-10"/>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <x-link.link href="{{route('dashboard')}}"
                                         :active="request()->is('dashboard')">
                                {{__('داشبورد')}}
                            </x-link.link>
                            {{--                                                        @if(auth()->user()->user_info->is_chat_allowed)--}}
                            {{--                                                            <x-link.link href="{{route('users')}}"--}}
                            {{--                                                                         :active="request()->is('users')">--}}
                            {{--                                                                {{__('چت')}}--}}
                            {{--                                                            </x-link.link>--}}
                            {{--                                                        @endif--}}
                            @if(auth()->user()->user_info->is_dictionary_allowed)
                                @can('view-any')
                                    <x-link.link href="{{route('translate')}}"
                                                 :active="request()->is('translate')">
                                        {{__('دیکشنری')}}
                                    </x-link.link>
                                @endcan
                            @endif
                            @if(auth()->user()->user_info->is_blog_allowed)
                                <x-link.link href="{{route('blogs.index')}}"
                                             :active="request()->is('blogs')">{{__('اخبار و اطلاعیه')}}
                                </x-link.link>
                            @endif
                            @if(auth()->user()->user_info->is_phoneList_allowed)
                                <x-link.link href="{{route('phones.index')}}"
                                             :active="request()->is('phones')">{{__('دفترچه تلفنی')}}
                                </x-link.link>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-4 flex justify-between gap-x-2 items-center md:ml-6">
                        {{--                        @if(Task::where('user_id',auth()->user()->id)->exists())--}}
                        {{--                            <a href="{{Illuminate\Support\Facades\URL::signedRoute('tasks.index')}}"--}}
                        {{--                               class="cursor-pointer relative flex gap-x-1 items-end rounded-md px-3 py-2 text-sm font-medium text-gray-800">--}}
                        {{--                                {{__('پیغام')}}--}}
                        {{--                                <span class="absolute right-1 top-1 flex h-3 w-3"><span--}}
                        {{--                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span><span--}}
                        {{--                                        class="relative inline-flex rounded-full h-3 w-3 bg-sky-500"></span></span>--}}
                        {{--                            </a>--}}
                        {{--                        @endif--}}
{{--                        <button wire:click="logout"--}}
{{--                                class="block w-full rounded-md px-4 py-2 text-sm text-gray-700 text-right hover:text-white transition ease-in-out duration-200 hover:bg-gray-800">--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
{{--                                 stroke="currentColor" class="size-5">--}}
{{--                                <path stroke-linecap="round" stroke-linejoin="round"--}}
{{--                                      d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>--}}
{{--                            </svg>--}}
{{--                        </button>--}}
                    </div>

                </div>
                <div class="-mr-2 flex md:hidden" @click="openNav = !openNav">
                    <!-- Mobile menu button -->
                    <button type="button"
                            class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                            aria-controls="mobile-menu" aria-expanded="false">
                        <span class="absolute -inset-0.5"></span>
                        <span class="sr-only">Open main menu</span>
                        <!-- Open Button -->
                        <svg :class="openNav ? 'hidden' : 'block'"
                             x-cloak
                             class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                        </svg>
                        <!-- Close Button -->
                        <svg :class="openNav ? 'block' : 'hidden'"
                             x-cloak
                             class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="md:hidden" x-show="openNav"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-cloak id="mobile-menu">
            <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
                <x-link.responsive-link href="{{route('dashboard')}}"
                                        :active="request()->is('dashboard')">
                    {{__('داشبورد')}}
                </x-link.responsive-link>
                {{--                                @if(auth()->user()->user_info->is_chat_allowed)--}}
                {{--                                    <x-link.responsive-link href="{{route('users')}}"--}}
                {{--                                                            :active="request()->is('users')">--}}
                {{--                                        {{__('چت')}}--}}
                {{--                                    </x-link.responsive-link>--}}
                {{--                                @endif--}}
                @if(auth()->user()->user_info->is_dictionary_allowed)
                    @can('view-any')
                        <x-link.responsive-link href="{{route('translate')}}"
                                                :active="request()->is('translate')">
                            {{__('دیکشنری')}}
                        </x-link.responsive-link>
                    @endcan
                @endif
                @if(auth()->user()->user_info->is_blog_allowed)
                    <x-link.responsive-link href="{{route('blogs.index')}}"
                                            :active="request()->is('blogs')">
                        {{__('اخبار و اطلاعیه')}}
                    </x-link.responsive-link>
                @endif
                @if(auth()->user()->user_info->is_phoneList_allowed)
                    <x-link.responsive-link href="{{route('phones.index')}}"
                                            :active="request()->is('phones')">
                        {{__('دفترچه تلفنی')}}
                    </x-link.responsive-link>
                @endif

            </div>
            <div class="border-t border-gray-700 pb-3 pt-4">
                @if(Task::where('user_id',auth()->user()->id)->exists())
                    <div class="block text-right rounded-md px-3 py-2 text-base font-medium text-gray-800">
                        <a href="{{Illuminate\Support\Facades\URL::signedRoute('tasks.index')}}"
                           class="cursor-pointer relative inline-flex gap-x-1 items-end rounded-md px-3 py-2 text-sm font-medium text-gray-800">
                            {{__('پیغام')}}
                            <span class="absolute right-1 top-1 flex h-3 w-3"><span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-sky-500"></span></span>
                        </a>
                    </div>
                @endif

                <div class="block text-right rounded-md px-3 py-2 text-base font-medium text-gray-800">
                    {{auth()->user()->user_info->full_name}}
                </div>


                <div class="mt-3 space-y-1 px-2">
                    @can('view-profile-page')
                        <x-link.responsive-link href="{{Illuminate\Support\Facades\URL::signedRoute('profile')}}">
                            {{__('پروفایل')}}
                        </x-link.responsive-link>
                    @endcan
                    <button wire:click="logout"
                            class="flex items-center justify-end gap-x-1 rounded-md px-3 w-full py-2 text-right text-base font-medium hover:text-white transition ease-in-out duration-200 hover:bg-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                        </svg>
                        {{__('خروج')}}
                    </button>
                </div>
            </div>
        </div>
    </nav>
</div>
