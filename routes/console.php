<?php

use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})
    ->purpose('Display an inspiring quote')
    ->hourly();

// Timezone setting untuk memastikan konsistensi waktu
Schedule::timezone('Asia/Jakarta');

// 1. Membuat jadwal harian - dijalankan setiap tengah malam
Schedule::command('manager:create-daily-schedule')
    ->dailyAt('00:00')
    ->withoutOverlapping(10) // timeout 10 menit
    ->onOneServer()
    ->runInBackground()
    ->evenInMaintenanceMode()
    ->appendOutputTo(storage_path('logs/scheduler/manager-daily-schedule.log'))
    ->emailOutputOnFailure(['admin@yourdomain.com'])
    ->onSuccess(function () {
        \Log::info('Daily schedule created successfully');
    })
    ->onFailure(function () {
        \Log::error('Failed to create daily schedule');
    });

// 2. Handle absen harian - dijalankan setiap jam 23:00
Schedule::command('manager:handle-absent')
    ->dailyAt('23:00')
    ->withoutOverlapping(15) // timeout 15 menit
    ->onOneServer()
    ->runInBackground()
    ->evenInMaintenanceMode()
    ->appendOutputTo(storage_path('logs/scheduler/manager-absent-daily.log'))
    ->emailOutputOnFailure(['admin@yourdomain.com'])
    ->onSuccess(function () {
        \Log::info('Daily absent handling completed');
    })
    ->onFailure(function () {
        \Log::error('Failed to handle daily absent');
    });

// 3. Handle absen mingguan - dijalankan setiap Senin jam 01:00
Schedule::command('manager:handle-absent', ['--date=' . now()->subDays(3)->format('Y-m-d')])
    ->weeklyOn(1, '01:00') // Senin jam 01:00
    ->withoutOverlapping(20) // timeout 20 menit
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/scheduler/manager-absent-weekly.log'))
    ->emailOutputOnFailure(['admin@yourdomain.com'])
    ->onSuccess(function () {
        \Log::info('Weekly absent handling completed');
    })
    ->onFailure(function () {
        \Log::error('Failed to handle weekly absent');
    });

// 4. Process attendance - dijalankan setiap menit (hati-hati dengan resource)
Schedule::command('app:process-attendance')
    ->everyMinute()
    ->withoutOverlapping(2) // timeout 2 menit
    ->onOneServer()
    ->runInBackground()
    ->evenInMaintenanceMode()
    ->appendOutputTo(storage_path('logs/scheduler/attendance-process.log'))
    // Rotasi log untuk mencegah file terlalu besar
    ->before(function () {
        $logFile = storage_path('logs/scheduler/attendance-process.log');
        if (file_exists($logFile) && filesize($logFile) > 10 * 1024 * 1024) {
            // 10MB
            rename($logFile, storage_path('logs/scheduler/attendance-process-' . date('Y-m-d-H-i-s') . '.log'));
        }
    })
    ->onFailure(function () {
        \Log::error('Failed to process attendance');
    });

// 5. Cleanup logs otomatis - dijalankan setiap hari jam 02:00
Schedule::call(function () {
    $logPath = storage_path('logs/scheduler/');
    $files = glob($logPath . '*.log');

    foreach ($files as $file) {
        if (filemtime($file) < strtotime('-30 days')) {
            unlink($file);
        }
    }
})
    ->dailyAt('02:00')
    ->name('cleanup-scheduler-logs')
    ->withoutOverlapping()
    ->onOneServer();

// 6. Health check scheduler - untuk monitoring
Schedule::call(function () {
    \Log::info('Scheduler is running - ' . now()->toDateTimeString());
})
    ->everyFiveMinutes()
    ->name('scheduler-heartbeat')
    ->withoutOverlapping()
    ->onOneServer();
