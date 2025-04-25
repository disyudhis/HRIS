<!-- resources/views/components/toast.blade.php -->
<div
    x-data="{
        show: false,
        message: '{{ $message }}',
        type: '{{ $type }}',
        duration: {{ $duration }},
        init() {
            if (this.message) {
                this.showToast(this.message, this.type);
            }

            // Event listener untuk Livewire 3
            Livewire.on('toast', (data) => {
                this.showToast(data[0].message, data[0].type || 'info', data[0].duration || 5000);
            });
        },
        showToast(message, type = 'info', duration = 5000) {
            this.message = message;
            this.type = type;
            this.show = true;
            setTimeout(() => { this.show = false }, duration);
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90"
    @click="show = false"
    class="fixed inset-x-0 bottom-4 flex items-center justify-center px-4 z-50"
    style="display: none;"
>
    <div
        class="px-6 py-3 rounded-lg shadow-lg max-w-md"
        :class="{
            'bg-blue-500 text-white': type === 'info',
            'bg-green-500 text-white': type === 'success',
            'bg-yellow-500 text-white': type === 'warning',
            'bg-red-500 text-white': type === 'error'
        }"
    >
        <div class="flex items-center space-x-3">
            <!-- Icons based on type -->
            <template x-if="type === 'info'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </template>
            <template x-if="type === 'success'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </template>
            <template x-if="type === 'warning'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </template>
            <template x-if="type === 'error'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </template>

            <span x-text="message" class="font-medium"></span>
        </div>
    </div>
</div>