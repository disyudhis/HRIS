<aside class="hidden md:flex fixed left-0 top-0 bottom-0 w-64 flex-col bg-white border-r border-gray-200 z-40">
    <!-- Logo & App Name -->
    <div class="p-6 flex items-center">
        <x-application-logo class="w-10 h-10" />
        <h1 class="ml-3 text-xl font-bold text-[#101317]">HR Attendee</h1>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6 space-y-1">
        <!-- Employee Default Navigation -->
        @if (!Auth::user()->isAdmin())
            <a href="{{ route('dashboard.check-in') }}"
                class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('dashboard.check-in') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Attendee
            </a>
        @endif

        <!-- Admin Navigation Links -->
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
        @endif

        <!-- Manager Navigation Links -->
        @if (Auth::user()->isManager())
            <a href="{{ route('manager.approvals.business-trips.index') }}"
                class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('manager.approvals.business-trips.index') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                SPPD
            </a>

            <a href="{{ route('manager.approvals.overtime.index') }}"
                class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('manager.approvals.overtime.index') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Overtime
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
        @endif

        <!-- Employee Schedule Link -->
        @if (Auth::user()->isEmployee())
        <a href="{{ route('employee.approvals.business-trips.index') }}"
                class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('employee.approvals.business-trips.index') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                SPPD
            </a>

            <a href="{{ route('employee.approvals.overtime.index') }}"
                class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('employee.approvals.overtime.*') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Overtime
            </a>

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
    </nav>

    <!-- User Profile Section -->
    <div class="p-4 border-t border-gray-200">
        <div x-data="{ open: false, showLogoutModal: false }" class="relative">
            <!-- User Profile Button -->
            <button @click="open = !open"
                class="flex items-center w-full p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <div
                    class="w-10 h-10 flex-shrink-0 rounded-full bg-[#3085FE] flex items-center justify-center text-white mr-3">
                    {{ Auth::user()->name[0] ?? 'U' }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-gray-900 truncate">{{ Auth::user()->name ?? 'User' }}</div>
                    <div class="text-xs text-gray-500 truncate">{{ ucfirst(Auth::user()->user_type) }}</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute bottom-full left-0 mb-3 w-full bg-white rounded-lg shadow-lg overflow-hidden z-50"
                style="display: none;">

                <!-- Profile Header -->
                <div class="p-3 bg-gradient-to-r from-[#3085FE] to-blue-600 text-white">
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 flex-shrink-0 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white mr-3 shadow-inner">
                            {{ Auth::user()->name[0] ?? 'U' }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="font-bold text-white truncate">{{ Auth::user()->name ?? 'User' }}</div>
                            <div class="text-xs text-white/80 truncate">
                                {{ Auth::user()->email ?? 'user@example.com' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Menu Options -->
                <div class="p-2">
                    <a href="{{ route('profile.index') }}"
                        class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-md transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#3085FE]" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Profile
                    </a>
                    <hr class="my-2 border-gray-200">

                    <button wire:ignore @click="showLogoutModal = true; open = false"
                        class="flex items-center w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </div>
            </div>

            <!-- Logout Modal -->
            <div x-show="showLogoutModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90"
                class="fixed inset-0 z-10 flex items-center justify-center p-4" style="display: none;">

                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black bg-opacity-50" @click="showLogoutModal = false"></div>

                <!-- Modal Content -->
                <div class="relative bg-white rounded-xl shadow-lg max-w-md w-full overflow-hidden">
                    <!-- Modal Header -->
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto shrink-0 flex items-center justify-center size-12 rounded-full bg-red-100 sm:mx-0 sm:size-10">
                                <svg class="size-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>

                            <div class="mt-3 text-center sm:mt-0 sm:ms-4 sm:text-start">
                                <h3 class="text-lg font-medium text-gray-900">Confirm Logout</h3>
                                <div class="mt-4 text-sm text-gray-600">
                                    Are you sure you want to log out of your account?
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-end">
                        <button @click="showLogoutModal = false"
                            class="mr-3 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                            Cancel
                        </button>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                                Log out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
