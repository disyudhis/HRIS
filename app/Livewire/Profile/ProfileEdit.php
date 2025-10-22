<?php

namespace App\Livewire\Profile;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileEdit extends Component
{
    use WithFileUploads;

    // User information
    public $user;
    public $userDetails;
    public $user_type;

    // Personal Information
    public $name;
    public $full_name;
    public $email;
    public $phone;
    public $employee_id;
    public $photo;
    public $current_photo; // Add this property to store current photo URL

    // Additional Information
    public $gender;
    public $birthday;
    public $religion;
    public $birth_place;
    public $marital_status;
    public $wedding_date;
    public $child;
    public $mother_name;
    public $blood_type;

    // Contract Details (mostly readonly)
    public $bidang;
    public $sub_bidang;
    public $office_id;
    public $manager_id;
    public $tarif_sppd;
    public $koefisien_lembur;
    public $is_shifting;
    public $is_magang;

    // Address
    public $address;
    public $provinsi;
    public $kota;
    public $kecamatan;
    public $kelurahan;
    public $rt;
    public $rw;
    public $kode_pos;

    // Account/Finance
    public $ktp;
    public $npwp;
    public $kk;
    public $bank;
    public $nama_rekening;
    public $nomor_rekening;
    public $bpjs;
    public $nominal_bpjs;
    public $bpjs_active_date;
    public $dlpk;
    public $cif;
    public $nominal_dlpk;
    public $dlpk_active_date;

    // Education
    public $pendidikan_terakhir;
    public $jurusan;
    public $gelar;

    // Size
    public $weight;
    public $height;
    public $ukuran_baju;

    protected $rules = [
        // Personal Information
        'name' => 'required|string|max:255',
        'full_name' => 'nullable|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'photo' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif',

        // Additional Information
        'gender' => 'nullable|string|in:laki-laki,perempuan',
        'birthday' => 'nullable|date',
        'religion' => 'nullable|string',
        'birth_place' => 'nullable|string|max:255',
        'marital_status' => 'nullable|string|in:lajang,menikah,janda/duda',
        'wedding_date' => 'nullable|date|required_if:marital_status,menikah',
        'child' => 'nullable|integer|min:0',
        'mother_name' => 'nullable|string|max:255',
        'blood_type' => 'nullable|string|in:A,B,AB,O',

        // Address
        'address' => 'nullable|string|max:500',
        'provinsi' => 'nullable|string|max:100',
        'kota' => 'nullable|string|max:100',
        'kecamatan' => 'nullable|string|max:100',
        'kelurahan' => 'nullable|string|max:100',
        'rt' => 'nullable|string|max:10',
        'rw' => 'nullable|string|max:10',
        'kode_pos' => 'nullable|string|max:10',

        // Account/Finance
        'ktp' => 'nullable|string|max:20',
        'npwp' => 'nullable|string|max:30',
        'kk' => 'nullable|string|max:20',
        'bank' => 'nullable|string|max:100',
        'nama_rekening' => 'nullable|string|max:255',
        'nomor_rekening' => 'nullable|string|max:50',
        'bpjs' => 'nullable|string|max:50',
        'nominal_bpjs' => 'nullable|numeric',
        'bpjs_active_date' => 'nullable|date',
        'dlpk' => 'nullable|string|max:50',
        'cif' => 'nullable|string|max:50',
        'nominal_dlpk' => 'nullable|numeric',
        'dlpk_active_date' => 'nullable|date',

        // Education
        'pendidikan_terakhir' => 'nullable|string|max:100',
        'jurusan' => 'nullable|string|max:100',
        'gelar' => 'nullable|string|max:100',

        // Size
        'weight' => 'nullable|numeric|min:0',
        'height' => 'nullable|numeric|min:0',
        'ukuran_baju' => 'nullable|string|in:XS,S,M,L,XL,XXL,XXXL',
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->userDetails = $this->user->user_details ?? null;
        $this->user_type = $this->user->user_type ?? null;

        // Load user data
        $this->loadUserData();
    }

