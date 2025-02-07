<button {{$attributes->merge(['class' => 'inline-flex items-center justify-between  p-2 text-sm font-medium text-white transition-colors rounded-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none bg-neutral-800 hover:bg-neutral-700'])}}>
    {{$slot}}
</button>
