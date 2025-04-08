<x-base-layout>
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-[#101317]">Create New Schedule</h1>
            <a href="{{ route('manager.schedules.index') }}" class="text-[#3085FE] hover:underline">
                Back to Schedules
            </a>
        </div>

        <livewire:manager.schedules.schedule-form :date="$date ?? null" :employeeId="$employeeId ?? null" />
    </div>
</x-base-layout>
