<?php

namespace App\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    const STATUS_PRESENT = "PRESENT";
    const STATUS_LATE = "LATE";
    const STATUS_ABSENT = "ABSENT";
    const STATUS_NULL = "NULL";

    const TYPE_CHECK_IN = "CHECK_IN";
    const TYPE_CHECK_OUT = "CHECK_OUT";

    protected $fillable = [
        'type',
        'checked_time',
        'latitude',
        'longitude',
        'distance',
        'status',
        'notes',
        'schedule_id',
        'is_checked',
    ];

    protected $casts = [
        'checked_time' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
        'distance' => 'float',
        'is_checked' => 'boolean',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function getStatusColorAttribute()
    {
        if ($this->status == self::STATUS_PRESENT) {
            return 'bg-green-100 text-green-800';
        } elseif ($this->status == self::STATUS_LATE) {
            return 'bg-yellow-100 text-yellow-800';
        } elseif ($this->status == self::STATUS_ABSENT) {
            return 'bg-red-100 text-red-800';
        }
        return 'bg-gray-100 text-gray-800';
    }

    /**
     * Get formatted check in/out time (HH:mm format)
     */
    public function getFormattedTimeAttribute()
    {
        if (!$this->checked_time) {
            return '-';
        }
        return Carbon::parse($this->checked_time)->format('H:i');
    }

    /**
     * Get formatted check in/out time with date
     */
    public function getFormattedDateTimeAttribute()
    {
        if (!$this->checked_time) {
            return '-';
        }
        return Carbon::parse($this->checked_time)->format('d M Y H:i');
    }

    /**
     * Check if this is a check in record
     */
    public function isCheckIn()
    {
        return $this->type === self::TYPE_CHECK_IN;
    }

    /**
     * Check if this is a check out record
     */
    public function isCheckOut()
    {
        return $this->type === self::TYPE_CHECK_OUT;
    }
}
