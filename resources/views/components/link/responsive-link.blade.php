@props(['active'])

@php
    $classes = ($active ?? false)
                ? 'block text-right rounded-md bg-gray-700 px-3 py-2 text-base font-medium text-white'
                : 'block text-right rounded-md px-3 py-2 text-base font-medium transition ease-in-out duration-200 text-gray-800 hover:bg-gray-700 hover:text-white';
@endphp

<a wire:navigate.hover {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
