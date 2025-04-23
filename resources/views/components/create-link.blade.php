@props(['href' => '#', 'loading' => false])

<a
    href="{{ $href }}"
    x-data="{ loading: false }"
    @click="loading = true"
    class="inline-flex items-center  gap-2 px-5 py-4 rounded-xl text-white bg-gradient-to-r from-[#4332BD] to-[#6B5CFF] hover:from-[#3624A7] hover:to-[#5949F6] transition-all duration-300 shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4332BD] disabled:opacity-50"
    :class="{ 'pointer-events-none': loading }"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="1.5"
         :class="{ 'animate-spin': loading }">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 4.5v15m7.5-7.5h-15"/>
    </svg>
    <span class="text-sm font-medium">
        {{ $slot }}
    </span>
</a>
