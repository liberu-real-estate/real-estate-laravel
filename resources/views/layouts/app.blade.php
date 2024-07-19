<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Liberu Real Estate') }}</title>

    <!-- Styles -->
    @vite('resources/css/app.css')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('components.header')
        @include('components.home-navbar')

        <main>
            @yield('content')
        </main>

        @include('components.footer')
    </div>

    <!-- Scripts -->
    @vite('resources/js/app.js')
</body>
</html>