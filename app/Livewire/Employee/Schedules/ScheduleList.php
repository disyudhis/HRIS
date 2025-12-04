<?php

namespace App\Livewire\Employee\Schedules;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class ScheduleList extends Component
{
    public $selectedDate;
    public $schedules = [];
    public $view = 'week'; // week or month
    public $displayLimit = 15;
    public $allDates = [];

    public function mount()
    {
        $this->selectedDate = Carbon::now()->format('Y-m-d');
        $this->loadSchedules();
        $this->prepareAllDates();
    }

    public function loadSchedules()
    {
        $date = Carbon::parse($this->selectedDate);

        if ($this->view === 'week') {
            $startDate = $date->copy()->startOfWeek();
            $endDate = $date->copy()->endOfWeek();
        } else {
            $startDate = $date->copy()->startOfMonth();
            $endDate = $date->copy()->endOfMonth();
        }

        $this->schedules = Schedule::where('user_id', Auth::id())
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['checkIn', 'checkOut']) // Eager load attendance relationships
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(function ($item) {
                return $item->date->format('Y-m-d');
            })
            ->all();

        $this->prepareAllDates();
    }

    public function prepareAllDates()
    {
        $date = Carbon::parse($this->selectedDate);
        $this->allDates = [];

        if ($this->view === 'month') {
            $startDate = $date->copy()->startOfMonth();
            $endDate = $date->copy()->endOfMonth();
            $currentDate = $startDate->copy();

            while ($currentDate <= $endDate) {
                $dateKey = $currentDate->format('Y-m-d');
                $hasSchedule = isset($this->schedules[$dateKey]);

                // Get attendance status for the date
                $attendanceStatus = null;
                $attendanceStatusLabel = null;
                $attendanceStatusBadgeClass = null;

                if ($hasSchedule) {
                    // Get the first schedule for the date to determine overall status
                    $schedule = collect($this->schedules[$dateKey])->first();
                    $attendanceStatus = $schedule->attendance_status;
                    $attendanceStatusLabel = $schedule->attendance_status_label;
                    $attendanceStatusBadgeClass = $schedule->attendance_status_badge_class;
                }

                $this->allDates[] = [
                    'date' => $dateKey,
                    'display_date' => $currentDate->format('F d, Y'),
                    'is_today' => $currentDate->isToday(),
                    'has_schedule' => $hasSchedule,
                    'attendance_status' => $attendanceStatus,
                    'attendance_status_label' => $attendanceStatusLabel,
                    'attendance_status_badge_class' => $attendanceStatusBadgeClass,
                ];
                $currentDate->addDay();
            }
        }
    }

    public function changeView($view)
    {
        $this->view = $view;
        $this->loadSchedules();
    }

    public function changeDate($direction)
    {
        $date = Carbon::parse($this->selectedDate);

        if ($this->view === 'week') {
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
        $this->displayLimit = 15;
    }

    /**
     * Get schedule status for a specific date
     * UPDATED: Priority untuk absent lebih tinggi untuk mengubah status hari menjadi merah
     */
    public function getScheduleStatus($dateKey)
    {
        if (!isset($this->schedules[$dateKey])) {
            return [
                'status' => null,
                'label' => 'No Schedule',
                'badge_class' => 'bg-gray-100 text-gray-600',
            ];
        }

        $schedules = $this->schedules[$dateKey];
        $overallStatus = 'scheduled';
        $hasAbsent = false;
        $hasLate = false;
        $hasEarlyOut = false;
        $hasPresent = false;

        // Check each schedule for the date
        foreach ($schedules as $schedule) {
            $scheduleStatus = $schedule->attendance_status;

            // Track different status types
            if ($scheduleStatus === 'absent') {
                $hasAbsent = true;
            } elseif (in_array($scheduleStatus, ['late', 'late_early_out', 'late_no_checkout'])) {
                $hasLate = true;
            } elseif (in_array($scheduleStatus, ['early_out', 'no_checkout'])) {
                $hasEarlyOut = true;
            } elseif ($scheduleStatus === 'present') {
                $hasPresent = true;
            }
        }

        // Priority determination: absent > late > early_out > present > scheduled
        // Jika ada satu schedule saja yang absent, maka status hari adalah ABSENT (MERAH)
        if ($hasAbsent) {
            $overallStatus = 'absent';
        } elseif ($hasLate) {
            $overallStatus = 'late';
        } elseif ($hasEarlyOut) {
            $overallStatus = 'early_out';
        } elseif ($hasPresent) {
            $overallStatus = 'present';
        }

        $labels = [
            'scheduled' => 'Scheduled',
            'present' => 'Present',
            'absent' => 'Absent',
            'late' => 'Late',
            'early_out' => 'Issues',
            'holiday' => 'Holiday',
        ];

        $badgeClasses = [
            'scheduled' => 'bg-blue-100 text-blue-800',
            'present' => 'bg-green-100 text-green-800',
            'absent' => 'bg-red-100 text-red-800',
            'late' => 'bg-orange-100 text-orange-800',
            'early_out' => 'bg-orange-100 text-orange-800',
            'holiday' => 'bg-gray-100 text-gray-800',
        ];

        return [
            'status' => $overallStatus,
            'label' => $labels[$overallStatus] ?? 'Unknown',
            'badge_class' => $badgeClasses[$overallStatus] ?? 'bg-gray-100 text-gray-600',
        ];
    }

    public function loadMoreDays()
    {
        $this->displayLimit += 15;
    }

    public function render()
    {
        $date = Carbon::parse($this->selectedDate);

        if ($this->view === 'week') {
            $weekStart = $date->copy()->startOfWeek();
            $dates = [];

            for ($i = 0; $i < 7; $i++) {
                $currentDate = $weekStart->copy()->addDays($i);
                $dateKey = $currentDate->format('Y-m-d');
                $scheduleStatus = $this->getScheduleStatus($dateKey);

                $dates[] = [
                    'date' => $dateKey,
                    'day' => $currentDate->format('D'),
                    'day_number' => $currentDate->format('d'),
                    'is_today' => $currentDate->isToday(),
                    'schedule_status' => $scheduleStatus,
                ];
            }

            return view('livewire.employee.schedules.schedule-list-week', [
                'dates' => $dates,
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
                    $dateKey = $currentDay->format('Y-m-d');
                    $scheduleStatus = $this->getScheduleStatus($dateKey);

                    $week[] = [
                        'date' => $dateKey,
                        'day' => $currentDay->format('d'),
                        'is_today' => $currentDay->isToday(),
                        'is_current_month' => $currentDay->month === $date->month,
                        'schedule_status' => $scheduleStatus,
                    ];

                    $currentDay->addDay();
                }

                $weeks[] = $week;
            }

            return view('livewire.employee.schedules.schedule-list-month', [
                'weeks' => $weeks,
                'month' => $date->format('F Y'),
                'displayDates' => array_slice($this->allDates, 0, $this->displayLimit),
            ]);
        }
    }
}
