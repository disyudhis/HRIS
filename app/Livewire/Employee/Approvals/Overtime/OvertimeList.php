<?php

namespace App\Livewire\Employee\Approvals\Overtime;

use Livewire\Component;
use App\Models\Overtime;
use Livewire\WithPagination;

class OvertimeList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $overtimeId = null;
    public $selectedOvertime = null;
    public $hours;

    // public function mount(){
    //     $this->hours = $this->calculateHours();
    // }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function getOvertimeDetails($id)
    {
        $overtime = Overtime::with('approver')->find($id);

        if ($overtime) {
            $formattedOvertimeData = [
                'id' => $overtime->id,
                'date' => $overtime->date->format('d M Y'),
                'start_time' => $overtime->start_time->format('H:i'),
                'end_time' => $overtime->end_time->format('H:i'),
                'hours' => $this->calculateHours($overtime->start_time, $overtime->end_time),
                'reason' => $overtime->reason,
                'tasks' => $overtime->tasks,
                'status' => $overtime->status,
                'created_at' => $overtime->created_at->format('d M Y H:i'),
                'approved_at' => $overtime->approved_at ? $overtime->approved_at->format('d M Y H:i') : null,
                'approvals' => [],
            ];

            if ($overtime->status == 'approved') {
                $formattedOvertimeData['approvals'][] = [
                    'id' => $overtime->approver->id,
                    'approver_name' => $overtime->approver?->name ?? 'Unknown',
                    'approver_position' => $overtime->approver?->user_type ?? 'Unknown',
                ];
            }

            // Pastikan data dikirim sebagai objek, bukan array
            $this->dispatch('detail-modal-data', overtime: $formattedOvertimeData);
        }
    }

    public function confirmCancelOvertime($id)
    {
        $this->overtimeId = $id;
        $this->dispatch('open-confirm-modal');
    }

    public function cancelOvertime()
    {
        $overtime = Overtime::where('id', $this->overtimeId)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($overtime) {
            $overtime->update(['status' => 'cancelled']);
            session()->flash('message', 'Pengajuan lembur berhasil dibatalkan.');
        } else {
            session()->flash('error', 'Pengajuan lembur tidak dapat dibatalkan.');
        }

        $this->dispatch('close-confirm-modal');
        $this->overtimeId = null;
    }

    public function calculateHours($startTime, $endTime)
    {
        $start = \Carbon\Carbon::parse($startTime);
        $end = \Carbon\Carbon::parse($endTime);

        if ($end < $start) {
            $end->addDay();
        }

        return round($end->diffInMinutes($start) / 60, 1);
    }

    public function getStatusClass($status)
    {
        return match ($status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function render()
    {
        $overtimes = Overtime::where('user_id', auth()->id())
        ->when($this->search, function ($query) {
            return $query->where(function ($subQuery) {
                $subQuery->where('reason', 'like', '%' . $this->search . '%')
                ->orWhere('no_reference', 'like', '%' . $this->search . '%')
                         ->orWhere('tasks', 'like', '%' . $this->search . '%');
            });
        })
        ->when($this->statusFilter && $this->statusFilter !== 'all', function ($query) {
            return $query->where('status', $this->statusFilter);
        })
        ->orderBy('date', 'desc')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('livewire.employee.approvals.overtime.overtime-list', [
        'overtimes' => $overtimes,
    ]);
    }
}
