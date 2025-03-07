<nav {{ $attributes->merge(['class' => 'flex justify-between mb-4 mt-20']) }}>
    <ol {{ $attributes->merge(['class' => 'inline-flex items-center mb-3 space-x-1 text-xs text-neutral-500 [&_.active-breadcrumb]:text-neutral-600 [&_.active-breadcrumb]:font-medium sm:mb-0']) }}>
        {{$slot}}
    </ol>
</nav>
