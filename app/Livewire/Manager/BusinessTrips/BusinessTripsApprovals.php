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

    protected $listeners = ['refreshBusinessTripApprovals' => '$refresh'];

    public function approve($tripId)
    {
        $trip = BusinessTrips::findOrFail($tripId);
        $trip->status = 'approved';
        $trip->approved_by = Auth::id();
        $trip->approved_at = now();
        $trip->save();

        session()->flash('message', 'Business trip request approved successfully.');
    }

    public function showRejectModal($tripId)
    {
        $this->selectedTrip = BusinessTrips::findOrFail($tripId);
        $this->rejectionReason = '';
        $this->showDetailsModal = true;
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

        session()->flash('message', 'Business trip request rejected.');
    }

    public function closeModal()
    {
        $this->showDetailsModal = false;
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