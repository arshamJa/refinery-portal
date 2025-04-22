<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex
items-center px-4 py-2 bg-red-600 border
border-transparent rounded-md font-semibold text-xs text-white uppercase
active:bg-red-700 focus:outline-none
hover:ring-2 hover:ring-red-500 hover:ring-offset-2
dark:focus:ring-offset-gray-800 transition ease-in-out duration-300']) }}>
    {{ $slot }}
</button>
