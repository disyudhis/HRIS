<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\Schedule;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Null_;

class ProcessAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process incomplete attendace record';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $unprocessedSchedules = $this->getUnprocessedSchedules();
            $count = $unprocessedSchedules->count();
            if ($unprocessedSchedules->isEmpty()) {
                $this->displayNoSchedulesMessage();
                return Command::SUCCESS;
            }else {
                $this->info("Jadwal yang belum terproses {$count}");
            }

            $this->processSchedules($unprocessedSchedules);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->handleError($e);
            return Command::FAILURE;
        }
    }

    /**
     * Get schedules that haven't been processed yet
     */
    private function getUnprocessedSchedules()
    {
        return Schedule::where('end_time', '<=', Carbon::now())
            ->whereNull('is_checked')
            ->with('attendances') // Eager load to prevent N+1 query
            ->get();
    }

    /**
     * Alternative: Get grace end time based on specific schedule's end time
     * Use this if you need to add 2 hours to each individual schedule's end time
     */
    private function getScheduleWithGracePeriod(Schedule $schedule): Carbon
    {
        return Carbon::parse($schedule->end_time)->addHours(2);
    }

    /**
     * Check if schedule has passed its grace period
     */
    private function hasPassedGracePeriod(Schedule $schedule): bool
    {
        $scheduleEndWithGrace = $this->getScheduleWithGracePeriod($schedule);
        return Carbon::now()->isAfter($scheduleEndWithGrace);
    }

    /**
     * Process each schedule and determine attendance status
     */
    private function processSchedules($schedules): void
    {
        foreach ($schedules as $schedule) {
            // Only process if grace period has passed
            if ($this->hasPassedGracePeriod($schedule)) {
                $this->processIndividualSchedule($schedule);
            }else {
                $this->info("Schedule ID {$schedule->id} is still within grace period.");
            }
        }
    }

    /**
     * Process individual schedule attendance
     */
    private function processIndividualSchedule(Schedule $schedule): void
    {
        $checkInAttendance = $this->getCheckInAttendance($schedule);
        $checkOutAttendance = $this->getCheckOutAttendance($schedule);

        if ($this->isAbsent($checkInAttendance)) {
            $this->markScheduleAsAbsent($schedule);
        } else {
            if($this->isAbsent($checkOutAttendance)) {
                $this->markCheckOutAttendance($checkOutAttendance);
            }
            $this->markScheduleAsPresent($schedule);
        }
    }

    private function markCheckOutAttendance(Attendance $attendance): void
    {
        $attendance->update([
            'is_checked' => true,
            'status' => Attendance::STATUS_PRESENT,
        ]);
    }

    private function getCheckOutAttendance(Schedule $schedule): ?Attendance
    {
        return $schedule->attendances->where('type', Attendance::TYPE_CHECK_OUT)->first();
    }

    /**
     * Get check-in attendance for the schedule
     */
    private function getCheckInAttendance(Schedule $schedule): ?Attendance
    {
        return $schedule->attendances->where('type', Attendance::TYPE_CHECK_IN)->first();
    }

    /**
     * Determine if employee is absent
     */
    private function isAbsent(?Attendance $checkInAttendance): bool
    {
        return is_null($checkInAttendance) || !$checkInAttendance->is_checked;
    }

    /**
     * Mark schedule as absent
     */
    private function markScheduleAsAbsent(Schedule $schedule): void
    {
        $schedule->update([
            'is_checked' => false,
            'notes' => 'ABSENT',
        ]);

        $this->logScheduleUpdate($schedule, 'ABSENT');
    }

    /**
     * Mark schedule as present (or completed)
     */
    private function markScheduleAsPresent(Schedule $schedule): void
    {
        $schedule->update([
            'is_checked' => true,
            'notes' => 'PRESENT',
        ]);

        $this->logScheduleUpdate($schedule, 'PRESENT');
    }

    /**
     * Log schedule update for debugging
     */
    private function logScheduleUpdate(Schedule $schedule, string $status): void
    {
        $this->info("Schedule ID {$schedule->id} marked as {$status}");
    }

    /**
     * Display message when no schedules need processing
     */
    private function displayNoSchedulesMessage(): void
    {
        $this->info('✅ Tidak ada jadwal yang perlu diproses');
    }

    /**
     * Display success message
     */
    private function displaySuccessMessage(int $processedCount): void
    {
        $this->info("✅ Berhasil memproses {$processedCount} jadwal");
    }

    /**
     * Handle and log errors
     */
    private function handleError(\Exception $e): void
    {
        $errorMessage = "Terjadi kesalahan: {$e->getMessage()}";

        $this->error($errorMessage);
        Log::error('Attendance processing failed', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
    }

    private function checkEndTime($schedule): bool
    {
        $endTime = Carbon::parse($schedule->end_time);
        $currentTime = Carbon::now();

        return $currentTime->greaterThanOrEqualTo($endTime);
    }
}
