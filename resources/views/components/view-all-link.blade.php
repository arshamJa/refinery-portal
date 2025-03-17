<a {{$attributes->merge([ 'class' => 'px-4 py-2 bg-[#E96742] text-[#F5F0F1] hover:ring-2 hover:ring-[#E96742] hover:ring-offset-2 border border-transparent rounded-md font-semibold text-xs uppercase hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'])}}>
    {{$slot}}
</a>
