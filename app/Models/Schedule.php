<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

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
     * Check if employee is late for check in
     */
    public function isLateCheckIn($checkInTime = null)
    {
        if (!$this->isWorkingDay() || !$this->start_time) {
            return false;
        }

        $checkInTime = $checkInTime ?: now();
        return Carbon::parse($checkInTime)->gt($this->start_time);
    }

    /**
     * Check if employee checked out early
     */
    public function isEarlyCheckOut($checkOutTime = null)
    {
        if (!$this->isWorkingDay() || !$this->end_time) {
            return false;
        }

        $checkOutTime = $checkOutTime ?: now();

        return Carbon::parse($checkOutTime)->lt($this->end_time);
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
     */
    public function getAttendanceStatusAttribute()
    {
        // If it's a holiday, no attendance expected
        if ($this->isHoliday()) {
            return 'holiday';
        }

        $checkIn = $this->attendances()->where('type', Attendance::TYPE_CHECK_IN)->where('is_checked', true)->first();
        $checkOut = $this->attendances()->where('type', Attendance::TYPE_CHECK_OUT)->where('is_checked', true)->first();

        // Jika schedule sudah lewat dan tidak ada check-in, maka ABSENT
        if ($this->hasEnded() && !$checkIn) {
            return 'absent';
        }

        // Jika schedule masih belum lewat dan tidak ada check-in, maka NOT_CHECKED_IN
        if (!$this->hasEnded() && !$checkIn) {
            return 'not_checked_in';
        }

        // Jika ada check-in tapi schedule belum selesai
        if ($checkIn && !$this->hasEnded()) {
            if ($this->isLateCheckIn($checkIn->checked_time)) {
                return 'late';
            }
            return 'present';
        }

        // Jika ada check-in dan check-out
        if ($checkIn && $checkOut) {
            $status = 'present';

            if ($this->isLateCheckIn($checkIn->checked_time)) {
                $status = 'late';
            }

            if ($this->isEarlyCheckOut($checkOut->checked_time)) {
                $status = $status === 'late' ? 'late_early_out' : 'early_out';
            }

            return $status;
        }

        // Jika ada check-in tapi tidak ada check-out dan schedule sudah selesai
        if ($checkIn && !$checkOut && $this->hasEnded()) {
            return $this->isLateCheckIn($checkIn->checked_time) ? 'late_no_checkout' : 'no_checkout';
        }

        // Default: scheduled
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
            'not_checked_in' => 'bg-red-100 text-red-800',
            'present' => 'bg-green-100 text-green-800',
            'absent' => 'bg-red-100 text-red-800',
            'late' => 'bg-yellow-100 text-yellow-800',
            'early_out' => 'bg-orange-100 text-orange-800',
            'late_early_out' => 'bg-red-100 text-red-800',
            'no_checkout' => 'bg-purple-100 text-purple-800',
            'late_no_checkout' => 'bg-red-100 text-red-800',
            'holiday' => 'bg-gray-100 text-gray-800',
        ];

        return $classes[$status] ?? 'bg-gray-100 text-gray-800';
    }
}
