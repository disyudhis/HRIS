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
    protected $fillable = ['user_id', 'date', 'start_time', 'no_reference', 'end_time', 'reason', 'status', 'approved_by', 'approved_at', 'rejection_reason', 'estimated_cost'];

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

        $totalMinutes = $start->diffInMinutes($end);
        $hours = intval($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        if ($hours > 0) {
            return $minutes > 0 ? "{$hours} jam {$minutes} menit" : "{$hours} jam";
        }

        return "{$minutes} menit";
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

    public function getStatusClassAttribute()
    {
        if ($this->status == self::STATUS_PENDING) {
            return 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200';
        } elseif ($this->status == self::STATUS_APPROVED) {
            return 'bg-green-100 text-green-800 hover:bg-green-200';
        } elseif ($this->status == self::STATUS_REJECTED) {
            return 'bg-red-100 text-red-800 hover:bg-red-200';
        } elseif ($this->status == self::STATUS_CANCELLED) {
            return 'bg-gray-100 text-gray-800';
        }
    }

    /**
     * Get status icon background classes
     */
    public function getStatusIconBgClassAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'bg-amber-50',
            self::STATUS_APPROVED => 'bg-green-50',
            self::STATUS_REJECTED => 'bg-red-50',
            self::STATUS_CANCELLED => 'bg-gray-50',
            default => 'bg-gray-50',
        };
    }

    /**
     * Get status icon SVG
     */
    public function getStatusIconAttribute(): string
    {
        $iconClass = match ($this->status) {
            self::STATUS_PENDING => 'text-amber-600',
            self::STATUS_APPROVED => 'text-green-600',
            self::STATUS_REJECTED => 'text-red-600',
            self::STATUS_CANCELLED => 'text-gray-600',
            default => 'text-gray-600',
        };

        return match ($this->status) {
            self::STATUS_PENDING => '<svg class="w-5 h-5 ' .
                $iconClass .
                '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>',
            self::STATUS_APPROVED => '<svg class="w-5 h-5 ' .
                $iconClass .
                '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>',
            self::STATUS_REJECTED => '<svg class="w-5 h-5 ' .
                $iconClass .
                '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>',
            self::STATUS_CANCELLED => '<svg class="w-5 h-5 ' .
                $iconClass .
                '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
        </svg>',
            default => '<svg class="w-5 h-5 ' .
                $iconClass .
                '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>',
        };
    }

    /**
     * Get status text classes
     */
    public function getStatusTextClassAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'text-amber-600',
            self::STATUS_APPROVED => 'text-green-600',
            self::STATUS_REJECTED => 'text-red-600',
            self::STATUS_CANCELLED => 'text-gray-600',
            default => 'text-gray-600',
        };
    }

    /**
     * Get status description
     */
    public function getStatusDescriptionAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Menunggu persetujuan atasan',
            self::STATUS_APPROVED => 'Disetujui oleh atasan',
            self::STATUS_REJECTED => 'Ditolak oleh atasan',
            self::STATUS_CANCELLED => 'Dibatalkan oleh pegawai',
            default => 'Status tidak diketahui',
        };
    }
}