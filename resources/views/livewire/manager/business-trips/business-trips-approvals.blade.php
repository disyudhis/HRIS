<div>
    @if(session()->has('message'))
        <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 space-y-4 md:space-y-0">
        <div class="flex flex-wrap gap-2">
            <button wire:click="$set('filter', 'pending')" class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'pending' ? 'bg-[#3085FE] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Pending
            </button>
            <button wire:click="$set('filter', 'approved')" class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'approved' ? 'bg-[#3085FE] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Approved
            </button>
            <button wire:click="$set('filter', 'rejected')" class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'rejected' ? 'bg-[#3085FE] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Rejected
            </button>
            <button wire:click="$set('filter', 'completed')" class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'completed' ? 'bg-[#3085FE] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Completed
            </button>
        </div>

        <div class="relative w-full md:w-64">
            <input type="text" wire:model.debounce.300ms="search" placeholder="Search requests..."
                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#3085FE] focus:border-transparent">
            <div class="absolute left-3 top-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($trips as $trip)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="h-12 w-12 rounded-full bg-[#3085FE] flex items-center justify-center text-white">
                            {{ $trip->user->name[0] }}
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $trip->user->name }}</h3>
                            <p class="text-sm text-gray-500">
                                {{ $trip->destination }} •
                                {{ $trip->start_date->format('M d, Y') }} - {{ $trip->end_date->format('M d, Y') }}
                                ({{ $trip->duration }} days)
                            </p>
                        </div>
                    </div>

                    @if($trip->status === 'pending')
                        <div class="flex space-x-2">
                            <button wire:click="approve({{ $trip->id }})" class="px-3 py-1 bg-green-100 text-green-800 rounded-lg text-sm font-medium">
                                Approve
                            </button>
                            <button wire:click="showRejectModal({{ $trip->id }})" class="px-3 py-1 bg-red-100 text-red-800 rounded-lg text-sm font-medium">
                                Reject
                            </button>
                        </div>
                    @else
                        <span class="px-3 py-1 rounded-lg text-sm font-medium
                            {{ $trip->status === 'approved' ? 'bg-green-100 text-green-800' :
                              ($trip->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($trip->status) }}
                        </span>
                    @endif
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="text-sm text-gray-500">
                        <span class="font-medium">Purpose:</span>
                        {{ $trip->purpose }}
                    </div>

                    @if($trip->estimated_cost)
                        <div class="mt-2 text-sm text-gray-500">
                            <span class="font-medium">Estimated Cost:</span>
                            ${{ number_format($trip->estimated_cost, 2) }}
                        </div>
                    @endif

                    @if($trip->notes)
                        <div class="mt-2 text-sm text-gray-500">
                            <span class="font-medium">Notes:</span>
                            {{ $trip->notes }}
                        </div>
                    @endif

                    @if($trip->status !== 'pending')
                        <div class="mt-2 text-sm text-gray-500">
                            <span class="font-medium">{{ ucfirst($trip->status) }} on:</span>
                            {{ $trip->approved_at->format('M d, Y h:i A') }}
                        </div>

                        @if($trip->status === 'rejected' && $trip->rejection_reason)
                            <div class="mt-2 text-sm text-gray-500">
                                <span class="font-medium">Rejection reason:</span>
                                {{ $trip->rejection_reason }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No business trip requests</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($filter === 'pending')
                        There are no pending business trip requests.
                    @elseif($filter === 'approved')
                        There are no approved business trip requests.
                    @elseif($filter === 'rejected')
                        There are no rejected business trip requests.
                    @elseif($filter === 'completed')
                        There are no completed business trips.
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $trips->links() }}
    </div>

    <!-- Rejection Modal -->
    <x-modal wire:model="showDetailsModal" maxWidth="md">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Reject Business Trip Request
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Please provide a reason for rejecting this business trip request.
                        </p>

                        <div class="mt-4">
                            <label for="rejectionReason" class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                            <textarea wire:model.defer="rejectionReason" id="rejectionReason" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]"></textarea>
                            @error('rejectionReason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button wire:click="reject" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                Reject
            </button>
            <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Cancel
            </button>
        </div>
    </x-modal>
</div>

