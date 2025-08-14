{{--<header--}}
{{--    class="absolute top-0 right-0 md:mr-64 md:px-12 px-4 md:border-b left-0 z-50 pt-4 mb-20 flex justify-between items-center">--}}
{{--<header--}}
{{--    class="fixed top-0 right-0 left-0 px-4 transition-all duration-300 ease-in-out z-50 pt-4 flex justify-between items-center"--}}
{{--    :class="expanded ? 'mr-64' : 'mr-16'" x-transition>--}}
{{--    <nav class="hidden md:flex items-center gap-x-10 w-full">--}}
{{--        <span class="border-b-2 border-b-blue-500 text-gray-800 px-3 py-2 text-sm font-medium">--}}
{{--            {{__('داشبورد ')}}{{ auth()->user()->getTranslatedRole() }}--}}
{{--        </span>--}}
{{--    </nav>--}}
{{--    <div class="md:hidden">--}}
{{--        <button @click="open = !open"--}}
{{--                class="rounded-md p-1 mb-1 focus:outline-none">--}}
{{--            <!-- Open Button -->--}}
{{--            <svg :class="open ? 'block' : 'hidden'" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none"--}}
{{--                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-gray-600  hover:text-gray-800 transition duration-200 ease-in-out">--}}
{{--                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>--}}
{{--            </svg>--}}
{{--            <!-- Close Button -->--}}
{{--            <svg :class="open ? 'hidden' : 'block'" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none"--}}
{{--                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-gray-300  hover:text-gray-50 transition duration-200 ease-in-out">--}}
{{--                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>--}}
{{--            </svg>--}}
{{--        </button>--}}
{{--    </div>--}}
{{--    <div>--}}
{{--        <x-application-logo class="size-10"/>--}}
{{--    </div>--}}
{{--</header>--}}
<header class="fixed inset-x-0 top-0 z-50
        transition-all ease-in-out duration-400"
        :class="expanded ? 'mr-64' : 'mr-16'"
        x-transition>
    <nav class="border-b border-gray-200 bg-gray/80 backdrop-blur shadow-sm">
        <div class="container flex items-center justify-between px-4" style="height: clamp(64px, 9vh, 80px)">
            <div class="hidden md:flex items-center gap-1">
                <span class="bg-sky-500 text-white rounded-md px-3 py-2 text-sm font-semibold">
                    {{ __('داشبورد ') }}{{ auth()->user()->getTranslatedRole() }}
                </span>
            </div>
            <span class="group inline-flex items-center gap-2 rounded-md focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                <x-application-logo class="size-12" />
            </span>
        </div>
    </nav>
</header>


