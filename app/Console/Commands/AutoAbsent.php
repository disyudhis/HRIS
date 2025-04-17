<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Attendance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoAbsent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-absent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark users as absent who did not check in';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai proses auto-absent...');

        // Ambil tanggal hari ini
        $today = Carbon::today();
        $now = Carbon::now();

        // Ambil semua jadwal untuk hari ini yang belum ditandai checked
        $schedules = Schedule::where('date', $today->format('Y-m-d'))->where('is_checked', false)->get();

        $count = 0;

        foreach ($schedules as $schedule) {
            // Konversi waktu jadwal ke objek Carbon untuk perbandingan
            $scheduleStart = Carbon::parse($schedule->start_time);
            $startDateTime = Carbon::parse($schedule->date . ' ' . $schedule->start_time);

            // Waktu maksimal untuk check-in (30 menit setelah jadwal dimulai)
            $maxCheckInTime = $startDateTime->copy()->addMinutes(30);

            // Jika waktu sekarang sudah melewati batas maksimal check-in
            if ($now->gte($maxCheckInTime)) {
                // Cek apakah user sudah check-in
                $attendance = Attendance::where('user_id', $schedule->user_id)->where('schedule_id', $schedule->id)->first();

                if ($attendance) {
                    // Jika sudah ada record attendance tapi check_in_time masih null
                    if (is_null($attendance->check_in_time)) {
                        $attendance->status = 'alpha';
                        $attendance->notes = $attendance->notes ? $attendance->notes . ' | Automatically marked as alpha at ' . $now->format('H:i') : 'Automatically marked as alpha at ' . $now->format('H:i');
                        $attendance->save();

                        // Tandai jadwal sebagai checked
                        $schedule->is_checked = true;
                        $schedule->save();

                        $userName = User::find($schedule->user_id)->name ?? 'Unknown';
                        Log::info("Auto alpha untuk user {$userName} (ID: {$schedule->user_id}) untuk jadwal pada {$schedule->date} {$schedule->start_time} karena tidak check-in dalam 30 menit");

                        $count++;
                    }
                } else {
                    // Jika belum ada absensi sama sekali, buat baru dengan status alpha
                    $attendance = new Attendance();
                    $attendance->user_id = $schedule->user_id;
                    $attendance->check_in_time = null; // Tetap null karena tidak check-in
                    $attendance->check_out_time = null;
                    $attendance->status = 'alpha';
                    $attendance->notes = 'Automatically marked as alpha for missing check-in at ' . $now->format('H:i');
                    $attendance->schedule_id = $schedule->id;
                    $attendance->save();

                    // Tandai jadwal sebagai checked
                    $schedule->is_checked = true;
                    $schedule->save();

                    $userName = User::find($schedule->user_id)->name ?? 'Unknown';
                    Log::info("Auto alpha untuk user {$userName} (ID: {$schedule->user_id}) untuk jadwal pada {$schedule->date} {$schedule->start_time}");

                    $count++;
                }
            }
        }

        $this->info("Berhasil menandai {$count} user sebagai alpha.");
        return Command::SUCCESS;
    }
}