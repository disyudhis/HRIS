<?php

namespace App\Livewire\Manager\Schedules;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Schedule;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ScheduleList extends Component
{
    use WithPagination;

    public $selectedDate;
    public $employees = [];
    public $schedules = [];
    public $selectedDepartment = 'all';
    public $departments = [];
    public $selectedView = 'week';
    public $search = '';

    protected $queryString = [
        'selectedDate' => ['except' => ''],
        'selectedDepartment' => ['except' => 'all'],
        'selectedView' => ['except' => 'week'],
        'search' => ['except' => ''],
    ];

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount()
    {
        $this->selectedDate = $this->selectedDate ?? Carbon::now()->format('Y-m-d');
        $this->loadDepartments();
        $this->loadEmployees();
        $this->loadSchedules();
    }

    public function loadDepartments()
    {
        // Get unique departments from the team members
        $this->departments = User::where('manager_id', Auth::id())->whereNotNull('department')->select('department')->distinct()->pluck('department')->toArray();
    }

    public function loadEmployees()
    {
        $query = User::where('manager_id', Auth::id());

        // Apply department filter if not 'all'
        if ($this->selectedDepartment !== 'all') {
            $query->where('department', $this->selectedDepartment);
        }

        // Apply search filter if provided
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'ilike', '%' . $this->search . '%')
                    ->orWhere('position', 'ilike', '%' . $this->search . '%')
                    ->orWhere('employee_id', 'ilike', '%' . $this->search . '%');
            });
        }

        $this->employees = $query->orderBy('name')->get();
    }

    public function loadSchedules()
    {
        $date = Carbon::parse($this->selectedDate);

        if ($this->selectedView === 'week') {
            $startDate = $date->copy()->startOfWeek();
            $endDate = $date->copy()->endOfWeek();
        } else {
            $startDate = $date->copy()->startOfMonth();
            $endDate = $date->copy()->endOfMonth();
        }

        $employeeIds = $this->employees->pluck('id');

        $schedules = Schedule::whereIn('user_id', $employeeIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('user')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        // Kelompokkan jadwal berdasarkan tanggal dalam format string
        $this->schedules = $schedules->groupBy(function ($schedule) {
            return $schedule->date->format('Y-m-d');
        })->all();
    }

    public function updatedSelectedDepartment()
    {
        $this->loadEmployees();
        $this->loadSchedules();
    }

    public function updatedSearch()
    {
        $this->loadEmployees();
        $this->loadSchedules();
    }

    public function changeView($view)
    {
        $this->selectedView = $view;
        $this->loadSchedules();
    }

    public function changeDate($direction)
    {
        $date = Carbon::parse($this->selectedDate);

        if ($this->selectedView === 'week') {
            if ($direction === 'prev') {
                $this->selectedDate = $date->subWeek()->format('Y-m-d');
            } else {
                $this->selectedDate = $date->addWeek()->format('Y-m-d');
            }
        } else {
            if ($direction === 'prev') {
                $this->selectedDate = $date->subMonth()->format('Y-m-d');
            } else {
                $this->selectedDate = $date->addMonth()->format('Y-m-d');
            }
        }

        $this->loadSchedules();
    }

    public function deleteSchedule($scheduleId)
    {
        $schedule = Schedule::findOrFail($scheduleId);
        $schedule->delete();

        $this->dispatchBrowserEvent('notify', [
            'message' => 'Schedule deleted successfully!',
            'type' => 'success',
        ]);

        $this->loadSchedules();
    }

    public function render()
    {
        $date = Carbon::parse($this->selectedDate);

        if ($this->selectedView === 'week') {
            $weekStart = $date->copy()->startOfWeek();
            $weekDates = [];

            for ($i = 0; $i < 7; $i++) {
                $currentDate = $weekStart->copy()->addDays($i);
                $weekDates[] = [
                    'date' => $currentDate->format('Y-m-d'),
                    'day' => $currentDate->format('D'),
                    'day_number' => $currentDate->format('d'),
                    'is_today' => $currentDate->isToday(),
                ];
            }

            return view('livewire.manager.schedules.schedule-list', [
                'weekDates' => $weekDates,
            ]);
        } else {
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            $startOfCalendar = $monthStart->copy()->startOfWeek();
            $endOfCalendar = $monthEnd->copy()->endOfWeek();

            $weeks = [];
            $currentDay = $startOfCalendar->copy();

            while ($currentDay->lte($endOfCalendar)) {
                $week = [];

                for ($i = 0; $i < 7; $i++) {
                    $week[] = [
                        'date' => $currentDay->format('Y-m-d'),
                        'day' => $currentDay->format('d'),
                        'is_today' => $currentDay->isToday(),
                        'is_current_month' => $currentDay->month === $date->month,
                    ];

                    $currentDay->addDay();
                }

                $weeks[] = $week;
            }

            return view('livewire.manager.schedules.schedule-list-month', [
                'weeks' => $weeks,
                'month' => $date->format('F Y'),
            ]);
        }
    }
}