<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- ... other head contents ... -->
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <!-- ... body contents ... -->
    @vite('resources/js/app.js')
    @livewireScripts
</body>
</html>