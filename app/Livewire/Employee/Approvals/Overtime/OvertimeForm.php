<?php

namespace App\Livewire\Employee\Approvals\Overtime;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Overtime;
use Illuminate\Validation\ValidationException;

class OvertimeForm extends Component
{
    public $date;
    public $type;
    public $start_time;
    public $end_time;
    public $reason;
    public $usedOvertimeHours;
    public $remainingOvertimeHours;
    public $estimatedDuration;
    public $estimatedCost;

    public const MAX_MONTHLY_OVERTIME_HOURS = 30; // Batas maksimal lembur per bulan
    public const HOURLY_RATE = 25000;
    // Validation rules
    protected $rules = [
        'date' => 'required|date|date_format:Y-m-d',
        'type' => 'required|in:weekday,weekend,holiday',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
        'reason' => 'required|string|min:10|max:500',
    ];

    // Custom validation messages
    protected $messages = [
        'date.required' => 'Tanggal lembur harus diisi',
        'date.date' => 'Format tanggal tidak valid',
        'type.required' => 'Jenis lembur harus dipilih',
        'type.in' => 'Jenis lembur tidak valid',
        'start_time.required' => 'Waktu mulai harus diisi',
        'start_time.date_format' => 'Format waktu mulai tidak valid',
        'end_time.required' => 'Waktu selesai harus diisi',
        'end_time.date_format' => 'Format waktu selesai tidak valid',
        'end_time.after' => 'Waktu selesai harus setelah waktu mulai',
        'reason.required' => 'Uraian pekerjaan harus diisi',
        'reason.min' => 'Uraian pekerjaan minimal 10 karakter',
        'reason.max' => 'Uraian pekerjaan maksimal 500 karakter',
    ];

    public function mount()
    {
        // Set tanggal default ke hari ini
        $this->date = Carbon::today()->format('Y-m-d');

        // Inisialisasi informasi kuota lembur
        $this->updateOvertimeQuotaInfo();
        $this->estimatedCost = 0;
    }

    /**
     * Update informasi kuota lembur saat ini
     */
    public function updateOvertimeQuotaInfo()
    {
        $this->usedOvertimeHours = $this->getTotalMonthlyOvertimeHours();
        $this->remainingOvertimeHours = max(0, self::MAX_MONTHLY_OVERTIME_HOURS - $this->usedOvertimeHours);
        $this->calculateEstimatedDuration();
        $this->calculateEstimatedCost();
    }

    /**
     * Menghitung durasi lembur dalam jam
     *
     * @return float
     */
    private function calculateOvertimeDuration(): float
    {
        $startDateTime = Carbon::parse($this->date . ' ' . $this->start_time);
        $endDateTime = Carbon::parse($this->date . ' ' . $this->end_time);

        // Jika waktu selesai adalah hari berikutnya
        if ($endDateTime->lt($startDateTime)) {
            $endDateTime->addDay();
        }

        // Hitung durasi dalam menit, lalu dibulatkan ke atas ke jam penuh
        $durationInMinutes = $startDateTime->diffInMinutes($endDateTime);
        $durationInHours = ceil($durationInMinutes / 60); // dibulatkan ke atas

        return $durationInHours;
    }


    /**
     * Menghitung estimasi durasi lembur untuk ditampilkan di form
     *
     * @return void
     */
    public function calculateEstimatedDuration(): void
    {
        if (!$this->start_time || !$this->end_time) {
            $this->estimatedDuration = 0;
            return;
        }

        try {
            $this->estimatedDuration = $this->calculateOvertimeDuration();
            $this->calculateEstimatedCost();
        } catch (\Exception $e) {
            $this->estimatedDuration = 0;
            $this->estimatedCost = 0;
        }
    }

    /**
     * Menghitung estimasi biaya lembur berdasarkan durasi
     *
     * @return void
     */
    public function calculateEstimatedCost(): void
    {
        if (!$this->estimatedDuration) {
            $this->estimatedCost = 0;
            return;
        }

        try {
            $this->estimatedCost = Overtime::calculateCost($this->estimatedDuration);
        } catch (\Exception $e) {
            $this->estimatedCost = 0;
        }
    }

    /**
     * Format biaya untuk tampilan
     *
     * @return string
     */
    public function getFormattedEstimatedCost(): string
    {
        return 'Rp ' . number_format($this->estimatedCost, 0, ',', '.');
    }

