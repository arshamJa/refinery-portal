<button {{ $attributes->merge(['type' => 'button', 'class' => '
inline-flex items-center px-4 py-2 bg-blue-500 text-white border
border-transparent rounded-md font-semibold text-xs uppercase shadow-sm
hover:bg-blue-600 hover:outline-none hover:ring-2 hover:ring-blue-500
hover:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-300
']) }}>
    {{ $slot }}
</button>

