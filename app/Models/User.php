<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    const ROLE_ADMIN = 'ADMIN';
    const ROLE_EMPLOYEE = 'PEGAWAI';
    const ROLE_MANAGER = 'MANAGER';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'full_name', 'phone', 'email', 'user_type', 'password', 'employee_id', 'office_id', 'manager_id', 'profile_photo_path', 'profile_photo_public_id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token', 'two_factor_recovery_codes', 'two_factor_secret'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['profile_photo_url'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->user_type === 'ADMIN';
    }

    /**
     * Check if user is manager
     */
    public function isManager()
    {
        return $this->user_type === 'MANAGER';
    }

    /**
     * Check if user is employee
     */
    public function isEmployee()
    {
        return $this->user_type === 'PEGAWAI';
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the employees managed by this user
     */
    public function employees()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    /**
     * Get the office this user belongs to
     */
    public function office()
    {
        return $this->belongsTo(Offices::class, 'office_id');
    }

    public function user_details()
    {
        return $this->hasOne(UserDetails::class, 'user_id');
    }

    public function schedules(){
        return $this->hasMany(Schedule::class, 'user_id');
    }
}
