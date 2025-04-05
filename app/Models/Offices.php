<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offices extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'check_in_radius',
        'description',
        'is_active',
    ];

    protected $casts = [
        'latitude'        => 'float',
        'longitude'       => 'float',
        'check_in_radius' => 'float',
        'is_active'       => 'boolean',
    ];

      /**
     * Get the manager of this office.
     */
    public function manager()
    {
        return $this->hasOne(User::class)->where('role', 'manager');
    }

      /**
     * Get all employees in this office.
     */
    public function employees()
    {
        return $this->hasMany(User::class);
    }

      /**
     * Get the full address as a string.
     */
    public function getFullAddressAttribute()
    {
        $parts = [$this->address, $this->city];

        if ($this->state) {
            $parts[] = $this->state;
        }

        if ($this->postal_code) {
            $parts[] = $this->postal_code;
        }

        $parts[] = $this->country;

        return implode(', ', $parts);
    }

      /**
     * Get the formatted work hours.
     */
    public function getWorkHoursAttribute()
    {
        return $this->work_start_time->format('H:i') . ' - ' . $this->work_end_time->format('H:i');
    }
}