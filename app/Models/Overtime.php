<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Overtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'reason',
        'tasks',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'approved_at' => 'datetime',
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
}