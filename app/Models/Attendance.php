<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    const STATUS_PRESENT = "PRESENT";
    const STATUS_LATE = "LATE";
    const STATUS_ABSENT = "ABSENT";

    protected $fillable = [
        'user_id',
        'check_in_time',
        'check_out_time',
        'latitude',
        'longitude',
        'distance',
        'status',
        'notes',
        'schedule_id'
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
        'distance' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule(){
        return $this->belongsTo(Schedule::class);
    }
    //
}