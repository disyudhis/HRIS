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
        'estimated_cost',
        'notes',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'approved_at' => 'datetime',
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
}