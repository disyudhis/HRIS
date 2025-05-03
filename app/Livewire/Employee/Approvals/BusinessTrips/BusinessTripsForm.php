<?php

namespace App\Livewire\Employee\Approvals\BusinessTrips;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\BusinessTrips;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class BusinessTripsForm extends Component
{
    use WithFileUploads;

    public $destination;
    public $purpose;
    public $start_date;
    public $end_date;
    public $estimated_cost;
    public $transportation;
    public $notes;
    public $additional_travelers = [];
    public $attachments = [];
    public $editMode = false;
    public $businessTrip;
    public $businessTripId;

    protected $rules = [
        'destination' => 'required|string|max:255',
        'purpose' => 'required|string',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after_or_equal:start_date',
        'estimated_cost' => 'required|numeric|min:0',
        'transportation' => 'required|string|in:plane,train,bus,car,motorcycle,other',
        'notes' => 'nullable|string',
        'additional_travelers' => 'nullable|array',
        'additional_travelers.*.name' => 'required|string|max:255',
        'additional_travelers.*.position' => 'nullable|string|max:255',
        'additional_travelers.*.employee_id' => 'nullable|string|max:50',
        'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
    ];

    protected $messages = [
        'destination.required' => 'Lokasi tujuan harus diisi',
        'purpose.required' => 'Tujuan perjalanan harus diisi',
        'start_date.required' => 'Tanggal mulai harus diisi',
        'start_date.after_or_equal' => 'Tanggal mulai harus hari ini atau setelahnya',
        'end_date.required' => 'Tanggal selesai harus diisi',
        'end_date.after_or_equal' => 'Tanggal selesai harus setelah tanggal mulai',
        'estimated_cost.required' => 'Estimasi biaya harus diisi',
        'estimated_cost.numeric' => 'Estimasi biaya harus berupa angka',
        'transportation.required' => 'Jenis transportasi harus dipilih',
        'additional_travelers.*.name.required' => 'Nama peserta tambahan harus diisi',
        'attachments.*.max' => 'Ukuran file maksimal 10MB',
        'attachments.*.mimes' => 'Format file harus PDF, DOC, DOCX, JPG, atau PNG',
    ];

    public function mount($id = null)
    {
        $this->start_date = Carbon::now()->format('Y-m-d');
        $this->end_date = Carbon::now()->addDay()->format('Y-m-d');

        if ($id) {
            $this->businessTripId = $id;
            $this->editMode = true;
            $this->loadBusinessTrip();
        }
    }

    public function loadBusinessTrip()
    {
        $this->businessTrip = BusinessTrips::findOrFail($this->businessTripId);

        $this->destination = $this->businessTrip->destination;
        $this->purpose = $this->businessTrip->purpose;
        $this->start_date = $this->businessTrip->start_date->format('Y-m-d');
        $this->end_date = $this->businessTrip->end_date->format('Y-m-d');
        $this->estimated_cost = $this->businessTrip->estimated_cost;
        $this->transportation = $this->businessTrip->transportation;
        $this->notes = $this->businessTrip->notes;
        $this->additional_travelers = $this->businessTrip->additional_travelers ?? [];
    }

    public function saveTrip()
    {
        // Remove formatting from estimated_cost
        if (is_string($this->estimated_cost)) {
            $this->estimated_cost = (int) preg_replace('/[^0-9]/', '', $this->estimated_cost);
        }

        $this->validate();

        try {
            if ($this->editMode) {
                $this->businessTrip->update([
                    'destination' => $this->destination,
                    'purpose' => $this->purpose,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'estimated_cost' => $this->estimated_cost,
                    'transportation' => $this->transportation,
                    'notes' => $this->notes,
                    'additional_travelers' => $this->additional_travelers,
                    'updated_at' => now(),
                ]);

                $message = 'Pengajuan perjalanan dinas berhasil diperbarui!';
            } else {
                BusinessTrips::create([
                    'user_id' => Auth::id(),
                    'destination' => $this->destination,
                    'purpose' => $this->purpose,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'estimated_cost' => $this->estimated_cost,
                    'transportation' => $this->transportation,
                    'notes' => $this->notes,
                    'additional_travelers' => $this->additional_travelers,
                    'status' => 'pending',
                ]);

                $message = 'Pengajuan perjalanan dinas berhasil disimpan!';
            }

            // Handle file uploads
            $this->uploadAttachments();

            session()->flash('message', $message);
            return redirect()->route('employee.approvals.business-trips.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    protected function uploadAttachments()
    {
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $attachment) {
                $path = $attachment->store('business-trips', 'public');

                // Save attachment to database
                // You can create an Attachment model and relationship if needed
                if ($this->editMode) {
                    $this->businessTrip->attachments()->create([
                        'file_path' => $path,
                        'file_name' => $attachment->getClientOriginalName(),
                        'file_type' => $attachment->getClientMimeType(),
                        'file_size' => $attachment->getSize(),
                    ]);
                } else {
                    // Handle for new trip
                    // This would require fetching the last inserted record
                    // or restructuring to return the created business trip
                }
            }
        }
    }

    public function cancel()
    {
        return redirect()->route('employee.approvals.business-trips.index');
    }
    public function render()
    {
        return view('livewire.employee.approvals.business-trips.business-trips-form');
    }
}