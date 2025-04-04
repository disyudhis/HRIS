<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-40">
    <div class="flex justify-around">
        <a href="{{ route('dashboard') }}"
            class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('dashboard') ? 'text-[#3085FE]' : 'text-gray-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-xs mt-1">Home</span>
        </a>

        <!-- Admin Only Mobile Navigation -->
        @if (Auth::user()->isAdmin())
        <a href="{{ route('admin.users.index') }}"
            class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('admin.users.*') ? 'text-[#3085FE]' : 'text-gray-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span class="text-xs mt-1">Users</span>
        </a>

        <a href="{{ route('admin.offices.index') }}"
            class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('admin.offices.index') ? 'text-[#3085FE]' : 'text-gray-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span class="text-xs mt-1">Offices</span>
        </a>
        {{--
        <a href="{{ route('admin.reports') }}"
            class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('admin.reports') ? 'text-[#3085FE]' : 'text-gray-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="text-xs mt-1">Reports</span>
        </a> --}}
        @endif

        <!-- Manager Only Mobile Navigation -->
        @if (Auth::user()->isManager())
        <a href="{{ route('manager.team') }}"
            class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('manager.team') ? 'text-[#3085FE]' : 'text-gray-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="text-xs mt-1">Team</span>
        </a>

        <a href="{{ route('manager.approvals') }}"
            class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('manager.approvals') ? 'text-[#3085FE]' : 'text-gray-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-xs mt-1">Approvals</span>
        </a>
        @endif

        <!-- Employee Navigation (Mobile) -->
        @if (!Auth::user()->isAdmin())
        <a href="{{ route('dashboard.check-in') }}"
            class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('dashboard.check-in') ? 'text-[#3085FE]' : 'text-gray-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span class="text-xs mt-1">Check In</span>
        </a>

        {{-- <a href="{{ route('profile.edit') }}"
            class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('profile.edit') ? 'text-[#3085FE]' : 'text-gray-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-xs mt-1">Profile</span>
        </a> --}}
        @endif
    </div>
</nav>
