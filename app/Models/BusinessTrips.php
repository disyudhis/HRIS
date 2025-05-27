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

    protected $fillable = ['user_id', 'destination', 'purpose', 'start_date', 'end_date', 'estimated_cost_per_day', 'total_estimated_cost', 'no_reference', 'total_days', 'notes', 'status', 'approved_by', 'approved_at', 'rejection_reason'];

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

    public function getStatusColorAttribute()
    {
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

    public function getStatusBarClassAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'bg-gradient-to-r from-yellow-400 to-yellow-500';
            case 'approved':
                return 'bg-gradient-to-r from-green-400 to-green-500';
            case 'rejected':
                return 'bg-gradient-to-r from-red-400 to-red-500';
            case 'cancelled':
                return 'bg-gradient-to-r from-gray-400 to-gray-500';
            default:
                return 'bg-gradient-to-r from-blue-400 to-blue-500';
        }
    }

    public function getIconBackgroundClassAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'bg-yellow-100';
            case 'approved':
                return 'bg-green-100';
            case 'rejected':
                return 'bg-red-100';
            case 'cancelled':
                return 'bg-gray-100';
            default:
                return 'bg-blue-100';
        }
    }

    public function getIconColorClassAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'text-yellow-600';
            case 'approved':
                return 'text-green-600';
            case 'rejected':
                return 'text-red-600';
            case 'cancelled':
                return 'text-gray-600';
            default:
                return 'text-blue-600';
        }
    }

    public function getStatusClassAttribute(): string
    {
        switch ($this->status) {
            case 'pending':
                return 'bg-yellow-100 text-yellow-800';
            case 'approved':
                return 'bg-green-100 text-green-800';
            case 'rejected':
                return 'bg-red-100 text-red-800';
            case 'cancelled':
                return 'bg-gray-100 text-gray-800';
            default:
                return 'bg-blue-100 text-blue-800';
        }
    }

    public function getStatusBorderClassAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'border-yellow-200';
            case 'approved':
                return 'border-green-200';
            case 'rejected':
                return 'border-red-200';
            case 'cancelled':
                return 'border-gray-200';
            default:
                return 'border-blue-200';
        }
    }

    public function getStatusIconAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>';
            case 'approved':
                return '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>';
            case 'rejected':
                return '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>';
            case 'cancelled':
                return '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>';
            default:
                return '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>';
        }
    }

    public function formatCurrency($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    public function getTripDuration($trip)
    {
        return $trip->start_date->diffInDays($trip->end_date) + 1;
    }
    public function getFormattedDateRange($trip)
    {
        return $trip->start_date->format('d M Y') . ' - ' . $trip->end_date->format('d M Y');
    }
}
