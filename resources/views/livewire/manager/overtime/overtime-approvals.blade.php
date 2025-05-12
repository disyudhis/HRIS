<div>
    <!-- Notifications -->
    @include('livewire.manager.overtime.partials.notifications')

    <div class="space-y-6">
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <!-- Filter Buttons -->
                <div class="flex flex-wrap gap-3">
                    @include('livewire.manager.overtime.partials.filters')
                </div>

                <!-- Search Box -->
                @include('livewire.manager.overtime.partials.search')
            </div>
        </div>

        <!-- Overtime List -->
        <div class="space-y-4">
            @forelse($overtimes as $overtime)
                @include('livewire.manager.overtime.partials.overtime-card')
            @empty
                @include('livewire.manager.overtime.partials.empty-state')
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $overtimes->links() }}
        </div>
    </div>

    <!-- Modals -->
    @include('livewire.manager.overtime.partials.rejection-modal')
    @include('livewire.manager.overtime.partials.approval-modal')
</div>
