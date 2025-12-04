<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    // Grace period untuk keterlambatan dalam menit
    const LATE_GRACE_PERIOD_MINUTES = 30;
    // Minimum early checkout dalam menit (keluar lebih dari 30 menit sebelum jadwal)
    const EARLY_CHECKOUT_THRESHOLD_MINUTES = 30;

    protected $fillable = ['user_id', 'date', 'start_time', 'end_time', 'shift_type', 'notes', 'is_checked', 'created_by'];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_checked' => 'boolean',
    ];

    /**
     * Get the user that owns the schedule.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that created the schedule.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function checkIn()
    {
        return $this->hasOne(Attendance::class)->where('type', Attendance::TYPE_CHECK_IN);
    }

    public function checkOut()
    {
        return $this->hasOne(Attendance::class)->where('type', Attendance::TYPE_CHECK_OUT);
    }

    /**
     * Check if this is a working day (not holiday)
     */
    public function isWorkingDay()
    {
        return $this->shift_type !== 'holiday';
    }

    /**
     * Check if this is a holiday
     */
    public function isHoliday()
    {
        return $this->shift_type === 'holiday';
    }

    /**
     * Get the shift type label
     */
    public function getShiftTypeLabelAttribute()
    {
        $labels = [
            'morning' => 'Morning Shift',
            'afternoon' => 'Afternoon Shift',
            'night' => 'Night Shift',
            'holiday' => 'Holiday',
        ];

        return $labels[$this->shift_type] ?? 'Unknown';
    }

    /**
     * Get the shift badge CSS classes
     */
    public function getShiftBadgeClassAttribute()
    {
        $classes = [
            'morning' => 'bg-green-100 text-green-800',
            'afternoon' => 'bg-yellow-100 text-yellow-800',
            'night' => 'bg-blue-100 text-blue-800',
            'holiday' => 'bg-gray-100 text-gray-800',
        ];

        return $classes[$this->shift_type] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Calculate the duration of the schedule in hours.
     */
    public function getDurationAttribute()
    {
        if (!$this->start_time || !$this->end_time) {
            return 0;
        }

        return $this->start_time->diffInHours($this->end_time);
    }

    /**
     * Get formatted duration string
     */
    public function getFormattedDurationAttribute()
    {
        $duration = $this->duration;

        if ($duration == 0) {
            return '-';
        }

        return $duration . 'h';
    }

    /**
     * Check if employee is late for check in (with grace period)
     * Grace period: 30 menit - late jika check in lebih dari 30 menit setelah jadwal
     */
    public function isLateCheckIn($checkInTime = null)
    {
        if (!$this->isWorkingDay() || !$this->start_time) {
            return false;
        }

        $checkInTime = $checkInTime ?: now();
        $checkInCarbon = Carbon::parse($checkInTime);
        $scheduleStart = Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->start_time->format('H:i:s'));

        // Hitung selisih dalam menit
        $minutesLate = $scheduleStart->diffInMinutes($checkInCarbon, false);

        // Dianggap late jika terlambat lebih dari grace period (30 menit)
        return $minutesLate > self::LATE_GRACE_PERIOD_MINUTES;
    }

    /**
     * Get minutes late (can be negative if early)
     */
    public function getMinutesLate($checkInTime = null)
    {
        if (!$this->isWorkingDay() || !$this->start_time) {
            return 0;
        }

        $checkInTime = $checkInTime ?: now();
        $checkInCarbon = Carbon::parse($checkInTime);
        $scheduleStart = Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->start_time->format('H:i:s'));

        return $scheduleStart->diffInMinutes($checkInCarbon, false);
    }

    /**
     * Check if employee checked out early
     * Early checkout = keluar lebih dari 30 menit sebelum jadwal berakhir
     */
    public function isEarlyCheckOut($checkOutTime = null)
    {
        if (!$this->isWorkingDay() || !$this->end_time) {
            return false;
        }

        $checkOutTime = $checkOutTime ?: now();
        $checkOutCarbon = Carbon::parse($checkOutTime);
        $scheduleEnd = Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->end_time->format('H:i:s'));

        // Hitung selisih dalam menit (berapa menit sebelum jadwal berakhir)
        $minutesEarly = $checkOutCarbon->diffInMinutes($scheduleEnd, false);

        // Dianggap early checkout jika keluar lebih dari 30 menit sebelum jadwal
        return $minutesEarly > self::EARLY_CHECKOUT_THRESHOLD_MINUTES;
    }

    /**
     * Get minutes early for checkout (positive = early, negative = overtime)
     */
    public function getMinutesEarly($checkOutTime = null)
    {
        if (!$this->isWorkingDay() || !$this->end_time) {
            return 0;
        }

        $checkOutTime = $checkOutTime ?: now();
        $checkOutCarbon = Carbon::parse($checkOutTime);
        $scheduleEnd = Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->end_time->format('H:i:s'));

        return $checkOutCarbon->diffInMinutes($scheduleEnd, false);
    }

    /**
     * Check if schedule is in the future (hasn't started yet)
     */
    public function isFutureSchedule()
    {
        if (!$this->date || !$this->start_time) {
            return false;
        }

        $scheduleDateTime = Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->start_time->format('H:i:s'));
        return $scheduleDateTime->isFuture();
    }

    /**
     * Check if schedule has ended (past end time)
     */
    public function hasEnded()
    {
        if (!$this->date || !$this->end_time) {
            return false;
        }

        $scheduleEndDateTime = Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->end_time->format('H:i:s'));
        return $scheduleEndDateTime->isPast();
    }

    /**
     * Check if schedule is currently active (between start and end time)
     */
    public function isActiveNow()
    {
        if (!$this->date || !$this->start_time || !$this->end_time) {
            return false;
        }

        $now = now();
        $scheduleDate = $this->date->format('Y-m-d');
        $scheduleStartDateTime = Carbon::parse($scheduleDate . ' ' . $this->start_time->format('H:i:s'));
        $scheduleEndDateTime = Carbon::parse($scheduleDate . ' ' . $this->end_time->format('H:i:s'));

        return $now->between($scheduleStartDateTime, $scheduleEndDateTime);
    }

    /**
     * Get attendance status for this schedule
     * Returns: 'scheduled', 'present', 'absent', 'late', 'early_out', 'partial'
     * UPDATED: Dengan grace period 30 menit untuk late check-in dan early checkout
     */
    public function getAttendanceStatusAttribute()
    {
        // Jika ini adalah hari libur, tidak perlu absensi
        if ($this->isHoliday()) {
            return 'holiday';
        }

        // Validasi bahwa schedule memiliki start_time dan end_time
        if (!$this->start_time || !$this->end_time) {
            return 'scheduled';
        }

        $checkIn = $this->attendances()->where('type', Attendance::TYPE_CHECK_IN)->where('is_checked', true)->first();
        $checkOut = $this->attendances()->where('type', Attendance::TYPE_CHECK_OUT)->where('is_checked', true)->first();

        // KASUS 1: Schedule belum mulai (masih di masa depan)
        if ($this->isFutureSchedule()) {
            return 'scheduled';
        }

        // KASUS 2: Schedule masih berjalan (belum melewati end_time)
        if (!$this->hasEnded()) {
            if ($checkIn && $checkOut) {
                // Sudah check-in dan check-out sebelum jadwal berakhir
                $isLate = $this->isLateCheckIn($checkIn->checked_time);
                $isEarlyOut = $this->isEarlyCheckOut($checkOut->checked_time);

                if ($isLate && $isEarlyOut) {
                    return 'late_early_out';
                } elseif ($isLate) {
                    return 'late';
                } elseif ($isEarlyOut) {
                    return 'early_out';
                }
                return 'present';
            } elseif ($checkIn) {
                // Sudah check-in, menunggu check-out
                return $this->isLateCheckIn($checkIn->checked_time) ? 'late' : 'present';
            } else {
                // Belum check-in dan jadwal masih berlangsung
                return 'not_checked_in';
            }
        }

        // KASUS 3: Schedule sudah berakhir (melewati end_time)
        if ($this->hasEnded()) {
            if ($checkIn && $checkOut) {
                // Sudah check-in dan check-out
                $isLate = $this->isLateCheckIn($checkIn->checked_time);
                $isEarlyOut = $this->isEarlyCheckOut($checkOut->checked_time);

                if ($isLate && $isEarlyOut) {
                    return 'late_early_out';
                } elseif ($isLate) {
                    return 'late';
                } elseif ($isEarlyOut) {
                    return 'early_out';
                }
                return 'present';
            } elseif ($checkIn && !$checkOut) {
                // Check-in tapi tidak check-out (masih bekerja atau lupa check-out)
                return $this->isLateCheckIn($checkIn->checked_time) ? 'late_no_checkout' : 'no_checkout';
            } else {
                // Tidak ada check-in sama sekali (ABSENT)
                return 'absent';
            }
        }

        return 'scheduled';
    }

    /**
     * Get formatted attendance status label
     */
    public function getAttendanceStatusLabelAttribute()
    {
        $status = $this->attendance_status;

        $labels = [
            'scheduled' => 'Scheduled',
            'not_checked_in' => 'Not Checked In',
            'present' => 'Present',
            'absent' => 'Absent',
            'late' => 'Late',
            'early_out' => 'Early Out',
            'late_early_out' => 'Late & Early Out',
            'no_checkout' => 'No Check Out',
            'late_no_checkout' => 'Late & No Check Out',
            'holiday' => 'Holiday',
        ];

        return $labels[$status] ?? 'Unknown';
    }

    /**
     * Get attendance status badge CSS classes
     */
    public function getAttendanceStatusBadgeClassAttribute()
    {
        $status = $this->attendance_status;

        $classes = [
            'scheduled' => 'bg-blue-100 text-blue-800',
            'not_checked_in' => 'bg-yellow-100 text-yellow-800',
            'present' => 'bg-green-100 text-green-800',
            'absent' => 'bg-red-100 text-red-800',
            'late' => 'bg-orange-100 text-orange-800',
            'early_out' => 'bg-orange-100 text-orange-800',
            'late_early_out' => 'bg-red-100 text-red-800',
            'no_checkout' => 'bg-purple-100 text-purple-800',
            'late_no_checkout' => 'bg-red-100 text-red-800',
            'holiday' => 'bg-gray-100 text-gray-800',
        ];

        return $classes[$status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get formatted late time (for display purposes)
     */
    public function getFormattedLateTimeAttribute()
    {
        $checkIn = $this->attendances()->where('type', Attendance::TYPE_CHECK_IN)->where('is_checked', true)->first();

        if (!$checkIn || !$this->isLateCheckIn($checkIn->checked_time)) {
            return null;
        }

        $minutesLate = $this->getMinutesLate($checkIn->checked_time);

        if ($minutesLate < 60) {
            return $minutesLate . ' minutes late';
        }

        $hours = floor($minutesLate / 60);
        $minutes = $minutesLate % 60;

        if ($minutes > 0) {
            return $hours . 'h ' . $minutes . 'm late';
        }

        return $hours . 'h late';
    }

    /**
     * Get formatted early checkout time
     */
    public function getFormattedEarlyTimeAttribute()
    {
        $checkOut = $this->attendances()->where('type', Attendance::TYPE_CHECK_OUT)->where('is_checked', true)->first();

        if (!$checkOut || !$this->isEarlyCheckOut($checkOut->checked_time)) {
            return null;
        }

        $minutesEarly = $this->getMinutesEarly($checkOut->checked_time);

        if ($minutesEarly < 60) {
            return $minutesEarly . ' minutes early';
        }

        $hours = floor($minutesEarly / 60);
        $minutes = $minutesEarly % 60;

        if ($minutes > 0) {
            return $hours . 'h ' . $minutes . 'm early';
        }

        return $hours . 'h early';
    }

    /**
     * Get check in time (formatted)
     */
    public function getCheckInTimeAttribute()
    {
        $checkIn = $this->attendances()->where('type', Attendance::TYPE_CHECK_IN)->where('is_checked', true)->first();

        if (!$checkIn) {
            return null;
        }

        return Carbon::parse($checkIn->checked_time)->format('H:i');
    }

    /**
     * Get check out time (formatted)
     */
    public function getCheckOutTimeAttribute()
    {
        $checkOut = $this->attendances()->where('type', Attendance::TYPE_CHECK_OUT)->where('is_checked', true)->first();

        if (!$checkOut) {
            return null;
        }

        return Carbon::parse($checkOut->checked_time)->format('H:i');
    }
}
