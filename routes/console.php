<?php

use App\Console\Commands\AutoCheckOut;
use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Artisan::command('inspire', function () {
//     /** @var ClosureCommand $this */
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');

Schedule::command('app:auto-check-out')->everyFourHours()->evenInMaintenanceMode();
Schedule::command('app:auto-absent')->everyFourHours()->evenInMaintenanceMode();