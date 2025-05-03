<div class="bg-white rounded-xl shadow-sm p-12 text-center">
    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
        <svg class="w-8 h-8 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
            <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-900">No business trip requests</h3>
    <p class="mt-2 text-sm text-gray-500">
        @if($filter === 'pending')
            There are no pending business trip requests to review.
        @elseif($filter === 'approved')
            No business trips have been approved yet.
        @elseif($filter === 'rejected')
            No business trips have been rejected.
        @elseif($filter === 'completed')
            No business trips have been completed.
        @endif
    </p>
</div>