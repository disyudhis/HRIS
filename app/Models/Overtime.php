<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Overtime extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    const HOURLY_RATE = 25000; // 25k per hour
    const MAX_OVERTIME_COST = 750000; // 750k maximum
    const MAX_PAID_HOURS = 30;
    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'estimated_cost',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'approved_at' => 'datetime',
        'estimated_cost' => 'decimal:2',
    ];

    /**
     * Get the user that owns the overtime request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that approved the overtime request.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Calculate the duration of overtime in hours.
     */
    public function getDurationAttribute()
    {
        $start = $this->start_time;
        $end = $this->end_time;

        return $start->diffInHours($end);
    }

    public function getStatusColorAttribute()
    {
        if ($this->status == self::STATUS_PENDING) {
            return 'bg-yellow-500';
        } elseif ($this->status == self::STATUS_APPROVED) {
            return 'bg-green-500';
        } elseif ($this->status == self::STATUS_REJECTED) {
            return 'bg-red-500';
        } elseif ($this->status == self::STATUS_CANCELLED) {
            return 'bg-gray-500';
        }
    }

    public static function calculateCost(float $hours): int
    {
        // Cap the payable hours at the maximum
        $payableHours = min($hours, self::MAX_PAID_HOURS);

        // Calculate the cost based on the hourly rate
        $cost = $payableHours * self::HOURLY_RATE;

        // Ensure the cost doesn't exceed the maximum
        return min($cost, self::MAX_OVERTIME_COST);
    }

}
