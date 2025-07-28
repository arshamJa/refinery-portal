<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="min-h-screen bg-background font-sans text-gray-900">

<x-sessionMessage name="status"/>


<div class="min-h-screen flex relative">
    <!-- Left Image / Branding Section -->
    <div
        class="hidden lg:flex lg:w-1/2 bg-cover bg-center relative"
        style="background-image: url('{{ asset('sunset-refinery.jpg') }}');"
    >
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/30"></div>
    </div>
    {{$slot}}
    <!-- Mobile Background -->
    <div
        class="lg:hidden absolute inset-0 bg-cover bg-center opacity-10"
        style="background-image: url('{{ asset('sunset-refinery.jpg') }}');"
    ></div>
</div>



</body>
</html>






