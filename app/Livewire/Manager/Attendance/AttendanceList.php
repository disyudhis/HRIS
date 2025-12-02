<?php

namespace App\Livewire\Manager\Attendance;

use App\Models\Schedule;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class AttendanceList extends Component
{
    use WithPagination;

    public $selectedDate;
    public $selectedUser = '';
    public $selectedShift = '';
    public $selectedCondition = '';

    public $shiftTypes = [
        'morning' => 'Morning Shift',
        'afternoon' => 'Afternoon Shift',
        'night' => 'Night Shift',
        'holiday' => 'Holiday',
    ];

    public $conditionTypes = [
        'checked_in' => 'Checked In',
        'not_checked_in' => 'Not Checked In',
        'not_checked_out' => 'Not Checked Out',
        'absent' => 'Absent',
        'late' => 'Late',
        'complete' => 'Complete (Both Check In & Out)',
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

    public function updatedSelectedCondition()
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
     * FIXED: Filter condition BEFORE pagination to get correct results
     */
    public function getUsersWithSchedulesProperty()
    {
        try {
            $currentManager = $this->getCurrentManager();

            // Step 1: Get base query with schedules
            $query = User::query()
                ->where('user_type', User::ROLE_EMPLOYEE)
                ->where('manager_id', $currentManager->id)
                ->with([
                    'schedules' => function ($query) {
                        $query->where('date', $this->selectedDate)->with([
                            'attendances' => function ($q) {
                                $q->where('is_checked', true);
                            },
                        ]);
                    },
                ]);

            // Filter by specific user if selected
            if ($this->selectedUser) {
                $query->where('id', $this->selectedUser);
            }

            // Filter by shift type
            if ($this->selectedShift) {
                $query->whereHas('schedules', function ($q) {
                    $q->where('date', $this->selectedDate)->where('shift_type', $this->selectedShift);
                });
            }

            // Step 2: Get all users first (without pagination) for condition filtering
            $allUsers = $query->orderBy('name')->get();

            // Step 3: Transform and add schedule/attendance data
            $transformedUsers = $allUsers->map(function ($user) {
                $schedule = $user->schedules->first();

                if (!$schedule) {
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

                if ($schedule->exists) {
                    $user->checkIn = $schedule->attendances->where('type', Attendance::TYPE_CHECK_IN)->where('is_checked', true)->first();

                    $user->checkOut = $schedule->attendances->where('type', Attendance::TYPE_CHECK_OUT)->where('is_checked', true)->first();
                } else {
                    $user->checkIn = null;
                    $user->checkOut = null;
                }

                return $user;
            });

            // Step 4: Filter by attendance condition BEFORE pagination
            if ($this->selectedCondition) {
                $transformedUsers = $transformedUsers->filter(function ($user) {
                    $status = $user->schedule->attendance_status;

                    switch ($this->selectedCondition) {
                        case 'checked_in':
                            return $user->checkIn !== null;

                        case 'not_checked_in':
                            return $status === 'not_checked_in';

                        case 'absent':
                            return $status === 'absent';

                        case 'late':
                            return in_array($status, ['late', 'late_no_checkout', 'late_early_out']);

                        case 'not_checked_out':
                            return $user->checkIn !== null && $user->checkOut === null;

                        case 'complete':
                            return $user->checkIn !== null && $user->checkOut !== null;

                        default:
                            return true;
                    }
                });
            }

            // Step 5: Manual pagination after filtering
            $perPage = 10;
            $currentPage = $this->getPage();
            $total = $transformedUsers->count();

            // Slice collection for current page
            $paginatedUsers = $transformedUsers->slice(($currentPage - 1) * $perPage, $perPage)->values();

            // Create paginator
            $paginator = new \Illuminate\Pagination\LengthAwarePaginator($paginatedUsers, $total, $perPage, $currentPage, ['path' => request()->url(), 'query' => request()->query()]);

            return $paginator;
        } catch (\Exception $e) {
            \Log::error('Error in getUsersWithSchedulesProperty: ' . $e->getMessage());
            return new \Illuminate\Pagination\LengthAwarePaginator(collect([]), 0, 10, 1);
        }
    }

    /**
     * Get list of employees under current manager for dropdown filter
     */
    public function getUsersProperty()
    {
        try {
            $currentManager = $this->getCurrentManager();

            return User::where('user_type', User::ROLE_EMPLOYEE)->where('manager_id', $currentManager->id)->orderBy('name')->get();
        } catch (\Exception $e) {
            \Log::error('Error in getUsersProperty: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Get count of employees under current manager
     */
    public function getTotalEmployeesProperty()
    {
        try {
            $currentManager = $this->getCurrentManager();

            return User::where('user_type', User::ROLE_EMPLOYEE)->where('manager_id', $currentManager->id)->count();
        } catch (\Exception $e) {
            \Log::error('Error in getTotalEmployeesProperty: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Check if current manager has any employees
     */
    public function getHasEmployeesProperty()
    {
        return $this->totalEmployees > 0;
    }

    public function render()
    {
        // Jika manager tidak memiliki bawahan, tampilkan pesan khusus
        if (!$this->hasEmployees) {
            return view('livewire.manager.attendance.attendance-list', [
                'usersWithSchedules' => new \Illuminate\Pagination\LengthAwarePaginator(collect([]), 0, 10, 1),
                'users' => collect([]),
                'message' => 'Anda belum memiliki pegawai yang ditugaskan sebagai bawahan.',
                'shiftTypes' => $this->shiftTypes,
                'conditionTypes' => $this->conditionTypes,
                'totalEmployees' => 0,
            ]);
        }

        return view('livewire.manager.attendance.attendance-list', [
            'usersWithSchedules' => $this->usersWithSchedules,
            'users' => $this->users,
            'shiftTypes' => $this->shiftTypes,
            'conditionTypes' => $this->conditionTypes,
            'totalEmployees' => $this->totalEmployees,
        ]);
    }
}
