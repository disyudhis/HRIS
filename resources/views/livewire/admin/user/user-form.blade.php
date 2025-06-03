<div>
    <div class="bg-white rounded-lg shadow-sm p-4 md:p-6 mb-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-4 md:mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-[#101317] mb-2 md:mb-0">
                {{ $isEdit ? 'Edit User: ' . $name : 'Create New User' }}
            </h1>
            <a href="{{ route('admin.users.index') }}" class="text-[#3085FE] hover:underline text-sm">
                Back to Users
            </a>
        </div>

        @if (session()->has('message'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md text-sm">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-4 md:mb-6 overflow-x-auto">
            <ul class="flex flex-nowrap md:flex-wrap text-sm font-medium text-center">
                <li class="mr-2">
                    <button wire:click="setActiveTab('account')"
                        class="p-2 md:p-4 whitespace-nowrap {{ $activeTab === 'account' ? 'text-[#3085FE] border-b-2 border-[#3085FE]' : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300' }}">
                        Account
                    </button>
                </li>
                <li class="mr-2">
                    <button wire:click="setActiveTab('personal')"
                        class="p-2 md:p-4 whitespace-nowrap {{ $activeTab === 'personal' ? 'text-[#3085FE] border-b-2 border-[#3085FE]' : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300' }}">
                        Personal
                    </button>
                </li>
                <li class="mr-2">
                    <button wire:click="setActiveTab('address')"
                        class="p-2 md:p-4 whitespace-nowrap {{ $activeTab === 'address' ? 'text-[#3085FE] border-b-2 border-[#3085FE]' : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300' }}">
                        Address
                    </button>
                </li>
                <li class="mr-2">
                    <button wire:click="setActiveTab('professional')"
                        class="p-2 md:p-4 whitespace-nowrap {{ $activeTab === 'professional' ? 'text-[#3085FE] border-b-2 border-[#3085FE]' : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300' }}">
                        Professional
                    </button>
                </li>
                <li class="mr-2">
                    <button wire:click="setActiveTab('education')"
                        class="p-2 md:p-4 whitespace-nowrap {{ $activeTab === 'education' ? 'text-[#3085FE] border-b-2 border-[#3085FE]' : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300' }}">
                        Education
                    </button>
                </li>
                <li class="mr-2">
                    <button wire:click="setActiveTab('documents')"
                        class="p-2 md:p-4 whitespace-nowrap {{ $activeTab === 'documents' ? 'text-[#3085FE] border-b-2 border-[#3085FE]' : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300' }}">
                        Documents
                    </button>
                </li>
                <li class="mr-2">
                    <button wire:click="setActiveTab('insurance')"
                        class="p-2 md:p-4 whitespace-nowrap {{ $activeTab === 'insurance' ? 'text-[#3085FE] border-b-2 border-[#3085FE]' : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300' }}">
                        Insurance
                    </button>
                </li>
                <li class="mr-2">
                    <button wire:click="setActiveTab('contract')"
                        class="p-2 md:p-4 whitespace-nowrap {{ $activeTab === 'contract' ? 'text-[#3085FE] border-b-2 border-[#3085FE]' : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300' }}">
                        Contract
                    </button>
                </li>
                <li>
                    <button wire:click="setActiveTab('size')"
                        class="p-2 md:p-4 whitespace-nowrap {{ $activeTab === 'size' ? 'text-[#3085FE] border-b-2 border-[#3085FE]' : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300' }}">
                        Size
                    </button>
                </li>
            </ul>
        </div>

        <form wire:submit.prevent="save" class="space-y-4 md:space-y-6" novalidate>
            <!-- Account Information Tab -->
            <div x-data class="{{ $activeTab === 'account' ? 'block' : 'hidden' }}">
                <h2 class="text-lg md:text-xl font-semibold mb-4 text-gray-800">Account Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Username<span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model.live="name" id="name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Nama Lengkap<span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model.live="full_name" id="full_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('full_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700">No. Induk</label>
                        <input type="text" wire:model.live="employee_id" id="employee_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('employee_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email <span
                                class="text-red-500">*</span></label>
                        <input type="email" wire:model.live="email" id="email"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">No. Handphone<span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model.live="phone" id="phone"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password <span
                                class="text-red-500">{{ $isEdit ? '(Leave blank to keep current)' : '*' }}</span>
                        </label>
                        <input type="password" wire:model.live="password" id="password"
                            {{ $isEdit ? '' : 'required' }}
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                            Password<span class="text-red-500">*</span></label>
                        <input type="password" wire:model.live="password_confirmation" id="password_confirmation"
                            {{ $isEdit ? '' : 'required' }}
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                    </div>
                </div>

                <div class="mt-4 text-right">
                    <button type="button" wire:click="setActiveTab('personal')"
                        class="bg-[#3085FE] text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Next: Personal Information
                    </button>
                </div>
            </div>

            <div class="{{ $activeTab === 'personal' ? 'block' : 'hidden' }}">
                <h2 class="text-lg md:text-xl font-semibold mb-4 text-gray-800">Personal Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                        <div class="flex space-x-5">
                            <div class="flex items-center">
                                <input type="radio" wire:model.live="gender" id="gender_male" value="Laki-laki"
                                    class="h-4 w-4 text-[#3085FE] focus:ring-[#3085FE] border-gray-300">
                                <label for="gender_male" class="ml-2 block text-sm text-gray-700">Laki-laki</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" wire:model.live="gender" id="gender_female" value="perempuan"
                                    class="h-4 w-4 text-[#3085FE] focus:ring-[#3085FE] border-gray-300">
                                <label for="gender_female" class="ml-2 block text-sm text-gray-700">Perempuan</label>
                            </div>
                        </div>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birthday" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" wire:model.live="birthday" id="birthday"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('birthday')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_place" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                        <input type="text" wire:model.live="birth_place" id="birth_place"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('birth_place')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="religion" class="block text-sm font-medium text-gray-700">Agama</label>
                        <select wire:model.live="religion" id="religion"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            <option value="">Select Religion</option>
                            <option value="Islam">Islam</option>
                            <option value="Christianity">Christianity</option>
                            <option value="Catholicism">Catholicism</option>
                            <option value="Hinduism">Hinduism</option>
                            <option value="Buddhism">Buddhism</option>
                            <option value="Confucianism">Confucianism</option>
                            <option value="Other">Other</option>
                        </select>
                        @error('religion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Golongan Darah</label>
                        <div class="flex justify-between gap-2">
                            <div class="flex items-center">
                                <input type="radio" wire:model.live="blood_type" id="blood_type_a" value="A"
                                    class="h-4 w-4 text-[#3085FE] focus:ring-[#3085FE] border-gray-300">
                                <label for="blood_type_a" class="ml-2 block text-sm text-gray-700">A</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" wire:model.live="blood_type" id="blood_type_b" value="B"
                                    class="h-4 w-4 text-[#3085FE] focus:ring-[#3085FE] border-gray-300">
                                <label for="blood_type_b" class="ml-2 block text-sm text-gray-700">B</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" wire:model.live="blood_type" id="blood_type_ab" value="AB"
                                    class="h-4 w-4 text-[#3085FE] focus:ring-[#3085FE] border-gray-300">
                                <label for="blood_type_ab" class="ml-2 block text-sm text-gray-700">AB</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" wire:model.live="blood_type" id="blood_type_o" value="O"
                                    class="h-4 w-4 text-[#3085FE] focus:ring-[#3085FE] border-gray-300">
                                <label for="blood_type_o" class="ml-2 block text-sm text-gray-700">O</label>
                            </div>

                        </div>
                        @error('blood_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Pernikahan</label>
                        <div class="flex space-x-5">
                            <div class="flex items-center">
                                <input type="radio" wire:model.live="marital_status" id="marital_status_single"
                                    value="lajang"
                                    class="h-4 w-4 text-[#3085FE] focus:ring-[#3085FE] border-gray-300">
                                <label for="marital_status_single"
                                    class="ml-2 block text-sm text-gray-700">Lajang</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" wire:model.live="marital_status" id="marital_status_married"
                                    value="menikah"
                                    class="h-4 w-4 text-[#3085FE] focus:ring-[#3085FE] border-gray-300">
                                <label for="marital_status_married"
                                    class="ml-2 block text-sm text-gray-700">Menikah</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" wire:model.live="marital_status" id="marital_status_divorced"
                                    value="janda/duda"
                                    class="h-4 w-4 text-[#3085FE] focus:ring-[#3085FE] border-gray-300">
                                <label for="marital_status_divorced"
                                    class="ml-2 block text-sm text-gray-700">Janda/Duda</label>
                            </div>
                        </div>
                        @error('marital_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="{{ $marital_status === 'married' ? 'block' : 'hidden' }}">
                        <label for="wedding_date" class="block text-sm font-medium text-gray-700">Tanggal
                            Pernikahan<span class="text-red-500">*</span></label>
                        <input type="date" wire:model.live="wedding_date" id="wedding_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('wedding_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="mother_name" class="block text-sm font-medium text-gray-700">Nama Ibu
                            Kandung</label>
                        <input type="text" wire:model.live="mother_name" id="mother_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('mother_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 flex justify-between">
                    <button type="button" wire:click="setActiveTab('account')"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        Previous: Account
                    </button>
                    <button type="button" wire:click="setActiveTab('address')"
                        class="bg-[#3085FE] text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Next: Address
                    </button>
                </div>
            </div>

            <div class="{{ $activeTab === 'address' ? 'block' : 'hidden' }}">
                <h2 class="text-lg md:text-xl font-semibold mb-4 text-gray-800">Address Information</h2>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea wire:model.live="address" id="address" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]"></textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label for="provinsi" class="block text-sm font-medium text-gray-700">Provinsi</label>
                        <input type="text" wire:model.live="provinsi" id="provinsi"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('provinsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kota" class="block text-sm font-medium text-gray-700">Kota</label>
                        <input type="text" wire:model.live="kota" id="kota"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('kota')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kecamatan" class="block text-sm font-medium text-gray-700">Kecamatan</label>
                        <input type="text" wire:model.live="kecamatan" id="kecamatan"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('kecamatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kelurahan" class="block text-sm font-medium text-gray-700">Kelurahan</label>
                        <input type="text" wire:model.live="kelurahan" id="kelurahan"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('kelurahan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-4">
                    <div>
                        <label for="rt" class="block text-sm font-medium text-gray-700">RT</label>
                        <input type="text" wire:model.live="rt" id="rt"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('rt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="rw" class="block text-sm font-medium text-gray-700">RW</label>
                        <input type="text" wire:model.live="rw" id="rw"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('rw')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kode_pos" class="block text-sm font-medium text-gray-700">Postal Code</label>
                        <input type="text" wire:model.live="kode_pos" id="kode_pos"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('kode_pos')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 flex justify-between">
                    <button type="button" wire:click="setActiveTab('personal')"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        Previous: Personal Info
                    </button>
                    <button type="button" wire:click="setActiveTab('professional')"
                        class="bg-[#3085FE] text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Next: Professional Details
                    </button>
                </div>
            </div>

            <div class="{{ $activeTab === 'professional' ? 'block' : 'hidden' }}">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Professional Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="bidang" class="block text-sm font-medium text-gray-700">Bidang</label>
                        <select wire:model.live="bidang" id="bidang"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            <option value="">Pilih Bidang</option>
                            <option value="Retail">Retail</option>
                            <option value="Corporate">Corporate</option>
                        </select>
                        @error('bidang')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sub_bidang" class="block text-sm font-medium text-gray-700">Sub-bidang</label>
                        <select wire:model.live="sub_bidang" id="sub_bidang"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            <option value="">Pilih Sub-bidang</option>
                            <option value="Aktivasi">Aktivasi</option>
                            <option value="Pemeliharaan">Pemeliharaan</option>
                            <option value="Network Operation Center">Network Operation Center</option>
                            <option value="Asset">Asset</option>
                            <option value="Gudang">Gudang</option>
                        </select>
                        @error('sub_bidang')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tarif_sppd" class="block text-sm font-medium text-gray-700">Tarif SPPD</label>
                        <p class="mt-1 block w-full rounded-md border-gray-500 shadow-sm bg-gray-100 focus:border-[#3085FE] focus:ring-[#3085FE] p-3">Rp. {{  $tarif_sppd }}</p>
                    </div>

                    <div>
                        <label for="koefisien_lembur" class="block text-sm font-medium text-gray-700">Koefisien
                            Lembur</label>
                            <p class="mt-1 block w-full rounded-md border-gray-500 shadow-sm bg-gray-100 focus:border-[#3085FE] focus:ring-[#3085FE] p-3">Rp. {{  $koefisien_lembur }}</p>
                    </div>
                    <div>
                        <label for="office_id" class="block text-sm font-medium text-gray-700">Office <span
                                class="text-red-500">*</span></label>
                        <select wire:model.live="office_id" id="office_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            <option value="">Select Office</option>
                            @foreach ($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->name }}</option>
                            @endforeach
                        </select>
                        @error('office_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="user_type" class="block text-sm font-medium text-gray-700">Jabatan <span
                                class="text-red-500">*</span></label>
                        <select wire:model.live="user_type" id="user_type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            <option value="PEGAWAI">Pegawai</option>
                            <option value="MANAGER">Manager</option>
                        </select>
                        @error('user_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="{{ $user_type === 'MANAGER' ? 'hidden' : '' }}">
                        <label for="manager_id" class="block text-sm font-medium text-gray-700">Manager<span class="text-red-500">*</span></label>
                        <select wire:model.live="manager_id" id="manager_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]"
                            {{ $office_id ? '' : 'disabled' }}>
                            <option value="">None</option>
                            @foreach ($managers as $manager)
                                <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                            @endforeach
                        </select>
                        @if (!$office_id)
                            <p class="mt-1 text-sm text-amber-600">Please select an office
                                first to see available managers.
                            </p>
                        @endif
                        @error('manager_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div class="flex items-center">
                        <input type="checkbox" wire:model.live="is_shifting" id="is_shifting"
                            class="rounded border-gray-300 text-[#3085FE] shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        <label for="is_shifting" class="ml-2 block text-sm font-medium text-gray-700">Shifting
                            Employee</label>
                        @error('is_shifting')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" wire:model.live="is_magang" id="is_magang"
                            class="rounded border-gray-300 text-[#3085FE] shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        <label for="is_magang" class="ml-2 block text-sm font-medium text-gray-700">Magang</label>
                        @error('is_magang')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 flex justify-between">
                    <button type="button" wire:click="setActiveTab('address')"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        Previous: Address
                    </button>
                    <button type="button" wire:click="setActiveTab('education')"
                        class="bg-[#3085FE] text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Next: Education
                    </button>
                </div>
            </div>

            <div class="{{ $activeTab === 'education' ? 'block' : 'hidden' }}">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Educational Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="pendidikan_terakhir" class="block text-sm font-medium text-gray-700">Pendidikan
                            Terakhir</label>
                        <select wire:model.live="pendidikan_terakhir" id="pendidikan_terakhir"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            <option value="">Select Education Level</option>
                            <option value="SD">Elementary School (SD)</option>
                            <option value="SMP">Junior High School (SMP)</option>
                            <option value="SMA">Senior High School (SMA)</option>
                            <option value="SMK">Vocational High School (SMK)</option>
                            <option value="D1">Diploma 1 (D1)</option>
                            <option value="D2">Diploma 2 (D2)</option>
                            <option value="D3">Diploma 3 (D3)</option>
                            <option value="D4">Diploma 4 (D4)</option>
                            <option value="S1">Bachelor's Degree (S1)</option>
                            <option value="S2">Master's Degree (S2)</option>
                            <option value="S3">Doctoral Degree (S3)</option>
                        </select>
                        @error('pendidikan_terakhir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jurusan" class="block text-sm font-medium text-gray-700">Jurusan</label>
                        <input type="text" wire:model.live="jurusan" id="jurusan"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('jurusan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gelar" class="block text-sm font-medium text-gray-700">Gelar</label>
                        <input type="text" wire:model.live="gelar" id="gelar"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('gelar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 flex justify-between">
                    <button type="button" wire:click="setActiveTab('professional')"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        Previous: Professional
                    </button>
                    <button type="button" wire:click="setActiveTab('documents')"
                        class="bg-[#3085FE] text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Next: ID & Banking
                    </button>
                </div>
            </div>

            <div class="{{ $activeTab === 'documents' ? 'block' : 'hidden' }}">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">ID & Banking Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="ktp" class="block text-sm font-medium text-gray-700">No. KTP</label>
                        <input type="text" wire:model.live="ktp" id="ktp"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('ktp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="npwp" class="block text-sm font-medium text-gray-700">No. NPWP</label>
                        <input type="text" wire:model.live="npwp" id="npwp"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('npwp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kk" class="block text-sm font-medium text-gray-700">No. KK</label>
                        <input type="text" wire:model.live="kk" id="kk"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('kk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <h3 class="text-lg font-medium mt-6 mb-4 text-gray-800">Banking Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="bank" class="block text-sm font-medium text-gray-700">Bank</label>
                        <input type="text" wire:model.live="bank" id="bank"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('bank')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama_rekening" class="block text-sm font-medium text-gray-700">Nama
                            Rekening</label>
                        <input type="text" wire:model.live="nama_rekening" id="nama_rekening"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('nama_rekening')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="nomor_rekening" class="block text-sm font-medium text-gray-700">No.
                            Rekening</label>
                        <input type="text" wire:model.live="nomor_rekening" id="nomor_rekening"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('nomor_rekening')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 flex justify-between">
                    <button type="button" wire:click="setActiveTab('education')"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        Previous: Education
                    </button>
                    <button type="button" wire:click="setActiveTab('insurance')"
                        class="bg-[#3085FE] text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Next: BPJS & DLPK
                    </button>
                </div>
            </div>

            <!-- Insurance Tab (BPJS & DLPK) -->
            <div class="{{ $activeTab === 'insurance' ? 'block' : 'hidden' }}">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">BPJS & DLPK Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="bpjs" class="block text-sm font-medium text-gray-700">No. BPJS</label>
                        <input type="text" wire:model.live="bpjs" id="bpjs"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('bpjs')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nominal_bpjs" class="block text-sm font-medium text-gray-700">Nominal BPJS</label>
                        <input type="number" wire:model.live="nominal_bpjs" id="nominal_bpjs"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('nominal_bpjs')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bpjs_active_date" class="block text-sm font-medium text-gray-700">Tanggal Aktif
                            BPJS</label>
                        <input type="date" wire:model.live="bpjs_active_date" id="bpjs_active_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('bpjs_active_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <h3 class="text-lg font-medium mt-6 mb-4 text-gray-800">DLPK Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="dlpk" class="block text-sm font-medium text-gray-700">No. DLPK</label>
                        <input type="text" wire:model.live="dlpk" id="dlpk"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('dlpk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cif" class="block text-sm font-medium text-gray-700">No. CIF</label>
                        <input type="text" wire:model.live="cif" id="cif"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('cif')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nominal_dlpk" class="block text-sm font-medium text-gray-700">Nominal DLPK</label>
                        <input type="number" wire:model.live="nominal_dlpk" id="nominal_dlpk"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('nominal_dlpk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="dlpk_active_date" class="block text-sm font-medium text-gray-700">Tanggal Aktif
                            DLPK</label>
                        <input type="date" wire:model.live="dlpk_active_date" id="dlpk_active_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('dlpk_active_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 flex justify-between">
                    <button type="button" wire:click="setActiveTab('documents')"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        Previous: ID & Banking
                    </button>
                    <button type="button" wire:click="setActiveTab('contract')"
                        class="bg-[#3085FE] text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Next: Contract
                    </button>
                </div>
            </div>

            <!-- Contract Tab -->
            <div class="{{ $activeTab === 'contract' ? 'block' : 'hidden' }}">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Contract Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status_kontrak" class="block text-sm font-medium text-gray-700">Status
                            Kontrak</label>
                        <select wire:model.live="status_kontrak" id="status_kontrak"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                            <option value="">Select Status</option>
                            <option value="PKWT">Fixed-Term Employment Contract (PKWT)</option>
                            <option value="PKWTT">Permanent Employment Contract (PKWTT)</option>
                            <option value="PROBATION">Probation Period</option>
                            <option value="MAGANG">Internship</option>
                        </select>
                        @error('status_kontrak')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nomor_kontrak" class="block text-sm font-medium text-gray-700">No. Kontrak</label>
                        <input type="text" wire:model.live="nomor_kontrak" id="nomor_kontrak"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('nomor_kontrak')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_penerimaan" class="block text-sm font-medium text-gray-700">Tanggal
                            Penerimaan</label>
                        <input type="date" wire:model.live="tanggal_penerimaan" id="tanggal_penerimaan"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('tanggal_penerimaan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_aktif_bekerja" class="block text-sm font-medium text-gray-700">Tanggal
                            Aktif Bekerja<span class="text-red-500"></span></label>
                        <input type="date" wire:model.live="tanggal_aktif_bekerja" id="tanggal_aktif_bekerja"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('tanggal_aktif_bekerja')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_keluar" class="block text-sm font-medium text-gray-700">Tanggal
                            Keluar</label>
                        <input type="date" wire:model.live="tanggal_keluar" id="tanggal_keluar"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('tanggal_keluar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-between">
                    <button type="button" wire:click="setActiveTab('insurance')"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        Previous: BPJS & DLPK
                    </button>
                    <button type="button" wire:click="setActiveTab('size')"
                        class="bg-[#3085FE] text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Next: Size
                    </button>
                </div>
            </div>

            <div class="{{ $activeTab === 'size' ? 'block' : 'hidden' }}">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Size Information</h2>


                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                        <input type="number" wire:model.live="weight" id="weight" step="0.1"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="height" class="block text-sm font-medium text-gray-700">Height (cm)</label>
                        <input type="number" wire:model.live="height" id="height" step="0.1"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                        @error('height')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="ukuran_baju" class="block text-sm font-medium text-gray-700">Shirt Size</label>
                        <select wire:model.live="ukuran_baju" id="ukuran_baju"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
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
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                <div class="mt-6 flex justify-between">
                    <button type="button" wire:click="setActiveTab('contract')"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        Previous: Contract
                    </button>
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                        {{ $isEdit ? 'Update User' : 'Create User' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