    /**
     * Menghitung total jam lembur dalam bulan yang sama dengan tanggal yang dipilih
     *
     * @return float
     */
    private function getTotalMonthlyOvertimeHours(): float
    {
        $selectedDate = Carbon::parse($this->date);
        $startOfMonth = $selectedDate->copy()->startOfMonth()->format('Y-m-d');
        $endOfMonth = $selectedDate->copy()->endOfMonth()->format('Y-m-d');

        // Ambil semua overtime yang sudah disetujui dan yang masih pending pada bulan yang sama
        $overtimes = Overtime::where('user_id', auth()->id())
            ->whereIn('status', ['approved', 'pending'])
            ->whereDate('date', '>=', $startOfMonth)
            ->whereDate('date', '<=', $endOfMonth)
            ->get();

        $totalHours = 0;

        foreach ($overtimes as $overtime) {
            $date = Carbon::parse($overtime->date)->format('Y-m-d');
            $startTime = Carbon::parse($overtime->start_time)->format('H:i:s');
            $endTime = Carbon::parse($overtime->end_time)->format('H:i:s');

            $startDateTime = Carbon::parse("$date $startTime");
            $endDateTime = Carbon::parse("$date $endTime");

            // Jika waktu selesai adalah hari berikutnya
            if ($endDateTime->lt($startDateTime)) {
                $endDateTime->addDay();
            }

            $durationInHours = $startDateTime->diffInMinutes($endDateTime) / 60;
            $totalHours += $durationInHours;
        }

        return round($totalHours, 2);
    }

    /**
     * Validasi apakah penambahan jam lembur melebihi batas maksimal
     *
     * @param float $currentDuration
     * @throws ValidationException
     */
    private function validateMonthlyOvertimeLimit(float $currentDuration): void
    {
        $totalMonthlyHours = $this->getTotalMonthlyOvertimeHours();
        $newTotalHours = $totalMonthlyHours + $currentDuration;

        if ($newTotalHours > self::MAX_MONTHLY_OVERTIME_HOURS) {
            $remainingHours = self::MAX_MONTHLY_OVERTIME_HOURS - $totalMonthlyHours;

            throw ValidationException::withMessages([
                'end_time' => "Total jam lembur melebihi batas maksimal " . self::MAX_MONTHLY_OVERTIME_HOURS . " jam per bulan. Sisa kuota lembur: {$remainingHours} jam."
            ]);
        }
    }

    public function save()
    {
        // Validasi input
        $this->validate();

        try {
            // Hitung durasi lembur yang diajukan
            $overtimeDuration = $this->calculateOvertimeDuration();

            // Validasi batas maksimal lembur per bulan
            $this->validateMonthlyOvertimeLimit($overtimeDuration);

            // Hitung estimasi biaya lembur
            $estimatedCost = Overtime::calculateCost($overtimeDuration);
            $no_reference = $this->generateReferenceNumber();
            // Simpan data overtime
            Overtime::create([
                'user_id' => auth()->id(),
                'date' => $this->date,
                'type' => $this->type,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'no_reference' => $no_reference,
                'reason' => $this->reason,
                'status' => 'pending', // Status default: pending
                'estimated_cost' => $estimatedCost,
            ]);

            // Reset form setelah berhasil disimpan
            $this->reset(['type', 'start_time', 'end_time', 'reason']);
            $this->date = Carbon::today()->format('Y-m-d');

            // Update informasi kuota lembur
            $this->updateOvertimeQuotaInfo();

            // Tampilkan notifikasi sukses
            session()->flash('message', 'Pengajuan lembur berhasil disimpan');

            // Redirect ke halaman index
            return redirect()->route('employee.approvals.overtime.index');

        } catch (ValidationException $e) {
            // Tangani validasi error khusus
            $this->setErrorBag($e->validator->getMessageBag());
        } catch (\Exception $e) {
            // Tangani error umum
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function generateReferenceNumber()
    {
        return 'OVT-' . strtoupper(uniqid());
    }
    /**
     * Update properti waktu lembur ketika berubah
     */
    public function updated($propertyName)
    {
        // Jika properti yang berubah adalah waktu mulai atau selesai, update estimasi durasi dan biaya
        if (in_array($propertyName, ['start_time', 'end_time', 'date'])) {
            $this->calculateEstimatedDuration();
            $this->calculateEstimatedCost();
        }

        // Jika tanggal berubah, perbarui informasi kuota lembur
        if ($propertyName === 'date') {
            $this->updateOvertimeQuotaInfo();
        }
    }

    public function render()
    {
        return view('livewire.employee.approvals.overtime.overtime-form');
    }
}
