<?php

namespace App\Livewire\Manager\Schedules;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Schedule;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class ScheduleForm extends Component
{
    public $scheduleId;
    public $employees = [];
    public $selectedEmployees = [];
    public $date;
    public $shiftType = 'morning';
    public $notes;
    public $startTime;
    public $endTime;
    public $editMode = false;
    public $isRecurring = false;
    public $repeatFrequency = 'weekly';
    public $repeatUntil;
    public $repeatDays = [];
    public $departments = [];
    public $selectedDepartment = 'all';
    public $search = '';

    protected $rules = [
        'selectedEmployees' => 'required|array|min:1',
        'date' => 'required|date',
        'shiftType' => 'required|in:morning,afternoon,night',
        'notes' => 'nullable|string',
        'repeatUntil' => 'required_if:isRecurring,true|nullable|date|after:date',
    ];

    protected $messages = [
        'selectedEmployees.required' => 'Please select at least one employee',
        'selectedEmployees.min' => 'Please select at least one employee',
        'repeatUntil.required_if' => 'Please specify an end date for recurring schedules',
        'repeatUntil.after' => 'Repeat until date must be after the start date',
    ];

    public function mount($schedule = null, $date = null, $employeeId = null)
    {
        // Load departments for filtering employees
        $this->loadDepartments();

        $this->loadEmployees();

        // Set default date to today if not provided
        $this->date = $date ?? now()->format('Y-m-d');

        // Set default repeat until date (2 weeks from start)
        $this->repeatUntil = Carbon::parse($this->date)->addWeeks(2)->format('Y-m-d');

        // Default repeat days (weekdays)
        $this->repeatDays = ['1', '2', '3', '4', '5']; // Mon, Tue, Wed, Thu, Fri

        // Set default times based on shift type
        $this->setDefaultTimes();

        // If editing an existing schedule
        if ($schedule) {
            $this->loadSchedule($schedule);
        }

        // If creating for a specific employee
        if ($employeeId) {
            $this->selectedEmployees = [$employeeId];
        }
    }

    public function loadDepartments()
    {
        $this->departments = User::where('manager_id', Auth::id())
            ->with('user_details')
            ->whereHas('user_details', function ($query) {
                $query->whereNotNull('bidang');
            })
            ->get()
            ->map(function ($user) {
                return $user->user_details->bidang;
            })
            ->unique()
            ->values()
            ->toArray();
    }

