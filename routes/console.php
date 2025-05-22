<?php

use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     /** @var ClosureCommand $this */
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');
Schedule::command('app:process-attendance')->everyMinute()->evenInMaintenanceMode()->appendOutputTo(storage_path('logs/attendance-scheduler.log'));
