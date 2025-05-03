<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
    <div class="p-6">
        <!-- Header -->
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <div class="flex items-center text-sm text-gray-500 mt-1 space-x-2">
                    <span>{{ $overtime->date->format('d M Y') }}</span>
                    <span>•</span>
                    <span>{{ $overtime->start_time->format('H:m') }} - {{ $overtime->end_time->format('H:m') }}</span>
                    <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-xs">
                        {{ floor(Carbon\Carbon::parse($overtime->start_time)->diffInHours(Carbon\Carbon::parse($overtime->end_time))) }} jam
                    </span>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $this->getStatusClass($overtime->status) }}">
                    {{ ucfirst($overtime->status) }}
                </span>
                @if($overtime->status === 'pending')
                    <button
                        wire:click="confirmCancelOvertime({{ $overtime->id }})"
                        class="p-1 text-gray-400 hover:text-red-500 transition-colors duration-200">
                        <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                @endif
            </div>
        </div>

        <!-- Content -->
        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-700">Alasan Lembur</h4>
                    <p class="mt-1 text-sm text-gray-600">{{ $overtime->reason }}</p>
                </div>

                @if($overtime->tasks)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Detail Tugas</h4>
                        <p class="mt-1 text-sm text-gray-600">{{ $overtime->tasks }}</p>
                    </div>
                @endif
            </div>

            <!-- Approval Status -->
            @if($overtime->status !== 'pending')
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">
                            {{ ucfirst($overtime->status) }} pada {{ $overtime->updated_at->format('d M Y H:i') }}
                        </span>
                        @if($overtime->status === 'rejected' && $overtime->rejection_reason)
                            <span class="text-red-600">{{ $overtime->rejection_reason }}</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="mt-4 flex justify-end">
            <button
                wire:click="getOvertimeDetails({{ $overtime->id }})"
                class="text-sm text-[#3085FE] hover:text-blue-700 font-medium flex items-center transition-colors duration-200">
                <span>Lihat Detail</span>
                <svg class="w-4 h-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </div>
</div>