<?php

namespace App\Livewire\Manager\BusinessTrips;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BusinessTrips;
use Illuminate\Support\Facades\Auth;

class BusinessTripsApprovals extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'pending';
    public $selectedTrip = null;
    public $showDetailsModal = false;
    public $rejectionReason = '';

    public $showApprovalModal = false;
    public $counts = [];

    // protected $listeners = ['refreshBusinessTripApprovals' => '$refresh'];

    public function mount()
    {
        // Initialize counts when component is mounted
        $this->updateCounts();
    }

    public function updatedSearch()
    {
        // Reset pagination when search is updated
        $this->resetPage();
        $this->updateCounts();
    }

    public function updatedFilter()
    {
        // Reset pagination when filter is changed
        $this->resetPage();
    }

    private function updateCounts()
    {
        // Get all employees managed by the current manager
        $employeeIds = User::where('manager_id', Auth::id())->pluck('id');

        // Get counts for each status
        $this->counts = [
            'pending' => BusinessTrips::whereIn('user_id', $employeeIds)
                ->where('status', 'pending')
                ->when($this->search, function ($query) {
                    return $query->whereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })->orWhere('destination', 'like', '%' . $this->search . '%')
                    ->orWhere('purpose', 'like', '%' . $this->search . '%');
                })
                ->count(),
            'approved' => BusinessTrips::whereIn('user_id', $employeeIds)
                ->where('status', 'approved')
                ->when($this->search, function ($query) {
                    return $query->whereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })->orWhere('destination', 'like', '%' . $this->search . '%')
                    ->orWhere('purpose', 'like', '%' . $this->search . '%');
                })
                ->count(),
            'rejected' => BusinessTrips::whereIn('user_id', $employeeIds)
                ->where('status', 'rejected')
                ->when($this->search, function ($query) {
                    return $query->whereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })->orWhere('destination', 'like', '%' . $this->search . '%')
                    ->orWhere('purpose', 'like', '%' . $this->search . '%');
                })
                ->count(),
        ];
    }

    public function approve()
    {
        $trip = BusinessTrips::findOrFail($this->selectedTrip);
        $trip->status = 'approved';
        $trip->approved_by = Auth::id();
        $trip->approved_at = now();
        $trip->save();

        // Update counts after approval
        $this->updateCounts();
        $this->closeModal();
        session()->flash('message', 'Business trip request approved successfully.');
    }

    public function showRejectModal($tripId)
    {
        $this->selectedTrip = BusinessTrips::findOrFail($tripId);
        $this->rejectionReason = '';
        $this->showDetailsModal = true;
    }

    public function showApproveModal($tripId)
    {
        $this->selectedTrip = $tripId;
        $this->showApprovalModal = true;
    }

    public function reject()
    {
        $this->validate([
            'rejectionReason' => 'required|min:5',
        ]);

        $this->selectedTrip->status = 'rejected';
        $this->selectedTrip->approved_by = Auth::id();
        $this->selectedTrip->approved_at = now();
        $this->selectedTrip->rejection_reason = $this->rejectionReason;
        $this->selectedTrip->save();

        $this->showDetailsModal = false;
        $this->selectedTrip = null;
        $this->rejectionReason = '';

        // Update counts after rejection
        $this->updateCounts();
        session()->flash('message', 'Business trip request rejected.');
    }

    public function closeModal()
    {
        $this->showDetailsModal = false;
        $this->showApprovalModal = false;
        $this->selectedTrip = null;
        $this->rejectionReason = '';
    }

    public function render()
    {
        // Get all employees managed by the current manager
        $employeeIds = User::where('manager_id', Auth::id())->pluck('id');

        $trips = BusinessTrips::whereIn('user_id', $employeeIds)
            ->when($this->filter === 'pending', function ($query) {
                return $query->where('status', 'pending');
            })
            ->when($this->filter === 'approved', function ($query) {
                return $query->where('status', 'approved');
            })
            ->when($this->filter === 'rejected', function ($query) {
                return $query->where('status', 'rejected');
            })
            ->when($this->filter === 'completed', function ($query) {
                return $query->where('status', 'completed');
            })
            ->when($this->search, function ($query) {
                return $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhere('destination', 'like', '%' . $this->search . '%')
                  ->orWhere('purpose', 'like', '%' . $this->search . '%');
            })
            ->with('user')
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('livewire.manager.business-trips.business-trips-approvals', [
            'trips' => $trips
        ]);
    }
}
