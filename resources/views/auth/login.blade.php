<x-guest-layout>
    <div class="mt-12 flex flex-col items-center">
        <!-- Logo -->
        <div class="mb-12 mt-4">
            <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
        </div>

        <!-- Welcome Text -->
        <div class="w-full text-center mb-8">
            <h1 class="text-4xl font-bold text-dark">
                Welcome Back <span class="inline-block animate-pulse">👋</span>
            </h1>
            <h2 class="text-4xl font-bold mt-1">
                to <span class="text-[#3085FE]">HR Attendee</span>
            </h2>
            <p class="text-[#ACAFB5] text-lg mt-2">Hello there, login to continue</p>
        </div>

        {{-- <x-auth-session-status class="mb-4" :status="session('status')" /> --}}
        <livewire:auth.login-form />
    </div>
</x-guest-layout>

