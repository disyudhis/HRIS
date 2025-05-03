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

    const TYPE_CHECK_IN = "CHECK_IN";
    const TYPE_CHECK_OUT = "CHECK_OUT";

    protected $fillable = [
        'type',
        'time',
        'latitude',
        'longitude',
        'distance',
        'status',
        'notes',
        'schedule_id',
        'is_checked',
    ];

    protected $casts = [
        'time' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
        'distance' => 'float',
    ];

    public function schedule(){
        return $this->belongsTo(Schedule::class);
    }
    //
}