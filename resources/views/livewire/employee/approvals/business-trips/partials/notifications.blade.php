@if (session()->has('message'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 2000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded"
        role="alert"
    >
        <div class="flex items-center">
            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"></path>
            </svg>
            <p>{{ session('message') }}</p>
        </div>
    </div>
@endif

@if (session()->has('error'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 2000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded"
        role="alert"
    >
        <div class="flex items-center">
            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd"></path>
            </svg>
            <p>{{ session('error') }}</p>
        </div>
    </div>
@endif
