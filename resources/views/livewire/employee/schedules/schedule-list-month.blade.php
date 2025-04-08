<div>
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
            <button wire:click="changeDate('prev')" class="p-2 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <h2 class="text-sm font-medium text-gray-900">{{ $month }}</h2>

            <button wire:click="changeDate('next')" class="p-2 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <div class="flex space-x-2">
            <button wire:click="changeView('week')"
                class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200">
                Week
            </button>
            <button wire:click="changeView('month')"
                class="px-4 py-2 rounded-lg text-sm font-medium bg-[#3085FE] text-white">
                Month
            </button>
        </div>
    </div>

    <div class="overflow-x-auto -mx-4 sm:mx-0">
        <div class="inline-block min-w-full align-middle">
            <div class="grid grid-cols-7 gap-1 text-center">
                <div class="text-[10px] sm:text-sm font-medium text-gray-700 p-1 sm:p-2">S</div>
                <div class="text-[10px] sm:text-sm font-medium text-gray-700 p-1 sm:p-2">M</div>
                <div class="text-[10px] sm:text-sm font-medium text-gray-700 p-1 sm:p-2">T</div>
                <div class="text-[10px] sm:text-sm font-medium text-gray-700 p-1 sm:p-2">W</div>
                <div class="text-[10px] sm:text-sm font-medium text-gray-700 p-1 sm:p-2">T</div>
                <div class="text-[10px] sm:text-sm font-medium text-gray-700 p-1 sm:p-2">F</div>
                <div class="text-[10px] sm:text-sm font-medium text-gray-700 p-1 sm:p-2">S</div>
            </div>

            <div class="grid grid-cols-7 gap-1">
                @foreach ($weeks as $week)
                    @foreach ($week as $day)
                        <div
                            class="border rounded-lg min-h-[60px] sm:min-h-[100px]
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

                            <div class="p-1 overflow-y-auto max-h-[50px] sm:max-h-[80px]">
                                @if (isset($schedules[$day['date']]))
                                    @foreach ($schedules[$day['date']] as $schedule)
                                        <div
                                            class="mb-1 p-1 rounded-lg text-[8px] sm:text-xs
                                           {{ $schedule->shift_type === 'morning'
                                               ? 'bg-green-100 text-green-800'
                                               : ($schedule->shift_type === 'afternoon'
                                                   ? 'bg-yellow-100 text-yellow-800'
                                                   : ($schedule->shift_type === 'night'
                                                       ? 'bg-indigo-100 text-indigo-800'
                                                       : 'bg-gray-100 text-gray-800')) }}">
                                            <div class="font-medium">{{ $schedule->start_time->format('H:i') }}</div>
                                            <div class="hidden sm:block">{{ $schedule->location }}</div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
</div>
