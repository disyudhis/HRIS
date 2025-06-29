<?php

namespace App\Livewire\Manager\Attendance;

use App\Models\Schedule;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class AttendanceList extends Component
{
    use WithPagination;

    public $selectedDate;
    public $selectedUser = '';
    public $selectedShift = '';

    public $shiftTypes = [
        'morning' => 'Morning Shift',
        'afternoon' => 'Afternoon Shift',
        'night' => 'Night Shift',
        'holiday' => 'Holiday',
    ];

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
    }

    public function updatedSelectedDate()
    {
        $this->resetPage();
    }

    public function updatedSelectedUser()
    {
        $this->resetPage();
    }

    public function updatedSelectedShift()
    {
        $this->resetPage();
    }

    public function previousDay()
    {
        $this->selectedDate = Carbon::parse($this->selectedDate)->subDay()->format('Y-m-d');
        $this->resetPage();
    }

    public function nextDay()
    {
        $this->selectedDate = Carbon::parse($this->selectedDate)->addDay()->format('Y-m-d');
        $this->resetPage();
    }

    public function today()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->resetPage();
    }

    public function getUsersWithSchedulesProperty()
    {
        $query = User::query()
            ->where('user_type', User::ROLE_EMPLOYEE)
            ->with([
                'schedules' => function ($query) {
                    $query->where('date', $this->selectedDate)
                          ->with(['attendances']);
                }
            ]);

        // Filter by user
        if ($this->selectedUser) {
            $query->where('id', $this->selectedUser);
        }

        $users = $query->paginate(10);

        // Transform users to include schedule and attendance data
        $users->getCollection()->transform(function ($user) {
            // Get schedule for the selected date
            $schedule = $user->schedules->first();

            if (!$schedule) {
                // Create default schedule (holiday) for display purposes only
                $schedule = new Schedule([
                    'user_id' => $user->id,
                    'date' => $this->selectedDate,
                    'shift_type' => 'holiday',
                    'start_time' => null,
                    'end_time' => null,
                    'notes' => 'No schedule set',
                ]);
            }

            $user->schedule = $schedule;

            // Get attendance data if schedule exists in database
            if ($schedule->exists) {
                $user->checkIn = $schedule->attendances
                    ->where('type', Attendance::TYPE_CHECK_IN)
                    ->first();

                $user->checkOut = $schedule->attendances
                    ->where('type', Attendance::TYPE_CHECK_OUT)
                    ->first();
            } else {
                $user->checkIn = null;
                $user->checkOut = null;
            }

            return $user;
        });

        // Filter by shift type after transformation
        if ($this->selectedShift) {
            $users->setCollection(
                $users->getCollection()->filter(function ($user) {
                    return $user->schedule->shift_type === $this->selectedShift;
                })
            );
        }

        return $users;
    }

    public function getUsersProperty()
    {
        return User::where('user_type', User::ROLE_EMPLOYEE)
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.manager.attendance.attendance-list', [
            'usersWithSchedules' => $this->usersWithSchedules,
            'users' => $this->users,
            'shiftTypes' => $this->shiftTypes,
        ]);
    }
}
