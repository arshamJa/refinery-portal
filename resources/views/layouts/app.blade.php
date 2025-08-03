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
    <style>[x-cloak] {display: none !important;}</style>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{asset('flowBiteChart.js')}}"></script>

    @livewireStyles
    <link rel="stylesheet" href="{{asset('multiSelect.css')}}">
</head>
<body>
<div dir="rtl" x-data="{ open: true }" x-cloak>
    <!-- Top Navigation Bar -->
    <x-navigation/>

    <div>
        <!-- Sidebar -->
        <aside
            :class="{'translate-x-full': open, 'translate-x-0': !open}"
            class="fixed bg-gray-800 md:pt-2 px-2 inset-y-0 right-0 top-0 bottom-0 z-20 w-64 transform transition-transform duration-300 md:translate-x-0">
            <livewire:side-bar/>
        </aside>

        <!-- Main content -->
        <main class="flex-1 px-4 md:mr-72  mt-20">
            @if (session()->has('status'))
                <div
                    x-data="{ showMessage: true }" x-show="showMessage" x-transition x-cloak
                    x-init="setTimeout(() => showMessage = false, 4000)"
                    dir="rtl"
                    class="fixed top-5 right-5 z-[99] max-w-xs bg-white border border-gray-200 rounded-xl shadow-lg dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="flex p-4">
                        <div class="shrink-0">
                            <svg class="shrink-0 size-4 text-teal-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16"
                                 height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path
                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"></path>
                            </svg>
                        </div>
                        <div class="ms-3">
                            <p class="text-sm text-gray-700 dark:text-neutral-400">
                                {{ session('status') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            <x-sessionMessage name="status"/>
            {{ $slot }}
        </main>

    </div>
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
