<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HR Attendee') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css'])

    <!-- Alpine.js -->
    @vite(['resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased bg-white">

    <div class="min-h-screen flex flex-col">
        {{ $slot }}
    </div>

    <x-toast />

    @livewireScripts
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session()->has('toast'))
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {!! json_encode(session('toast')) !!}
                }));
            @endif
        });
    </script>
</body>

</html>
