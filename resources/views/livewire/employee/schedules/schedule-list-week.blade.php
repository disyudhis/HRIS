<div>
    <div class="flex flex-wrap justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
            <button wire:click="changeDate('prev')" class="p-2 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <h2 class="text-sm font-medium text-gray-900">
                {{ Carbon\Carbon::parse($selectedDate)->startOfWeek()->format('M d') }} -
                {{ Carbon\Carbon::parse($selectedDate)->endOfWeek()->format('M d, Y') }}
            </h2>


            <button wire:click="changeDate('next')" class="p-2 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

        </div>

        <div class="flex space-x-2">
            <button wire:click="changeView('week')"
                class="px-4 py-2 rounded-lg text-sm font-medium bg-[#3085FE] text-white">
                Week
            </button>
            <button wire:click="changeView('month')"
                class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200">
                Month
            </button>
        </div>
    </div>

    <div class="overflow-x-auto -mx-4 sm:mx-0">
        <div class="inline-block min-w-full align-middle">
            <div class="grid grid-cols-7 gap-1 sm:gap-2">
                @foreach ($dates as $date)
                    <div
                        class="border rounded-lg {{ $date['is_today'] ? 'border-blue-300 bg-blue-50' : 'border-gray-200' }}">
                        <div
                            class="p-1 sm:p-2 border-b {{ $date['is_today'] ? 'border-blue-300 bg-blue-100' : 'border-gray-200 bg-gray-50' }}">
                            <div class="text-center">
                                <div
                                    class="text-[10px] sm:text-sm font-medium {{ $date['is_today'] ? 'text-blue-800' : 'text-gray-700' }}">
                                    {{ $date['day'] }}</div>
                                <div
                                    class="text-sm sm:text-lg {{ $date['is_today'] ? 'text-blue-600 font-semibold' : 'text-gray-900' }}">
                                    {{ $date['day_number'] }}</div>
                            </div>
                        </div>

                        <div class="p-1 sm:p-2 min-h-[100px] sm:min-h-[150px] overflow-y-auto">
                            @if (isset($schedules[$date['date']]))
                                @foreach ($schedules[$date['date']] as $schedule)
                                    <div
                                        class="mb-1 sm:mb-2 p-1 sm:p-2 rounded-lg text-[10px] sm:text-xs
                                       {{ $schedule->shift_type === 'morning'
                                           ? 'bg-green-100 text-green-800'
                                           : ($schedule->shift_type === 'afternoon'
                                               ? 'bg-yellow-100 text-yellow-800'
                                               : ($schedule->shift_type === 'night'
                                                   ? 'bg-indigo-100 text-indigo-800'
                                                   : 'bg-gray-100 text-gray-800')) }}">
                                        <div class="font-medium">{{ $schedule->start_time->format('H:i') }} -
                                            {{ $schedule->end_time->format('H:i') }}</div>
                                        <div>{{ $schedule->location }}</div>
                                        @if ($schedule->notes)
                                            <div class="mt-1 text-[8px] sm:text-xs truncate">{{ $schedule->notes }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-[10px] sm:text-xs text-gray-500 mt-4">No schedule</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
