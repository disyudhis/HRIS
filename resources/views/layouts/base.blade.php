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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Styles -->
    @vite(['resources/css/app.css'])
    @stack('styles')

    <!-- Alpine.js -->
    @vite(['resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-50">
    {{-- @component('components.header')
    @endcomponent --}}

    @component('components.sidebar')
    @endcomponent

    @component('components.mobile-nav')
    @endcomponent

    <!-- Main Content -->
    <main class="md:ml-64 pb-20 px-4 md:py-8 md:px-8">
        {{ $slot }}
    </main>

    <x-toast />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
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
    @stack('scripts')

</body>

</html>
