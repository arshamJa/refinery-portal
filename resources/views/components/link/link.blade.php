@props(['active'])

@php
    $classes = ($active ?? false)
                ? 'border-b-2 border-b-[#001BC9] text-[#001BC9] px-3 py-2 text-sm font-medium'
                : 'px-3 py-2 text-sm font-medium text-gray-600 transition ease-in-out hover:text-blue-500 border-b-transparent border-b-2 hover:border-b-2 hover:border-b-[#001BC9] duration-200';
@endphp

<a wire:navigate {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
