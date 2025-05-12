<div
    x-data="{ show: @entangle('showRejectionModal') }"
    x-show="show"
    x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50"
    style="display: none;"
>
    <div
        @click.away="show = false"
        class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6"
        x-show="show"
        x-transition
    >
        <h3 class="text-lg font-medium text-gray-900 mb-2">Reject Overtime Request</h3>
        <p class="text-sm text-gray-500 mb-4">Please provide a reason for rejection.</p>
        <textarea
            wire:model.defer="rejectionReason"
            rows="3"
            class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-blue-500 focus:outline-none"
            placeholder="Enter rejection reason..."></textarea>
        @error('rejectionReason')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror

        <div class="mt-4 flex justify-end space-x-3">
            <button
                wire:click="closeModal"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Cancel
            </button>
            <button
                wire:click="reject"
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                Reject Request
            </button>
        </div>
    </div>
</div>
