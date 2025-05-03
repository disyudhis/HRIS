<?php

namespace App\Livewire\Employee\Approvals\Overtime;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Overtime;

class OvertimeForm extends Component
{
    public $date;
    public $type;
    public $start_time;
    public $end_time;
    public $reason;
    public $tasks;

    // Validation rules
    protected $rules = [
        'date' => 'required|date|date_format:Y-m-d',
        'type' => 'required|in:weekday,weekend,holiday',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
        'reason' => 'required|string|min:10|max:500',
        'tasks' => 'required|string|min:10|max:500',
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
        'tasks.required' => 'Detail tugas harus diisi',
        'tasks.min' => 'Detail tugas minimal 10 karakter',
        'tasks.max' => 'Detail tugas maksimal 500 karakter',
    ];

    public function mount()
    {
        // Set tanggal default ke hari ini
        $this->date = Carbon::today()->format('Y-m-d');
    }

    public function save()
    {
        // Validasi input
        $this->validate();

        try {
            // Simpan data overtime
            Overtime::create([
                'user_id' => auth()->id(),
                'date' => $this->date,
                'type' => $this->type,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'reason' => $this->reason,
                'tasks' => $this->tasks,
                'status' => 'pending', // Status default: pending
            ]);

            // Reset form setelah berhasil disimpan
            $this->reset(['type', 'start_time', 'end_time', 'reason', 'tasks']);
            $this->date = Carbon::today()->format('Y-m-d');

            // Tampilkan notifikasi sukses
            $this->dispatch('notify', 'Pengajuan lembur berhasil disimpan');

            // Redirect ke halaman index
            return redirect()->route('employee.approvals.overtime.index');

        } catch (\Exception $e) {
            // Tangani error
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.employee.approvals.overtime.overtime-form');
    }
}