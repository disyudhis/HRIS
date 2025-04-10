<div>
    <div class="px-6 py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('profile.index') }}" class="text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-xl font-bold text-gray-900">Change Password</h1>
            <div class="w-6"></div> <!-- Spacer for alignment -->
        </div>

        @if(session()->has('message'))
            <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="updatePassword" class="space-y-6">
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                <input type="password" wire:model.defer="current_password" id="current_password"
                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-14 px-4">
                @error('current_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                <input type="password" wire:model.defer="password" id="password"
                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-14 px-4">
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                <input type="password" wire:model.defer="password_confirmation" id="password_confirmation"
                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-14 px-4">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-[#3085FE] text-white py-4 rounded-xl text-center font-medium">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

