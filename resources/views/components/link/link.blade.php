@props(['active'])

@php
    $classes = ($active ?? false)
                ? 'border-b-2 border-b-[#001BC9] text-gray-900 px-3 py-2 text-sm font-medium'
                : 'px-3 py-2 text-sm font-medium text-gray-600 transition ease-in-out hover:text-gray-900 duration-200';
@endphp

<a wire:navigate.hover {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
