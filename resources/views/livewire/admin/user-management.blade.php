<div>
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-[#101317]">User Management</h1>
            <button wire:click="openModal" class="bg-[#3085FE] text-white px-4 py-2 rounded-lg text-sm font-medium">
                Add New User
            </button>
        </div>

        @if(session()->has('message'))
            <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6">
                {{ session('message') }}
            </div>
        @endif

        <div class="mb-4">
            <div class="relative">
                <input type="text" wire:model.debounce.300ms="search" placeholder="Search users..."
                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#3085FE] focus:border-transparent">
                <div class="absolute left-3 top-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('name')">
                            <div class="flex items-center">
                                Name
                                @if($sortField === 'name')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('email')">
                            <div class="flex items-center">
                                Email
                                @if($sortField === 'email')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('user_type')">
                            <div class="flex items-center">
                                User Type
                                @if($sortField === 'user_type')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('department')">
                            <div class="flex items-center">
                                Department
                                @if($sortField === 'department')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-[#3085FE] flex items-center justify-center text-white">
                                            {{ $user->name[0] }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $user->employee_id ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $user->user_type === 'admin' ? 'bg-purple-100 text-purple-800' :
                                       ($user->user_type === 'manager' ? 'bg-blue-100 text-blue-800' :
                                       'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($user->user_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->department ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="edit({{ $user->id }})" class="text-[#3085FE] hover:text-blue-900 mr-3">
                                    Edit
                                </button>

                                <button wire:click="delete({{ $user->id }})" class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>

    <!-- User Modal -->
    <x-modal wire:model="showModal" maxWidth="lg">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $editMode ? 'Edit User' : 'Create New User' }}
                    </h3>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" wire:model.defer="name" id="name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" wire:model.defer="email" id="email"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Password {{ $editMode ? '(Leave blank to keep current)' : '' }}
                            </label>
                            <input type="password" wire:model.defer="password" id="password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" wire:model.defer="password_confirmation" id="password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        </div>

                        <div>
                            <label for="user_type" class="block text-sm font-medium text-gray-700">User Type</label>
                            <select wire:model.defer="user_type" id="user_type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                <option value="employee">Employee</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                            @error('user_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="manager_id" class="block text-sm font-medium text-gray-700">Manager</label>
                            <select wire:model.defer="manager_id" id="manager_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                <option value="">None</option>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                @endforeach
                            </select>
                            @error('manager_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                            <input type="text" wire:model.defer="department" id="department"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            @error('department') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                            <input type="text" wire:model.defer="position" id="position"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            @error('position') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="employee_id" class="block text-sm font-medium text-gray-700">Employee ID</label>
                            <input type="text" wire:model.defer="employee_id" id="employee_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            @error('employee_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            @if($editMode)
                <button wire:click="update" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#3085FE] text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Update
                </button>
            @else
                <button wire:click="store" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#3085FE] text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Create
                </button>
            @endif

            <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Cancel
            </button>
        </div>
    </x-modal>
</div>

