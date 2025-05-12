<div
    x-data="{ show: @entangle('showApprovalModal') }"
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
        <h3 class="text-lg font-medium text-gray-900 mb-2">Approve SPPD Request</h3>
        <p class="text-sm text-gray-500 mb-4">Are you sure to approve this request? This action can't be undone</p>

        <div class="mt-4 flex justify-end space-x-3">
            <button
                wire:click="closeModal"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Cancel
            </button>
            <button
                wire:click="approve"
                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                Approve Request
            </button>
        </div>
    </div>
</div>
