<div>
    <!-- Desktop View -->
    <div class="hidden md:block">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-4">
                <button wire:click="changeDate('prev')" class="p-2 rounded-full hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <h2 class="text-lg font-medium text-gray-900">
                    {{ Carbon\Carbon::parse($selectedDate)->startOfWeek()->format('M d') }} -
                    {{ Carbon\Carbon::parse($selectedDate)->endOfWeek()->format('M d, Y') }}
                </h2>

                <button wire:click="changeDate('next')" class="p-2 rounded-full hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
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

        <div class="overflow-x-auto">
            <div class="grid grid-cols-7 gap-2">
                @foreach ($dates as $date)
                    <div
                        class="border rounded-lg {{ $date['is_today'] ? 'border-blue-300 bg-blue-50' : 'border-gray-200' }}">
                        <div
                            class="p-2 border-b {{ $date['is_today'] ? 'border-blue-300 bg-blue-100' : 'border-gray-200 bg-gray-50' }}">
                            <div class="text-center">
                                <div
                                    class="text-sm font-medium {{ $date['is_today'] ? 'text-blue-800' : 'text-gray-700' }}">
                                    {{ $date['day'] }}</div>
                                <div
                                    class="text-lg {{ $date['is_today'] ? 'text-blue-600 font-semibold' : 'text-gray-900' }}">
                                    {{ $date['day_number'] }}</div>
                            </div>
                        </div>

                        <div class="p-2 min-h-[150px]">
                            @if (isset($schedules[$date['date']]))
                                @foreach ($schedules[$date['date']] as $schedule)
                                    <div
                                        class="mb-2 p-2 rounded-lg text-xs
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
                                            <div class="mt-1 text-xs">{{ $schedule->notes }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Mobile View (Figma Design) -->
    <div class="md:hidden">
        <div class="flex items-center justify-between mb-6">
            <button wire:click="changeDate('prev')" class="p-2 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <h2 class="text-sm text-center text-gray-900">
                {{ Carbon\Carbon::parse($selectedDate)->startOfWeek()->format('M d') }} -
                {{ Carbon\Carbon::parse($selectedDate)->endOfWeek()->format('M d, Y') }}
            </h2>

            <button wire:click="changeDate('next')" class="p-2 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <div class="flex space-x-2 mb-6">
            <button wire:click="changeView('week')"
                class="flex-1 px-4 py-2 rounded-lg text-sm font-medium {{ $view === 'week' ? 'bg-[#3085FE] text-white' : 'bg-gray-100 text-gray-700' }}">
                Week
            </button>
            <button wire:click="changeView('month')"
                class="flex-1 px-4 py-2 rounded-lg text-sm font-medium {{ $view === 'month' ? 'bg-[#3085FE] text-white' : 'bg-gray-100 text-gray-700' }}">
                Month
            </button>
        </div>

        <div class="space-y-4">
            @foreach ($dates as $date)
                @php
                    $dateObj = Carbon\Carbon::parse($date['date']);
                    $hasSchedule = isset($schedules[$date['date']]) && count($schedules[$date['date']]) > 0;
                @endphp

                <div class="relative">
                    <!-- Blue vertical bar for days with schedule, gray for days without -->
                    <div
                        class="absolute left-0 top-0 bottom-0 w-2 rounded-l-lg {{ $hasSchedule ? 'bg-[#3085FE]' : 'bg-gray-300' }}">
                    </div>

                    <div class="pl-4 pb-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">
                            {{ $dateObj->format('F d, Y') }}
                        </h3>

                        @if ($hasSchedule)
                            <div class="flex flex-wrap gap-4">
                                @foreach ($schedules[$date['date']] as $schedule)
                                    <div class="flex items-center">
                                        <div
                                            class="flex items-center justify-center w-12 h-12 rounded-lg bg-blue-100 mr-3">
                                            @if ($schedule->start_time->format('a') == 'am')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#3085FE]"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#3085FE]"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="text-lg font-medium text-gray-900">
                                            {{ $schedule->start_time->format('h:i a') }}
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div
                                            class="flex items-center justify-center w-12 h-12 rounded-lg bg-blue-100 mr-3">
                                            @if ($schedule->end_time->format('a') == 'pm')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#3085FE]"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#3085FE]"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            @endif

                                        </div>
                                        <div class="text-lg font-medium text-gray-900">
                                            {{ $schedule->end_time->format('h:i a') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-gray-100 mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                    </svg>
                                </div>
                                <div class="text-lg font-medium text-gray-400">
                                    00:00 am
                                </div>
                            </div>

                            <div class="flex items-center mt-4">
                                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-gray-100 mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div class="text-lg font-medium text-gray-400">
                                    00:00 am
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