    protected function loadUserData()
    {
        // Personal Information
        $this->name = $this->user->name;
        $this->full_name = $this->user->full_name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->employee_id = $this->user->employee_id;

        // Additional Information
        if ($this->userDetails) {
            $this->gender = $this->userDetails->gender;
            $this->birthday = $this->userDetails->birthday ? $this->userDetails->birthday->format('Y-m-d') : null;
            $this->religion = $this->userDetails->religion;
            $this->birth_place = $this->userDetails->birth_place;
            $this->marital_status = $this->userDetails->marital_status;
            $this->wedding_date = $this->userDetails->wedding_date ? $this->userDetails->wedding_date->format('Y-m-d') : null;
            $this->child = $this->userDetails->child;
            $this->mother_name = $this->userDetails->mother_name;
            $this->blood_type = $this->userDetails->blood_type;

            // Contract Details (Read Only)
            $this->bidang = $this->userDetails->bidang;
            $this->sub_bidang = $this->userDetails->sub_bidang;
            $this->jabatan = $this->userDetails->jabatan;
            $this->tarif_sppd = $this->userDetails->tarif_sppd;
            $this->koefisien_lembur = $this->userDetails->koefisien_lembur;
            $this->is_shifting = $this->userDetails->is_shifting;
            $this->is_magang = $this->userDetails->is_magang;

            // Address
            $this->address = $this->userDetails->address;
            $this->provinsi = $this->userDetails->provinsi;
            $this->kota = $this->userDetails->kota;
            $this->kecamatan = $this->userDetails->kecamatan;
            $this->kelurahan = $this->userDetails->kelurahan;
            $this->rt = $this->userDetails->rt;
            $this->rw = $this->userDetails->rw;
            $this->kode_pos = $this->userDetails->kode_pos;

            // Account/Finance
            $this->ktp = $this->userDetails->ktp;
            $this->npwp = $this->userDetails->npwp;
            $this->kk = $this->userDetails->kk;
            $this->bank = $this->userDetails->bank;
            $this->nama_rekening = $this->userDetails->nama_rekening;
            $this->nomor_rekening = $this->userDetails->nomor_rekening;
            $this->bpjs = $this->userDetails->bpjs;
            $this->nominal_bpjs = $this->userDetails->nominal_bpjs;
            $this->bpjs_active_date = $this->userDetails->bpjs_active_date ? $this->userDetails->bpjs_active_date->format('Y-m-d') : null;
            $this->dlpk = $this->userDetails->dlpk;
            $this->cif = $this->userDetails->cif;
            $this->nominal_dlpk = $this->userDetails->nominal_dlpk;
            $this->dlpk_active_date = $this->userDetails->dlpk_active_date ? $this->userDetails->dlpk_active_date->format('Y-m-d') : null;

            // Education
            $this->pendidikan_terakhir = $this->userDetails->pendidikan_terakhir;
            $this->jurusan = $this->userDetails->jurusan;
            $this->gelar = $this->userDetails->gelar;

            // Size
            $this->weight = $this->userDetails->weight;
            $this->height = $this->userDetails->height;
            $this->ukuran_baju = $this->userDetails->ukuran_baju;
        }
    }

    // Add method to get photo URL for display
    public function getPhotoUrlProperty()
    {
        // Jika ada foto baru yang diupload (temporary)
        if ($this->photo && is_object($this->photo)) {
            return $this->photo->temporaryUrl();
        }

        // Jika ada foto yang sudah tersimpan
        if ($this->user->profile_photo_path) {
            // Pastikan path tidak mengandung 'public/' di awal
            $path = str_replace('public/', '', $this->user->profile_photo_path);
            return asset('storage/' . $path);
        }

        return null;
    }

