<a {{$attributes->merge([ 'class' => 'px-4 py-2 bg-orange-500 hover:bg-orange-600 text-[#F5F0F1] hover:ring-2 hover:ring-orange-500 hover:ring-offset-2 border border-transparent rounded-md font-semibold text-xs uppercase hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'])}}>
    {{$slot}}
</a>
