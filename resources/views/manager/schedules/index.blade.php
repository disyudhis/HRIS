<x-base-layout>
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h1 class="text-2xl font-bold text-[#101317] mb-6">Schedule Management</h1>

        @if (session('message'))
            <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6">
                {{ session('message') }}
            </div>
        @endif

        <livewire:manager.schedules.schedule-list />
    </div>
</x-base-layout>
