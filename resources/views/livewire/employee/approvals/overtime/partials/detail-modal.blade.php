<div x-data="{ open: false, overtimeData: null }" x-show="open"
    x-on:detail-modal-data.window="open = true; overtimeData = $event.detail.overtime"
    x-on:close-detail-modal.window="open = false" class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                <div class="flex justify-between items-start">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Detail Lembur
                    </h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4" x-show="overtimeData" x-transition>
                    <div wire:loading class="text-center py-4">
                        <svg class="animate-spin h-6 w-6 mx-auto text-blue-500" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Memuat detail...</p>
                    </div>

                    <div wire:loading.remove>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Tanggal Lembur:</p>
                                <p class="font-medium" x-text="overtimeData?.date || '-'"></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Status:</p>
                                <p class="font-medium capitalize" x-text="overtimeData?.status || '-'"></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Waktu:</p>
                                <p class="font-medium" x-text="overtimeData ? (overtimeData?.start_time + ' - ' + overtimeData.end_time) : '-'"></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Durasi:</p>
                                <p class="font-medium" x-text="overtimeData?.hours ? (overtimeData.hours + ' jam') : '-'"></p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <p class="text-gray-500">Alasan Lembur:</p>
                            <p class="mt-1" x-text="overtimeData?.reason || '-'"></p>
                        </div>

                        <div class="mt-4" x-show="overtimeData?.tasks">
                            <p class="text-gray-500">Tugas yang Dikerjakan:</p>
                            <p class="mt-1" x-text="overtimeData?.tasks || '-'"></p>
                        </div>

                        <div class="mt-4" x-show="overtimeData?.rejection_reason">
                            <p class="text-gray-500">Alasan Penolakan:</p>
                            <p class="mt-1 text-red-600" x-text="overtimeData?.rejection_reason || '-'"></p>
                        </div>

                        <!-- Approval Timeline -->
                        <div class="mt-6" x-show="overtimeData?.approvals && overtimeData?.approvals.length > 0">
                            <p class="text-gray-700 font-medium mb-2">Riwayat Persetujuan:</p>
                            <div class="border-l-2 border-gray-200 pl-4 space-y-6 mt-2">
                                <template x-for="approval in overtimeData?.approvals" :key="approval.id">
                                    <div class="relative">
                                        <!-- Status Indicator -->
                                        <div class="absolute -left-6 mt-1">
                                            <div class="h-4 w-4 rounded-full"
                                                 :class="{
                                                    'bg-green-500': approval.status === 'approved',
                                                    'bg-red-500': approval.status === 'rejected',
                                                    'bg-yellow-500': approval.status === 'pending'
                                                 }">
                                            </div>
                                        </div>

                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-medium" x-text="approval?.approver_name"></p>
                                                    <p class="text-sm text-gray-500" x-text="approval?.approver_position"></p>
                                                </div>
                                                <span class="text-xs font-medium px-2 py-1 rounded-full"
                                                      :class="{
                                                        'bg-green-100 text-green-800': approval.status === 'approved',
                                                        'bg-red-100 text-red-800': approval.status === 'rejected',
                                                        'bg-yellow-100 text-yellow-800': approval.status === 'pending'
                                                      }"
                                                      x-text="approval?.status">
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1" x-text="approval?.updated_at"></p>

                                            <div class="mt-2" x-show="approval?.notes">
                                                <p class="text-sm" x-text="approval?.notes"></p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="mt-6 border-t pt-4">
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>Diajukan: <span x-text="overtimeData?.created_at ?? '-'"></span></span>
                                <template x-if="overtimeData?.approved_at">
                                    <span>Disetujui: <span x-text="overtimeData?.approved_at"></span></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex justify-end">
                <button @click="open = false" type="button"
                    class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>