<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Liberu Real Estate') }}</title>

    @if(config('googletagmanager.id'))
        @include('googletagmanager::head')
    @endif

    <!-- Styles -->
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="font-sans antialiased">
    @if(config('googletagmanager.id'))
        @include('googletagmanager::body')
    @endif

    <div class="min-h-screen bg-gray-100 flex flex-col">
        <nav class="bg-green-900 fixed w-full z-10">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <a class="navbar-brand flex items-center" href="/">
                        <img src="{{ asset('/build/images/logo.png') }}" alt="Logo" class="h-8">
                    </a>
                    <div class="hidden lg:flex lg:items-center lg:space-x-4">
                        {!! $menu !!}
                    </div>
                    <div class="lg:hidden">
                        <!-- Add mobile menu toggle button here if needed -->
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow mt-16">
            @yield('content')
        </main>

        @include('components.footer')
    </div>

    <!-- Scripts -->
    @vite('resources/js/app.js')
    @livewireScripts
</body>
</html>
