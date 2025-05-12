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
    public $transportation;
    public $tripStats;
    public $notes;
    // public $additional_travelers = [];
    // public $attachments = [];
    public $editMode = false;
    public $maxMonthlyDays = 5;
    public $maxDailyCost = 300000; // 300k per hari
    public $maxMonthlyCost = 1500000; // 1.5 juta per bulan
    public $businessTrip;
    public $businessTripId;
    public $total_estimated_cost;
    public $tripDays;
    public $currentStep = 1;
    public $maxStep = 3;

    protected $rules = [
        'destination' => 'required|string|max:255',
        'purpose' => 'required|string',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after_or_equal:start_date',
        'transportation' => 'required|string|in:plane,train,bus,car,motorcycle,other',
        // 'total_estimated_cost' => 'required|numeric|min:0|max:300000',
        'notes' => 'nullable|string',
        // 'additional_travelers' => 'nullable|array',
        // 'additional_travelers.*.name' => 'required|string|max:255',
        // 'additional_travelers.*.position' => 'nullable|string|max:255',
        // 'additional_travelers.*.employee_id' => 'nullable|string|max:50',
        // 'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
    ];

    protected $messages = [
        'destination.required' => 'Lokasi tujuan harus diisi',
        'purpose.required' => 'Tujuan perjalanan harus diisi',
        'start_date.required' => 'Tanggal mulai harus diisi',
        'start_date.after_or_equal' => 'Tanggal mulai harus hari ini atau setelahnya',
        'end_date.required' => 'Tanggal selesai harus diisi',
        'end_date.after_or_equal' => 'Tanggal selesai harus setelah tanggal mulai',
        'transportation.required' => 'Jenis transportasi harus dipilih',
        'total_estimated_cost.required' => 'Estimasi biaya per hari harus diisi',
        'total_estimated_cost.numeric' => 'Estimasi biaya harus berupa angka',
        'total_estimated_cost.min' => 'Estimasi biaya tidak boleh negatif',
        // 'total_estimated_cost.max' => 'Estimasi biaya maksimal Rp 300.000 per hari',
        // 'additional_travelers.*.name.required' => 'Nama peserta tambahan harus diisi',
        // 'attachments.*.max' => 'Ukuran file maksimal 10MB',
        // 'attachments.*.mimes' => 'Format file harus PDF, DOC, DOCX, JPG, atau PNG',
    ];

    public function mount($id = null)
    {
        $this->start_date = Carbon::now()->format('Y-m-d');
        $this->end_date = Carbon::now()->format('Y-m-d');
        $this->tripStats = $this->getMonthlyTripStats();
        // dd($this->tripStats);

        if ($id) {
            $this->businessTripId = $id;
            $this->editMode = true;
            $this->loadBusinessTrip();
        }
    }

    public function getTotalCost()
    {
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);
        $tripDays = $startDate->diffInDays($endDate) + 1;
        $totalCost = $tripDays * $this->maxDailyCost;
        if ($totalCost > $this->maxMonthlyCost) {
            $this->addError('total_estimated_cost', 'Total biaya perjalanan melebihi batas maksimal bulanan.');
        }
        $data = [
            'tripDays' => $tripDays,
            'totalCost' => $totalCost,
        ];
        return $data;
    }

    public function loadBusinessTrip()
    {
        $this->businessTrip = BusinessTrips::findOrFail($this->businessTripId);

        $this->destination = $this->businessTrip->destination;
        $this->purpose = $this->businessTrip->purpose;
        $this->start_date = $this->businessTrip->start_date->format('Y-m-d');
        $this->end_date = $this->businessTrip->end_date->format('Y-m-d');
        $this->transportation = $this->businessTrip->transportation;
        $this->notes = $this->businessTrip->notes;
        // $this->additional_travelers = $this->businessTrip->additional_travelers ?? [];
    }

    protected function validateMonthlyLimits()
    {
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);
        $tripDays = $endDate->diffInDays($startDate) + 1;
        $totalEstimatedCost = $tripDays * $this->maxDailyCost;

        $monthStart = Carbon::parse($startDate)->startOfMonth();
        $monthEnd = Carbon::parse($startDate)->endOfMonth();

        // Query untuk mengambil perjalanan dinas di bulan yang sama
        $query = BusinessTrips::where('user_id', Auth::id())
            ->where('status', '!=', 'rejected')
            ->where(function ($q) use ($monthStart, $monthEnd) {
                // Perjalanan yang mulai atau berakhir di bulan ini
                $q->whereBetween('start_date', [$monthStart, $monthEnd])
                  ->orWhereBetween('end_date', [$monthStart, $monthEnd]);
            });

        // Jika dalam mode edit, kecualikan SPPD yang sedang diedit
        if ($this->editMode) {
            $query->where('id', '!=', $this->businessTripId);
        }

        $trips = $query->get();

        // Hitung total hari perjalanan di bulan ini
        $usedDays = 0;
        $usedCost = 0;

        foreach ($trips as $trip) {
            $tripStart = Carbon::parse($trip->start_date);
            $tripEnd = Carbon::parse($trip->end_date);

            // Jika perjalanan melewati batas bulan, potong bagian yang di luar bulan ini
            if ($tripStart->lt($monthStart)) {
                $tripStart = $monthStart;
            }

            if ($tripEnd->gt($monthEnd)) {
                $tripEnd = $monthEnd;
            }

            // Hitung hari efektif di bulan ini
            $effectiveDays = $tripStart->diffInDays($tripEnd) + 1;
            $usedDays += $effectiveDays;

            // Hitung biaya
            $usedCost += $effectiveDays * $this->maxDailyCost;
        }

        // Hitung hari yang akan digunakan untuk perjalanan ini (dibatasi dalam bulan yang sama)
        $effectiveTripStart = $startDate->lt($monthStart) ? $monthStart : $startDate;
        $effectiveTripEnd = $endDate->gt($monthEnd) ? $monthEnd : $endDate;
        $effectiveTripDays = $effectiveTripStart->diffInDays($effectiveTripEnd) + 1;

        // Total proyeksi setelah menambahkan perjalanan ini
        $projectedDays = $usedDays + $effectiveTripDays;
        $projectedCost = $usedCost + ($effectiveTripDays * $this->maxDailyCost);

        $result = [
            'isValid' => true,
            'message' => null,
            'usedDays' => $usedDays,
            'usedCost' => $usedCost,
            'requestedDays' => $effectiveTripDays,
            'requestedCost' => $effectiveTripDays * $this->maxDailyCost,
            'projectedDays' => $projectedDays,
            'projectedCost' => $projectedCost,
            'remainingDays' => $this->maxMonthlyDays - $usedDays,
            'remainingCost' => $this->maxMonthlyCost - $usedCost,
        ];

        // Validasi terhadap batas hari
        if ($projectedDays > $this->maxMonthlyDays) {
            $result['isValid'] = false;
            $result['message'] = "Anda telah melebihi batas maksimal {$this->maxMonthlyDays} hari perjalanan dinas untuk bulan " .
                $startDate->locale('id')->translatedFormat('F Y') . ". Sisa jatah: {$result['remainingDays']} hari.";
            return $result;
        }

        // Validasi terhadap batas biaya
        if ($projectedCost > $this->maxMonthlyCost) {
            $result['isValid'] = false;
            $result['message'] = "Anda telah melebihi batas maksimal biaya perjalanan dinas sebesar Rp " .
                number_format($this->maxMonthlyCost, 0, ',', '.') . " untuk bulan " .
                $startDate->locale('id')->translatedFormat('F Y') . ". Sisa anggaran: Rp " .
                number_format($result['remainingCost'], 0, ',', '.');
            return $result;
        }

        return $result;
    }

    public function saveTrip()
    {
        $this->validate();

        $validationResult = $this->validateMonthlyLimits();
        if (!$validationResult['isValid']) {
            session()->flash('error', $validationResult['message']);
            return null;
        }

        try {
            $tripDays = $this->getTotalCost()['tripDays'];
            $totalCost = $this->total_estimated_cost;
            $tripData = [
                'destination' => $this->destination,
                'purpose' => $this->purpose,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'transportation' => $this->transportation,
                'total_days' => $tripDays,
                'total_estimated_cost' => $totalCost,
                'notes' => $this->notes,
                // 'additional_travelers' => $this->additional_travelers,
                'updated_at' => now(),
            ];

            if ($this->editMode) {
                $this->businessTrip->update($tripData);
                $message = 'Pengajuan perjalanan dinas berhasil diperbarui!';
            } else {
                $tripData['user_id'] = Auth::id();
                $tripData['status'] = 'pending';

                $this->businessTrip = BusinessTrips::create($tripData);
                $message = 'Pengajuan perjalanan dinas berhasil disimpan!';
            }

            // Handle file uploads
            // $this->uploadAttachments();

            session()->flash('message', $message);
            return redirect()->route('employee.approvals.business-trips.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // protected function uploadAttachments()
    // {
    //     if (!empty($this->attachments)) {
    //         foreach ($this->attachments as $attachment) {
    //             $path = $attachment->store('business-trips', 'public');

    //             // Save attachment to database
    //             $this->businessTrip->attachments()->create([
    //                 'file_path' => $path,
    //                 'file_name' => $attachment->getClientOriginalName(),
    //                 'file_type' => $attachment->getClientMimeType(),
    //                 'file_size' => $attachment->getSize(),
    //             ]);
    //         }
    //     }
    // }

    public function getMonthlyTripStats()
    {
        $currentMonth = Carbon::now()->format('F Y');
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        $query = BusinessTrips::where('user_id', Auth::id())
            ->where('status', '!=', 'rejected')
            ->where(function ($q) use ($monthStart, $monthEnd) {
                $q->whereBetween('start_date', [$monthStart, $monthEnd])
                  ->orWhereBetween('end_date', [$monthStart, $monthEnd]);
            });

        $trips = $query->get();

        // Hitung total hari dan biaya
        $usedDays = 0;
        $usedCost = 0;

        foreach ($trips as $trip) {
            $tripStart = Carbon::parse($trip->start_date);
            $tripEnd = Carbon::parse($trip->end_date);

            // Jika perjalanan melewati batas bulan, potong bagian yang di luar bulan ini
            if ($tripStart->lt($monthStart)) {
                $tripStart = $monthStart;
            }

            if ($tripEnd->gt($monthEnd)) {
                $tripEnd = $monthEnd;
            }

            // Hitung hari efektif di bulan ini
            $effectiveDays = $tripStart->diffInDays($tripEnd) + 1;
            $usedDays += $effectiveDays;

            // Hitung biaya
            $usedCost += $effectiveDays * $this->maxDailyCost;
        }

        return [
            'usedDays' => $usedDays,
            'maxDays' => $this->maxMonthlyDays,
            'remainingDays' => max(0, $this->maxMonthlyDays - $usedDays),
            'usedCost' => $usedCost,
            'maxCost' => $this->maxMonthlyCost,
            'remainingCost' => max(0, $this->maxMonthlyCost - $usedCost),
            'month' => $currentMonth,
        ];
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['start_date', 'end_date'])) {
            $this->validateOnly($propertyName);
            $this->calculateTripDetails();
        }

        if ($propertyName === 'total_estimated_cost') {
            $this->validateOnly($propertyName);
        }
    }

    public function calculateTripDetails()
    {
        if (!empty($this->start_date) && !empty($this->end_date)) {
            $startDate = Carbon::parse($this->start_date);
            $endDate = Carbon::parse($this->end_date);

            if ($endDate->lt($startDate)) {
                $this->end_date = $this->start_date;
                $endDate = $startDate;
            }
        }
    }

    public function cancel()
    {
        return redirect()->route('employee.approvals.business-trips.index');
    }

    public function validateStep1()
    {
        $this->validate([
            'destination' => 'required|string|max:255',
            'purpose' => 'required|string',
            'transportation' => 'required|string|in:plane,train,bus,car,motorcycle,other',
        ]);

        $this->nextStep();
    }

    public function validateStep2()
    {
        $this->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $validationResult = $this->validateMonthlyLimits();
        if (!$validationResult['isValid']) {
            $this->addError('monthly_limit', $validationResult['message']);
            return;
        }

        $this->nextStep();
    }

    public function nextStep()
    {
        $this->currentStep++;
        if ($this->currentStep > $this->maxStep) {
            $this->maxStep = $this->currentStep;
        }
    }

    public function goToStep($step)
    {
        if ($step <= $this->maxStep) {
            $this->currentStep = $step;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function render()
    {
        $this->total_estimated_cost = $this->getTotalCost()['totalCost'];
        return view('livewire.employee.approvals.business-trips.business-trips-form');
    }
}