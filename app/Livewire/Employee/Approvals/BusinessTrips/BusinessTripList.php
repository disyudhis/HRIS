<?php

namespace App\Livewire\Employee\Approvals\BusinessTrips;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BusinessTrips;
use Illuminate\Support\Facades\Auth;

class BusinessTripList extends Component
{
    use WithPagination;

    public $destination;
    public $purpose;
    public $start_date;
    public $end_date;
    public $estimated_cost;
    public $notes;
    public $search = '';

    public $statusFilter = 'pending';

    public $showModal = false;
    public $showDetailModal = false;
    public $selectedTrip = null;

    public $editMode = false;
    public $tripId = null;

    protected $listeners = [
        'openDetailModal' => 'openDetailModal',
        'closeDetailModal' => 'closeDetailModal',
    ];

    protected $rules = [
        'destination' => 'required|string|max:255',
        'purpose' => 'required|string',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'estimated_cost' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
    ];

    public function mount()
    {
        // Initialization if needed
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmCancelTrip($tripId)
    {
        $this->tripId = $tripId;
        $this->dispatch('confirm-cancel-trip');
    }

    public function openDetailModal($tripId)
    {
        $trip = BusinessTrips::with('approver')->findOrFail($tripId);

        if ($trip) {
            $tripData = [
                'id' => $trip->id,
                'destination' => $trip->destination,
                'purpose' => $trip->purpose,
                'start_date' => $trip->start_date->format('d M Y'),
                'end_date' => $trip->end_date->format('d M Y'),
                'duration' => $trip->getDurationAttribute(),
                'estimated_cost' => $trip->estimated_cost,
                'status' => $trip->status,
                'notes' => $trip->notes,
                'created_at' => $trip->created_at->format('d M Y H:i'),
                'updated_at' => $trip->updated_at->format('d M Y H:i'),
                'approvals' => [],
            ];

            if ($trip->status == 'approved') {
                $tripData['approvals'][] = [
                    'id' => $trip->approver->id,
                    'approver_name' => $trip->approver?->name ?? 'Unknown',
                    'approver_position' => $trip->approver?->user_type ?? 'Unknown',
                ];
            }
        }

        $this->selectedTrip = $tripData;

        // Dispatch event to open the modal with tripData
        $this->dispatch('open-detail-modal', trip: $tripData);
    }

    public function closeDetailModal()
    {
        $this->dispatch('close-detail-modal');
        $this->selectedTrip = null;
    }

    public function resetForm()
    {
        $this->reset(['destination', 'purpose', 'start_date', 'end_date', 'estimated_cost', 'notes', 'editMode', 'tripId']);
        $this->resetErrorBag();
    }

    public function editTrip($tripId)
    {
        $this->editMode = true;
        $this->tripId = $tripId;

        $trip = BusinessTrips::find($tripId);

        $this->destination = $trip->destination;
        $this->purpose = $trip->purpose;
        $this->start_date = $trip->start_date->format('Y-m-d');
        $this->end_date = $trip->end_date->format('Y-m-d');
        $this->estimated_cost = $trip->estimated_cost;
        $this->notes = $trip->notes;

        $this->showModal = true;
    }

    public function saveTrip()
    {
        $this->validate();

        if ($this->editMode) {
            $trip = BusinessTrips::find($this->tripId);

            if ($trip->status !== 'pending') {
                session()->flash('error', 'Hanya pengajuan dengan status pending yang dapat diubah.');
                return;
            }

            $success = $trip->updateRequest([
                'destination' => $this->destination,
                'purpose' => $this->purpose,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'estimated_cost' => $this->estimated_cost,
                'notes' => $this->notes,
            ]);

            if (!$success) {
                session()->flash('error', 'Gagal memperbarui pengajuan perjalanan dinas.');
                return;
            }
        } else {
            $trip = BusinessTrips::createRequest(
                [
                    'destination' => $this->destination,
                    'purpose' => $this->purpose,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'estimated_cost' => $this->estimated_cost,
                    'notes' => $this->notes,
                ],
                Auth::id(),
            );
        }

        $this->closeModal();
        $message = $this->editMode ? 'Pengajuan perjalanan dinas berhasil diperbarui' : 'Pengajuan perjalanan dinas berhasil dibuat';
        session()->flash('message', $message);
    }

    public function cancelTrip()
    {
        $trip = BusinessTrips::where('id', $this->tripId)->where('user_id', Auth::id())->first();

        if (!$trip) {
            session()->flash('error', 'Data perjalanan tidak ditemukan atau Anda tidak memiliki akses.');
            return;
        }

        if ($trip->status !== 'pending') {
            session()->flash('error', 'Hanya pengajuan dengan status pending yang dapat dibatalkan.');
            return;
        }

        try {
            $trip->delete();
            $this->dispatch('close-cancel-trip');
            session()->flash('message', 'Pengajuan perjalanan dinas berhasil dibatalkan.');
        } catch (\Exception $e) {
            $this->dispatch('close-cancel-trip');
            session()->flash('error', 'Terjadi kesalahan saat membatalkan pengajuan.');
        }
    }

    public function getStatusClass($status)
    {
        return [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            'completed' => 'bg-blue-100 text-blue-800',
        ][$status] ?? 'bg-gray-100 text-gray-800';
    }

    public function render()
    {
        $trips = BusinessTrips::where('user_id', Auth::id())
            ->when($this->statusFilter !== 'all', function ($query) {
                return $query->where('status', $this->statusFilter);
            })
            ->when($this->search, function ($query) {
                $term = '%' . $this->search . '%';
                return $query->where(function ($q) use ($term) {
                    $q->where('destination', 'like', $term)->orWhere('purpose', 'like', $term)->orWhere('status', 'like', $term);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.employee.approvals.business-trips.business-trip-list', compact('trips'));
    }
}