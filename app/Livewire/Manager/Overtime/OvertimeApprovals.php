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
    public $showDetailsModal = false;
    public $rejectionReason = '';

    protected $listeners = ['refreshOvertimeApprovals' => '$refresh'];

    public function approve($overtimeId)
    {
        $overtime = Overtime::findOrFail($overtimeId);
        $overtime->status = 'approved';
        $overtime->approved_by = Auth::id();
        $overtime->approved_at = now();
        $overtime->save();

        session()->flash('message', 'Overtime request approved successfully.');
    }

    public function showRejectModal($overtimeId)
    {
        $this->selectedOvertime = Overtime::findOrFail($overtimeId);
        $this->rejectionReason = '';
        $this->showDetailsModal = true;
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

        $this->showDetailsModal = false;
        $this->selectedOvertime = null;
        $this->rejectionReason = '';

        session()->flash('message', 'Overtime request rejected.');
    }

    public function closeModal()
    {
        $this->showDetailsModal = false;
        $this->selectedOvertime = null;
        $this->rejectionReason = '';
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
                    ->orWhere('reason', 'like', '%' . $this->search . '%')
                    ->orWhere('tasks', 'like', '%' . $this->search . '%');
            })
            ->with('user')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('livewire.manager.overtime.overtime-approvals', [
            'overtimes' => $overtimes,
        ]);
    }
}