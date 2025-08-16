<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <style>[x-cloak] {
            display: none !important;
        }</style>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{asset('flowBiteChart.js')}}"></script>

    @livewireStyles
    <link rel="stylesheet" href="{{asset('multiSelect.css')}}">
</head>
<body>
{{--<div dir="rtl" x-data="{ open: true }" x-cloak>--}}
{{--    <!-- Top Navigation Bar -->--}}
{{--    <x-navigation/>--}}
{{--    <!-- Sidebar -->--}}
{{--    <aside--}}
{{--        :class="{'translate-x-full': open, 'translate-x-0': !open}"--}}
{{--        class="fixed bg-gray-800 md:pt-2 px-2 inset-y-0 right-0 top-0 bottom-0 z-20 w-64 transform transition-transform duration-300 md:translate-x-0">--}}
{{--        <livewire:side-bar/>--}}
{{--    </aside>--}}
{{--    <!-- Main content -->--}}
{{--    <main class="flex-1 px-4 md:mr-72  mt-20">--}}
{{--        <x-sessionMessage name="status"/>--}}
{{--        {{ $slot }}--}}
{{--    </main>--}}
{{--</div>--}}

<div class="min-h-screen flex" dir="rtl">
    <!-- Sidebar -->
    <div
        x-data="{ expanded: false, dropdownOpen: false }"
        class="flex relative z-[9999]" x-cloak>
        <x-navigation/>
        <!-- Sidebar container -->
        <div
            :class="expanded ? 'w-64' : 'w-16'"
            class="flex flex-col bg-gray-800 md:pt-2 px-2 transform transition-all duration-300 ease-in-out"
        >
            <!-- Toggle Button -->
            <button
                @click="expanded = !expanded; dropdownOpen = false"
                class="py-2 px-4 flex items-start justify-start hover:cursor-pointer"
            >
                <x-icon.collapse-icon/>
            </button>

            <!-- Menu Items -->
            <nav class="flex-1">
                <livewire:side-bar/>
            </nav>
        </div>
    </div>
    <!-- Main Content -->
    <main class="px-4 mt-4 flex-1 overflow-auto">
        <x-sessionMessage name="status"/>
        {{ $slot }}
    </main>
</div>


@livewireScripts


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('js/persian-date-mask.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        applyPersianDateMask('.persian-date');
    });
</script>
<script src="{{ asset('js/outerGuest.js') }}"></script>
<script src="{{ asset('js/holders-dropdown.js') }}"></script>
<script src="{{ asset('js/manageDropdown.js') }}"></script>
</body>
</html>
