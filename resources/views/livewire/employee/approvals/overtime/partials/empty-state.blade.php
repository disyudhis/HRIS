<div class="bg-white rounded-xl shadow-sm p-12 text-center">
    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
        <svg class="w-8 h-8 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-900">Belum ada pengajuan lembur</h3>
    <p class="mt-2 text-sm text-gray-500">
        @if($statusFilter === 'pending')
            Anda tidak memiliki pengajuan yang sedang menunggu persetujuan.
        @elseif($statusFilter === 'approved')
            Anda belum memiliki pengajuan yang disetujui.
        @elseif($statusFilter === 'rejected')
            Anda tidak memiliki pengajuan yang ditolak.
        @elseif($statusFilter === 'cancelled')
            Anda tidak memiliki pengajuan yang dibatalkan.
        @else
            Mulai buat pengajuan lembur Anda sekarang.
        @endif
    </p>
    <a href="{{ route('employee.approvals.overtime.create') }}"
        class="mt-4 inline-flex items-center px-4 py-2 bg-[#3085FE] text-white text-sm font-medium rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3085FE] transition-colors duration-200">
        <svg class="w-4 h-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
        </svg>
        Buat Pengajuan
    </a>
</div>