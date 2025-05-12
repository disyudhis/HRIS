<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessTrips extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'destination',
        'purpose',
        'start_date',
        'end_date',
        'estimated_cost_per_day',
        'total_estimated_cost',
        'total_days',
        'notes',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'additional_travelers' => 'array',
        'estimated_cost_per_day' => 'decimal:2',
        'approved_at' => 'datetime',
        'total_estimated_cost' => 'decimal:2',
    ];

    /**
     * Get the user that owns the business trip request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that approved the business trip request.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getStatusColorAttribute(){
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200';
            case self::STATUS_APPROVED:
                return 'bg-green-100 text-green-800 hover:bg-green-200';
            case self::STATUS_REJECTED:
                return 'bg-red-100 text-red-800 hover:bg-red-200';
            case self::STATUS_CANCELLED:
                return 'bg-gray-100 text-gray-800';
            default:
                return '';
        }
    }

    /**
     * Calculate the duration of the business trip in days.
     */
    public function getDurationAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function calculateTotalDays(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Calculate the total estimated cost of the trip.
     */
    public function calculateTotalCost(): float
    {
        return $this->estimated_cost_per_day * $this->calculateTotalDays();
    }

    /**
     * Scope a query to only include pending trips.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved trips.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected trips.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
