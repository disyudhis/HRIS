<div class="p-4 hover:bg-gray-50 transition-all duration-200" x-data="{ expanded: false }">
    <div class="flex justify-between items-start">
        <div class="flex-grow pr-2">
            <div class="flex items-center mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium text-gray-900">{{ $overtime->date->format('d M Y') }}</span>
            </div>
            <div class="flex items-center text-sm text-gray-500 mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $overtime->start_time }} - {{ $overtime->end_time }}
            </div>
            <p class="text-sm text-gray-500 line-clamp-1">{{ $overtime->reason }}</p>
        </div>
        <div class="flex flex-col items-end">
            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $this->getStatusClass($overtime->status) }} mb-2">
                {{ ucfirst($overtime->status) }}
            </span>
            <button @click="expanded = !expanded"
                class="text-blue-600 text-sm flex items-center focus:outline-none">
                <span x-text="expanded ? 'Sembunyikan' : 'Lihat detail'"></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1"
                    :class="{ 'transform rotate-180': expanded }" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>
    </div>
    <div x-show="expanded" x-collapse class="mt-2 pt-2 border-t border-gray-100">
        <div class="grid grid-cols-2 gap-2 text-sm">
            <div>
                <p class="text-gray-500">Durasi:</p>
                <p class="font-medium">{{ $this->calculateHours($overtime->start_time, $overtime->end_time) }} jam</p>
            </div>
            <div>
                <p class="text-gray-500">Diajukan pada:</p>
                <p class="font-medium">{{ $overtime->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <div class="mt-3">
            <p class="text-gray-500">Alasan Lembur:</p>
            <p class="mt-1">{{ $overtime->reason }}</p>
        </div>

        @if($overtime->tasks)
        <div class="mt-3">
            <p class="text-gray-500">Tugas:</p>
            <p class="mt-1">{{ $overtime->tasks }}</p>
        </div>
        @endif

        @if ($overtime->status === 'pending')
            <div class="mt-3 flex justify-end">
                <button wire:click="confirmCancelOvertime({{ $overtime->id }})"
                    class="text-red-600 hover:text-red-800 flex items-center text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Batalkan Pengajuan
                </button>
            </div>
        @endif
    </div>
</div>