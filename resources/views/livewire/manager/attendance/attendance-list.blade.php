{{-- livewire.manager.attendance.attendance-list.blade.php --}}
<div class="max-w-7xl mx-auto my-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                    </path>
                </svg>
                Attendance Overview
            </h1>
            <p class="text-gray-600 text-sm mt-1">View employee attendance status</p>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                <!-- Date Navigation -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Date</label>
                    <div class="flex items-center space-x-2">
                        <button wire:click="previousDay"
                            class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <input type="date" wire:model.live="selectedDate"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button wire:click="nextDay"
                            class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <button wire:click="today"
                        class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Today
                    </button>
                </div>

                <!-- User Filter -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Filter by Employee</label>
                    <select wire:model.live="selectedUser"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Employees</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Shift Filter -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Filter by Shift</label>
                    <select wire:model.live="selectedShift"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Shifts</option>
                        @foreach ($shiftTypes as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Attendance Condition Filter -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Filter by Condition</label>
                    <select wire:model.live="selectedCondition"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Conditions</option>
                        @foreach ($conditionTypes as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Info -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 mb-6 border border-blue-100">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <div>
                <h3 class="font-semibold text-gray-900">
                    {{ Carbon\Carbon::parse($selectedDate)->format('l, F d, Y') }}
                </h3>
                <p class="text-sm text-gray-600">
                    {{ Carbon\Carbon::parse($selectedDate)->diffForHumans() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Attendance List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Employee</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                            Shift</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                            Schedule</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($usersWithSchedules as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Employee Info -->
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-700">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        <!-- Mobile: Show shift info -->
                                        <div class="md:hidden mt-1">
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $user->schedule->shift_badge_class }}">
                                                {{ $user->schedule->shift_type_label }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Shift Type (Desktop) -->
                            <td class="px-4 py-4 text-center hidden md:table-cell">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->schedule->shift_badge_class }}">
                                    {{ $user->schedule->shift_type_label }}
                                </span>
                            </td>

                            <!-- Schedule Time (Desktop) -->
                            <td class="px-4 py-4 text-center hidden lg:table-cell">
                                @if ($user->schedule->isWorkingDay())
                                    <div class="text-sm text-gray-900">
                                        <svg class="w-4 h-4 inline mr-1 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $user->schedule->start_time ? $user->schedule->start_time->format('H:i') : '-' }}
                                        -
                                        {{ $user->schedule->end_time ? $user->schedule->end_time->format('H:i') : '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        ({{ $user->schedule->formatted_duration }})
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <!-- Attendance Status - Compact & Mobile Friendly Version -->
                            <td class="px-4 py-4">
                                @if ($user->schedule->isHoliday())
                                    <!-- Holiday Status -->
                                    <div class="flex justify-center">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            Holiday
                                        </span>
                                    </div>
                                @else
                                    @php
                                        $status = $user->schedule->attendance_status;
                                        $hasCheckIn = $user->checkIn !== null;
                                        $hasCheckOut = $user->checkOut !== null;
                                        $isLate =
                                            $hasCheckIn && $user->schedule->isLateCheckIn($user->checkIn->checked_time);
                                        $isEarlyOut =
                                            $hasCheckOut &&
                                            $user->schedule->isEarlyCheckOut($user->checkOut->checked_time);
                                    @endphp

                                    <div class="flex flex-col items-center space-y-1.5">
                                        @if ($status === 'absent')
                                            <!-- Absent -->
                                            <div class="flex items-center justify-center w-full">
                                                <span
                                                    class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-red-50 text-red-700 border border-red-200">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    Absent
                                                </span>
                                            </div>
                                        @elseif (!$hasCheckIn)
                                            <!-- Not Checked In Yet -->
                                            <div class="flex items-center justify-center w-full">
                                                <span
                                                    class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Not Checked In
                                                </span>
                                            </div>
                                        @else
                                            <!-- Has Check In/Out Data -->
                                            <div class="flex items-center justify-center space-x-3 w-full">
                                                <!-- Check In Time -->
                                                <div class="flex flex-col items-center">
                                                    <div
                                                        class="flex items-center space-x-1 {{ $isLate ? 'text-orange-600' : 'text-green-600' }}">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                                            </path>
                                                        </svg>
                                                        <span class="text-sm font-bold">
                                                            {{ \Carbon\Carbon::parse($user->checkIn->checked_time)->format('H:i') }}
                                                        </span>
                                                    </div>
                                                    <span
                                                        class="text-xs text-gray-500 mt-0.5">In{{ $isLate ? ' (Late)' : '' }}</span>
                                                </div>

                                                <span class="text-gray-300 text-lg">→</span>

                                                <!-- Check Out Time -->
                                                <div class="flex flex-col items-center">
                                                    @if ($hasCheckOut)
                                                        <div
                                                            class="flex items-center space-x-1 {{ $isEarlyOut ? 'text-orange-600' : 'text-blue-600' }}">
                                                            <svg class="w-3.5 h-3.5" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                                                </path>
                                                            </svg>
                                                            <span class="text-sm font-bold">
                                                                {{ \Carbon\Carbon::parse($user->checkOut->checked_time)->format('H:i') }}
                                                            </span>
                                                        </div>
                                                        <span
                                                            class="text-xs text-gray-500 mt-0.5">Out{{ $isEarlyOut ? ' (Early)' : '' }}</span>
                                                    @else
                                                        <div class="flex items-center space-x-1 text-gray-400">
                                                            <svg class="w-3.5 h-3.5" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                </path>
                                                            </svg>
                                                            <span class="text-sm font-medium">--:--</span>
                                                        </div>
                                                        <span class="text-xs text-gray-500 mt-0.5">Pending</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Late Info -->
                                            @if ($isLate)
                                                <div class="text-xs text-orange-600 font-medium">
                                                    {{ $user->schedule->formatted_late_time }}
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No employees found</h3>
                                <p class="mt-1 text-sm text-gray-500">Adjust your filters to see more results</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if ($usersWithSchedules->hasPages())
        <div class="mt-6">
            {{ $usersWithSchedules->links() }}
        </div>
    @endif
</div>
