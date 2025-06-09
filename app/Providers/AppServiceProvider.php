<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->resolving(Schedule::class, function ($schedule) {
            $schedule->onEvent(function ($event) {
                Log::info("Scheduled event started: {$event->description}");
            });

            $schedule->onFailure(function ($event, $output) {
                Log::error("Scheduled event failed: {$event->description}", ['output' => $output]);
            });
        });
    }
}