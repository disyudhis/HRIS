<div class="px-4 py-6 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('profile.index') }}" class="text-gray-800 hover:text-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-xl md:text-2xl font-bold text-gray-900">Edit Profile</h1>
            <div class="w-6"></div> <!-- Spacer for alignment -->
        </div>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6">
                {{ session('message') }}
            </div>
        @endif

        <!-- Profile Photo -->
        <div class="flex flex-col items-center mb-6">
            <div class="relative mb-4">
                <div class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden border-2 border-white shadow-md">
                    @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}" alt="{{ $user->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <img src="{{ $user->profile_photo_path }}" alt="{{ $user->name }}"
                            class="w-full h-full object-cover">
                    @endif
                </div>
                <label for="photo"
                    class="absolute bottom-0 right-0 bg-[#3085FE] rounded-full p-2 shadow-md cursor-pointer hover:bg-blue-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </label>
                <input type="file" wire:model="photo" id="photo" class="hidden" accept="image/*">
            </div>
            @error('photo')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Form Sections -->
        <div x-data="{ activeTab: 'account' }" class="bg-white rounded-xl shadow-sm border border-gray-100">
            <!-- Tabs Navigation -->
            <div class="flex overflow-x-auto md:flex-wrap border-b border-gray-200 px-2 md:px-4">
                <button @click="activeTab = 'account'"
                    :class="{ 'border-b-2 border-[#3085FE] text-[#3085FE]': activeTab === 'account' }"
                    class="px-4 py-3 text-sm font-medium whitespace-nowrap hover:text-[#3085FE] transition">
                    Personal Information
                </button>
                <button @click="activeTab = 'personal'"
                    :class="{ 'border-b-2 border-[#3085FE] text-[#3085FE]': activeTab === 'personal' }"
                    class="px-4 py-3 text-sm font-medium whitespace-nowrap hover:text-[#3085FE] transition">
                    Additional Information
                </button>
                <button @click="activeTab = 'employment'"
                    :class="{ 'border-b-2 border-[#3085FE] text-[#3085FE]': activeTab === 'employment' }"
                    class="px-4 py-3 text-sm font-medium whitespace-nowrap hover:text-[#3085FE] transition">
                    Contract Details
                </button>
                <button @click="activeTab = 'address'"
                    :class="{ 'border-b-2 border-[#3085FE] text-[#3085FE]': activeTab === 'address' }"
                    class="px-4 py-3 text-sm font-medium whitespace-nowrap hover:text-[#3085FE] transition">
                    Address
                </button>
                <button @click="activeTab = 'finance'"
                    :class="{ 'border-b-2 border-[#3085FE] text-[#3085FE]': activeTab === 'finance' }"
                    class="px-4 py-3 text-sm font-medium whitespace-nowrap hover:text-[#3085FE] transition">
                    Account
                </button>
                <button @click="activeTab = 'education'"
                    :class="{ 'border-b-2 border-[#3085FE] text-[#3085FE]': activeTab === 'education' }"
                    class="px-4 py-3 text-sm font-medium whitespace-nowrap hover:text-[#3085FE] transition">
                    Education
                </button>
                <button @click="activeTab = 'size'"
                    :class="{ 'border-b-2 border-[#3085FE] text-[#3085FE]': activeTab === 'size' }"
                    class="px-4 py-3 text-sm font-medium whitespace-nowrap hover:text-[#3085FE] transition">
                    Size
                </button>
            </div>

            <div class="space-y-6">
                <div class="p-4 md:p-6">
                    <!-- Personal Information Tab -->
                    <div x-show="activeTab === 'account'" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" wire:model="name" id="name"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" wire:model="full_name" id="full_name"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                                @error('full_name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" wire:model="email" id="email"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                                @error('email')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">No.
                                    Handphone</label>
                                <input type="text" wire:model="phone" id="phone"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                                @error('phone')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="employee_id" class="block text-sm font-medium text-gray-700">No.
                                    Induk</label>
                                <input type="text" wire:model="employee_id" id="employee_id" readonly
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm bg-gray-100 focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                                @error('employee_id')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" wire:model="email" id="email" disabled
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                                @error('email')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div x-show="activeTab === 'personal'" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                                <div class="flex space-x-5">
                                    <div class="flex items-center">
                                        <input type="radio" wire:model.live="gender" id="gender_male"
                                            value="laki-laki"
                                            class="h-4 w-4 text-[#3085FE] focus:ring-[#3085FE] border-gray-300">
                                        <label for="gender_male"
                                            class="ml-2 block text-sm text-gray-700">Laki-laki</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" wire:model.live="gender" id="gender_female"
                                            value="perempuan"
                                            class="h-4 w-4 text-[#3085FE] focus:ring-[#3085FE] border-gray-300">
                                        <label for="gender_female"
                                            class="ml-2 block text-sm text-gray-700">Perempuan</label>
                                    </div>
                                </div>
                                @error('gender')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="birthday" class="block text-sm font-medium text-gray-700">Tanggal
                                    Lahir</label>
                                <input type="date" wire:model="birthday" id="birthday"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                            </div>
                            @error('birthday')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="religion" class="block text-sm font-medium text-gray-700">Agama</label>
                                <select wire:model="religion" id="religion"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                                    <option value="">Select Religion</option>
                                    <option value="ISLAM">Islam</option>
                                    <option value="PROTESTAN">Protestan</option>
                                    <option value="KATOLIK">Katolik</option>
                                    <option value="BUDDHA">Buddha</option>
                                    <option value="HINDU">Hindu</option>
                                    <option value="KONGHUCU">Konghucu</option>
                                    <option value="LAINNYA">Lainnya</option>
                                </select>
                            </div>
                            @error('religion')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="birth_place" class="block text-sm font-medium text-gray-700">Tempat
                                    Lahir</label>
                                <input type="text" wire:model="birth_place" id="birth_place"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                            </div>
                            @error('birth_place')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status Pernikahan</label>
                                <div class="flex space-x-5">
                                    <div class="flex items-center">
                                        <input type="radio" wire:model.live="marital_status"
                                            id="marital_status_single" value="lajang"
                                            class="h-4 w-4 text-[#3085FE] focus:ring-[#3085FE] border-gray-300">
                                        <label for="marital_status_single"
                                            class="ml-2 block text-sm text-gray-700">Lajang</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" wire:model.live="marital_status"
                                            id="marital_status_married" value="menikah"
                                            class="h-4 w-4 text-[#3085FE] focus:ring-[#3085FE] border-gray-300">
                                        <label for="marital_status_married"
                                            class="ml-2 block text-sm text-gray-700">Menikah</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" wire:model.live="marital_status"
                                            id="marital_status_divorced" value="janda/duda"
                                            class="h-4 w-4 text-[#3085FE] focus:ring-[#3085FE] border-gray-300">
                                        <label for="marital_status_divorced"
                                            class="ml-2 block text-sm text-gray-700">Janda/Duda</label>
                                    </div>
                                </div>
                                @error('marital_status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-show="$wire.marital_status === 'married'">
                                <label for="wedding_date" class="block text-sm font-medium text-gray-700">Wedding
                                    Date</label>
                                <input type="date" wire:model="wedding_date" id="wedding_date"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                                @error('wedding_date')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div x-show="$wire.marital_status === 'married'">
                                <label for="child" class="block text-sm font-medium text-gray-700">Number of
                                    Children</label>
                                <input type="number" wire:model="child" id="child" min="0"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                                @error('child')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="mother_name" class="block text-sm font-medium text-gray-700">Nama Ibu
                                    Kandung</label>
                                <input type="text" wire:model="mother_name" id="mother_name"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                            </div>
                            @error('mother_name')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Golongan Darah</label>
                                <div class="flex justify-between gap-2">
                                    <div class="flex items-center">
                                        <input type="radio" wire:model.live="blood_type" id="blood_type_a"
                                            value="A"
                                            class="h-4 w-4 text-[#3085FE] focus:ring-[#3085FE] border-gray-300">
                                        <label for="blood_type_a" class="ml-2 block text-sm text-gray-700">A</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" wire:model.live="blood_type" id="blood_type_b"
                                            value="B"
                                            class="h-4 w-4 text-[#3085FE] focus:ring-[#3085FE] border-gray-300">
                                        <label for="blood_type_b" class="ml-2 block text-sm text-gray-700">B</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" wire:model.live="blood_type" id="blood_type_ab"
                                            value="AB"
                                            class="h-4 w-4 text-[#3085FE] focus:ring-[#3085FE] border-gray-300">
                                        <label for="blood_type_ab" class="ml-2 block text-sm text-gray-700">AB</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" wire:model.live="blood_type" id="blood_type_o"
                                            value="O"
                                            class="h-4 w-4 text-[#3085FE] focus:ring-[#3085FE] border-gray-300">
                                        <label for="blood_type_o" class="ml-2 block text-sm text-gray-700">O</label>
                                    </div>

                                </div>
                                @error('blood_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Employment Details Tab -->
                    <div x-show="activeTab === 'employment'" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="bidang" class="block text-sm font-medium text-gray-700">Bidang</label>
                                <input type="text" value="{{ $userDetails->bidang ?? '' }}" id="bidang"
                                    disabled
                                    class="mt-1 block w-full rounded-xl border-gray-300 bg-gray-100 shadow-sm h-12 px-4">
                            </div>
                            <div>
                                <label for="sub_bidang"
                                    class="block text-sm font-medium text-gray-700">Sub-Bidang</label>
                                <input type="text" value="{{ $userDetails->sub_bidang ?? '' }}" id="sub_bidang"
                                    disabled
                                    class="mt-1 block w-full rounded-xl border-gray-300 bg-gray-100 shadow-sm h-12 px-4">
                            </div>
                            <div>
                                <label for="sub_bidang"
                                    class="block text-sm font-medium text-gray-700">Sub-Bidang</label>
                                <input type="text" value="{{ $userDetails->sub_bidang ?? '' }}" id="sub_bidang"
                                    disabled
                                    class="mt-1 block w-full rounded-xl border-gray-300 bg-gray-100 shadow-sm h-12 px-4">
                            </div>
                            <div>
                                <label for="jabatan" class="block text-sm font-medium text-gray-700">Jabatan</label>
                                <input type="text" value="{{ $userDetails->jabatan ?? '' }}" id="jabatan"
                                    disabled
                                    class="mt-1 block w-full rounded-xl border-gray-300 bg-gray-100 shadow-sm h-12 px-4">
                            </div>
                            <div>
                                <label for="office_id" class="block text-sm font-medium text-gray-700">Lokasi
                                    Kantor</label>
                                <input type="text" value="{{ $user->office->name ?? '' }}" id="office_id"
                                    disabled
                                    class="mt-1 block w-full rounded-xl border-gray-300 bg-gray-100 shadow-sm h-12 px-4">
                            </div>
                            <div x-show="$wire.user_type === 'PEGAWAI'">
                                <label for="manager_id"
                                    class="block text-sm font-medium text-gray-700">Manager</label>
                                <input type="text" value="{{ $user->manager->name ?? '' }}" id="manager_id"
                                    disabled
                                    class="mt-1 block w-full rounded-xl border-gray-300 bg-gray-100 shadow-sm h-12 px-4">
                            </div>

                            {{-- <div>
                                <label for="tanggal_penerimaan"
                                    class="block text-sm font-medium text-gray-700">Tanggal Penerimaan</label>
                                <input type="date" wire:model="tanggal_penerimaan" id="tanggal_penerimaan"
                                    readonly
                                    class="mt-1 block w-full rounded-xl border-gray-300 bg-gray-100 shadow-sm h-12 px-4">
                            </div>

                            <div>
                                <label for="tanggal_aktif_bekerja"
                                    class="block text-sm font-medium text-gray-700">Tanggal Aktif Bekerja</label>
                                <input type="date" wire:model="tanggal_aktif_bekerja" id="tanggal_aktif_bekerja"
                                    readonly
                                    class="mt-1 block w-full rounded-xl border-gray-300 bg-gray-100 shadow-sm h-12 px-4">
                            </div> --}}

                            <div>
                                <label for="tarif_sppd" class="block text-sm font-medium text-gray-700">Tarif
                                    SPPD</label>
                                <input type="number" wire:model="tarif_sppd" id="tarif_sppd" disabled
                                    class="mt-1 block w-full rounded-xl border-gray-300 bg-gray-100 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                            </div>

                            <div>
                                <label for="koefisien_lembur"
                                    class="block text-sm font-medium text-gray-700">Koefisien Lembur</label>
                                <input type="number" wire:model="koefisien_lembur" id="koefisien_lembur"
                                    step="0.01" disabled
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm bg-gray-100 focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="is_shifting" id="is_shifting" disabled
                                    class="rounded border-gray-300 text-[#3085FE] shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                <label for="is_shifting" class="ml-2 block text-sm text-gray-700">Shifting</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" wire:model="is_magang" id="is_magang" disabled
                                    class="rounded border-gray-300 text-[#3085FE] shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                <label for="is_magang" class="ml-2 block text-sm text-gray-700">Is
                                    Intern</label>
                            </div>
                        </div>
                    </div>

                    <!-- Address Tab -->
                    <div x-show="activeTab === 'address'" class="space-y-4">
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea wire:model="address" id="address" rows="3"
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] px-4 py-3"></textarea>
                        </div>
                        @error('address')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="provinsi" class="block text-sm font-medium text-gray-700">Provinsi</label>
                                <input type="text" wire:model="provinsi" id="provinsi"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                            </div>
                            @error('provinsi')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="kota" class="block text-sm font-medium text-gray-700">Kota</label>
                                <input type="text" wire:model="kota" id="kota"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                            </div>
                            @error('kota')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="kecamatan"
                                    class="block text-sm font-medium text-gray-700">Kecamatan</label>
                                <input type="text" wire:model="kecamatan" id="kecamatan"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                            </div>
                            @error('kecamatan')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="kelurahan"
                                    class="block text-sm font-medium text-gray-700">Kelurahan</label>
                                <input type="text" wire:model="kelurahan" id="kelurahan"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                            </div>
                            @error('kelurahan')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="rt" class="block text-sm font-medium text-gray-700">RT</label>
                                <input type="text" wire:model="rt" id="rt"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                            </div>
                            @error('rt')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="rw" class="block text-sm font-medium text-gray-700">RW</label>
                                <input type="text" wire:model="rw" id="rw"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                            </div>
                            @error('rw')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="kode_pos" class="block text-sm font-medium text-gray-700">Kode Pos</label>
                                <input type="text" wire:model="kode_pos" id="kode_pos"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                            </div>
                            @error('kode_pos')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Financial Info Tab -->
                    <div x-show="activeTab === 'finance'" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="ktp" class="block text-sm font-medium text-gray-700">No. KTP</label>
                                <input type="text" wire:model.live="ktp" id="ktp"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            </div>
                            @error('ktp')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="npwp" class="block text-sm font-medium text-gray-700">No. NPWP</label>
                                <input type="text" wire:model.live="npwp" id="npwp"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            </div>
                            @error('npwp')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="kk" class="block text-sm font-medium text-gray-700">No. KK</label>
                                <input type="text" wire:model.live="kk" id="kk"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            </div>
                            @error('kk')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <hr>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="bank" class="block text-sm font-medium text-gray-700">Bank</label>
                                <input type="text" wire:model.live="bank" id="bank"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            </div>
                            @error('bank')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="nama_rekening" class="block text-sm font-medium text-gray-700">Nama
                                    Rekening</label>
                                <input type="text" wire:model.live="nama_rekening" id="nama_rekening"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            </div>
                            @error('nama_rekening')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="nomor_rekening" class="block text-sm font-medium text-gray-700">No.
                                    Rekening</label>
                                <input type="text" wire:model.live="nomor_rekening" id="nomor_rekening"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            </div>
                            @error('nomor_rekening')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <hr>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="bpjs" class="block text-sm font-medium text-gray-700">No. BPJS</label>
                                <input type="text" wire:model.live="bpjs" id="bpjs"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            </div>
                            @error('bpjs')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="nominal_bpjs" class="block text-sm font-medium text-gray-700">Nominal
                                    BPJS</label>
                                <input type="number" wire:model.live="nominal_bpjs" id="nominal_bpjs"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            </div>
                            @error('nominal_bpjs')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="bpjs_active_date" class="block text-sm font-medium text-gray-700">Tanggal
                                    Aktif
                                    BPJS</label>
                                <input type="date" wire:model.live="bpjs_active_date" id="bpjs_active_date"

                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            </div>
                            @error('bpjs_active_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                        </div>

                        <hr>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="dlpk" class="block text-sm font-medium text-gray-700">No. DLPK</label>
                                <input type="text" wire:model.live="dlpk" id="dlpk"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            </div>
                            @error('dlpk')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="cif" class="block text-sm font-medium text-gray-700">No. CIF</label>
                                <input type="text" wire:model.live="cif" id="cif"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            </div>
                            @error('cif')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="nominal_dlpk" class="block text-sm font-medium text-gray-700">Nominal
                                    DLPK</label>
                                <input type="number" wire:model.live="nominal_dlpk" id="nominal_dlpk"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            </div>
                            @error('nominal_dlpk')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="dlpk_active_date" class="block text-sm font-medium text-gray-700">Tanggal
                                    Aktif
                                    DLPK</label>
                                <input type="date" wire:model.live="dlpk_active_date" id="dlpk_active_date"

                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            </div>
                            @error('dlpk_active_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Education Tab -->
                    <div x-show="activeTab === 'education'" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="pendidikan_terakhir"
                                    class="block text-sm font-medium text-gray-700">Pendidikan
                                    Terakhir</label>
                                <input type="text" wire:model.live="pendidikan_terakhir" id="pendidikan_terakhir"

                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            </div>
                            @error('pendidikan_terakhir')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="jurusan" class="block text-sm font-medium text-gray-700">Jurusan</label>
                                <input type="text" wire:model.live="jurusan" id="jurusan"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            </div>
                            @error('jurusan')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div>
                                <label for="gelar"
                                    class="block text-sm font-medium text-gray-700">Gelar</label>
                                <input type="text" wire:model.live="gelar" id="gelar"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            </div>
                            @error('gelar')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div x-show="activeTab === 'size'" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="weight" class="block text-sm font-medium text-gray-700">Weight
                                    (kg)</label>
                                <input type="number" wire:model="weight" id="weight" min="0"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                                @error('weight')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="height" class="block text-sm font-medium text-gray-700">Height
                                    (cm)</label>
                                <input type="number" wire:model="height" id="height" min="0"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                                @error('height')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="ukuran_baju" class="block text-sm font-medium text-gray-700">Shirt
                                    Size</label>
                                <select wire:model="ukuran_baju" id="ukuran_baju"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE] h-12 px-4">
                                    <option value="">Select Size</option>
                                    <option value="XS">XS</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                    <option value="XXXL">XXXL</option>
                                </select>
                                @error('ukuran_baju')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end p-4 md:p-6">
            <button type="button" wire:click='updateProfile'
                class="ml-3 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#3085FE] border border-transparent rounded-md shadow-sm hover:bg-[#3085FE] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3085FE]">
                Save Changes
            </button>
        </div>

    </div>
</div>
