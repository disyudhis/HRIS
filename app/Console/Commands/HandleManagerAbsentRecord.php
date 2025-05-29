<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Attendance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class HandleManagerAbsentRecord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manager:handle-absent {--date= : Specific date to process (YYYY-MM-DD)} {--hours= : Hours after end time to mark as absent (default: 2)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark managers as absent if they haven\'t checked in/out within specified time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::yesterday();
        $hoursAfterEnd = (int) ($this->option('hours') ?? 2);

        $this->info('Processing manager absent records for date: ' . $targetDate->format('Y-m-d'));
        $this->info("Grace period: {$hoursAfterEnd} hours after end time");

        // Get all managers
        $managers = User::where('user_type', 'manager')
        ->get();

        if ($managers->isEmpty()) {
            $this->warn('No active managers found.');
            return;
        }

        $processedCount = 0;
        $absentCount = 0;
        $alreadyProcessedCount = 0;

        foreach ($managers as $manager) {
            $result = $this->processManagerAttendance($manager, $targetDate, $hoursAfterEnd);

            switch ($result) {
                case 'absent':
                    $absentCount++;
                    $this->warn("✗ Marked as ABSENT: {$manager->name}");
                    break;
                case 'processed':
                    $processedCount++;
                    $this->info("✓ Processed: {$manager->name}");
                    break;
                case 'already_processed':
                    $alreadyProcessedCount++;
                    $this->info("- Already processed: {$manager->name}");
                    break;
            }
        }

        $this->info("\nProcess completed!");
        $this->info("Processed: {$processedCount}");
        $this->info("Marked as absent: {$absentCount}");
        $this->info("Already processed: {$alreadyProcessedCount}");

        Log::info('Manager absent records processed', [
            'date' => $targetDate->format('Y-m-d'),
            'processed' => $processedCount,
            'absent' => $absentCount,
            'already_processed' => $alreadyProcessedCount,
        ]);
    }

    /**
     * Process attendance for a specific manager
     *
     * @param User $manager
     * @param Carbon $date
     * @param int $hoursAfterEnd
     * @return string
     */
    private function processManagerAttendance($manager, $date, $hoursAfterEnd)
    {
        // Get manager's schedule for the date
        $schedule = Schedule::where('user_id', $manager->id)->whereDate('date', $date)->first();

        if (!$schedule) {
            $this->info("No schedule found for manager: {$manager->name} on {$date->format('Y-m-d')}");
            return 'no_schedule';
        }

        // If schedule is already marked as checked, skip
        if ($schedule->is_checked) {
            return 'already_processed';
        }

        // Get check-in and check-out records
        $checkInRecord = Attendance::where('schedule_id', $schedule->id)->where('type', 'CHECK_IN')->first();

        $checkOutRecord = Attendance::where('schedule_id', $schedule->id)->where('type', 'CHECK_OUT')->first();

        // Calculate cutoff time (end time + grace period)
        $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->end_time);
        $cutoffTime = $endTime->copy()->addHours($hoursAfterEnd);
        $now = Carbon::now();

        // Only process if current time is past the cutoff time
        if ($now->lt($cutoffTime)) {
            $this->info("Still within grace period for manager: {$manager->name}");
            return 'within_grace_period';
        }

        try {
            // Check if manager didn't check in at all
            if (!$checkInRecord || !$checkInRecord->is_checked) {
                $this->markManagerAsAbsent($schedule, $checkInRecord, $checkOutRecord, 'NO_CHECK_IN');
                return 'absent';
            }

            // Check if manager checked in but didn't check out
            if ($checkInRecord->is_checked && (!$checkOutRecord || !$checkOutRecord->is_checked)) {
                $this->markManagerAsIncomplete($schedule, $checkOutRecord, 'NO_CHECK_OUT');
                return 'processed';
            }

            // If both check-in and check-out are done, mark schedule as complete
            if ($checkInRecord->is_checked && $checkOutRecord && $checkOutRecord->is_checked) {
                $schedule->is_checked = true;
                $schedule->notes = 'PRESENT';
                $schedule->save();
                return 'processed';
            }
        } catch (\Exception $e) {
            $this->error("Error processing manager {$manager->name}: " . $e->getMessage());
            Log::error('Error processing manager attendance', [
                'manager_id' => $manager->id,
                'date' => $date->format('Y-m-d'),
                'error' => $e->getMessage(),
            ]);
            return 'error';
        }

        return 'processed';
    }

    /**
     * Mark manager as absent
     *
     * @param Schedule $schedule
     * @param Attendance|null $checkInRecord
     * @param Attendance|null $checkOutRecord
     * @param string $reason
     */
    private function markManagerAsAbsent($schedule, $checkInRecord, $checkOutRecord, $reason)
    {
        // Update or create check-in record
        if (!$checkInRecord) {
            Attendance::create([
                'type' => 'CHECK_IN',
                'schedule_id' => $schedule->id,
                'is_checked' => false,
                'status' => 'ABSENT',
                'notes' => "Auto-marked absent: {$reason}",
            ]);
        } else {
            $checkInRecord->status = 'ABSENT';
            $checkInRecord->notes = "Auto-marked absent: {$reason}";
            $checkInRecord->save();
        }

        // Update or create check-out record
        if (!$checkOutRecord) {
            Attendance::create([
                'type' => 'CHECK_OUT',
                'schedule_id' => $schedule->id,
                'is_checked' => false,
                'status' => 'ABSENT',
                'notes' => "Auto-marked absent: {$reason}",
            ]);
        } else {
            $checkOutRecord->status = 'ABSENT';
            $checkOutRecord->notes = "Auto-marked absent: {$reason}";
            $checkOutRecord->save();
        }

        // Mark schedule as processed but absent
        $schedule->is_checked = true;
        $schedule->notes = 'ABSENT';
        $schedule->save();
    }

    /**
     * Mark manager attendance as incomplete (checked in but not out)
     *
     * @param Schedule $schedule
     * @param Attendance|null $checkOutRecord
     * @param string $reason
     */
    private function markManagerAsIncomplete($schedule, $checkOutRecord, $reason)
    {
        // Update or create check-out record as not checked
        if (!$checkOutRecord) {
            Attendance::create([
                'type' => 'CHECK_OUT',
                'schedule_id' => $schedule->id,
                'is_checked' => false,
                'status' => 'ABSENT',
                'notes' => "Auto-marked: {$reason}",
            ]);
        } else {
            $checkOutRecord->status = 'ABSENT';
            $checkOutRecord->notes = "Auto-marked: {$reason}";
            $checkOutRecord->save();
        }

        // Mark schedule as processed but incomplete
        $schedule->is_checked = true;
        $schedule->notes = 'INCOMPLETE';
        $schedule->save();
    }
}