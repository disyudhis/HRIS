<?php

namespace App\Livewire\Manager\Attendance;

use App\Models\Schedule;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Foundation\Console\LangPublishCommand;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Get current manager
     */
    private function getCurrentManager()
    {
        $user = Auth::user();

        if (!$user || !$user->isManager()) {
            throw new \Exception('Unauthorized access. Only managers can view this page.');
        }

        return $user;
    }

    /**
     * Get employees under current manager with their schedules
     */
    public function getUsersWithSchedulesProperty()
    {
        $currentManager = $this->getCurrentManager();

        $query = User::query()
            ->where('user_type', User::ROLE_EMPLOYEE)
            ->where('manager_id', $currentManager->id) // Filter hanya bawahan manager yang login
            ->with([
                'schedules' => function ($query) {
                    $query->where('date', $this->selectedDate)
                          ->with(['attendances']);
                }
            ]);

        // Filter by specific user if selected
        if ($this->selectedUser) {
            $query->where('id', $this->selectedUser);
        }

        $users = $query->orderBy('name')->paginate(10);

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
            $filteredCollection = $users->getCollection()->filter(function ($user) {
                return $user->schedule->shift_type === $this->selectedShift;
            });

            $users->setCollection($filteredCollection);
        }

        return $users;
    }

    /**
     * Get list of employees under current manager for dropdown filter
     */
    public function getUsersProperty()
    {
        $currentManager = $this->getCurrentManager();

        return User::where('user_type', User::ROLE_EMPLOYEE)
            ->where('manager_id', $currentManager->id) // Filter hanya bawahan manager yang login
            ->orderBy('name')
            ->get();
    }

    /**
     * Get count of employees under current manager
     */
    public function getTotalEmployeesProperty()
    {
        $currentManager = $this->getCurrentManager();

        return User::where('user_type', User::ROLE_EMPLOYEE)
            ->where('manager_id', $currentManager->id)
            ->count();
    }

    /**
     * Check if current manager has any employees
     */
    public function getHasEmployeesProperty()
    {
        return $this->totalEmployees > 0;
    }

    /**
     * Authorization check
     */

    public function render()
    {
        // Jika manager tidak memiliki bawahan, tampilkan pesan khusus
        if (!$this->hasEmployees) {
            return view('livewire.manager.attendance.attendance-list', [
                'message' => 'Anda belum memiliki pegawai yang ditugaskan sebagai bawahan.',
                'shiftTypes' => $this->shiftTypes,
            ]);
        }

        return view('livewire.manager.attendance.attendance-list', [
            'usersWithSchedules' => $this->usersWithSchedules,
            'users' => $this->users,
            'shiftTypes' => $this->shiftTypes,
            'totalEmployees' => $this->totalEmployees,
        ]);
    }
}
