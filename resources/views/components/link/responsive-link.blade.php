@props(['active'])

@php
    $classes = ($active ?? false)
                ? 'block text-sm text-right text-white rounded-md bg-gray-700  px-3 py-2 text-base font-medium'
                : 'block text-sm text-right rounded-md px-3 py-2 text-base font-medium transition ease-in-out duration-300 text-gray-300 hover:bg-gray-700 hover:text-white';
@endphp

<a wire:navigate wire:cloak {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
