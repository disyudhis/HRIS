<div>
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-[#101317]">
                {{ $isEdit ? 'Edit User: ' . $name : 'Create New User' }}
            </h1>
            <a href="{{ route('admin.users.index') }}" class="text-[#3085FE] hover:underline">
                Back to Users
            </a>
        </div>

        <form wire:submit.prevent="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" wire:model.live="name" id="name" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" wire:model.live="email" id="email" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password {{ $isEdit ? '(Leave blank to keep current)' : '' }}
                    </label>
                    <input type="password" wire:model.live="password" id="password" {{ $isEdit ? '' : 'required' }}
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                        Password</label>
                    <input type="password" wire:model.live="password_confirmation" id="password_confirmation"
                        {{ $isEdit ? '' : 'required' }}
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                </div>

                <div>
                    <label for="office_id" class="block text-sm font-medium text-gray-700">Office</label>
                    <select wire:model.live="office_id" id="office_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        <option value="">Select Office</option>
                        @foreach ($offices as $office)
                            <option value="{{ $office->id }}">{{ $office->name }}</option>
                        @endforeach
                    </select>
                    @error('office_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="user_type" class="block text-sm font-medium text-gray-700">Role</label>
                    <select wire:model.live="user_type" id="user_type" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        <option value="PEGAWAI">Employee</option>
                        <option value="MANAGER">Manager</option>
                    </select>
                    @error('user_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="{{ $user_type === 'MANAGER' ? 'hidden' : '' }} grid grid-cols-1 gap-6">
                    <div>
                        <label for="manager_id" class="block text-sm font-medium text-gray-700">Manager</label>
                        <select wire:model.live="manager_id" id="manager_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]"
                            {{ $office_id ? '' : 'disabled' }}>
                            <option value="">None</option>
                            @foreach ($managers as $manager)
                                <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                            @endforeach
                        </select>
                        @if (!$office_id)
                            <p class="mt-1 text-sm text-amber-600">Please select an office first to see available
                                managers.
                            </p>
                        @endif
                        @error('manager_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                        <input type="text" wire:model.live="department" id="department"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                        <input type="text" wire:model.live="position" id="position"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('position')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700">Employee ID</label>
                    <input type="text" wire:model.live="employee_id" id="employee_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                    @error('employee_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-[#3085FE] text-white px-4 py-2 rounded-lg text-sm font-medium">
                    {{ $isEdit ? 'Update User' : 'Create User' }}
                </button>
            </div>
        </form>
    </div>
</div>
