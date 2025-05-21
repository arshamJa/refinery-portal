<button {{ $attributes->merge(['type' => 'button', 'class' => '
px-4 py-2 bg-orange-400 text-white border
border-transparent rounded-md font-semibold text-xs uppercase shadow-sm
hover:bg-orange-500 hover:outline-none hover:ring-2 hover:ring-orange-500
hover:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-300
']) }}>
    {{ $slot }}
</button>

