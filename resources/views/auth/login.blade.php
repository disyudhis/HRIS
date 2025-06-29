<x-guest-layout>
    <div class="flex min-h-screen">
        <!-- Left Side - Interactive Background -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-500 to-indigo-700 relative overflow-hidden">
            <div class="absolute inset-0 bg-pattern opacity-10"></div>
            <div class="flex flex-col justify-center items-center relative z-10 w-full px-12">
                <div class="mb-8">
                    <x-application-logo class="w-24 h-24 fill-current text-white" />
                </div>
                <h2 class="text-4xl font-bold text-white mb-6">ESS SIMBIKA</h2>
                <p class="text-white text-xl mb-8 text-center">Manage attendance efficiently with our powerful dashboard</p>

                <!-- Simple animated elements -->
                <div class="flex justify-center space-x-4 mt-8">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm animate-bounce">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm animate-pulse delay-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm animate-bounce delay-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 bg-white flex flex-col justify-center items-center px-6 py-12 lg:px-16">
            <div class="w-full max-w-md">
                <!-- Logo for mobile only -->
                <div class="lg:hidden mb-12 flex justify-center">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </div>

                <!-- Welcome Text -->
                <div class="w-full text-center mb-10">
                    <h1 class="text-4xl font-bold text-gray-900 flex items-center justify-center">
                        Welcome Back <span class="inline-block animate-wave ml-2">👋</span>
                    </h1>
                    <h2 class="text-3xl font-bold mt-2">
                        to <span class="text-[#3085FE]">ESS SIMBIKA</span>
                    </h2>
                    <p class="text-gray-500 text-lg mt-3">Hello there, login to continue</p>
                </div>

                <!-- Login Form -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100 transition-all duration-300 hover:shadow-xl">
                    <livewire:auth.login-form />
                </div>

                <!-- Additional Info -->
                {{-- <div class="mt-8 text-center text-gray-500">
                    <p>Need help? Contact <a href="#" class="text-[#3085FE] hover:underline">support@hrattendee.com</a></p>
                </div> --}}
            </div>
        </div>
    </div>
</x-guest-layout>

<style>
    @keyframes wave {
        0% { transform: rotate(0deg); }
        20% { transform: rotate(-10deg); }
        40% { transform: rotate(10deg); }
        60% { transform: rotate(-10deg); }
        80% { transform: rotate(10deg); }
        100% { transform: rotate(0deg); }
    }
    .animate-wave {
        animation: wave 2s infinite;
        transform-origin: bottom right;
        display: inline-block;
    }
    .bg-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
    }
</style>
