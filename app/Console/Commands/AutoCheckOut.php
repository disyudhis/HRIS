<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Attendance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoCheckOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-check-out';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process automatic checkout for uses who did not check out';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pendingAttendaces = Attendance::whereNotNull('check_in_time')->whereNull('check_out_time')->with('user', 'schedule')->get();

        $now = Carbon::now();
        $count = 0;

        foreach ($pendingAttendaces as $attendace) {
            $scheduleEnd = Carbon::parse($attendace->schedule->end_time);

            if ($scheduleEnd->format('H:i') < Carbon::parse($attendace->schedule->start_time)->format('H:i')) {
                $scheduleEnd->addDay();
            }

            $autoCheckoutTime = $scheduleEnd->copy()->addHours(2);

            if ($now->gte($autoCheckoutTime)) {
                $attendance->check_out_time = $autoCheckoutTime;
                $attendance->save();

                \Log::info("Auto checkout untuk user {$attendace->user->name} (ID: {$attendance->user_id}) pada {$autoCheckoutTime}");

                $count++;
            }
        }

        $this->info("Berhasil melakukan auto-checkout untuk {$count} user.");
    }
}