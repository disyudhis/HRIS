<?php

namespace App\Livewire\Employee\Dashboard;

use App\Models\Offices;
use Livewire\Component;
use App\Models\Schedule;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
    public $todayAttendance = null;
    public $checkOutRecord = null;
    public $todaySchedule = null;

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

        $this->loadTodaySchedule();
        $this->loadTodayAttendance();
        $this->checkScheduleTime();
        // dd($this->isWithinCheckOutTime);
    }

    public function render()
    {
        return view('livewire.employee.dashboard.check-in');
    }

    /**
     * Validate user's office assignment
     *
     * @return bool
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
     * Load today's attendance records
     */
    public function loadTodayAttendance()
    {
        $this->todayAttendance = Attendance::with('schedule')
            ->whereHas('schedule', function ($query) {
                $query->where('user_id', Auth::id())->whereDate('date', Carbon::today());
            })
            ->where('type', 'CHECK_IN')
            ->first();

        if ($this->todayAttendance && $this->todayAttendance->is_checked) {
            $this->loadCheckOutRecord();
        }
    }

    /**
     * Load check-out record for today
     */
    public function loadCheckOutRecord()
    {
        $this->checkOutRecord = Attendance::with('schedule')
            ->whereHas('schedule', function ($query) {
                $query->where('user_id', Auth::id())->whereDate('date', Carbon::today());
            })
            ->where('type', 'CHECK_OUT')
            ->first();
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
        $alreadyCheckedIn = $this->todayAttendance && $this->todayAttendance->is_checked;

        // Check if schedule crosses midnight
        $crossesMidnight = $scheduleEnd->lt($scheduleStart);

        // Allow check-in 30 minutes before schedule starts
        $checkInWindow = $scheduleStart->copy()->subMinutes(30);

        // Allow check-out until 2 hours after schedule ends
        $checkOutWindow = $scheduleEnd->copy()->addHours(2);

        if ($crossesMidnight) {
            $this->handleCrossMidnightSchedule($now, $checkInWindow, $checkOutWindow, $scheduleStart, $scheduleEnd, $alreadyCheckedIn);
        } else {
            $this->handleNormalSchedule($now, $checkInWindow, $checkOutWindow, $scheduleStart, $scheduleEnd, $alreadyCheckedIn);
        }
    }

    /**
     * Handle schedule that crosses midnight
     */
    private function handleCrossMidnightSchedule($now, $checkInWindow, $checkOutWindow, $scheduleStart, $scheduleEnd, $alreadyCheckedIn)
    {
        // If current time is before midnight and after start time
        if ($now->gte($checkInWindow) && $now->lt($scheduleStart)) {
            $this->isWithinCheckInTime = true;
            dd('cross');
        }
        // If current time is after midnight but before end time
        elseif ($now->lte($checkOutWindow)) {
            $this->isWithinCheckInTime = true;
            dd('criss');
            $this->isWithinCheckOutTime = true;
        } else {
            $this->isWithinCheckInTime = false;
            $this->isWithinCheckOutTime = true;
            $this->setScheduleStatusMessage($now, $checkInWindow, $scheduleStart, $scheduleEnd, $checkOutWindow, $alreadyCheckedIn);
        }
    }

    /**
     * Handle normal schedule (not crossing midnight)
     */
    private function handleNormalSchedule($now, $checkInWindow, $checkOutWindow, $scheduleStart, $scheduleEnd, $alreadyCheckedIn)
    {
        if ($now->between($checkInWindow, $scheduleEnd)) {
            $this->isWithinCheckInTime = true;
        }elseif($now->gte($scheduleEnd) && $now->lte($checkOutWindow)) {
            $this->isWithinCheckInTime = false;
            $this->isWithinCheckOutTime = true;
            $this->setScheduleStatusMessage($now, $checkInWindow, $scheduleStart, $scheduleEnd, $checkOutWindow, $alreadyCheckedIn);
        }  else {
            $this->isWithinCheckInTime = false;
            $this->isWithinCheckOutTime = false;
        }
    }

    /**
     * Set schedule status message based on current time
     */
    private function setScheduleStatusMessage($now, $checkInWindow, $scheduleStart, $scheduleEnd, $checkOutWindow, $alreadyCheckedIn)
    {
        if (!$alreadyCheckedIn) {
            if ($now->lt($checkInWindow)) {
                $this->scheduleStatus = 'Your schedule starts at ' . $scheduleStart->format('h:i A') . '. You can check in 30 minutes before.';
            } else {
                $this->scheduleStatus = 'Your schedule ended at ' . $scheduleEnd->format('h:i A') . '. You can no longer check in/out for today.';
            }
        } else {
            $this->scheduleStatus = 'You can check out until ' . $checkOutWindow->format('h:i A') . '.';
        }
    }

    /**
     * Update user location and reset status messages
     */
    public function locationUpdated($latitude, $longitude, $distance, $isInRange)
    {
        $this->userLatitude = $latitude;
        $this->userLongitude = $longitude;
        $this->distance = round($distance);
        $this->isInRange = $isInRange;

        // Reset status messages when location changes
        $this->checkInStatus = null;
        $this->errorMessage = null;
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
            $this->todayAttendance->checked_time = Carbon::now();
            $this->todayAttendance->latitude = $this->userLatitude;
            $this->todayAttendance->longitude = $this->userLongitude;
            $this->todayAttendance->distance = $this->distance;
            $this->todayAttendance->status = $this->determineAttendanceStatus();
            $this->todayAttendance->is_checked = true;
            $this->todayAttendance->save();

            $this->checkInStatus = 'Success! You have checked in at ' . Carbon::now()->format('h:i A');
            $this->errorMessage = null;

            $this->createCheckOutRecord();
            $this->loadTodayAttendance();

            $this->dispatch('checkInSuccess');
        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to check in. Please try again!';
            $this->checkInStatus = null;
        }
    }

    /**
     * Validate check-in conditions
     *
     * @return bool
     */
    private function validateCheckInConditions(): bool
    {
        if (!$this->isInRange) {
            $this->errorMessage = "You must be within {$this->allowedRadius} meters of the office to check in.";
            return false;
        }

        if (!$this->isWithinCheckInTime && $this->todaySchedule) {
            $this->errorMessage = $this->scheduleStatus;
            return false;
        }

        if (!$this->todaySchedule) {
            $this->errorMessage = "You don't have a schedule for today. Please contact your manager.";
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
            // Update attendance record with check out time
            $this->checkOutRecord->checked_time = Carbon::now();
            $this->checkOutRecord->latitude = $this->userLatitude;
            $this->checkOutRecord->longitude = $this->userLongitude;
            $this->checkOutRecord->distance = $this->distance;
            $this->checkOutRecord->is_checked = true;
            $this->checkOutRecord->save();

            $this->todaySchedule->is_checked = true;
            $this->todaySchedule->notes = 'PRESENT';
            $this->todaySchedule->save();

            $this->checkInStatus = 'Success! You have checked out at ' . Carbon::now()->format('h:i A');
            $this->errorMessage = null;

            $this->loadTodayAttendance();
            $this->loadCheckOutRecord();

            $this->dispatch('checkOutSuccess');
        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to check out. Please try again.';
            $this->checkInStatus = null;
        }
    }

    /**
     * Validate check-out conditions
     *
     * @return bool
     */
    private function validateCheckOutConditions(): bool
    {
        if (!$this->isInRange) {
            $this->errorMessage = "You must be within {$this->allowedRadius} meters of the office to check out.";
            return false;
        }

        if (!$this->todayAttendance || !$this->todayAttendance->is_checked) {
            $this->errorMessage = "You haven't checked in today.";
            return false;
        }

        if ($this->checkOutRecord && $this->checkOutRecord->is_checked) {
            $this->errorMessage = 'You have already checked out today.';
            return false;
        }

        return true;
    }

    /**
     * Determine attendance status based on check-in time
     *
     * @return string
     */
    private function determineAttendanceStatus(): string
    {
        if (!$this->todaySchedule) {
            return 'PRESENT';
        }

        $now = Carbon::now();
        $scheduleStart = Carbon::parse($this->todaySchedule->start_time);

        // If check-in is more than 15 minutes late, mark as 'late'
        if ($now->gt($scheduleStart->copy()->addMinutes(15))) {
            return 'LATE';
        }

        return 'PRESENT';
    }

    /**
     * Create check-out record
     */
    public function createCheckOutRecord()
    {
        Attendance::create([
            'type' => 'CHECK_OUT',
            'schedule_id' => $this->todaySchedule->id,
            'is_checked' => false,
            'status' => 'ABSENT',
            'notes' => 'Auto-generated check-out record',
        ]);
    }
}
