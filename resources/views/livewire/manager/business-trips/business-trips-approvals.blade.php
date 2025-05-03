<div>
    <!-- Notifications -->
    @include('livewire.manager.business-trips.partials.notifications')

    <div class="space-y-6">
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <!-- Filter Buttons -->
                <div class="flex flex-wrap gap-3">
                    @include('livewire.manager.business-trips.partials.filters')
                </div>

                <!-- Search Box -->
                @include('livewire.manager.business-trips.partials.search')
            </div>
        </div>

        <!-- Business Trips List -->
        <div class="space-y-4">
            @forelse($trips as $trip)
                @include('livewire.manager.business-trips.partials.trip-card')
            @empty
                @include('livewire.manager.business-trips.partials.empty-state')
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $trips->links() }}
        </div>
    </div>

    <!-- Modals -->
    @include('livewire.manager.business-trips.partials.rejection-modal')
</div>