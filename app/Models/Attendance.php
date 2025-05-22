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
    const STATUS_MISSED = "MISSED";
    const STATUS_ABSENT = "ABSENT";

    const TYPE_CHECK_IN = "CHECK_IN";
    const TYPE_CHECK_OUT = "CHECK_OUT";

    protected $fillable = [
        'type',
        'checked_time',
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

    public function getStatusColorAttribute(){
        if($this->status == self::STATUS_PRESENT) {
            return 'bg-green-100 text-green-800';
        } elseif ($this->status == self::STATUS_LATE) {
            return 'bg-yellow-100 text-yellow-800';
        } elseif ($this->status == self::STATUS_ABSENT) {
            return 'bg-red-100 text-red-800';
        }
    }

    // public function check_in_time(){
    //     return $this->where('type', self::TYPE_CHECK_IN)->pluck('time')->first();
    // }

    // public function check_out_time(){
    //     return $this->where('type', self::TYPE_CHECK_OUT)->pluck('time')->first();
    // }
}
