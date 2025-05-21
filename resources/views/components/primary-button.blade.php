<button {{ $attributes->merge(['type' => 'submit', 'class' => ' hover:ring-2
 hover:ring-gray-800 hover:ring-offset-2
 border border-transparent rounded-md text-xs uppercase transition ease-in-out duration-300
 border-[#990302] hover:border-transparent
  px-4 py-2 bg-gray-800 dark:bg-gray-200
 font-semibold  text-white dark:text-gray-800 uppercase hover:bg-gray-700 dark:hover:bg-white
  focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none
 focus:ring-offset-2 dark:focus:ring-offset-gray-800']) }}>
    {{ $slot }}
</button>




