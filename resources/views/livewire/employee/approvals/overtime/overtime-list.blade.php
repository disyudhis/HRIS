<div>
    <!-- Notifications -->
    @include('livewire.employee.approvals.overtime.partials.notifications')

    <div class="space-y-6">
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <!-- Title and Create Button -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center w-full gap-4">
                    <h2 class="text-xl font-semibold text-gray-800">Pengajuan Lembur</h2>
                    <a href="{{ route('employee.approvals.overtime.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-[#3085FE] text-white text-sm font-medium rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3085FE] transition-colors duration-200 w-full sm:w-auto justify-center">
                        <svg class="w-4 h-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Buat Pengajuan
                    </a>
                </div>

                <!-- Search and Filter -->
                <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                    @include('livewire.employee.approvals.overtime.partials.search')
                    @include('livewire.employee.approvals.overtime.partials.status-filter')
                </div>
            </div>
        </div>

        <!-- Overtime List -->
        <div class="space-y-4">
            @forelse($overtimes as $overtime)
                @include('livewire.employee.approvals.overtime.partials.overtime-card')
            @empty
                @include('livewire.employee.approvals.overtime.partials.empty-state')
            @endforelse
        </div>

        <!-- Pagination -->
        @if($overtimes->hasPages())
            <div class="bg-white rounded-xl shadow-sm p-4">
                {{ $overtimes->links() }}
            </div>
        @endif
    </div>

    <!-- Modals -->
    @include('livewire.employee.approvals.overtime.partials.confirmation-modal')
    @include('livewire.employee.approvals.overtime.partials.detail-modal')
</div>