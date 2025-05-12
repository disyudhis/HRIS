<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
    <div class="p-6">
        <!-- Header dengan informasi user dan status -->
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#3085FE] to-blue-600 flex items-center justify-center text-white font-medium text-lg shadow-inner">
                        {{ $trip->user->name[0] }}
                    </div>
                    <div class="absolute -bottom-1 -right-1 h-5 w-5 rounded-full border-2 border-white {{ $trip->status_color }} flex items-center justify-center">
                        <span class="text-white text-xs">{{ substr($trip->status, 0, 1) }}</span>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $trip->user->name }}</h3>
                    <div class="flex items-center text-sm text-gray-500 space-x-2">
                        <span>{{ $trip->destination }}</span>
                        <span>•</span>
                        <span>{{ $trip->start_date->format('M d') }} - {{ $trip->end_date->format('M d, Y') }}</span>
                        <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-xs">
                            {{ $trip->duration }} days
                        </span>
                    </div>
                </div>
            </div>

            @if($trip->status === 'pending')
                <div class="flex space-x-3">
                    <button
                        wire:click="showApproveModal({{ $trip->id }})"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        Approve
                    </button>
                    <button
                        wire:click="showRejectModal({{ $trip->id }})"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                        Reject
                    </button>
                </div>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium {{ $trip->status_color }}">
                    {{ ucfirst($trip->status) }}
                </span>
            @endif
        </div>

        <!-- Informasi utama -->
        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-700">Purpose</h4>
                    <p class="mt-1 text-sm text-gray-600">{{ $trip->purpose }}</p>
                </div>

                <!-- Cost section yang ditingkatkan berdasarkan model BusinessTrips -->
                <div class="relative">
                    <h4 class="text-sm font-medium text-gray-700 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        Budget Estimation
                    </h4>
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        <div class="bg-gray-50 rounded-lg p-2">
                            <p class="text-xs text-gray-500">Daily Cost</p>
                            <p class="text-sm font-semibold text-gray-800">Rp. {{ number_format($trip->estimated_cost_per_day, 2) }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2">
                            <p class="text-xs text-gray-500">Total Days</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $trip->total_days ?? $trip->duration }} days</p>
                        </div>
                    </div>
                    <div class="mt-2 bg-blue-50 rounded-lg p-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-blue-700">Total Budget</span>
                            <span class="text-lg font-bold text-blue-800">Rp. {{ number_format($trip->total_estimated_cost, 2) }}</span>
                        </div>
                    </div>

                    <div x-data="{ showDetails: false }" class="mt-2">
                        <button
                            @click="showDetails = !showDetails"
                            class="text-blue-600 text-xs hover:underline focus:outline-none flex items-center">
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
                            <p>Calculation: Rp. {{ number_format($trip->estimated_cost_per_day, 2) }} × {{ $trip->total_days ?? $trip->duration }} days = Rp. {{ number_format($trip->total_estimated_cost, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menghapus bagian expense summary karena tidak ada di model -->
            <!-- Expenses dapat ditambahkan di masa depan jika ada model/relasi untuk tracking pengeluaran aktual -->

            @if($trip->notes)
                <div class="mt-4">
                    <h4 class="text-sm font-medium text-gray-700">Notes</h4>
                    <p class="mt-1 text-sm text-gray-600">{{ $trip->notes }}</p>
                </div>
            @endif

            @if($trip->status !== 'pending')
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">
                            {{ ucfirst($trip->status) }} on {{ $trip->approved_at->format('M d, Y H:i') }}
                        </span>
                        @if($trip->status === 'rejected' && $trip->rejection_reason)
                            <span class="text-red-600">{{ $trip->rejection_reason }}</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal removed since cost breakdown is not in the model structure -->
    <!-- Jika di masa depan ingin menambahkan breakdown, dapat ditambahkan kembali dengan struktur yang sesuai -->

</div>
