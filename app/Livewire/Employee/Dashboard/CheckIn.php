<?php

namespace App\Livewire\Employee\Dashboard;

use Carbon\Carbon;
use App\Models\Offices;
use Livewire\Component;
use App\Models\Schedule;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckIn extends Component
{
    // User location properties
    public $userLatitude = null;
    public $userLongitude = null;
    public $distance = null;
    public $isInRange = false;

    // Office location properties
    public $officeLatitude = null;
    public $officeLongitude = null;
    public $allowedRadius = null;
    public $officeName = null;

    // Attendance properties
    public $todaySchedule = null;
    public $todayCheckIn = null;
    public $todayCheckOut = null;
    public $incompleteAttendance = null;

    // Status properties
    public $checkInStatus = null;
    public $errorMessage = null;
    public $scheduleStatus = null;
    public $isWithinCheckInTime = false;
    public $isWithinCheckOutTime = false;

    protected $listeners = ['locationUpdated', 'performCheckIn', 'performCheckOut'];

    public function mount()
    {
        if (!$this->validateUserOffice()) {
            return;
        }

        // Auto-checkout expired schedules first
        $this->autoCheckoutExpiredSchedules();

        // Check for incomplete attendance
        $this->checkIncompleteAttendance();

        // If no incomplete attendance, load today's schedule and attendance
        if (!$this->incompleteAttendance) {
            $this->loadTodaySchedule();
            $this->loadTodayAttendance();
            $this->checkScheduleTime();
        }
    }

    public function render()
    {
        return view('livewire.employee.dashboard.check-in');
    }

    /**
     * Auto-checkout expired schedules that passed checkout window
     */
    private function autoCheckoutExpiredSchedules()
    {
        $now = Carbon::now();

        // Find all schedules with check-in but no check-out that are past checkout window
        $expiredSchedules = Schedule::where('user_id', Auth::id())
            ->where('is_checked', false)
            ->whereHas('checkIn', function ($query) {
                $query->where('is_checked', true);
            })
            ->whereDoesntHave('checkOut', function ($query) {
                $query->where('is_checked', true);
            })
            ->get();

        foreach ($expiredSchedules as $schedule) {
            $scheduleDate = $schedule->date->format('Y-m-d');
            $scheduleEnd = Carbon::parse($scheduleDate . ' ' . $schedule->end_time->format('H:i:s'));

            // Handle cross-midnight
            if ($scheduleEnd->lt(Carbon::parse($scheduleDate . ' ' . $schedule->start_time->format('H:i:s')))) {
                $scheduleEnd->addDay();
            }

            // Checkout window: 2 hours after schedule end
            $checkoutWindowEnd = $scheduleEnd->copy()->addHours(2);

            // If current time is past checkout window, auto-checkout
            if ($now->gt($checkoutWindowEnd)) {
                try {
                    DB::beginTransaction();

                    $checkIn = $schedule->checkIn;
                    $checkOut = $schedule->checkOut;

                    // Determine status based on check-in
                    $finalStatus = Attendance::STATUS_LATE;

                    // If no checkout record exists, create one
                    if (!$checkOut) {
                        $checkOut = Attendance::create([
                            'type' => Attendance::TYPE_CHECK_OUT,
                            'schedule_id' => $schedule->id,
                            'is_checked' => false,
                            'status' => Attendance::STATUS_ABSENT,
                            'notes' => 'Auto-generated check-out record',
                        ]);
                    }

                    // Auto-checkout with CURRENT REALTIME (bukan schedule end time)
                    $checkOut->update([
                        'checked_time' => $now, // Menggunakan waktu realtime saat trigger
                        'latitude' => $checkIn->latitude,
                        'longitude' => $checkIn->longitude,
                        'distance' => $checkIn->distance,
                        'status' => $finalStatus,
                        'is_checked' => true,
                        'notes' => 'Auto checkout - exceeded checkout window at ' . $now->format('Y-m-d H:i:s'),
                    ]);

                    // Update schedule
                    $scheduleNotes = $finalStatus === Attendance::STATUS_LATE ? 'LATE (Auto)' : 'PRESENT (Auto)';
                    $schedule->update([
                        'is_checked' => true,
                        'notes' => $scheduleNotes,
                    ]);

                    DB::commit();

                    \Log::info("Auto-checkout completed for schedule ID: {$schedule->id}, Date: {$scheduleDate}, Checkout Time: {$now->format('Y-m-d H:i:s')}");
                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::error("Auto-checkout failed for schedule ID: {$schedule->id}, Error: {$e->getMessage()}");
                }
            }
        }
    }

    /**
     * Check if user has incomplete attendance (checked-in but not checked-out)
     */
    private function checkIncompleteAttendance()
    {
        $now = Carbon::now();

        $this->incompleteAttendance = Attendance::with(['schedule'])
            ->whereHas('schedule', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('type', Attendance::TYPE_CHECK_IN)
            ->where('is_checked', true)
            ->whereDoesntHave('schedule.checkOut', function ($query) {
                $query->where('is_checked', true);
            })
            ->whereHas('schedule', function ($query) use ($now) {
                // Ambil schedule yang seharusnya sudah selesai
                $query->where(function ($q) use ($now) {
                    // Normal schedule (tidak cross midnight)
                    $q->whereRaw('TIME(end_time) > TIME(start_time)')
                        ->where('end_time', '<', $now)
                        // Cross midnight schedule
                        ->orWhere(function ($q2) use ($now) {
                            $q2->whereRaw('TIME(end_time) <= TIME(start_time)')->where('date', '<', $now->copy()->subDay()->format('Y-m-d'));
                        });
                });
            })
            ->orderBy('checked_time', 'desc')
            ->first();

        if ($this->incompleteAttendance) {
            $scheduleDate = $this->incompleteAttendance->schedule->date->format('d M Y');
            $this->errorMessage = "You must complete checkout for {$scheduleDate} before starting a new attendance session.";

            // Load the incomplete schedule and checkout record
            $this->todaySchedule = $this->incompleteAttendance->schedule;
            $this->todayCheckIn = $this->incompleteAttendance;
            $this->loadCheckOutRecord();

            // Always allow checkout for incomplete attendance
            $this->isWithinCheckOutTime = true;
            $this->isWithinCheckInTime = false;
        }
    }

    /**
     * Validate user's office assignment
     */
    private function validateUserOffice(): bool
    {
        $user = Auth::user();

        if (!$user->office_id) {
            $this->errorMessage = 'You are not assigned to any office. Please contact your administrator.';
            return false;
        }

        $office = Offices::find($user->office_id);

        if (!$office) {
            $this->errorMessage = 'Office not found. Please contact your administrator.';
            return false;
        }

        if (!$office->is_active) {
            $this->errorMessage = 'Your office is currently inactive. Please contact your administrator.';
            return false;
        }

        $this->officeLatitude = $office->latitude;
        $this->officeLongitude = $office->longitude;
        $this->allowedRadius = $office->check_in_radius;
        $this->officeName = $office->name;

        return true;
    }

    /**
     * Load today's schedule
     */
    public function loadTodaySchedule()
    {
        $this->todaySchedule = Schedule::where('user_id', Auth::id())->whereDate('date', Carbon::today())->first();

        if (!$this->todaySchedule) {
            $this->scheduleStatus = "You don't have a schedule for today.";
        }
    }

    /**
     * Load today's attendance records
     */
    public function loadTodayAttendance()
    {
        if (!$this->todaySchedule) {
            return;
        }

        // Load check-in record
        $this->todayCheckIn = Attendance::where('schedule_id', $this->todaySchedule->id)->where('type', Attendance::TYPE_CHECK_IN)->first();

        // Load check-out record
        $this->loadCheckOutRecord();
    }

    /**
     * Load check-out record
     */
    public function loadCheckOutRecord()
    {
        $scheduleId = $this->todaySchedule ? $this->todaySchedule->id : null;

        if (!$scheduleId) {
            return;
        }

        $this->todayCheckOut = Attendance::where('schedule_id', $scheduleId)->where('type', Attendance::TYPE_CHECK_OUT)->first();
    }

    /**
     * Check if current time is within schedule window
     */
    public function checkScheduleTime()
    {
        if (!$this->todaySchedule) {
            $this->isWithinCheckInTime = false;
            $this->isWithinCheckOutTime = false;
            return;
        }

        $now = Carbon::now();
        $scheduleDate = $this->todaySchedule->date->format('Y-m-d');
        $scheduleStart = Carbon::parse($scheduleDate . ' ' . $this->todaySchedule->start_time->format('H:i:s'));
        $scheduleEnd = Carbon::parse($scheduleDate . ' ' . $this->todaySchedule->end_time->format('H:i:s'));

        $alreadyCheckedIn = $this->todayCheckIn && $this->todayCheckIn->is_checked;

        // Allow check-in 60 minutes before schedule starts
        $checkInWindow = $scheduleStart->copy()->subMinutes(60);

        // Handle cross-midnight schedules
        if ($scheduleEnd->lt($scheduleStart)) {
            $scheduleEnd->addDay();
        }

        $checkOutWindow = $scheduleEnd->copy()->addHours(2);

        // Check-in window: dari 60 menit sebelum jadwal sampai jadwal berakhir
        if (!$alreadyCheckedIn && $now->between($checkInWindow, $scheduleEnd)) {
            $this->isWithinCheckInTime = true;
            $this->isWithinCheckOutTime = false;
            $this->scheduleStatus = 'You can check in now. Schedule: ' . $scheduleStart->format('H:i') . ' - ' . $scheduleEnd->format('H:i');
        }
        // Check-out window: dari jadwal mulai sampai 2 jam setelah jadwal berakhir
        elseif ($alreadyCheckedIn && $now->between($scheduleStart, $checkOutWindow)) {
            $this->isWithinCheckInTime = false;
            $this->isWithinCheckOutTime = true;
            $this->scheduleStatus = 'You can check out until ' . $checkOutWindow->format('H:i') . '.';
        }
        // Di luar waktu check-in/check-out
        else {
            $this->isWithinCheckInTime = false;
            $this->isWithinCheckOutTime = false;

            if (!$alreadyCheckedIn) {
                if ($now->lt($checkInWindow)) {
                    $this->scheduleStatus = 'Your schedule starts at ' . $scheduleStart->format('H:i') . '. You can check in 60 minutes before.';
                } else {
                    $this->scheduleStatus = 'Your schedule ended at ' . $scheduleEnd->format('H:i') . '. You can no longer check in for this shift.';
                }
            } else {
                $this->scheduleStatus = 'Check-out window has ended at ' . $checkOutWindow->format('H:i') . '.';
            }
        }
    }

    /**
     * Update user location
     */
    public function locationUpdated($latitude, $longitude, $distance, $isInRange)
    {
        $this->userLatitude = $latitude;
        $this->userLongitude = $longitude;
        $this->distance = round($distance);
        $this->isInRange = $isInRange;

        // Reset status messages when location changes (but keep incomplete attendance error)
        if (!$this->incompleteAttendance) {
            $this->errorMessage = null;
        }
        $this->checkInStatus = null;
    }

    /**
     * Perform check-in
     */
    public function performCheckIn()
    {
        if (!$this->validateCheckInConditions()) {
            return;
        }

        try {
            DB::beginTransaction();

            $status = $this->determineAttendanceStatus();

            // Update check-in record
            $this->todayCheckIn->update([
                'checked_time' => Carbon::now(),
                'latitude' => $this->userLatitude,
                'longitude' => $this->userLongitude,
                'distance' => $this->distance,
                'status' => $status,
                'is_checked' => true,
            ]);

            // Auto-generate checkout record if not exists
            if (!$this->todayCheckOut) {
                $this->createCheckOutRecord();
            }

            DB::commit();

            $statusMessage = $status === Attendance::STATUS_LATE ? ' (Late)' : '';
            $this->checkInStatus = 'Success! You have checked in at ' . Carbon::now()->format('H:i') . $statusMessage;
            $this->errorMessage = null;

            // Reload data
            $this->loadTodayAttendance();
            $this->checkScheduleTime();

            $this->dispatch('checkInSuccess');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorMessage = 'Failed to check in: ' . $e->getMessage();
            $this->checkInStatus = null;
        }
    }

    /**
     * Validate check-in conditions
     */
    private function validateCheckInConditions(): bool
    {
        // Block check-in if user has incomplete attendance
        if ($this->incompleteAttendance) {
            return false;
        }

        if (!$this->isInRange) {
            $this->errorMessage = "You must be within {$this->allowedRadius} meters of the office to check in.";
            return false;
        }

        if (!$this->todaySchedule) {
            $this->errorMessage = "You don't have a schedule for today. Please contact your manager.";
            return false;
        }

        if (!$this->isWithinCheckInTime) {
            $this->errorMessage = $this->scheduleStatus;
            return false;
        }

        if ($this->todayCheckIn && $this->todayCheckIn->is_checked) {
            $this->errorMessage = 'You have already checked in today.';
            return false;
        }

        return true;
    }

    /**
     * Perform check-out
     */
    public function performCheckOut()
    {
        if (!$this->validateCheckOutConditions()) {
            return;
        }

        try {
            DB::beginTransaction();

            // Determine checkout status based on check-in status
            $checkInStatus = $this->todayCheckIn->status;
            $checkOutStatus = Attendance::STATUS_PRESENT;

            // Jika check-in late, maka status tetap LATE
            if ($checkInStatus === Attendance::STATUS_LATE) {
                $checkOutStatus = Attendance::STATUS_LATE;
            }

            // Update check-out record
            $this->todayCheckOut->update([
                'checked_time' => Carbon::now(),
                'latitude' => $this->userLatitude,
                'longitude' => $this->userLongitude,
                'distance' => $this->distance,
                'status' => $checkOutStatus,
                'is_checked' => true,
                'notes' => 'Check-out completed',
            ]);

            // Update schedule as completed
            $scheduleNotes = $checkOutStatus === Attendance::STATUS_LATE ? 'LATE' : 'PRESENT';
            $this->todaySchedule->update([
                'is_checked' => true,
                'notes' => $scheduleNotes,
            ]);

            // Clear incomplete attendance if any
            $this->incompleteAttendance = null;
            $this->errorMessage = null;

            DB::commit();

            $this->checkInStatus = 'Success! You have checked out at ' . Carbon::now()->format('H:i');

            // Reload data
            $this->loadTodayAttendance();
            $this->checkScheduleTime();

            $this->dispatch('checkOutSuccess');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorMessage = 'Failed to check out: ' . $e->getMessage();
            $this->checkInStatus = null;
        }
    }

    /**
     * Validate check-out conditions
     */
    private function validateCheckOutConditions(): bool
    {
        if (!$this->isInRange) {
            $this->errorMessage = "You must be within {$this->allowedRadius} meters of the office to check out.";
            return false;
        }

        // Allow checkout for incomplete attendance
        if ($this->incompleteAttendance) {
            return true;
        }

        if (!$this->todayCheckIn || !$this->todayCheckIn->is_checked) {
            $this->errorMessage = "You haven't checked in today.";
            return false;
        }

        if ($this->todayCheckOut && $this->todayCheckOut->is_checked) {
            $this->errorMessage = 'You have already checked out today.';
            return false;
        }

        if (!$this->isWithinCheckOutTime) {
            $this->errorMessage = $this->scheduleStatus;
            return false;
        }

        return true;
    }

    /**
     * Determine attendance status based on check-in time
     */
    private function determineAttendanceStatus(): string
    {
        if (!$this->todaySchedule) {
            return Attendance::STATUS_PRESENT;
        }

        $now = Carbon::now();
        $scheduleDate = $this->todaySchedule->date->format('Y-m-d');
        $scheduleStart = Carbon::parse($scheduleDate . ' ' . $this->todaySchedule->start_time->format('H:i:s'));

        // Consider late if more than 30 minutes after schedule start (sesuai grace period)
        if ($now->gt($scheduleStart->copy()->addMinutes(Schedule::LATE_GRACE_PERIOD_MINUTES))) {
            return Attendance::STATUS_LATE;
        }

        return Attendance::STATUS_PRESENT;
    }

    /**
     * Create check-out record after successful check-in
     */
    private function createCheckOutRecord()
    {
        Attendance::create([
            'type' => Attendance::TYPE_CHECK_OUT,
            'schedule_id' => $this->todaySchedule->id,
            'is_checked' => false,
            'status' => Attendance::STATUS_ABSENT,
            'notes' => 'Auto-generated check-out record',
        ]);
    }
}
