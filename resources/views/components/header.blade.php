<!-- Mobile Header -->
<header class="fixed top-0 left-0 right-0 bg-white border-b border-gray-200 z-40 md:hidden">
    <div class="px-4 py-3 flex items-center justify-between">
        <div class="flex items-center">
            <x-application-logo class="w-8 h-8" />
            <h1 class="ml-2 text-lg font-semibold text-[#101317]">ESS SIMBIKA</h1>
        </div>

        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                <div class="w-8 h-8 rounded-full bg-[#3085FE] flex items-center justify-center text-white">
                    {{ Auth::user()->name[0] ?? 'U' }}
                </div>
            </button>

            <div x-show="open" @click.away="open = false"
                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                <div class="px-4 py-2 border-b border-gray-100">
                    <div class="font-medium text-gray-900">{{ Auth::user()->name ?? 'User' }}</div>
                    <div class="text-xs text-gray-500 truncate">{{ ucfirst(Auth::user()->role) }}</div>
                </div>
                <a href="{{ route('profile.edit') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
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
</header>
