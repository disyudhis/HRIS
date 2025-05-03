<tr class="hover:bg-gray-50 transition-all duration-200">
    <td class="py-3 px-4">
        <div class="font-medium text-gray-900">{{ $overtime->date->format('d M Y') }}</div>
    </td>
    <td class="py-3 px-4">
        <div class="text-sm">{{ $overtime->start_time }} - {{ $overtime->end_time }}</div>
    </td>
    <td class="py-3 px-4">
        {{ $this->calculateHours($overtime->start_time, $overtime->end_time) }} jam
    </td>
    <td class="py-3 px-4">
        <div class="text-sm text-gray-500 truncate max-w-xs">{{ $overtime->reason }}</div>
    </td>
    <td class="py-3 px-4">
        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $this->getStatusClass($overtime->status) }}">
            {{ ucfirst($overtime->status) }}
        </span>
    </td>
    <td class="py-3 px-4">
        <div class="flex space-x-2">
            <button wire:click="getOvertimeDetails({{ $overtime->id }})" class="text-blue-600 hover:text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                    <path fill-rule="evenodd"
                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                        clip-rule="evenodd" />
                </svg>
            </button>
            @if ($overtime->status === 'pending')
                <button wire:click="confirmCancelOvertime({{ $overtime->id }})" class="text-red-600 hover:text-red-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            @endif
        </div>
    </td>
</tr>