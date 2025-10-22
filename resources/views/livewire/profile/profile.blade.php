<div>
    <!-- Mobile View -->
    <div class="md:hidden">
        <div class="flex flex-col items-center px-6 py-8">
            <!-- Profile Photo -->
            <div class="relative mb-4">
                <div class="w-32 h-32 rounded-full overflow-hidden border-2 border-white shadow-md">
                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- User Info -->
            <h1 class="text-2xl font-bold text-[#101317]">{{ $user->name }}</h1>
            <p class="text-[#ACAFB5] text-lg mb-6">{{ $user->position ?? 'Employee' }}</p>

            <!-- Edit Profile Button -->
            <a href="{{ route('profile.edit') }}"
                class="w-full h-[60px] bg-[#3085FE] text-white rounded-xl text-xl font-medium mb-8 flex items-center justify-center">
                Edit Profile
            </a>

            <!-- Menu Items -->
            <div class="w-full space-y-4">
                {{-- <a href="{{ route('profile.show') }}"
                    class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="text-lg font-medium">My Profile</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a> --}}

                {{-- <a href="{{ route('profile.settings') }}"
                    class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-lg font-medium">Settings</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a> --}}

                <a href="{{ route('profile.change-password') }}"
                    class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <span class="text-lg font-medium">Change Password</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                {{-- <a href="{{ route('terms') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-lg font-medium">Terms & Conditions</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a> --}}

                {{-- <a href="{{ route('privacy') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span class="text-lg font-medium">Privacy Policy</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a> --}}

                <!-- Logout Button -->
                <div x-data="{ showLogoutModal: false }">
                    <button @click="showLogoutModal = true" type="button"
                        class="w-full flex items-center p-4 bg-red-50 rounded-xl text-red-500">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </div>
                            <span class="text-lg font-medium">Log out</span>
                        </div>
                    </button>

                    <!-- Logout Confirmation Modal -->
                    <div x-show="showLogoutModal" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-90"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-90"
                        class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
                        <div class="absolute inset-0 bg-black bg-opacity-50" @click="showLogoutModal = false"></div>
                        <div class="relative bg-white rounded-xl shadow-lg max-w-md w-full p-6 overflow-hidden">
                            <div class="flex items-center justify-center mb-6">
                                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-center mb-2">Confirm Logout</h3>
                            <p class="text-gray-600 text-center mb-6">Are you sure you want to log out of your account?
                            </p>
                            <div class="flex space-x-3">
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit"
                                        class="w-full py-3 bg-red-500 text-white rounded-lg font-medium">
                                        Log out
                                    </button>
                                </form>
                                <button @click="showLogoutModal = false"
                                    class="w-full py-3 bg-gray-200 text-gray-800 rounded-lg font-medium">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop View -->
    <div class="hidden md:block">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Profile Settings</h1>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="md:flex">
                    <!-- Left Sidebar -->
                    <div class="md:w-1/3 bg-gray-50 p-6 border-r border-gray-200">
                        <div class="flex flex-col items-center mb-8">
                            <div class="relative mb-4">
                                <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-white shadow-md">
                                    <img src="{{ asset('storage/'. $user->profile_photo_path) }}" alt="{{ $user->name }}"
                                        class="w-full h-full object-cover">
                                </div>

                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                            <p class="text-gray-500 mb-2">{{ $user->position ?? 'Employee' }}</p>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>{{ $user->department ?? 'No Department' }}</span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            @if (!Auth::user()->isAdmin())
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('profile.edit') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Profile
                                </a>
                            @endif

                            {{-- <a href="{{ route('profile.settings') }}"
                                class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('profile.settings') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Settings
                            </a> --}}

                            <a href="{{ route('profile.change-password') }}"
                                class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('profile.change-password') ? 'bg-[#3085FE] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                Change Password
                            </a>

                            <div class="pt-4 mt-4 border-t border-gray-200">
                                {{-- <a href="{{ route('terms') }}"
                                    class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Terms & Conditions
                                </a>

                                <a href="{{ route('privacy') }}"
                                    class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Privacy Policy
                                </a> --}}

                                <!-- Logout Button -->
                                <div x-data="{ showLogoutModal: false }" class="mt-2">
                                    <button @click="showLogoutModal = true" type="button"
                                        class="flex items-center w-full px-4 py-3 rounded-lg text-red-600 hover:bg-red-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Log out
                                    </button>

                                    <!-- Logout Confirmation Modal -->
                                    <div x-show="showLogoutModal"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 transform scale-90"
                                        x-transition:enter-end="opacity-100 transform scale-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 transform scale-100"
                                        x-transition:leave-end="opacity-0 transform scale-90"
                                        class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                        style="display: none;">
                                        <div class="absolute inset-0 bg-black bg-opacity-50"
                                            @click="showLogoutModal = false"></div>
                                        <div
                                            class="relative bg-white rounded-xl shadow-lg max-w-md w-full p-6 overflow-hidden">
                                            <div class="flex items-center justify-center mb-6">
                                                <div
                                                    class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-6 w-6 text-red-500" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <h3 class="text-xl font-bold text-center mb-2">Confirm Logout</h3>
                                            <p class="text-gray-600 text-center mb-6">Are you sure you want to log out
                                                of your account?</p>
                                            <div class="flex space-x-3">
                                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                                    @csrf
                                                    <button type="submit"
                                                        class="w-full py-3 bg-red-500 text-white rounded-lg font-medium">
                                                        Log out
                                                    </button>
                                                </form>
                                                <button @click="showLogoutModal = false"
                                                    class="w-full py-3 bg-gray-200 text-gray-800 rounded-lg font-medium">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Content Area -->
                    <div class="md:w-2/3 p-8">
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Account Information</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Username</h3>
                                    <p class="text-gray-900 font-medium">{{ $user->name ?? 'Not set' }}</p>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Full Name</h3>
                                    <p class="text-gray-900 font-medium">{{ $user->full_name ?? 'Not set' }}</p>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Email Address</h3>
                                    <p class="text-gray-900 font-medium">{{ $user->email }}</p>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Phone Number</h3>
                                    <p class="text-gray-900 font-medium">{{ $user->phone ?? 'Not set' }}</p>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Employee ID</h3>
                                    <p class="text-gray-900 font-medium">{{ $user->employee_id ?? 'Not set' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Work Information</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Bidang</h3>
                                    <p class="text-gray-900 font-medium">{{ $user->user_details->bidang ?? 'Not set' }}</p>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Sub-bidang</h3>
                                    <p class="text-gray-900 font-medium">{{ $user->user_details->sub_bidang ?? 'Not set' }}</p>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Position</h3>
                                    <p class="text-gray-900 font-medium">{{ $user->user_type ?? 'Not set' }}</p>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Office</h3>
                                    <p class="text-gray-900 font-medium">{{ $user->office->name ?? 'Not assigned' }}
                                    </p>
                                </div>

                                <div x-show="$wire.user_type === 'PEGAWAI'" class="bg-gray-50 rounded-xl p-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Manager</h3>
                                    <p class="text-gray-900 font-medium">{{ $user->manager->name ?? 'Not assigned' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Personal Information</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Date of Birth</h3>
                                    <p class="text-gray-900 font-medium">
                                        {{ $user->user_details->birthday ? $user->user_details->birthday->format('F d, Y') : 'Not set' }}
                                    </p>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Gender</h3>
                                    <p class="text-gray-900 font-medium">{{ $user->user_details->gender ?? 'Not set' }}</p>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Blood Type</h3>
                                    <p class="text-gray-900 font-medium">{{ $user->user_details->blood_type ?? 'Not set' }}</p>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Marital Status</h3>
                                    <p class="text-gray-900 font-medium">{{ $user->user_details->marital_status ?? 'Not set' }}</p>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 md:col-span-2">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Address</h3>
                                    <p class="text-gray-900 font-medium">{{ $user->user_details->address ?? 'Not set' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            @if (!Auth::user()->isAdmin())
                                <a href="{{ route('profile.edit') }}"
                                    class="inline-flex items-center px-6 py-3 bg-[#3085FE] text-white rounded-lg font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Profile
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
