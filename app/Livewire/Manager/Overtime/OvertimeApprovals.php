<?php

namespace App\Livewire\Manager\Overtime;

use App\Models\User;
use Livewire\Component;
use App\Models\Overtime;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class OvertimeApprovals extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'pending';
    public $selectedOvertime = null;
    public $showRejectionModal = false;
    public $showApprovalModal = false;
    public $rejectionReason = '';
    public $counts = [];

    // protected $listeners = ['refreshOvertimeApprovals' => '$refresh'];

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
            'pending' => Overtime::whereIn('user_id', $employeeIds)
                ->where('status', 'pending')
                ->when($this->search, function ($query) {
                    return $query->whereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })->orWhere('reason', 'like', '%' . $this->search . '%');
                })
                ->count(),
            'approved' => Overtime::whereIn('user_id', $employeeIds)
                ->where('status', 'approved')
                ->when($this->search, function ($query) {
                    return $query->whereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })->orWhere('reason', 'like', '%' . $this->search . '%');
                })
                ->count(),
            'rejected' => Overtime::whereIn('user_id', $employeeIds)
                ->where('status', 'rejected')
                ->when($this->search, function ($query) {
                    return $query->whereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })->orWhere('reason', 'like', '%' . $this->search . '%');
                })
                ->count(),
        ];
    }
    public function approve()
    {
        $this->selectedOvertime->status = 'approved';
        $this->selectedOvertime->approved_by = Auth::id();
        $this->selectedOvertime->approved_at = now();
        $this->selectedOvertime->save();

        $this->closeModal();
        session()->flash('message', 'Overtime request approved successfully.');
    }

    public function showRejectModal($overtimeId)
    {
        // dd('tes');
        $this->selectedOvertime = Overtime::findOrFail($overtimeId);
        $this->rejectionReason = '';
        $this->showRejectionModal = true;
    }

    public function showApproveModal($overtimeId)
    {
        $this->selectedOvertime = Overtime::findOrFail($overtimeId);
        $this->showApprovalModal = true;
    }

    public function reject()
    {
        $this->validate([
            'rejectionReason' => 'required|min:5',
        ]);

        $this->selectedOvertime->status = 'rejected';
        $this->selectedOvertime->approved_by = Auth::id();
        $this->selectedOvertime->approved_at = now();
        $this->selectedOvertime->rejection_reason = $this->rejectionReason;
        $this->selectedOvertime->save();

        $this->selectedOvertime = null;
        $this->rejectionReason = '';
        $this->closeModal();

        session()->flash('message', 'Overtime request rejected.');
    }

    public function closeModal()
    {
        $this->showRejectionModal = false;
        $this->showApprovalModal = false;
    }

    public function render()
    {
        // Get all employees managed by the current manager
        $employeeIds = User::where('manager_id', Auth::id())->pluck('id');

        $overtimes = Overtime::whereIn('user_id', $employeeIds)
            ->when($this->filter === 'pending', function ($query) {
                return $query->where('status', 'pending');
            })
            ->when($this->filter === 'approved', function ($query) {
                return $query->where('status', 'approved');
            })
            ->when($this->filter === 'rejected', function ($query) {
                return $query->where('status', 'rejected');
            })
            ->when($this->search, function ($query) {
                return $query
                    ->whereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('no_reference', 'like', '%' . $this->search . '%')
                    ->orWhere('reason', 'like', '%' . $this->search . '%');
            })
            ->with('user')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('livewire.manager.overtime.overtime-approvals', [
            'overtimes' => $overtimes,
        ]);
    }
}
