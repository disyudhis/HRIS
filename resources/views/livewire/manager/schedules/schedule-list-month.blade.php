<div>
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="relative">
            <input type="text" wire:model.debounce.300ms="search" placeholder="Search employees..."
                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#3085FE] focus:border-transparent">
            <div class="absolute left-3 top-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
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
            <button wire:click="changeView('week')"
                class="px-4 py-2 rounded-lg text-sm font-medium {{ $selectedView === 'week' ? 'bg-[#3085FE] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Week
            </button>
            <button wire:click="changeView('month')"
                class="px-4 py-2 rounded-lg text-sm font-medium {{ $selectedView === 'month' ? 'bg-[#3085FE] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Month
            </button>
        </div>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
            <button wire:click="changeDate('prev')" class="p-2 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <h2 class="text-lg font-medium text-gray-900">{{ $month }}</h2>

            <button wire:click="changeDate('next')" class="p-2 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <div>
            <a href="{{ route('manager.schedules.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#3085FE] hover:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add Schedule
            </a>
        </div>
    </div>

    <!-- Calendar Header -->
    <div class="grid grid-cols-7 gap-1 text-center mb-2">
        <div class="text-[10px] sm:text-sm font-medium text-gray-700 p-1 sm:p-2">S</div>
        <div class="text-[10px] sm:text-sm font-medium text-gray-700 p-1 sm:p-2">M</div>
        <div class="text-[10px] sm:text-sm font-medium text-gray-700 p-1 sm:p-2">T</div>
        <div class="text-[10px] sm:text-sm font-medium text-gray-700 p-1 sm:p-2">W</div>
        <div class="text-[10px] sm:text-sm font-medium text-gray-700 p-1 sm:p-2">T</div>
        <div class="text-[10px] sm:text-sm font-medium text-gray-700 p-1 sm:p-2">F</div>
        <div class="text-[10px] sm:text-sm font-medium text-gray-700 p-1 sm:p-2">S</div>
    </div>

    <!-- Calendar Grid -->
    <div class="grid grid-cols-7 gap-1">
        @foreach ($weeks as $week)
            @foreach ($week as $day)
                <div
                    class="border rounded-lg min-h-[60px] sm:min-h-[120px]
                    {{ $day['is_today'] ? 'border-blue-300 bg-blue-50' : 'border-gray-200' }}
                    {{ !$day['is_current_month'] ? 'opacity-50' : '' }}">
                    <div class="p-1 border-b {{ $day['is_today'] ? 'border-blue-300' : 'border-gray-200' }}">
                        <div class="text-right">
                            <span
                                class="text-[10px] sm:text-sm {{ $day['is_today'] ? 'text-blue-600 font-semibold' : 'text-gray-700' }}">
                                {{ $day['day'] }}
                            </span>
                        </div>
                    </div>

                    <div class="p-1 overflow-y-auto max-h-[50px] sm:max-h-[100px]">
                        @if (isset($schedules[$day['date']]))
                            @php
                                // Group schedules by employee for this day
                                $employeeSchedules = $schedules[$day['date']]->groupBy('user_id');
                            @endphp

                            @foreach ($employeeSchedules as $userId => $userSchedules)
                                @php
                                    $employee = $employees->firstWhere('id', $userId);
                                    if (!$employee) {
                                        continue;
                                    }
                                @endphp

                                <div class="mb-1 p-1 rounded-lg text-[8px] sm:text-xs bg-gray-100">
                                    <div class="font-medium text-gray-800 truncate">{{ $employee->name }}</div>
                                    @foreach ($userSchedules as $schedule)
                                        <div
                                            class="flex justify-between items-center mt-1
                                            {{ $schedule->shift_type === 'morning'
                                                ? 'text-green-800'
                                                : ($schedule->shift_type === 'afternoon'
                                                    ? 'text-yellow-800'
                                                    : ($schedule->shift_type === 'night'
                                                        ? 'text-indigo-800'
                                                        : 'text-gray-800')) }}">
                                            <span>{{ $schedule->start_time->format('H:i') }}</span>
                                            <div class="flex space-x-1">
                                                <a href="{{ route('manager.schedules.edit', $schedule->id) }}"
                                                    class="text-blue-600 hover:text-blue-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-2 w-2 sm:h-3 sm:w-3" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <button wire:click="deleteSchedule({{ $schedule->id }})"
                                                    class="text-red-600 hover:text-red-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-2 w-2 sm:h-3 sm:w-3" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @endif

                        @if ($day['is_current_month'])
                            <a href="{{ route('manager.schedules.create', ['date' => $day['date']]) }}"
                                class="block w-full text-center text-[8px] sm:text-xs text-gray-500 hover:text-[#3085FE] mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 sm:h-4 sm:w-4 mx-auto"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>

    <!-- Mobile View for Employees -->
    <div class="md:hidden mt-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Employees</h3>

        @forelse($employees as $employee)
            <div class="mb-4 border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-50 p-4 flex items-center">
                    <div class="h-10 w-10 rounded-full bg-[#3085FE] flex items-center justify-center text-white mr-3">
                        {{ $employee->name[0] }}
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">{{ $employee->name }}</div>
                        <div class="text-xs text-gray-500">{{ $employee->position ?? 'No position' }}</div>
                    </div>
                </div>

                <div class="p-4">
                    <a href="{{ route('manager.schedules.create', ['employee_id' => $employee->id]) }}"
                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-[#3085FE] hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Schedule
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-8 bg-white rounded-lg shadow border border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No employees found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if ($selectedDepartment !== 'all' || !empty($search))
                        Try adjusting your search or filter criteria.
                    @else
                        You don't have any team members assigned to you yet.
                    @endif
                </p>
            </div>
        @endforelse
    </div>
</div>
