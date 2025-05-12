@php
    $filterOptions = [
        'pending' => ['label' => 'Pending', 'color' => 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200'],
        'approved' => ['label' => 'Approved', 'color' => 'bg-green-100 text-green-800 hover:bg-green-200'],
        'rejected' => ['label' => 'Rejected', 'color' => 'bg-red-100 text-red-800 hover:bg-red-200'],
    ];
@endphp

@foreach($filterOptions as $key => $option)
    <button
        wire:click="$set('filter', '{{ $key }}')"
        class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $filter === $key ? str_replace('hover:', '', $option['color']) : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3085FE]">
        {{ $option['label'] }}
        <span class="ml-2 inline-flex items-center justify-center {{ $filter === $key ? 'bg-white bg-opacity-25' : 'bg-gray-200' }} rounded-full h-5 w-5 text-xs">
            {{ $counts[$key] ?? 0 }}
        </span>
    </button>
@endforeach
