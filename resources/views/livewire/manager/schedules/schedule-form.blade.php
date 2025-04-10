<div>
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Employee Selection</h3>

                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
                    <div class="relative w-full md:w-64">
                        <input type="text" wire:model.debounce.300ms="search" placeholder="Search employees..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#3085FE] focus:border-transparent">
                        <div class="absolute left-3 top-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <div>
                        <select wire:model="selectedDepartment"
                            class="w-full rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#3085FE] focus:border-transparent">
                            <option value="all">All Departments</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department }}">{{ $department }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex space-x-2">
                        <button type="button" wire:click="selectAllEmployees"
                            class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200">
                            Select All
                        </button>
                        <button type="button" wire:click="clearEmployeeSelection"
                            class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200">
                            Clear
                        </button>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg p-4 max-h-60 overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                        @forelse($employees as $employee)
                            <label class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-50">
                                <input type="checkbox" wire:model="selectedEmployees" value="{{ $employee->id }}"
                                    class="rounded border-gray-300 text-[#3085FE] shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                <span class="text-sm">{{ $employee->name }}</span>
                            </label>
                        @empty
                            <div class="col-span-3 text-center text-gray-500 py-4">
                                No employees found. Please adjust your search or filter.
                            </div>
                        @endforelse
                    </div>
                </div>

                @error('selectedEmployees')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Schedule Details</h3>

                <div class="space-y-4">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" wire:model="date" id="date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="shiftType" class="block text-sm font-medium text-gray-700">Shift Type</label>
                        <select wire:model="shiftType" id="shiftType"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            <option value="morning">Morning (08:00 - 17:00)</option>
                            <option value="afternoon">Afternoon (14:00 - 23:00)</option>
                            <option value="night">Night (23:00 - 07:00)</option>
                        </select>
                        @error('shiftType')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <select wire:model="location" id="location"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            <option value="Office">Office</option>
                            <option value="WFH">Work From Home</option>
                            <option value="Field">Field Work</option>
                            <option value="Remote">Remote Location</option>
                        </select>
                        @error('location')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div> --}}
                </div>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Additional Options</h3>

                <div class="space-y-4">
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea wire:model="notes" id="notes" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]"></textarea>
                        @error('notes')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    @if (!$editMode)
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="isRecurring" id="isRecurring"
                                class="rounded border-gray-300 text-[#3085FE] shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            <label for="isRecurring" class="ml-2 block text-sm text-gray-700">Create recurring
                                schedule</label>
                        </div>

                        @if ($isRecurring)
                            <div class="pl-6 space-y-4">
                                <div>
                                    <label for="repeatFrequency"
                                        class="block text-sm font-medium text-gray-700">Repeat Frequency</label>
                                    <select wire:model="repeatFrequency" id="repeatFrequency"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                    </select>
                                </div>

                                @if ($repeatFrequency === 'weekly')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Repeat on</label>
                                        <div class="flex flex-wrap gap-2">
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" wire:model="repeatDays" value="1"
                                                    class="rounded border-gray-300 text-[#3085FE] shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                                <span class="text-sm">Mon</span>
                                            </label>
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" wire:model="repeatDays" value="2"
                                                    class="rounded border-gray-300 text-[#3085FE] shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                                <span class="text-sm">Tue</span>
                                            </label>
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" wire:model="repeatDays" value="3"
                                                    class="rounded border-gray-300 text-[#3085FE] shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                                <span class="text-sm">Wed</span>
                                            </label>
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" wire:model="repeatDays" value="4"
                                                    class="rounded border-gray-300 text-[#3085FE] shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                                <span class="text-sm">Thu</span>
                                            </label>
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" wire:model="repeatDays" value="5"
                                                    class="rounded border-gray-300 text-[#3085FE] shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]" />
                                                <span class="text-sm">Fri</span>
                                            </label>
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" wire:model="repeatDays" value="6"
                                                    class="rounded border-gray-300 text-[#3085FE] shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]" />
                                                <span class="text-sm">Sat</span>
                                            </label>
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" wire:model="repeatDays" value="0"
                                                    class="rounded border-gray-300 text-[#3085FE] shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]" />
                                                <span class="text-sm">Sun</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <label for="repeatUntil" class="block text-sm font-medium text-gray-700">Repeat
                                        Until</label>
                                    <input type="date" wire:model="repeatUntil" id="repeatUntil"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                    @error('repeatUntil')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('manager.schedules.index') }}"
                class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit"
                class="px-4 py-2 bg-[#3085FE] text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                {{ $editMode ? 'Update Schedule' : 'Create Schedule' }}
            </button>
        </div>
    </form>
</div>
