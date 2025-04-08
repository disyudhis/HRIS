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
                $this->allDates[] = [
                    'date' => $currentDate->format('Y-m-d'),
                    'display_date' => $currentDate->format('F d, Y'),
                    'is_today' => $currentDate->isToday(),
                    'has_schedule' => isset($this->schedules[$currentDate->format('Y-m-d')]),
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
                $dates[] = [
                    'date' => $currentDate->format('Y-m-d'),
                    'day' => $currentDate->format('D'),
                    'day_number' => $currentDate->format('d'),
                    'is_today' => $currentDate->isToday(),
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

            return view('livewire.employee.schedules.schedule-list-month', [
                'weeks' => $weeks,
                'month' => $date->format('F Y'),
                'displayDates' => array_slice($this->allDates, 0, $this->displayLimit)
            ]);
        }
    }
}