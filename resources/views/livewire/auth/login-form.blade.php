<div class="lg:w-1/2 w-full">
    <div class="w-full mx-auto space-y-4">
        <form wire:submit.prevent="login">
            <div class="relative">
                <label class="absolute left-4 top-2 text-primary text-sm font-medium">
                    Email Address
                </label>
                <input type="email" wire:model.defer="email"
                    class="w-full h-[70px] px-4 pt-7 pb-2 border border-border rounded-xl text-dark focus:outline-none focus:ring-2 focus:ring-primary">
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="relative mt-4" x-data="{ showPassword: false }">
                <label class="absolute left-4 top-2 text-primary text-sm font-medium">
                    Password
                </label>
                <input x-bind:type="showPassword ? 'text' : 'password'" wire:model.defer="password"
                    class="w-full h-[70px] px-4 pt-7 pb-2 border border-border rounded-xl text-dark focus:outline-none focus:ring-2 focus:ring-primary">
                <button type="button" x-on:click="showPassword = !showPassword"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-[#292D32]">
                    <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                        <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                        <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                        <line x1="2" x2="22" y1="2" y2="22"></line>
                    </svg>
                    <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" style="display: none;">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- <div class="flex justify-end mt-4">
                <a href="{{ route('password.request') }}" class="text-primary text-base">
                    Forgot Password ?
                </a>
            </div> --}}

            <button type="submit" class="w-full h-[70px] bg-primary text-white rounded-xl text-xl font-medium mt-4">
                Login
            </button>
        </form>
    </div>
</div>
