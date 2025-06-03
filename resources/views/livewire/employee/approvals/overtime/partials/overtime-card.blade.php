<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 border border-gray-100">
    <div class="p-6">
        <!-- Header with Status Badge -->
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 {{ $overtime->status_icon_bg_class }} rounded-lg">
                        {!! $overtime->status_icon !!}
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Pengajuan Lembur</h3>
                        <p class="text-sm {{ $overtime->status_text_class }}">
                            {{ $overtime->status_description }}
                        </p>
                        @if ($overtime->no_reference)
                            <p class="text-xs text-gray-400 font-mono">{{ $overtime->no_reference }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $overtime->status_class }}">
                    {{ ucfirst($overtime->status) }}
                </span>
                @if ($overtime->status === App\Models\Overtime::STATUS_PENDING)
                    <button wire:click="confirmCancelOvertime({{ $overtime->id }})"
                        class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200"
                        title="Batalkan pengajuan">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                @endif
            </div>
        </div>

        <!-- Date & Time Info -->
        <div class="bg-gray-50 rounded-lg p-4 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Tanggal</p>
                        <p class="text-sm font-medium text-gray-900">{{ $overtime->date->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Waktu</p>
                        <p class="text-sm font-medium text-gray-900">{{ $overtime->start_time->format('H:i') }} -
                            {{ $overtime->end_time->format('H:i') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Durasi</p>
                        <p class="text-sm font-medium text-gray-900">{{ $overtime->duration }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Estimasi Biaya</p>
                        <p class="text-sm font-medium text-gray-900">Rp
                            {{ number_format($overtime->estimated_cost ?? App\Models\Overtime::calculateCost($overtime->duration), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="space-y-4">
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Alasan Lembur
                </h4>
                <p class="text-sm text-gray-600 bg-blue-50 p-3 rounded-lg">{{ $overtime->reason }}</p>
            </div>
        </div>

        <!-- Approval/Rejection Status -->
        @if ($overtime->status !== App\Models\Overtime::STATUS_PENDING)
            <div class="mt-4 pt-4 border-t border-gray-100">
                @if ($overtime->status === App\Models\Overtime::STATUS_APPROVED)
                    <div class="bg-green-50 rounded-lg p-3 mb-3">
                        <div class="flex items-center gap-2 text-sm text-green-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">
                                Disetujui pada
                                {{ $overtime->approved_at?->format('d M Y, H:i') ?? $overtime->updated_at->format('d M Y, H:i') }}
                                @if ($overtime->approver)
                                    oleh {{ $overtime->approver->name }}
                                @endif
                            </span>
                        </div>
                    </div>
                @elseif($overtime->status === App\Models\Overtime::STATUS_REJECTED)
                    <div class="bg-red-50 rounded-lg p-3 mb-3">
                        <div class="flex items-start gap-2 text-sm text-red-700">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.334 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <div>
                                <p class="font-medium mb-1">
                                    Ditolak pada {{ $overtime->updated_at->format('d M Y, H:i') }}
                                    @if ($overtime->approver)
                                        oleh {{ $overtime->approver->name }}
                                    @endif
                                </p>
                                @if ($overtime->rejection_reason)
                                    <p class="text-red-600">Alasan: {{ $overtime->rejection_reason }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @elseif($overtime->status === App\Models\Overtime::STATUS_CANCELLED)
                    <div class="bg-gray-50 rounded-lg p-3 mb-3">
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                            </svg>
                            <span class="font-medium">Dibatalkan pada
                                {{ $overtime->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Submission Info & Actions -->
        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="flex items-center justify-between text-sm text-gray-500">
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Diajukan pada {{ $overtime->created_at->format('d M Y, H:i') }}
                </span>
                <button wire:click="getOvertimeDetails({{ $overtime->id }})"
                    class="text-[#3085FE] hover:text-blue-700 font-medium flex items-center gap-1 transition-colors duration-200">
                    <span>Lihat Detail</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