    public function updateProfile()
    {
        $this->validate();

        // Ensure email is unique except for the current user
        $this->validate([
            'email' => 'unique:users,email,' . $this->user->id,
        ]);

        // Update User model
        $this->user->name = $this->name;
        $this->user->full_name = $this->full_name;
        $this->user->email = $this->email;
        $this->user->phone = $this->phone;

        // Handle photo upload to local storage
        if ($this->photo && is_object($this->photo)) {
            // Delete old photo if exists
            if ($this->user->profile_photo_path) {
                Storage::disk('cpanel')->delete($this->user->profile_photo_path);
            }

            $photoPath = $this->uploadPhotoToStorage($this->photo);
            if ($photoPath) {
                $this->user->profile_photo_path = $photoPath;
                $this->user->profile_photo_public_id = null;
            }
        }

        $this->user->save();

        // Update or create UserDetails
        $detailsData = [
            // Additional Information
            'gender' => $this->gender,
            'birthday' => $this->birthday,
            'religion' => $this->religion,
            'birth_place' => $this->birth_place,
            'marital_status' => $this->marital_status,
            'wedding_date' => $this->wedding_date,
            'child' => $this->child,
            'mother_name' => $this->mother_name,
            'blood_type' => $this->blood_type,

            // Address
            'address' => $this->address,
            'provinsi' => $this->provinsi,
            'kota' => $this->kota,
            'kecamatan' => $this->kecamatan,
            'kelurahan' => $this->kelurahan,
            'rt' => $this->rt,
            'rw' => $this->rw,
            'kode_pos' => $this->kode_pos,

            // Account/Finance
            'ktp' => $this->ktp,
            'npwp' => $this->npwp,
            'kk' => $this->kk,
            'bank' => $this->bank,
            'nama_rekening' => $this->nama_rekening,
            'nomor_rekening' => $this->nomor_rekening,
            'bpjs' => $this->bpjs,
            'nominal_bpjs' => $this->nominal_bpjs,
            'bpjs_active_date' => $this->bpjs_active_date,
            'dlpk' => $this->dlpk,
            'cif' => $this->cif,
            'nominal_dlpk' => $this->nominal_dlpk,
            'dlpk_active_date' => $this->dlpk_active_date,

            // Education
            'pendidikan_terakhir' => $this->pendidikan_terakhir,
            'jurusan' => $this->jurusan,
            'gelar' => $this->gelar,

            // Size
            'weight' => $this->weight,
            'height' => $this->height,
            'ukuran_baju' => $this->ukuran_baju,
        ];

        if ($this->userDetails) {
            $this->user->user_details()->update($detailsData);
        } else {
            $this->user->user_details()->create($detailsData);
        }

        // Reset photo property after successful save
        $this->photo = null;

        // PENTING: Refresh user instance untuk memastikan data terbaru
        $this->user->refresh();

        session()->flash('message', 'Profile successfully updated.');

        // Redirect ke halaman yang sama
        return redirect()->route('profile.edit');
    }

    protected function uploadPhotoToStorage($photo)
    {
        try {
            // Generate unique filename
            $filename = 'profile_' . $this->user->id . '_' . time() . '.' . $photo->getClientOriginalExtension();

            // Create the directory if it doesn't exist
            $directory = 'profile-photos';
            if (!Storage::disk('cpanel')->exists($directory)) {
                Storage::disk('cpanel')->makeDirectory($directory);
            }

            // Process image with Intervention Image v3
            $manager = new ImageManager(new Driver());
            // Resize and optimize image
            $image = $manager->read($photo)->resize(300, 300);

            // Convert to JPEG and set quality
            $processedImage = $image->toJpeg(80);

            // Store the processed image
            $path = $directory . '/' . $filename;
            Storage::disk('cpanel')->put($path, $processedImage);

            return $path;
        } catch (\Exception $e) {
            \Log::error('Photo upload failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to upload profile photo: ' . $e->getMessage());
            return null;
        }
    }

    public function render()
    {
        return view('livewire.profile.profile-edit');
    }
}
