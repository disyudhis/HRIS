<select
    wire:model.live="statusFilter"
    class="w-full sm:w-48 px-4 py-2.5 rounded-lg border border-gray-300 bg-gray-50 focus:bg-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#3085FE] focus:border-transparent text-sm">
    <option value="all">Semua Status</option>
    <option value="pending">Pending</option>
    <option value="approved">Approved</option>
    <option value="rejected">Rejected</option>
    <option value="cancelled">Cancelled</option>
</select>