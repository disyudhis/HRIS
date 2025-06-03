<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
    <div class="p-6">
        <!-- Header dengan informasi user dan status -->
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#3085FE] to-blue-600 flex items-center justify-center text-white font-medium text-lg shadow-inner">
                        {{ $overtime->user->name[0] }}
                    </div>
                    <div class="absolute -bottom-1 -right-1 h-5 w-5 rounded-full border-2 border-white {{ $overtime->status_color }} flex items-center justify-center">
                        <span class="text-white text-xs">{{ substr($overtime->status, 0, 1) }}</span>
                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $overtime->user->name }}</h3>
                        <span class="px-2 py-0.5 rounded-full bg-orange-100 text-orange-800 text-xs font-medium">
                            {{ $overtime->no_reference ?? 'OT-' . str_pad($overtime->id, 6, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                    <div class="flex items-center text-sm text-gray-500 space-x-2">
                        <span>{{ $overtime->date->format('M d, Y') }}</span>
                        <span>•</span>
                        <span>{{ $overtime->start_time->format('H:i') }} - {{ $overtime->end_time->format('H:i') }}</span>
                        <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-xs">
                            {{ $overtime->duration }}
                        </span>
                    </div>
                </div>
            </div>

            @if($overtime->status === 'pending')
                <div class="flex space-x-3">
                    <button
                        wire:click="showApproveModal({{ $overtime->id }})"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        Approve
                    </button>
                    <button
                        wire:click="showRejectModal({{ $overtime->id }})"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                        Reject
                    </button>
                </div>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium {{ $overtime->status_color }}">
                    {{ ucfirst($overtime->status) }}
                </span>
            @endif
        </div>

        <!-- Informasi utama -->
        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-700">Reason</h4>
                    <p class="mt-1 text-sm text-gray-600">{{ $overtime->reason }}</p>
                </div>

                <!-- Cost section untuk overtime -->
                <div class="relative">
                    <h4 class="text-sm font-medium text-gray-700 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        Overtime Compensation
                    </h4>
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        <div class="bg-gray-50 rounded-lg p-2">
                            <p class="text-xs text-gray-500">Hourly Rate</p>
                            <p class="text-sm font-semibold text-gray-800">Rp. {{ number_format(\App\Models\Overtime::HOURLY_RATE, 0) }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2">
                            <p class="text-xs text-gray-500">Duration</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $overtime->duration }}</p>
                        </div>
                    </div>
                    <div class="mt-2 bg-orange-50 rounded-lg p-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-orange-700">Total Compensation</span>
                            <span class="text-lg font-bold text-orange-800">Rp. {{ number_format($overtime->estimated_cost, 0) }}</span>
                        </div>
                    </div>

                    <div x-data="{ showDetails: false }" class="mt-2">
                        <button
                            @click="showDetails = !showDetails"
                            class="text-orange-600 text-xs hover:underline focus:outline-none flex items-center">
                            <span x-text="showDetails ? 'Hide details' : 'Show calculation'"></span>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 :class="{'rotate-180': showDetails}"
                                 class="h-3 w-3 ml-1 transform transition-transform duration-200"
                                 viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="showDetails"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             class="mt-2 text-xs text-gray-600 bg-gray-50 p-2 rounded">
                            <p>Calculation: Rp. {{ number_format(\App\Models\Overtime::HOURLY_RATE, 0) }} × {{ $overtime->duration }} = Rp. {{ number_format($overtime->estimated_cost, 0) }}</p>
                            @if($overtime->duration > \App\Models\Overtime::MAX_PAID_HOURS)
                                <p class="text-orange-600 mt-1">Note: Compensation capped at {{ \App\Models\Overtime::MAX_PAID_HOURS }} hours (Rp. {{ number_format(\App\Models\Overtime::MAX_OVERTIME_COST, 0) }} max)</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($overtime->status !== 'pending')
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">
                            {{ ucfirst($overtime->status) }} on {{ $overtime->approved_at->format('M d, Y H:i') }}
                        </span>
                        @if($overtime->status === 'rejected' && $overtime->rejection_reason)
                            <span class="text-red-600">{{ $overtime->rejection_reason }}</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
