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

        // Check for incomplete attendance first
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
                // hanya ambil schedule yang seharusnya sudah selesai
                $query->where('end_time', '<', $now);
            })
            ->orderBy('checked_time', 'desc')
            ->first();

        if ($this->incompleteAttendance) {
            $scheduleDate = $this->incompleteAttendance->schedule->date->format('Y-m-d');
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
        $scheduleStart = Carbon::parse($this->todaySchedule->start_time);
        $scheduleEnd = Carbon::parse($this->todaySchedule->end_time);
        $alreadyCheckedIn = $this->todayCheckIn && $this->todayCheckIn->is_checked;

        // Allow check-in 30 minutes before schedule starts
        $checkInWindow = $scheduleStart->copy()->subMinutes(30);

        if ($scheduleEnd->lt($scheduleStart)) {
            // Cross midnight
            $scheduleEnd->addDay(); // geser ke hari berikutnya
            $this->handleCrossMidnightSchedule($now, $checkInWindow, $scheduleStart, $scheduleEnd, $alreadyCheckedIn);
        } else {
            // Normal
            $this->handleNormalSchedule($now, $checkInWindow, $scheduleStart, $scheduleEnd, $alreadyCheckedIn);
        }
    }

    private function handleNormalSchedule($now, $checkInWindow, $scheduleStart, $scheduleEnd, $alreadyCheckedIn)
    {
        $checkOutWindow = $scheduleEnd->copy()->addHours(2);

        if ($now->between($checkInWindow, $scheduleEnd)) {
            $this->isWithinCheckInTime = !$alreadyCheckedIn;
            $this->isWithinCheckOutTime = false;
        } elseif ($alreadyCheckedIn && $now->between($scheduleStart, $checkOutWindow)) {
            $this->isWithinCheckInTime = false;
            $this->isWithinCheckOutTime = true;
        } else {
            $this->isWithinCheckInTime = false;
            $this->isWithinCheckOutTime = false;
        }
    }

    private function handleCrossMidnightSchedule($now, $checkInWindow, $scheduleStart, $scheduleEnd, $alreadyCheckedIn)
    {
        $checkOutWindow = $scheduleEnd->copy()->addHours(2);

        // Sama saja dengan normal setelah scheduleEnd digeser ke +1 hari
        if ($now->between($checkInWindow, $scheduleEnd)) {
            $this->isWithinCheckInTime = !$alreadyCheckedIn;
            $this->isWithinCheckOutTime = false;
        } elseif ($alreadyCheckedIn && $now->between($scheduleStart, $checkOutWindow)) {
            $this->isWithinCheckInTime = false;
            $this->isWithinCheckOutTime = true;
        } else {
            $this->isWithinCheckInTime = false;
            $this->isWithinCheckOutTime = false;
        }
    }

    private function setScheduleStatusMessage($now, $checkInWindow, $scheduleStart, $scheduleEnd, $checkOutWindow, $alreadyCheckedIn)
    {
        if (!$alreadyCheckedIn) {
            if ($now->lt($checkInWindow)) {
                $this->scheduleStatus = 'Your schedule starts at ' . $scheduleStart->format('H:i') . '. You can check in 30 minutes before.';
            } else {
                $endTimeDisplay = $scheduleEnd->lt($scheduleStart) ? $scheduleEnd->copy()->addDay()->format('H:i (next day)') : $scheduleEnd->format('H:i');
                $this->scheduleStatus = 'Your schedule ended at ' . $endTimeDisplay . '. You can no longer check in for this shift.';
            }
        } else {
            $checkOutDisplay = $checkOutWindow->lt($scheduleStart) ? $checkOutWindow->copy()->addDay()->format('H:i (next day)') : $checkOutWindow->format('H:i');
            $this->scheduleStatus = 'You can check out until ' . $checkOutDisplay . '.';
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

            // Update check-in record
            $this->todayCheckIn->update([
                'checked_time' => Carbon::now(),
                'latitude' => $this->userLatitude,
                'longitude' => $this->userLongitude,
                'distance' => $this->distance,
                'status' => $this->determineAttendanceStatus(),
                'is_checked' => true,
            ]);

            // Auto-generate checkout record
            $this->createCheckOutRecord();

            DB::commit();

            $this->checkInStatus = 'Success! You have checked in at ' . Carbon::now()->format('h:i A');
            $this->errorMessage = null;

            // Reload data
            $this->loadTodayAttendance();
            $this->checkScheduleTime();

            $this->dispatch('checkInSuccess');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorMessage = 'Failed to check in. Please try again!';
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

            // Update check-out record
            $this->todayCheckOut->update([
                'checked_time' => Carbon::now(),
                'latitude' => $this->userLatitude,
                'longitude' => $this->userLongitude,
                'distance' => $this->distance,
                'status' => Attendance::STATUS_PRESENT,
                'is_checked' => true,
            ]);

            // Update schedule as completed
            $this->todaySchedule->update([
                'is_checked' => true,
                'notes' => 'PRESENT',
            ]);

            // Clear incomplete attendance if any
            $this->incompleteAttendance = null;
            $this->errorMessage = null;

            DB::commit();

            $this->checkInStatus = 'Success! You have checked out at ' . Carbon::now()->format('h:i A');

            // Reload data
            $this->loadTodayAttendance();
            $this->checkScheduleTime();

            $this->dispatch('checkOutSuccess');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorMessage = 'Failed to check out. Please try again.';
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
        $scheduleStart = Carbon::parse($this->todaySchedule->start_time);

        // Consider late if more than 15 minutes after schedule start
        if ($now->gt($scheduleStart->copy()->addMinutes(15))) {
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
