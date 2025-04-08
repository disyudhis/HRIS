<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessTrips extends Model
{
    use HasFactory;

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

    /**
     * Calculate the duration of the business trip in days.
     */
    public function getDurationAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}