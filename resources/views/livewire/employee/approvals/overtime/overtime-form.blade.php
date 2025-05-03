<form wire:submit.prevent="save" class="space-y-6">
    <div x-data="{ loading: false }" @submit="loading = true">
        <!-- User Name (Readonly) -->
        <div class="mb-6">
            <label for="user_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Pegawai</label>
            <input type="text" id="user_name"
                   value="{{ auth()->user()->name }}"
                   class="bg-gray-50 w-full rounded-lg border-gray-300 focus:border-[#3085FE] focus:ring-[#3085FE]"
                   readonly>
        </div>

        <!-- Date -->
        <div class="mb-6">
            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lembur</label>
            <input type="date" id="date" wire:model="date"
                   class="w-full rounded-lg border-gray-300 focus:border-[#3085FE] focus:ring-[#3085FE]">
            @error('date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Overtime Type -->
        <div class="mb-6">
            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Jenis Lembur</label>
            <select id="type" wire:model="type"
                    class="w-full rounded-lg border-gray-300 focus:border-[#3085FE] focus:ring-[#3085FE]">
                <option value="">Pilih Jenis Lembur</option>
                <option value="weekday">Hari Kerja</option>
                <option value="weekend">Hari Libur</option>
                <option value="holiday">Hari Raya</option>
            </select>
            @error('type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Time Range -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                <input type="time" id="start_time" wire:model="start_time"
                       class="w-full rounded-lg border-gray-300 focus:border-[#3085FE] focus:ring-[#3085FE]">
                @error('start_time')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai</label>
                <input type="time" id="end_time" wire:model="end_time"
                       class="w-full rounded-lg border-gray-300 focus:border-[#3085FE] focus:ring-[#3085FE]">
                @error('end_time')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Work Description -->
        <div class="mb-6">
            <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Uraian Pekerjaan</label>
            <textarea id="reason" wire:model="reason" rows="3"
                      class="w-full rounded-lg border-gray-300 focus:border-[#3085FE] focus:ring-[#3085FE]"
                      placeholder="Jelaskan uraian pekerjaan yang akan dilakukan..."></textarea>
            @error('reason')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tasks -->
        <div class="mb-6">
            <label for="tasks" class="block text-sm font-medium text-gray-700 mb-1">Detail Tugas</label>
            <textarea id="tasks" wire:model="tasks" rows="3"
                      class="w-full rounded-lg border-gray-300 focus:border-[#3085FE] focus:ring-[#3085FE]"
                      placeholder="Rincian tugas yang akan dikerjakan..."></textarea>
            @error('tasks')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('employee.approvals.overtime.index') }}"
               class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3085FE]">
                Batal
            </a>
            <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-[#3085FE] border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3085FE]"
                    :disabled="loading">
                <span x-show="!loading">Simpan</span>
                <span x-show="loading" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menyimpan...
                </span>
            </button>
        </div>
    </div>

    <!-- Success Notification -->
    <div x-data="{ show: false, message: '' }"
         @notify.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
         x-show="show"
         x-transition:enter="transform ease-out duration-300 transition"
         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-0 right-0 mb-4 mr-4 bg-green-50 p-4 rounded-lg border border-green-200"
         style="display: none;">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p x-text="message" class="text-sm font-medium text-green-800"></p>
            </div>
        </div>
    </div>
</form>