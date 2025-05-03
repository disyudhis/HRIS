<x-base-layout>
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-[#101317]">Buat Pengajuan Lembur</h1>
            <a href="{{ route('employee.approvals.overtime.index') }}"
               class="text-gray-600 hover:text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali
            </a>
        </div>

        <livewire:employee.approvals.overtime.overtime-form />
    </div>
</x-base-layout>