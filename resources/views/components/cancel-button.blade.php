<button {{ $attributes->merge(['type' => 'button','class' => 'text-xs px-4 py-2 hover:ring-2 hover:ring-[#E96742] text-white hover:ring-offset-2 border border-transparent rounded-md font-semibold text-xs uppercase transition ease-in-out duration-300 border-[#990302] hover:border-transparent bg-[#E96742]']) }}>
    {{ $slot }}
</button>
