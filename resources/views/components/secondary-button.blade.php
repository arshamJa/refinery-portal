{{--<button {{ $attributes->merge(['type' => 'button', 'class' => '--}}
{{--inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border--}}
{{-- border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs--}}
{{--  text-gray-700 dark:text-gray-300 uppercase shadow-sm hover:bg-gray-50--}}
{{--   dark:hover:bg-gray-700 focus:outline-none hover:ring-2 hover:ring-indigo-500--}}
{{--   hover:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25--}}
{{--   transition ease-in-out duration-300--}}

{{--   ']) }}>--}}
{{--    {{ $slot }}--}}
{{--</button>--}}


<button {{ $attributes->merge(['type' => 'button', 'class' => '
inline-flex items-center px-4 py-2 bg-blue-500 text-white border
border-transparent rounded-md font-semibold text-xs uppercase shadow-sm
hover:bg-blue-600 hover:outline-none hover:ring-2 hover:ring-blue-500
hover:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-300
']) }}>
    {{ $slot }}
</button>
