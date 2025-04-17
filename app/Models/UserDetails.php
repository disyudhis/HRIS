<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDetails extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'address',
        'provinsi',
        'kota',
        'kecamatan',
        'kelurahan',
        'rt',
        'rw',
        'kode_pos',
        'bidang',
        'sub_bidang',
        'tarif_sppd',
        'koefisien_lembur',
        'is_shifting',
        'is_magang',
        'gender',
        'religion',
        'birthday',
        'birth_place',
        'marital_status',
        'wedding_date',
        'child',
        'mother_name',
        'blood_type',
        'weight',
        'height',
        'ukuran_baju',
        'bank',
        'nama_rekening',
        'nomor_rekening',
        'ktp',
        'npwp',
        'kk',
        'bpjs',
        'nominal_bpjs',
        'bpjs_active_date',
        'dlpk',
        'cif',
        'nominal_dlpk',
        'dlpk_active_date',
        'pendidikan_terakhir',
        'jurusan',
        'gelar',
        'status_kontrak',
        'nomor_kontrak',
        'tanggal_penerimaan',
        'tanggal_aktif_bekerja',
        'tanggal_keluar',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_shifting' => 'boolean',
        'is_magang' => 'boolean',
        'birthday' => 'date',
        'wedding_date' => 'date',
        'weight' => 'integer',
        'height' => 'integer',
        'child' => 'integer',
        'bpjs_active_date' => 'date',
        'dlpk_active_date' => 'date',
        'tanggal_penerimaan' => 'date',
        'tanggal_aktif_bekerja' => 'date',
        'tanggal_keluar' => 'date',
    ];

    /**
     * Get the user that owns the user detail.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active employees.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereNull('tanggal_keluar');
    }

    /**
     * Determine if the employee is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return is_null($this->tanggal_keluar);
    }
}