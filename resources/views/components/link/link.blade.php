@props(['active'])

@php
    $classes = ($active ?? false)
                ? 'rounded-md bg-gray-700 px-3 py-2 text-sm font-medium text-white'
                : 'rounded-md px-3 py-2 text-sm font-medium text-gray-800 transition ease-in-out duration-200 hover:bg-gray-700 hover:text-white';
@endphp

<a wire:navigate.hover {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
