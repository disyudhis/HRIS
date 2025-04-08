<aside class="hidden md:flex fixed left-0 top-0 bottom-0 w-64 flex-col bg-white border-r border-gray-200 z-40">
    <div class="p-6 flex items-center">
        <x-application-logo class="w-10 h-10" />
        <h1 class="ml-3 text-xl font-bold text-[#101317]">HR Attendee</h1>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-1">
        <!-- Common Navigation Items -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
        </a>

        <!-- Admin Only Navigation -->
        @if (Auth::user()->isAdmin())
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                User Management
            </a>

            <a href="{{ route('admin.offices.index') }}"
                class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.offices.*') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Offices
            </a>

            {{-- <a href="{{ route('admin.departments') }}"
            class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.departments') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            Departments
        </a>

        <a href="{{ route('admin.reports') }}"
            class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.reports') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Reports
        </a> --}}
        @endif

        <!-- Manager Only Navigation -->
        @if (Auth::user()->isManager())
            {{-- <a href="{{ route('manager.team') }}"
                class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('manager.team*') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                My Team
            </a> --}}

            <a href="{{ route('manager.approvals.overtime.index') }}"
                class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('manager.approvals.overtime.index') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Overtime
            </a>

            <a href="{{ route('manager.approvals.business-trips.index') }}"
                class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('manager.approvals.business-trips.index') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                SPPD
            </a>

            <a href="{{ route('manager.schedules.index') }}"
                class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('manager.schedules.*') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Schedule
            </a>

            {{-- <a href="{{ route('manager.reports') }}"
                class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('manager.reports') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Reports
            </a> --}}
        @endif

        <!-- Employee Navigation -->
        @if (!Auth::user()->isAdmin())
            <a href="{{ route('dashboard.check-in') }}"
                class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('dashboard.check-in') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Check In
            </a>
        @endif

        @if (Auth::user()->isEmployee())
            <a href="{{ route('employee.schedules.index') }}"
                class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('employee.schedules.*') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Schedule
            </a>
        @endif

        {{-- <a href="{{ route('history') }}"
            class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('history') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Attendance History
        </a>

        <a href="{{ route('profile.edit') }}"
            class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('profile.edit') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Profile Settings
        </a> --}}
    </nav>

    <div class="p-4 border-t border-gray-200">
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                class="flex items-center w-full p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="w-10 h-10 rounded-full bg-[#3085FE] flex items-center justify-center text-white mr-3">
                    {{ Auth::user()->name[0] ?? 'U' }}
                </div>
                <div class="flex-1 text-left">
                    <div class="font-medium text-gray-900">{{ Auth::user()->name ?? 'User' }}</div>
                    <div class="text-xs text-gray-500 truncate">{{ ucfirst(Auth::user()->role) }}</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <div x-show="open" @click.away="open = false"
                class="absolute bottom-full left-0 mb-2 w-full bg-white rounded-md shadow-lg py-1 z-50"
                style="display: none;">
                {{-- <a href="{{ route('profile.edit') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a> --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>
