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
    @stack('styles')

    <!-- Alpine.js -->
    @vite(['resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Desktop Sidebar -->
    <aside class="hidden md:flex fixed left-0 top-0 bottom-0 w-64 flex-col bg-white border-r border-gray-200 z-40">
        <div class="p-6 flex items-center">
            <x-application-logo class="w-10 h-10" />
            <h1 class="ml-3 text-xl font-bold text-[#101317]">HR Attendee</h1>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            <a href="{{ route('dashboard.check-in') }}" wire:navigate class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('dashboard.check-in') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Check In
            </a>

            {{-- <a href="{{ route('history') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('history') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Attendance History
            </a>

            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('profile.edit') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profile Settings
            </a> --}}
        </nav>

        <div class="p-4 border-t border-gray-200">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = ! open" class="flex items-center w-full p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-10 rounded-full bg-primary flex items-center justify-center text-white mr-3">
                        {{ Auth::user()->name[0] ?? 'U' }}
                    </div>
                    <div class="flex-1 text-left">
                        <div class="font-medium text-gray-900 text-xs">{{ Auth::user()->name ?? 'User' }}</div>
                        <div class="text-sm text-gray-500 truncate text-xs">{{ Auth::user()->email ?? 'user@example.com' }}</div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" class="absolute bottom-full left-0 mb-2 w-full bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                    {{-- <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a> --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile Header -->
    <header class="fixed top-0 left-0 right-0 bg-white border-b border-gray-200 z-40 md:hidden">
        <div class="px-4 py-3 flex items-center justify-between">
            <div class="flex items-center">
                <x-application-logo class="w-8 h-8" />
                <h1 class="ml-2 text-lg font-semibold text-[#101317]">HR Attendee</h1>
            </div>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white">
                        {{ Auth::user()->name[0] ?? 'U' }}
                    </div>
                </button>

                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <div class="font-medium text-gray-900">{{ Auth::user()->name ?? 'User' }}</div>
                        <div class="text-sm text-gray-500 truncate">{{ Auth::user()->email ?? 'user@example.com' }}</div>
                    </div>
                    {{-- <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a> --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Bottom Navigation -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-40">
        <div class="flex justify-around">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-gray-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-xs mt-1">Home</span>
            </a>

            <a href="{{ route('dashboard.check-in') }}" wire:navigate class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('dashboard.check-in') ? 'text-primary' : 'text-gray-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span class="text-xs mt-1">Check In</span>
            </a>

            {{-- <a href="{{ route('history') }}" class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('history') ? 'text-primary' : 'text-gray-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs mt-1">History</span>
            </a>

            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('profile.edit') ? 'text-primary' : 'text-gray-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs mt-1">Profile</span>
            </a> --}}
        </div>
    </nav>

    <!-- Main Content -->
    <main class="md:ml-64 pt-24 pb-20 px-4 md:py-8 md:px-8">
        {{ $slot }}
    </main>

    @livewireScripts
    @stack('scripts')
</body>
</html>

