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
    public $userLatitude     = null;
    public $userLongitude    = null;
    public $distance         = null;
    public $isInRange        = false;
    public $checkInStatus    = null;
    public $errorMessage     = null;
    public $todayAttendance  = null;
    public $todaySchedule    = null;
    public $isWithinSchedule = false;
    public $scheduleStatus   = null;

      // Office location coordinates
    public $officeLatitude  = null;
    public $officeLongitude = null;
    public $allowedRadius   = null;
    public $officeName      = null;

    protected $listeners = ['locationUpdated', 'performCheckIn', 'performCheckOut'];

    public function mount()
    {
        $user = Auth::user();

        if (!$user->office_id) {
            $this->errorMessage = 'You are not assigned to any office. Please contact your administrator.';
            return;
        }

        $office = Offices::find($user->office_id);

        if (!$office) {
            $this->errorMessage = 'Office not found. Please contact your administrator.';
            return;
        }

        if (!$office->is_active) {
            $this->errorMessage = 'Your office is currently inactive. Please contact your administrator.';
            return;
        }

        $this->officeLatitude  = $office->latitude;
        $this->officeLongitude = $office->longitude;
        $this->allowedRadius   = $office->check_in_radius;
        $this->officeName      = $office->name;

          // Check if user already has attendance for today
        $this->loadTodayAttendance();

          // Load today's schedule
        $this->loadTodaySchedule();

          // Check if current time is within schedule
        $this->checkScheduleTime();
    }

    public function loadTodayAttendance()
    {
        $this->todayAttendance = Attendance::where('user_id', Auth::id())->whereDate('check_in_time', Carbon::today())->first();
    }

    public function loadTodaySchedule()
    {
        $this->todaySchedule = Schedule::where('user_id', Auth::id())->whereDate('date', Carbon::today())->first();

        if (!$this->todaySchedule) {
            $this->scheduleStatus = "You don't have a schedule for today.";
        }
    }

    public function checkScheduleTime()
    {
        if (!$this->todaySchedule) {
            $this->isWithinSchedule = false;
            return;
        }

        $now           = Carbon::now();
        $scheduleStart = Carbon::parse($this->todaySchedule->start_time);
        $scheduleEnd   = Carbon::parse($this->todaySchedule->end_time);

          // Check if schedule crosses midnight
        $crossesMidnight = $scheduleEnd->lt($scheduleStart);

          // Allow check-in 30 minutes before schedule starts
        $checkInWindow = $scheduleStart->copy()->subMinutes(30);

          // Allow check-out until 2 hours after schedule ends
        $checkOutWindow = $scheduleEnd->copy()->addHours(2);

          // Adjust end time if schedule crosses midnight
        if ($crossesMidnight) {
              // If current time is before midnight and after start time
            if ($now->gte($checkInWindow)) {
                $this->isWithinSchedule = true;
            }
              // If current time is after midnight but before end time
            elseif ($now->lte($checkOutWindow)) {
                $this->isWithinSchedule = true;
            } else {
                $this->isWithinSchedule = false;

                if ($now->lt($checkInWindow)) {
                    $this->scheduleStatus = 'Your schedule starts at ' . $scheduleStart->format('h:i A') . '. You can check in 30 minutes before.';
                } else {
                    $this->scheduleStatus = 'Your schedule ended at ' . $scheduleEnd->format('h:i A') . '. You can no longer check in/out for today.';
                }
            }
        } else {
              // Normal case (not crossing midnight)
            if ($now->between($checkInWindow, $checkOutWindow)) {
                $this->isWithinSchedule = true;
            } else {
                $this->isWithinSchedule = false;

                if ($now->lt($checkInWindow)) {
                    $this->scheduleStatus = 'Your schedule starts at ' . $scheduleStart->format('h:i A') . '. You can check in 30 minutes before.';
                } else {
                    $this->scheduleStatus = 'Your schedule ended at ' . $scheduleEnd->format('h:i A') . '. You can no longer check in/out for today.';
                }
            }
        }
    }

    public function locationUpdated($latitude, $longitude, $distance, $isInRange)
    {
        $this->userLatitude  = $latitude;
        $this->userLongitude = $longitude;
        $this->distance      = round($distance);
        $this->isInRange     = $isInRange;

          // Reset status messages when location changes
        $this->checkInStatus = null;
        $this->errorMessage  = null;
    }

    public function performCheckIn()
    {
        if (!$this->isInRange) {
            $this->errorMessage = "You must be within {$this->allowedRadius} meters of the office to check in.";
            return;
        }

        if (!$this->isWithinSchedule && $this->todaySchedule) {
            $this->errorMessage = $this->scheduleStatus;
            return;
        }

        if (!$this->todaySchedule) {
            $this->errorMessage = "You don't have a schedule for today. Please contact your manager.";
            return;
        }

        try {
              // Create attendance record
            Attendance::create([
                'user_id'       => Auth::id(),
                'schedule_id'   => $this->todaySchedule->id,
                'check_in_time' => Carbon::now(),
                'latitude'      => $this->userLatitude,
                'longitude'     => $this->userLongitude,
                'distance'      => $this->distance,
                'status'        => $this->determineAttendanceStatus(),
            ]);

            $this->checkInStatus = 'Success! You have checked in at ' . Carbon::now()->format('h:i A');
            $this->errorMessage  = null;

              // Reload attendance data
            $this->loadTodayAttendance();

            $this->dispatch('checkInSuccess');
        } catch (\Exception $e) {
            $this->errorMessage  = 'Failed to check in. Please try again!';
            $this->checkInStatus = null;
        }
    }

    public function performCheckOut()
    {
        if (!$this->isInRange) {
            $this->errorMessage = "You must be within {$this->allowedRadius} meters of the office to check out.";
            return;
        }

        if (!$this->todayAttendance) {
            $this->errorMessage = "You haven't checked in today.";
            return;
        }

        if ($this->todayAttendance->check_out_time) {
            $this->errorMessage = 'You have already checked out today.';
            return;
        }

        try {
              // Update attendance record with check out time
            $this->todayAttendance->check_out_time = Carbon::now();
            $this->todayAttendance->save();

            $this->checkInStatus = 'Success! You have checked out at ' . Carbon::now()->format('h:i A');
            $this->errorMessage  = null;

              // Reload attendance data
            $this->loadTodayAttendance();

            $this->dispatch('checkOutSuccess');
        } catch (\Exception $e) {
            $this->errorMessage  = 'Failed to check out. Please try again.';
            $this->checkInStatus = null;
        }
    }

    private function determineAttendanceStatus()
    {
        if (!$this->todaySchedule) {
            return 'PRESENT';
        }

        $now           = Carbon::now();
        $scheduleStart = Carbon::parse($this->todaySchedule->start_time);

          // If check-in is more than 15 minutes late, mark as 'late'
        if ($now->gt($scheduleStart->copy()->addMinutes(15))) {
            return 'LATE';
        }

        return 'PRESENT';
    }

    public function render()
    {
        return view('livewire.employee.dashboard.check-in');
    }
}