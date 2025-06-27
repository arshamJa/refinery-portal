<header
    class="absolute top-0 right-0 md:px-12 px-4 md:mr-64 md:border-b left-0 z-50 pt-4 mb-20 flex justify-between items-center">
    <nav class="hidden md:flex items-center gap-x-10 w-full">
        <x-link.link href="{{route('dashboard')}}" :active="request()->is('dashboard')">
            {{__('داشبورد ')}}{{ auth()->user()->getTranslatedRole() }}
        </x-link.link>
        <x-link.link href="{{Illuminate\Support\Facades\URL::signedRoute('profile')}}" :active="request()->is('profile')">
            {{__('پروفایل')}}
        </x-link.link>
    </nav>
    <div class="md:hidden">
        <button @click="open = !open"
                class="text-gray-800 hover:bg-gray-50 transition ease-in-out duration-300 rounded-md p-1 hover:text-gray-800 focus:outline-none">
            <!-- Open Button -->
            <svg :class="open ? 'block' : 'hidden'" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 block">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>
            </svg>
            <!-- Close Button -->
            <svg :class="open ? 'hidden' : 'block'" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 block text-white hover:text-gray-800">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <div>
        <x-application-logo class="size-8"/>
    </div>
</header>
