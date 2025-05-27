<div class="relative w-full lg:w-72">
    <input
        type="text"
        wire:model.live="search"
        placeholder="Search requests..."
        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 bg-gray-50 focus:bg-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#3085FE] focus:border-transparent"
    >
    <div class="absolute left-3 top-2.5 text-gray-400">
        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
        </svg>
    </div>
</div>
