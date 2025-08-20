<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'shift_type',
        'notes',
        'is_checked',
        'created_by',
    ];

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
}
