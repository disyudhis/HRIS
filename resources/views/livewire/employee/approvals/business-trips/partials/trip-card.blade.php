<div
    class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100">
    <!-- Status Bar -->
    <div class="h-1 {{ $trip->status_bar_class }}"></div>

    <div class="p-6">
        <!-- Header Section -->
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                    <div
                        class="flex items-center justify-center w-10 h-10 {{ $trip->icon_background_class }} rounded-lg">
                        <svg class="w-5 h-5 {{ $trip->icon_color_class }}" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $trip->destination }}</h3>
                        <p class="text-sm text-gray-500 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M9.243 3.03a1 1 0 01.727 1.213L9.53 6h2.94l.56-2.243a1 1 0 111.94.486L14.53 6H17a1 1 0 110 2h-2.97l-1 4H15a1 1 0 110 2h-2.47l-.56 2.242a1 1 0 11-1.94-.485L10.47 14H7.53l-.56 2.242a1 1 0 11-1.94-.485L5.47 14H3a1 1 0 110-2h2.97l1-4H5a1 1 0 110-2h2.47l.56-2.243a1 1 0 011.213-.727zM9.03 8l-1 4h2.94l1-4H9.03z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $trip->no_reference }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <span
                    class="px-4 py-2 rounded-full text-sm font-semibold {{ $trip->status_class }} border {{ $trip->status_border_class }} flex items-center">
                    <svg class="w-4 h-4 mr-1 inline" fill="currentColor" viewBox="0 0 20 20">
                        {{ $trip->status_icon }}
                    </svg>
                    {{ ucfirst($trip->status) }}
                </span>

                @if ($trip->status === 'pending')
                    <button wire:click="confirmCancelTrip({{ $trip->id }})"
                        class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                @endif
            </div>
        </div>

        <!-- Trip Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <!-- Dates -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center space-x-2 mb-2">
                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Tanggal Perjalanan</span>
                </div>
                <div class="text-sm text-gray-900 font-semibold">
                    {{ $trip->start_date->format('d M Y') }} - {{ $trip->end_date->format('d M Y') }}
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    <svg class="w-3 h-3 mr-1 inline" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ $trip->start_date->diffInDays($trip->end_date) + 1 }} hari
                </div>
            </div>

            <!-- Cost -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center space-x-2 mb-2">
                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Estimasi Biaya</span>
                </div>
                <div class="text-sm text-gray-900 font-semibold">
                    Rp {{ number_format($trip->total_estimated_cost, 0, ',', '.') }}
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    Rp {{ number_format($trip->estimated_cost_per_day, 0, ',', '.') }} / hari
                </div>
            </div>

            <!-- Purpose -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center space-x-2 mb-2">
                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                            clip-rule="evenodd" />
                        <path
                            d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Keperluan</span>
                </div>
                <div class="text-sm text-gray-900 font-semibold line-clamp-2">
                    {{ $trip->purpose }}
                </div>
            </div>
        </div>

        <!-- Additional Notes -->
        @if ($trip->notes)
            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start space-x-2">
                    <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Catatan:</p>
                        <p class="text-sm text-blue-700 mt-1">{{ $trip->notes }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Status Specific Information -->
        @if ($trip->status !== 'pending')
            <div class="border-t border-gray-100 pt-4">
                @if ($trip->status === 'approved')
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center space-x-4 text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Disetujui {{ $trip->approver ? 'oleh ' . $trip->approver->name : '' }} pada
                                {{ $trip->approved_at->format('d M Y H:i') }}
                            </span>
                        </div>
                    </div>
                @elseif($trip->status === 'rejected')
                    @if ($trip->rejection_reason)
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-3">
                            <div class="flex items-start space-x-2">
                                <svg class="w-4 h-4 text-red-600 mt-0.5 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-red-800">Alasan Penolakan:</p>
                                    <p class="text-sm text-red-700 mt-1">{{ $trip->rejection_reason }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center space-x-4 text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                Ditolak {{ $trip->approver ? 'oleh ' . $trip->approver->name : '' }} pada
                                {{ $trip->updated_at->format('d M Y H:i') }}
                            </span>
                        </div>
                    </div>
                @elseif($trip->status === 'cancelled')
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center space-x-4 text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                Dibatalkan pada {{ $trip->updated_at->format('d M Y H:i') }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="border-t border-gray-100 pt-4">
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <div class="flex items-center space-x-4">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                            Diajukan pada {{ $trip->created_at->format('d M Y H:i') }}
                        </span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="mt-4 flex justify-end">
            <button wire:click="openDetailModal({{ $trip->id }})"
                class="text-blue-600 hover:text-blue-800 font-medium flex items-center space-x-1 hover:bg-blue-50 px-2 py-1 rounded transition-all duration-200">
                <span>Lihat Detail</span>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
