<div class="w-full mx-auto px-4 py-6 bg-gray-50 min-h-screen">
    <!-- Back button - simplified -->
    <a href="{{ route('employee.approvals.business-trips.index') }}"
        class="inline-flex items-center mb-4 text-blue-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        <span>Kembali</span>
    </a>

    <!-- Page title -->
    <h1 class="text-xl font-bold text-gray-800 mb-4">
        {{ $editMode ? 'Edit Perjalanan Dinas' : 'Perjalanan Dinas Baru' }}
    </h1>

    <!-- Form -->
    <form wire:submit.prevent="saveTrip" class="space-y-6" x-data="{
        startDate: @entangle('start_date'),
        endDate: @entangle('end_date'),
        additionalTravelers: @entangle('additional_travelers').defer,
        step: 1,
        calculateDuration() {
            if (!this.startDate || !this.endDate) return '0';
            const start = new Date(this.startDate);
            const end = new Date(this.endDate);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            return diffDays;
        }
    }">
        <!-- Progress indicator -->
        <div class="flex justify-between mb-6">
            <button type="button" @click="step = 1" :class="step === 1 ? 'text-blue-600 font-medium' : 'text-gray-400'" class="flex flex-col items-center text-xs">
                <div :class="step === 1 ? 'bg-blue-600' : 'bg-gray-200'" class="w-6 h-6 rounded-full flex items-center justify-center text-white mb-1">1</div>
                Info
            </button>
            <div class="flex-1 mx-1 mt-3">
                <div class="h-1 bg-gray-200 relative">
                    <div :class="step >= 2 ? 'bg-blue-600' : 'bg-gray-200'" class="h-full absolute inset-0 transition-all duration-300"></div>
                </div>
            </div>
            <button type="button" @click="step = 2" :class="step === 2 ? 'text-blue-600 font-medium' : 'text-gray-400'" class="flex flex-col items-center text-xs">
                <div :class="step === 2 ? 'bg-blue-600' : 'bg-gray-200'" class="w-6 h-6 rounded-full flex items-center justify-center text-white mb-1">2</div>
                Waktu
            </button>
            <div class="flex-1 mx-1 mt-3">
                <div class="h-1 bg-gray-200 relative">
                    <div :class="step >= 3 ? 'bg-blue-600' : 'bg-gray-200'" class="h-full absolute inset-0 transition-all duration-300"></div>
                </div>
            </div>
            <button type="button" @click="step = 3" :class="step === 3 ? 'text-blue-600 font-medium' : 'text-gray-400'" class="flex flex-col items-center text-xs">
                <div :class="step === 3 ? 'bg-blue-600' : 'bg-gray-200'" class="w-6 h-6 rounded-full flex items-center justify-center text-white mb-1">3</div>
                Selesai
            </button>
        </div>

        <!-- Alert messages -->
        @if (session()->has('message'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-3 mb-4 rounded" role="alert">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm">{{ session('message') }}</p>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 mb-4 rounded" role="alert">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Step 1: Trip Information -->
        <div x-show="step === 1" class="space-y-6">
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Informasi Perjalanan
                </h2>

                <!-- Destination field -->
                <div class="mb-4">
                    <label for="destination" class="block text-sm font-medium text-gray-700 mb-1">
                        Lokasi Tujuan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model.live="destination" id="destination" placeholder="Masukkan lokasi tujuan" class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    @error('destination')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Purpose -->
                <div>
                    <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">
                        Tujuan Perjalanan <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model.live="purpose" id="purpose" rows="3" placeholder="Deskripsikan tujuan perjalanan" class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                    @error('purpose')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Transportation options - simplified to dropdown on mobile -->
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Transportasi
                </h2>

                <div>
                    <label for="transportation" class="block text-sm font-medium text-gray-700 mb-1">
                        Jenis Transportasi <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="transportation" id="transportation" class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Transportasi --</option>
                        <option value="plane">Pesawat</option>
                        <option value="train">Kereta</option>
                        <option value="bus">Bus</option>
                        <option value="car">Mobil</option>
                        <option value="motorcycle">Motor</option>
                        <option value="other">Lainnya</option>
                    </select>
                    @error('transportation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Next button -->
            <div class="flex justify-end">
                <button type="button" @click="step = 2" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Lanjut
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Step 2: Date and Cost -->
        <div x-show="step === 2" class="space-y-6">
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Waktu & Biaya
                </h2>

                <!-- Start date -->
                <div class="mb-4">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" wire:model="start_date" x-model="startDate" id="start_date" min="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End date -->
                <div class="mb-4">
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" wire:model="end_date" x-model="endDate" id="end_date" :min="startDate" class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Duration badge -->
                    <div class="mt-2">
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Durasi: <span x-text="calculateDuration()"></span> hari
                        </span>
                    </div>
                </div>

                <!-- Estimated cost -->
                <div>
                    <label for="estimated_cost" class="block text-sm font-medium text-gray-700 mb-1">
                        Estimasi Biaya <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500">Rp</span>
                        </div>
                        <input type="text" wire:model.live="estimated_cost" id="estimated_cost"
                            x-data
                            x-on:input="$event.target.value = $event.target.value.replace(/[^0-9]/g, '')"
                            x-on:blur="$event.target.value = new Intl.NumberFormat('id-ID').format($event.target.value)"
                            x-on:focus="$event.target.value = $event.target.value.replace(/\D/g, '')"
                            placeholder="0"
                            class="pl-10 w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    @error('estimated_cost')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Navigation buttons -->
            <div class="flex justify-between">
                <button type="button" @click="step = 1" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Kembali
                </button>
                <button type="button" @click="step = 3" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Lanjut
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Step 3: Additional and Submit -->
        <div x-show="step === 3" class="space-y-6">
            <!-- Additional travelers -->
            <div class="bg-white p-4 rounded-lg shadow-sm" x-data="{
                travelers: @entangle('additional_travelers').defer || [],
                showTravelerForm: false,
                newTraveler: { name: '', position: '', employee_id: '' },
                addTraveler() {
                    this.travelers.push({...this.newTraveler});
                    this.newTraveler = { name: '', position: '', employee_id: '' };
                    this.showTravelerForm = false;
                }
            }">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Peserta Tambahan
                </h2>

                <!-- Traveler list -->
                <div class="space-y-3 mb-4">
                    <template x-for="(traveler, index) in travelers" :key="index">
                        <div class="bg-gray-50 p-3 rounded-md relative">
                            <button @click="travelers.splice(index, 1)" type="button" class="absolute top-2 right-2 text-gray-400 hover:text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div class="grid grid-cols-1 gap-1">
                                <p class="text-sm font-medium" x-text="traveler.name"></p>
                                <p class="text-xs text-gray-500" x-text="traveler.position + (traveler.employee_id ? ' · ' + traveler.employee_id : '')"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Add traveler form -->
                <div x-show="showTravelerForm" class="bg-gray-50 p-3 rounded-md mb-3">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" x-model="newTraveler.name" class="w-full text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jabatan</label>
                            <input type="text" x-model="newTraveler.position" class="w-full text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">ID Karyawan</label>
                            <input type="text" x-model="newTraveler.employee_id" class="w-full text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="showTravelerForm = false" class="px-3 py-1 text-xs text-gray-600 bg-gray-200 rounded-md">
                                Batal
                            </button>
                            <button type="button" @click="addTraveler()" class="px-3 py-1 text-xs text-white bg-blue-600 rounded-md">
                                Tambah
                            </button>
                        </div>
                    </div>
                </div>

                <button x-show="!showTravelerForm" @click="showTravelerForm = true" type="button" class="inline-flex items-center text-sm text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Tambah Peserta
                </button>
            </div>

            <!-- Notes -->
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Catatan
                </h2>
                <textarea wire:model.live="notes" rows="3" placeholder="Catatan tambahan (opsional)" class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <!-- Summary -->
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Ringkasan
                </h2>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tujuan:</span>
                        <span class="font-medium">{{ $destination ?: 'Belum diisi' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Waktu:</span>
                        <span class="font-medium">
                            {{ $start_date ? date('d/m/Y', strtotime($start_date)) : '?' }} - {{ $end_date ? date('d/m/Y', strtotime($end_date)) : '?' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Transportasi:</span>
                        <span class="font-medium">
                            @switch($transportation)
                                @case('plane') Pesawat @break
                                @case('train') Kereta @break
                                @case('bus') Bus @break
                                @case('car') Mobil @break
                                @case('motorcycle') Motor @break
                                @case('other') Lainnya @break
                                @default Belum dipilih
                            @endswitch
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Biaya:</span>
                        <span class="font-medium">Rp {{ $estimated_cost ? number_format($estimated_cost, 0, ',', '.') : '0' }}</span>
                    </div>
                </div>
            </div>

            <!-- Navigation buttons -->
            <div class="flex justify-between">
                <button type="button" @click="step = 2" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Kembali
                </button>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    {{ $editMode ? 'Update' : 'Kirim' }}
                </button>
            </div>
        </div>
    </form>
</div>