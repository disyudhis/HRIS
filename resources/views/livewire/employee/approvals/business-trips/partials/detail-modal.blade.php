<div x-data="{ open: false, tripData: null }" x-show="open" x-on:open-detail-modal.window="open = true; tripData = $event.detail.trip"
    x-on:close-detail-modal.window="open = false" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

        <div x-show="open" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button @click="open = false" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mt-2 space-y-6">
                <div wire:loading class="text-center py-8">
                    <svg class="animate-spin h-8 w-8 mx-auto text-[#3085FE]" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">Memuat detail...</p>
                </div>

                <div wire:loading.remove x-show="tripData">
                    <div class="space-y-6">
                        <!-- Basic Info -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900" x-text="tripData?.destination"></h3>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <span x-text="tripData?.start_date"></span>
                                <span class="mx-2">-</span>
                                <span x-text="tripData?.end_date"></span>
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Status</h4>
                                <p class="mt-1 text-sm" x-text="tripData?.status"
                                    :class="{
                                        'text-yellow-600 uppercase': tripData?.status === 'pending',
                                        'text-green-600 uppercase': tripData?.status === 'approved',
                                        'text-red-600 uppercase': tripData?.status === 'rejected',
                                        'text-gray-600 uppercase': tripData?.status === 'cancelled'
                                    }">
                                </p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Durasi</h4>
                                <p class="mt-1 text-sm text-gray-600" x-text="`${tripData?.duration} hari`"></p>
                            </div>
                            <div class="col-span-2">
                                <h4 class="text-sm font-medium text-gray-700">Keperluan</h4>
                                <p class="mt-1 text-sm text-gray-600" x-text="tripData?.purpose"></p>
                            </div>
                            <div class="col-span-2">
                                <h4 class="text-sm font-medium text-gray-700">Estimasi Biaya</h4>
                                <p class="mt-1 text-sm text-gray-600"
                                    x-text="tripData?.estimated_cost ? `Rp ${new Intl.NumberFormat('id-ID').format(tripData.estimated_cost)}` : '-'">
                                </p>
                            </div>
                        </div>

                        <!-- Approval Timeline -->
                        <div x-show="tripData?.approvals?.length > 0">
                            <h4 class="text-sm font-medium text-gray-700 mb-4">Riwayat Persetujuan</h4>
                            <div class="space-y-4">
                                <template x-for="approval in tripData?.approvals" :key="approval.id">
                                    <div class="border-l-2 border-gray-200 pl-4 pb-4">
                                        <div class="relative">
                                            <div class="absolute -left-[21px] mt-1.5">
                                                <div class="h-3 w-3 rounded-full"
                                                    :class="{
                                                        'bg-yellow-500': approval.status === 'pending',
                                                        'bg-green-500': approval.status === 'approved',
                                                        'bg-red-500': approval.status === 'rejected'
                                                    }">
                                                </div>
                                            </div>
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="text-sm font-medium" x-text="approval.approver_name"></p>
                                                    <p class="text-xs text-gray-500"
                                                        x-text="approval.approver_position"></p>
                                                </div>
                                                <span class="text-xs px-2 py-1 rounded-full"
                                                    :class="{
                                                        'bg-yellow-100 text-yellow-800': approval.status === 'pending',
                                                        'bg-green-100 text-green-800': approval.status === 'approved',
                                                        'bg-red-100 text-red-800': approval.status === 'rejected'
                                                    }"
                                                    x-text="approval.status">
                                                </span>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500" x-text="approval.updated_at"></p>
                                            <p class="mt-2 text-sm text-gray-600" x-show="approval.notes"
                                                x-text="approval.notes"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Timestamps -->
                        <div class="border-t pt-4 mt-6">
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>Diajukan: <span x-text="tripData?.created_at"></span></span>
                                <template x-if="tripData?.updated_at">
                                    <span>Terakhir diupdate: <span x-text="tripData?.updated_at"></span></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
