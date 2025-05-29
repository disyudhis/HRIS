<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Attendance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateManagerDailySchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manager:create-daily-schedule {--date= : Specific date to create schedule for (YYYY-MM-DD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create daily schedule and attendance records for all managers';


    /**
     * Default manager working hours
     */
    private $defaultWorkingHours = [
        'start' => '08:00:00',
        'end' => '17:00:00'
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::today();

        $this->info("Creating manager schedules for date: " . $targetDate->format('Y-m-d'));

        $managers = User::where('user_type', 'MANAGER')
            ->get();

        if ($managers->isEmpty()) {
            $this->warn('No active managers found.');
            return;
        }

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($managers as $manager) {
            if ($this->createManagerSchedule($manager, $targetDate)) {
                $createdCount++;
                $this->info("✓ Created schedule for manager: {$manager->name}");
            } else {
                $skippedCount++;
                $this->info("- Skipped manager: {$manager->name} (already exists)");
            }
        }

        $this->info("Process completed!");
        $this->info("Created: {$createdCount} schedules");
        $this->info("Skipped: {$skippedCount} schedules");

        Log::info("Manager daily schedules created", [
            'date' => $targetDate->format('Y-m-d'),
            'created' => $createdCount,
            'skipped' => $skippedCount
        ]);
    }

    /**
     * Create schedule for a specific manager
     *
     * @param User $manager
     * @param Carbon $date
     * @return bool
     */
    private function createManagerSchedule($manager, $date)
    {
        // Check if schedule already exists
        $existingSchedule = Schedule::where('user_id', $manager->id)
            ->whereDate('date', $date)
            ->first();

        if ($existingSchedule) {
            return false; // Schedule already exists
        }

        // Skip weekends if needed (optional)
        if ($this->shouldSkipWeekend($date)) {
            $this->info("Skipped weekend for manager: {$manager->name}");
            return false;
        }

        try {
            // Get manager's custom working hours if available
            $workingHours = $this->getManagerWorkingHours($manager);

            // Create schedule
            $schedule = Schedule::create([
                'user_id' => $manager->id,
                'date' => $date,
                'start_time' => $workingHours['start'],
                'end_time' => $workingHours['end'],
                'is_checked' => false,
                'notes' => 'Auto-generated manager schedule',
                'created_by' => 1 // System user or admin ID
            ]);

            // Create check-in attendance record
            Attendance::create([
                'type' => 'CHECK_IN',
                'schedule_id' => $schedule->id,
                'is_checked' => false,
                'status' => 'ABSENT',
                'notes' => 'Auto-generated manager check-in record',
            ]);

            return true;

        } catch (\Exception $e) {
            $this->error("Failed to create schedule for manager {$manager->name}: " . $e->getMessage());
            Log::error("Failed to create manager schedule", [
                'manager_id' => $manager->id,
                'date' => $date->format('Y-m-d'),
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get manager's working hours (can be customized per manager)
     *
     * @param User $manager
     * @return array
     */
    private function getManagerWorkingHours($manager)
    {
        // You can customize this to get working hours from manager profile
        // or office settings. For now, using default hours.

        // Example: if manager has custom working hours in profile
        if (isset($manager->working_hours) && $manager->working_hours) {
            return [
                'start' => $manager->working_hours['start'] ?? $this->defaultWorkingHours['start'],
                'end' => $manager->working_hours['end'] ?? $this->defaultWorkingHours['end']
            ];
        }

        // You could also get office-specific working hours
        if ($manager->office && $manager->office->manager_working_hours) {
            return [
                'start' => $manager->office->manager_working_hours['start'] ?? $this->defaultWorkingHours['start'],
                'end' => $manager->office->manager_working_hours['end'] ?? $this->defaultWorkingHours['end']
            ];
        }

        return $this->defaultWorkingHours;
    }

    /**
     * Check if we should skip weekend
     *
     * @param Carbon $date
     * @return bool
     */
    private function shouldSkipWeekend($date)
    {
        // You can modify this based on your business requirements
        // Return true to skip weekends, false to include them
        return false; // For now, managers work every day including weekends

        // Uncomment below if you want to skip weekends
        // return $date->isWeekend();
    }
}
