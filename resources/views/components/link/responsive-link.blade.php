@props(['active'])

@php
    $classes = ($active ?? false)
                ? 'block text-right text-[#0184B1] rounded-md bg-[#B6F9FF] px-3 py-2 text-base font-medium'
                : 'block text-right rounded-md px-3 py-2 text-base font-medium transition ease-in-out duration-200 text-gray-600 hover:text-black hover:bg-gray-200';
@endphp

<a wire:navigate.hover {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
