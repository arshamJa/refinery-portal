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
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @livewireStyles
    <link rel="stylesheet" href="{{asset('style.css')}}">
    <link rel="stylesheet" href="{{asset('multiSelect.css')}}">
    <link rel="stylesheet" href="{{asset('richText.css')}}">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{asset('jalali-moment.js')}}" ></script>
</head>
<body class="font-sans antialiased bg-gray-50">

{{--<livewire:layout.navigation/>--}}
<div dir="rtl"
    x-data="{ open: true }"
>
    <!-- Top Navigation Bar -->
    <x-navigation/>
{{--    <div class="md:hidden inline-flex bg-red-600">--}}
{{--        <button @click="open = !open"--}}
{{--                class="text-gray-50 hover:bg-gray-50 transition ease-in-out duration-300 rounded-md p-1 hover:text-gray-800 focus:outline-none">--}}
{{--            <!-- Open Button -->--}}
{{--            <svg :class="open ? 'block' : 'hidden'"--}}
{{--                 x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
{{--                 stroke="currentColor" class="size-6 block">--}}
{{--                <path stroke-linecap="round" stroke-linejoin="round"--}}
{{--                      d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>--}}
{{--            </svg>--}}
{{--            <!-- Close Button -->--}}
{{--            <svg :class="open ? 'hidden' : 'block'"--}}
{{--                 x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
{{--                 stroke="currentColor" class="size-6 block">--}}
{{--                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>--}}
{{--            </svg>--}}
{{--        </button>--}}
{{--    </div>--}}
    <div class="flex flex-1">
        <!-- Sidebar -->
        <aside
            :class="{'translate-x-full': open, 'translate-x-0': !open}"
            class="fixed pt-14 pr-2 inset-y-0 right-0 top-0 bottom-0 z-20 min-h-dvh bg-gray-800 text-white w-64 transform transition-transform duration-300 md:static md:translate-x-0">
            <x-side-bar/>
        </aside>

        <!-- Main content -->
        <main  class="flex-1 px-4">
            <x-sessionMessage name="status"/>
            {{ $slot }}
        </main>

    </div>
</div>


@livewireScripts
<script src="{{asset('multiSelect.js')}}"></script>
<script src="{{asset('richText.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>
</html>
