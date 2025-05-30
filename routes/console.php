<?php

use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     /** @var ClosureCommand $this */
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');
Schedule::command('manager:create-daily-schedule')->dailyAt('02:35')->withoutOverlapping()->onOneServer()->evenInMaintenanceMode()->appendOutputTo(storage_path('logs/manager-scheduler.log'));
Schedule::command('manager:handle-absent')->dailyAt('23:00')->withoutOverlapping()->onOneServer()->evenInMaintenanceMode()->appendOutputTo(storage_path('logs/manager-absent.log'));
Schedule::command('manager:handle-absent --date=' . now()->subDays(3)->format('Y-m-d'))
    ->weeklyOn(1, '01:00')
    ->withoutOverlapping()
    ->onOneServer()
    ->appendOutputTo(storage_path('logs/manager-absent-weekly.log'));
Schedule::command('app:process-attendance')->everyMinute()->evenInMaintenanceMode()->appendOutputTo(storage_path('logs/attendance-scheduler.log'));
