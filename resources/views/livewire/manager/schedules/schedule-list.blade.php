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

            <h2 class="text-lg font-medium text-gray-900">
                @if ($selectedView === 'week')
                    {{ Carbon\Carbon::parse($selectedDate)->startOfWeek()->format('M d') }} -
                    {{ Carbon\Carbon::parse($selectedDate)->endOfWeek()->format('M d, Y') }}
                @else
                    {{ Carbon\Carbon::parse($selectedDate)->format('F Y') }}
                @endif
            </h2>

            <button wire:click="changeDate('next')" class="p-2 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto -mx-4 sm:mx-0">
        <div class="inline-block min-w-full align-middle">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-2 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24 sm:w-40">
                            Employee
                        </th>
                        @foreach ($weekDates as $date)
                            <th scope="col"
                                class="px-1 sm:px-2 py-2 sm:py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider {{ $date['is_today'] ? 'bg-blue-50' : '' }}">
                                <div>{{ $date['day'] }}</div>
                                <div class="text-sm {{ $date['is_today'] ? 'text-blue-600 font-semibold' : '' }}">
                                    {{ $date['day_number'] }}</div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($employees as $employee)
                        <tr>
                            <td class="px-2 sm:px-6 py-2 sm:py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 sm:h-10 sm:w-10">
                                        <div
                                            class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-[#3085FE] flex items-center justify-center text-white">
                                            {{ $employee->name[0] }}
                                        </div>
                                    </div>
                                    <div class="ml-2 sm:ml-4">
                                        <div
                                            class="text-xs sm:text-sm font-medium text-gray-900 truncate max-w-[80px] sm:max-w-full">
                                            {{ $employee->name }}</div>
                                        <div class="text-xs text-gray-500 hidden sm:block">
                                            {{ $employee->position ?? 'No position' }}</div>
                                    </div>
                                </div>
                            </td>

                            @foreach ($weekDates as $date)
                                <td class="px-1 sm:px-2 py-2 sm:py-4 {{ $date['is_today'] ? 'bg-blue-50' : '' }}">
                                    @if (isset($schedules[$date['date']]))
                                        @foreach ($schedules[$date['date']] as $schedule)
                                            @if ($schedule->user_id === $employee->id)
                                                <div
                                                    class="mb-1 p-1 sm:p-2 rounded-lg text-xs
                                               {{ $schedule->shift_type === 'morning'
                                                   ? 'bg-green-100 text-green-800'
                                                   : ($schedule->shift_type === 'afternoon'
                                                       ? 'bg-yellow-100 text-yellow-800'
                                                       : ($schedule->shift_type === 'night'
                                                           ? 'bg-indigo-100 text-indigo-800'
                                                           : 'bg-gray-100 text-gray-800')) }}">
                                                    <div class="font-medium text-[10px] sm:text-xs">
                                                        {{ $schedule->start_time->format('H:i') }}</div>
                                                    <div class="hidden sm:block text-[10px]">{{ $schedule->location }}
                                                    </div>
                                                    <div class="flex justify-between mt-1">
                                                        <a href="{{ route('manager.schedules.edit', $schedule->id) }}"
                                                            class="text-blue-600 hover:text-blue-800">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </a>
                                                        <button wire:click="deleteSchedule({{ $schedule->id }})"
                                                            class="text-red-600 hover:text-red-800">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif

                                    <a href="{{ route('manager.schedules.create', ['date' => $date['date'], 'employee_id' => $employee->id]) }}"
                                        class="w-full text-center text-[10px] sm:text-xs text-gray-500 hover:text-[#3085FE]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 mx-auto"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </a>
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                No employees found. Add employees to your team to manage their schedules.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
