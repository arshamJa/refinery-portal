<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center hover:ring-2
 hover:ring-green-800 hover:ring-offset-2
 border border-transparent rounded-md text-xs uppercase transition ease-in-out duration-300
 border-[#28a745] hover:border-transparent
  px-4 py-2 bg-green-600 dark:bg-green-200
 font-semibold  text-white dark:text-green-800 uppercase hover:bg-green-700 dark:hover:bg-white
  focus:bg-green-700 dark:focus:bg-white active:bg-green-900 dark:active:bg-green-300 focus:outline-none
 focus:ring-offset-2 dark:focus:ring-offset-green-800']) }}>
    {{ $slot }}
</button>
