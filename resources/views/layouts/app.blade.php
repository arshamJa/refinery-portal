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
    <script src="{{asset('flowBiteChart.js')}}"></script>
</head>
<body class="font-sans antialiased">

{{--<livewire:layout.navigation/>--}}
<div dir="rtl"
    x-data="{ open: true }"
>
    <!-- Top Navigation Bar -->
    <x-navigation/>

    <div>
        <!-- Sidebar -->
        <aside
            :class="{'translate-x-full': open, 'translate-x-0': !open}"
            class="fixed bg-[#F5F0F1] md:pt-2 pt-14 drop-shadow-md pr-2 inset-y-0 right-0 top-0 bottom-0 z-20 w-64 transform transition-transform duration-300 md:translate-x-0">
            <x-side-bar/>
        </aside>

        <!-- Main content -->
        <main class="flex-1 px-4 md:mr-72  mt-20">
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