    public function loadEmployees()
    {
        $query = User::where('manager_id', Auth::id());

        // Apply department filter if not 'all'
        if ($this->selectedDepartment !== 'all') {
            $query->where('department', $this->selectedDepartment);
        }

        // Apply search if provided
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('position', 'like', '%' . $this->search . '%')
                    ->orWhere('employee_id', 'like', '%' . $this->search . '%');
            });
        }

        $this->employees = $query->orderBy('name')->get();
    }

    public function updatedSelectedDepartment()
    {
        $this->loadEmployees();
    }

    public function updatedSearch()
    {
        $this->loadEmployees();
    }

    public function loadSchedule($schedule)
    {
        if (is_object($schedule)) {
            $this->scheduleId = $schedule->id;
            $this->selectedEmployees = [$schedule->user_id];
            $this->date = $schedule->date->format('Y-m-d');
            $this->shiftType = $schedule->shift_type;
            $this->notes = $schedule->notes;
        } else {
            $schedule = Schedule::findOrFail($schedule);
            $this->scheduleId = $schedule->id;
            $this->selectedEmployees = [$schedule->user_id];
            $this->date = $schedule->date->format('Y-m-d');
            $this->shiftType = $schedule->shift_type;
            $this->notes = $schedule->notes;
        }

        $this->editMode = true;
    }

    public function setDefaultTimes()
    {
        switch ($this->shiftType) {
            case 'morning':
                $this->startTime = '08:00';
                $this->endTime = '17:00';
                break;
            case 'afternoon':
                $this->startTime = '14:00';
                $this->endTime = '23:00';
                break;
            case 'night':
                $this->startTime = '23:00';
                $this->endTime = '07:00';
                break;
            default:
                $this->startTime = '08:00';
                $this->endTime = '17:00';
        }
    }

    public function updatedShiftType()
    {
        $this->setDefaultTimes();
    }

    public function selectAllEmployees()
    {
        $this->selectedEmployees = $this->employees->pluck('id')->toArray();
    }

    public function clearEmployeeSelection()
    {
        $this->selectedEmployees = [];
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            $schedule = Schedule::findOrFail($this->scheduleId);
            $schedule->update([
                'date' => $this->date,
                'start_time' => $this->startTime,
                'end_time' => $this->endTime,
                'shift_type' => $this->shiftType,
                'notes' => $this->notes,
                'created_by' => Auth::id(),
            ]);

            // Update attendance records jika perlu
            $this->updateAttendanceRecords($schedule);

            session()->flash('message', 'Schedule updated successfully!');
        } else {
            // Create new schedule(s)
            if ($this->isRecurring) {
                $this->createRecurringSchedules();
            } else {
                $this->createSingleDaySchedules();
            }
        }

        return redirect()->route('manager.schedules.index');
    }

    public function createSingleDaySchedules()
    {
        $count = 0;
        foreach ($this->selectedEmployees as $employeeId) {
            // Check if the employee already has a schedule for this date
            $existingSchedule = Schedule::where('user_id', $employeeId)->where('date', $this->date)->first();

            if ($existingSchedule) {
                continue; // Skip this employee
            }

            $schedule = Schedule::create([
                'user_id' => $employeeId,
                'date' => $this->date,
                'start_time' => $this->startTime,
                'end_time' => $this->endTime,
                'shift_type' => $this->shiftType,
                'notes' => $this->notes,
                'created_by' => Auth::id(),
            ]);

            // Create attendance records for this schedule
            $this->createAttendanceRecords($schedule);

            $count++;
        }

        session()->flash('message', "{$count} schedule(s) created successfully!");
    }

    public function createRecurringSchedules()
    {
        $count = 0;
        $startDate = Carbon::parse($this->date);
        $endDate = Carbon::parse($this->repeatUntil);

        // Set up date iterator based on frequency
        $currentDate = $startDate->copy();
        $daysOfWeek = array_map('intval', $this->repeatDays);

        // Loop through dates from start to end
        while ($currentDate->lte($endDate)) {
            // Skip if day of week is not selected for weekly repetition
            if ($this->repeatFrequency === 'weekly' && !in_array($currentDate->dayOfWeek, $daysOfWeek)) {
                $currentDate->addDay();
                continue;
            }

            // For each employee selected
            foreach ($this->selectedEmployees as $employeeId) {
                // Check if the employee already has a schedule for this date
                $existingSchedule = Schedule::where('user_id', $employeeId)->where('date', $currentDate->format('Y-m-d'))->first();

                if ($existingSchedule) {
                    continue; // Skip this date/employee combination
                }

                $schedule = Schedule::create([
                    'user_id' => $employeeId,
                    'date' => $currentDate->format('Y-m-d'),
                    'start_time' => $this->startTime,
                    'end_time' => $this->endTime,
                    'shift_type' => $this->shiftType,
                    'notes' => $this->notes,
                    'created_by' => Auth::id(),
                ]);

                // Create attendance records for this schedule
                $this->createAttendanceRecords($schedule);

                $count++;
            }

            // Move to next day
            $currentDate->addDay();
        }

        session()->flash('message', "{$count} recurring schedule(s) created successfully!");
    }

    /**
     * Create check-in and check-out attendance records for a schedule
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function createAttendanceRecords(Schedule $schedule)
    {
        // Create check-in record
        Attendance::create([
            'schedule_id' => $schedule->id,
            'time' => null, // Will be filled when employee checks in
            'status' => Attendance::STATUS_ABSENT, // Default status
            'is_checked' => false,
            'type' => 'CHECK_IN', // Tambahkan kolom type di model Attendance
            'notes' => 'Auto-generated check-in record',
        ]);
    }

    /**
     * Update attendance records when schedule is updated
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function updateAttendanceRecords(Schedule $schedule)
    {
        // Check if attendance records exist
        $checkInRecord = Attendance::where('schedule_id', $schedule->id)->where('type', 'CHECK_IN')->first();

        // If records don't exist, create them
        if (!$checkInRecord) {
            $this->createAttendanceRecords($schedule);
            return;
        }

        // Update existing records if they haven't been checked yet
        if (!$checkInRecord->is_checked) {
            $checkInRecord->update([
                'notes' => 'Updated check-in record - Schedule changed',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.manager.schedules.schedule-form');
    }
}
