<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-40">
    <div class="flex justify-around">
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

        <!-- Employee Navigation (Mobile) -->
        @if (Auth::user()->isEmployee())
            <a href="{{ route('dashboard.check-in') }}"
                class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('dashboard.check-in') ? 'text-[#3085FE]' : 'text-gray-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span class="text-xs mt-1">Attendee</span>
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

        <!-- Manager Only Mobile Navigation -->
        @if (Auth::user()->isManager())
            <a href="{{ route('manager.attendance.index') }}"
                class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('manager.attendance.*') ? 'text-[#3085FE]' : 'text-gray-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="text-xs mt-1">Attendance List</span>
            </a>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('manager.approvals.*') || request()->routeIs('manager.approvals.*') ? 'text-[#3085FE]' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xs mt-1">Approvals</span>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-1" @click.away="open = false"
                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 bg-white rounded-xl shadow-lg py-2 z-50 border border-gray-100"
                    style="display: none;">
                    <a href="{{ route('manager.approvals.overtime.index') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('manager.approvals.overtime.*') ? 'text-[#3085FE] bg-blue-50' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 {{ request()->routeIs('manager.approvals.overtime.*') ? 'text-[#3085FE]' : 'text-gray-400' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Overtime</span>
                    </a>
                    <a href="{{ route('manager.approvals.business-trips.index') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('manager.approvals.business-trips.*') ? 'text-[#3085FE] bg-blue-50' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 {{ request()->routeIs('manager.approvals.business-trips.*') ? 'text-[#3085FE]' : 'text-gray-400' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Business Trips</span>
                    </a>
                    <div
                        class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-3 h-3 bg-white transform rotate-45 border-r border-b border-gray-100">
                    </div>
                </div>
            </div>

            <a href="{{ route('manager.schedules.index') }}"
                class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('manager.schedules*') ? 'text-[#3085FE]' : 'text-gray-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-xs mt-1">Schedule</span>
            </a>
        @endif



        @if (Auth::user()->isEmployee())
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('manager.approvals.*') || request()->routeIs('manager.approvals.*') ? 'text-[#3085FE]' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xs mt-1">Approvals</span>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-1" @click.away="open = false"
                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 bg-white rounded-xl shadow-lg py-2 z-50 border border-gray-100"
                    style="display: none;">
                    <a href="{{ route('employee.approvals.overtime.index') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('manager.approvals.overtime.*') ? 'text-[#3085FE] bg-blue-50' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 {{ request()->routeIs('manager.approvals.overtime.*') ? 'text-[#3085FE]' : 'text-gray-400' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Overtime</span>
                    </a>
                    <a href="{{ route('employee.approvals.business-trips.index') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('manager.approvals.business-trips.*') ? 'text-[#3085FE] bg-blue-50' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 {{ request()->routeIs('manager.approvals.business-trips.*') ? 'text-[#3085FE]' : 'text-gray-400' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Business Trips</span>
                    </a>
                    <div
                        class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-3 h-3 bg-white transform rotate-45 border-r border-b border-gray-100">
                    </div>
                </div>
            </div>
            <a href="{{ route('employee.schedules.index') }}"
                class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('employee.schedules.*') ? 'text-[#3085FE]' : 'text-gray-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-xs mt-1">Schedules</span>
            </a>
        @endif

        <a href="{{ route('profile.index') }}"
            class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('profile.*') ? 'text-[#3085FE]' : 'text-gray-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-xs mt-1">Profile</span>
        </a>
    </div>
</nav>
