@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white dark:bg-gray-800 rounded-lg shadow-xl'])
@php
    $alignmentClasses = match ($align) {
//        'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
//        'top' => 'origin-top',
         'left' => 'ltr:left-full ltr:top-0 rtl:right-full rtl:top-0 ml-1',  // dropdown right of trigger (LTR), left of trigger (RTL)
        'right' => 'ltr:right-full ltr:top-0 rtl:left-full rtl:top-0 mr-1', // dropdown left of trigger (LTR), right of trigger (RTL)

        default => 'ltr:origin-top-right rtl:origin-top-left end-0',
    };

    $width = match ($width) {
        '48' => 'w-48',
        default => $width,
    };
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open" class="cursor-pointer">
        {{ $trigger }}
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-50 mt-2 {{ $width }} rounded-lg shadow-lg {{ $alignmentClasses }}"
         style="display: none;">

        <div class="rounded-lg ring-1 ring-black ring-opacity-10 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>

