<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
    <div class="p-6">
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
                        wire:click="approve({{ $trip->id }})"
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

        <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h4 class="text-sm font-medium text-gray-700">Purpose</h4>
                <p class="mt-1 text-sm text-gray-600">{{ $trip->purpose }}</p>
            </div>

            @if($trip->estimated_cost)
                <div>
                    <h4 class="text-sm font-medium text-gray-700">Estimated Cost</h4>
                    <p class="mt-1 text-sm text-gray-600">${{ number_format($trip->estimated_cost, 2) }}</p>
                </div>
            @endif

            @if($trip->notes)
                <div class="md:col-span-2">
                    <h4 class="text-sm font-medium text-gray-700">Notes</h4>
                    <p class="mt-1 text-sm text-gray-600">{{ $trip->notes }}</p>
                </div>
            @endif

            @if($trip->status !== 'pending')
                <div class="md:col-span-2 pt-3 border-t border-gray-100">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">
                            {{ ucfirst($trip->status) }} on {{ $trip->approved_at->format('M d, Y h:i A') }}
                        </span>
                        @if($trip->status === 'rejected' && $trip->rejection_reason)
                            <span class="text-red-600">{{ $trip->rejection_reason }}</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>