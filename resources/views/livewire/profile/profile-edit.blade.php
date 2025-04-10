<div>
    <div class="px-6 py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('profile.index') }}" class="text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-xl font-bold text-gray-900">Edit Profile</h1>
            <div class="w-6"></div> <!-- Spacer for alignment -->
        </div>

        @if(session()->has('message'))
            <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="updateProfile" class="space-y-6">
            <!-- Profile Photo -->
            <div class="flex flex-col items-center mb-6">
                <div class="relative mb-4">
                    <div class="w-24 rounded-full overflow-hidden border-2 border-white shadow-md">
                        @if($photo)
                            <img src="{{ $photo->temporaryUrl() }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <label for="photo" class="absolute bottom-0 right-0 bg-[#3085FE] rounded-full p-2 shadow-md cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </label>
                    <input type="file" wire:model="photo" id="photo" class="hidden" accept="image/*">
                </div>
                @error('photo')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Form Fields -->
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" wire:model="name" id="name"
                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-14 px-4">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" wire:model="email" id="email"
                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-14 px-4">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="tel" wire:model="phone" id="phone"
                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-14 px-4">
                    @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                    <input type="text" wire:model="position" id="position"
                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-14 px-4">
                    @error('position') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                    <input type="text" wire:model="department" id="department"
                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-14 px-4">
                    @error('department') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                    <input type="date" wire:model="date_of_birth" id="date_of_birth"
                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-14 px-4">
                    @error('date_of_birth') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="emergency_contact" class="block text-sm font-medium text-gray-700">Emergency Contact</label>
                    <input type="text" wire:model="emergency_contact" id="emergency_contact"
                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-14 px-4">
                    @error('emergency_contact') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea wire:model="address" id="address" rows="3"
                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] px-4 py-3"></textarea>
                    @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-[#3085FE] text-white py-4 rounded-xl text-center font-medium">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

