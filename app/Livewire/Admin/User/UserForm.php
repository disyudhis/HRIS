<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use App\Models\Offices;
use App\Models\UserDetails;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserForm extends Component
{
    public $user;
    public $userId;
    public $userDetail;

    // Basic user information
    public $name;
    public $full_name;
    public $email;
    public $password;
    public $phone;

    public $password_confirmation;
    public $user_type = 'PEGAWAI';
    public $manager_id;
    public $employee_id;
    public $office_id;

    // Personal information
    public $gender;
    public $birthday;
    public $birth_place;
    public $religion;
    public $blood_type;
    public $weight;
    public $height;
    public $ukuran_baju;
    public $marital_status;
    public $wedding_date;
    public $child;
    public $mother_name;

    // Address information
    public $address;
    public $provinsi;
    public $kota;
    public $kecamatan;
    public $kelurahan;
    public $rt;
    public $rw;
    public $kode_pos;

    // Professional information
    public $bidang;
    public $sub_bidang;
    public $jabatan;
    public $tarif_sppd = "150000";
    public $koefisien_lembur = "50000";
    public $is_shifting = false;
    public $is_magang = false;

    // Educational information
    public $pendidikan_terakhir;
    public $jurusan;
    public $gelar;

    // Banking & Documents
    public $bank;
    public $nama_rekening;
    public $nomor_rekening;
    public $ktp;
    public $npwp;
    public $kk;

    // BPJS Information
    public $bpjs;
    public $nominal_bpjs;
    public $bpjs_active_date;

    // DLPK Information
    public $dlpk;
    public $cif;
    public $nominal_dlpk;
    public $dlpk_active_date;

    // Contract information
    public $status_kontrak;
    public $nomor_kontrak;
    public $tanggal_penerimaan;
    public $tanggal_aktif_bekerja;
    public $tanggal_keluar;

    public $isEdit = false;
    public $activeTab = 'account';

    protected function rules()
    {
        $basicRules = [
            'name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'user_type' => 'required|in:MANAGER,PEGAWAI',
            'manager_id' => 'nullable|exists:users,id',
            'employee_id' => ['nullable', 'string', 'max:255', Rule::unique('users', 'employee_id')->ignore($this->userId)],
            'office_id' => 'required|exists:offices,id',
        ];

        // Password only required when creating new user
        if (!$this->isEdit) {
            $basicRules['password'] = 'required|min:8|confirmed';
        } else {
            $basicRules['password'] = 'nullable|min:8|confirmed';
        }

        $detailRules = [
            // Personal information
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'birthday' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:100',
            'blood_type' => 'nullable|string|max:5',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'ukuran_baju' => 'nullable|string|max:10',
            'marital_status' => 'nullable|string|max:50',
            'wedding_date' => 'nullable|date',
            'child' => 'nullable|integer|min:0',
            'mother_name' => 'nullable|string|max:255',

            // Address information
            'address' => 'nullable|string',
            'provinsi' => 'nullable|string|max:100',
            'kota' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'kode_pos' => 'nullable|string|max:20',

            // Professional information
            'bidang' => 'nullable|string|max:255',
            'sub_bidang' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'tarif_sppd' => 'nullable|numeric',
            'koefisien_lembur' => 'nullable|numeric',
            'is_shifting' => 'boolean',
            'is_magang' => 'boolean',

            // Educational information
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'jurusan' => 'nullable|string|max:255',
            'gelar' => 'nullable|string|max:100',

            // Banking & Documents
            'bank' => 'nullable|string|max:100',
            'nama_rekening' => 'nullable|string|max:255',
            'nomor_rekening' => 'nullable|string|max:100',
            'ktp' => 'nullable|string|max:50',
            'npwp' => 'nullable|string|max:50',
            'kk' => 'nullable|string|max:50',

            // BPJS Information
            'bpjs' => 'nullable|string|max:50',
            'nominal_bpjs' => 'nullable|numeric',
            'bpjs_active_date' => 'nullable|date',

            // DLPK Information
            'dlpk' => 'nullable|string|max:50',
            'cif' => 'nullable|string|max:50',
            'nominal_dlpk' => 'nullable|numeric',
            'dlpk_active_date' => 'nullable|date',

            // Contract information
            'status_kontrak' => 'nullable|string|max:100',
            'nomor_kontrak' => 'nullable|string|max:100',
            'tanggal_penerimaan' => 'nullable|date',
            'tanggal_aktif_bekerja' => 'nullable|date',
            'tanggal_keluar' => 'nullable|date',
        ];

        return array_merge($basicRules, $detailRules);
    }

    public function mount($user = null)
    {
        $this->isEdit = $user !== null;

        if ($this->isEdit) {
            $this->user = $user;
            $this->userId = $user->id;
            $this->name = $user->name;
            $this->full_name = $user->full_name;
            $this->email = $user->email;
            $this->user_type = $user->user_type;
            $this->manager_id = $user->manager_id;
            $this->phone = $user->phone;
            $this->employee_id = $user->employee_id;
            $this->office_id = $user->office_id;

            // Load user details if they exist
            $userDetail = UserDetails::where('user_id', $user->id)->first();
            if ($userDetail) {
                $this->userDetail = $userDetail;

                // Personal information
                $this->gender = $userDetail->gender;
                $this->birthday = $userDetail->birthday ? $userDetail->birthday->format('Y-m-d') : null;
                $this->birth_place = $userDetail->birth_place;
                $this->religion = $userDetail->religion;
                $this->blood_type = $userDetail->blood_type;
                $this->weight = $userDetail->weight;
                $this->height = $userDetail->height;
                $this->ukuran_baju = $userDetail->ukuran_baju;
                $this->marital_status = $userDetail->marital_status;
                $this->wedding_date = $userDetail->wedding_date;
                $this->child = $userDetail->child;
                $this->mother_name = $userDetail->mother_name;

                // Address information
                $this->address = $userDetail->address;
                $this->provinsi = $userDetail->provinsi;
                $this->kota = $userDetail->kota;
                $this->kecamatan = $userDetail->kecamatan;
                $this->kelurahan = $userDetail->kelurahan;
                $this->rt = $userDetail->rt;
                $this->rw = $userDetail->rw;
                $this->kode_pos = $userDetail->kode_pos;

                // Professional information
                $this->bidang = $userDetail->bidang;
                $this->sub_bidang = $userDetail->sub_bidang;
                $this->jabatan = $userDetail->jabatan;
                $this->tarif_sppd = $userDetail->tarif_sppd;
                $this->koefisien_lembur = $userDetail->koefisien_lembur;
                $this->is_shifting = $userDetail->is_shifting;
                $this->is_magang = $userDetail->is_magang;

                // Educational information
                $this->pendidikan_terakhir = $userDetail->pendidikan_terakhir;
                $this->jurusan = $userDetail->jurusan;
                $this->gelar = $userDetail->gelar;

                // Banking & Documents
                $this->bank = $userDetail->bank;
                $this->nama_rekening = $userDetail->nama_rekening;
                $this->nomor_rekening = $userDetail->nomor_rekening;
                $this->ktp = $userDetail->ktp;
                $this->npwp = $userDetail->npwp;
                $this->kk = $userDetail->kk;

                // BPJS Information
                $this->bpjs = $userDetail->bpjs;
                $this->nominal_bpjs = $userDetail->nominal_bpjs;
                $this->bpjs_active_date = $userDetail->bpjs_active_date;

                // DLPK Information
                $this->dlpk = $userDetail->dlpk;
                $this->cif = $userDetail->cif;
                $this->nominal_dlpk = $userDetail->nominal_dlpk;
                $this->dlpk_active_date = $userDetail->dlpk_active_date;

                // Contract information
                $this->status_kontrak = $userDetail->status_kontrak;
                $this->nomor_kontrak = $userDetail->nomor_kontrak;
                $this->tanggal_penerimaan = $userDetail->tanggal_penerimaan;
                $this->tanggal_aktif_bekerja = $userDetail->tanggal_aktif_bekerja;
                $this->tanggal_keluar = $userDetail->tanggal_keluar;
            }
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // Check if user_type changed to manager, verify if office already has a manager
        if ($propertyName === 'user_type' && $this->user_type === 'MANAGER') {
            $this->checkOfficeManager();
        }

        // If office_id changed and user_type is manager, check if office already has a manager
        if ($propertyName === 'office_id' && $this->user_type === 'MANAGER') {
            $this->checkOfficeManager();
        }

        // If marital status changes to something other than 'married', reset wedding_date
        if ($propertyName === 'marital_status' && $this->marital_status !== 'menikah') {
            $this->wedding_date = null;
        }
    }

    public function checkOfficeManager()
    {
        if (!$this->office_id) {
            return;
        }

        $existingManager = User::where('user_type', 'MANAGER')
            ->where('office_id', $this->office_id)
            ->when($this->isEdit, function ($query) {
                return $query->where('id', '!=', $this->userId);
            })
            ->first();

        if ($existingManager) {
            $this->addError('user_type', 'This office already has a manager: ' . $existingManager->name);
        }
    }

    public function save()
    {
        $this->validate();

        // Double check if office already has a manager if user_type is manager
        if ($this->user_type === 'MANAGER') {
            $existingManager = User::where('user_type', 'MANAGER')
                ->where('office_id', $this->office_id)
                ->when($this->isEdit, function ($query) {
                    return $query->where('id', '!=', $this->userId);
                })
                ->first();

            if ($existingManager) {
                $this->addError('user_type', 'This office already has a manager: ' . $existingManager->name);
                return;
            }
        }

        // If user_type is employee, make sure manager_id corresponds to office_id
        if ($this->user_type === 'PEGAWAI' && $this->manager_id) {
            $manager = User::find($this->manager_id);
            if ($manager && $manager->office_id != $this->office_id) {
                $this->addError('manager_id', 'Manager must be from the same office.');
                return;
            }
        }

        // Begin database transaction
        \DB::beginTransaction();

        try {
            if ($this->isEdit) {
                $user = $this->user;
                $user->name = $this->name;
                $user->email = $this->email;
                $user->user_type = $this->user_type;
                $user->manager_id = $this->manager_id;
                $user->employee_id = $this->employee_id;
                $user->office_id = $this->office_id;

                if ($this->password) {
                    $user->password = Hash::make($this->password);
                }

                $user->save();

                // Update or create user details
                $userDetail = UserDetails::updateOrCreate(['user_id' => $user->id], $this->getUserDetailData());

                \DB::commit();
                session()->flash('message', 'User updated successfully.');
            } else {
                // Create user
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'user_type' => $this->user_type,
                    'phone' => $this->phone,
                    'full_name' => $this->full_name,
                    'manager_id' => $this->manager_id,
                    'employee_id' => $this->employee_id,
                    'office_id' => $this->office_id,
                ]);

                // Create user details
                UserDetails::create(array_merge(['user_id' => $user->id], $this->getUserDetailData()));

                \DB::commit();
                session()->flash('message', 'User created successfully.');
            }

            return redirect()->route('admin.users.index');
        } catch (\Exception $e) {
            \DB::rollBack();
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    private function getUserDetailData()
    {
        return [
            // Personal information
            'gender' => $this->gender,
            'birthday' => $this->birthday,
            'birth_place' => $this->birth_place,
            'religion' => $this->religion,
            'blood_type' => $this->blood_type,
            'weight' => $this->weight,
            'height' => $this->height,
            'ukuran_baju' => $this->ukuran_baju,
            'marital_status' => $this->marital_status,
            'wedding_date' => $this->wedding_date,
            'child' => $this->child,
            'mother_name' => $this->mother_name,

            // Address information
            'address' => $this->address,
            'provinsi' => $this->provinsi,
            'kota' => $this->kota,
            'kecamatan' => $this->kecamatan,
            'kelurahan' => $this->kelurahan,
            'rt' => $this->rt,
            'rw' => $this->rw,
            'kode_pos' => $this->kode_pos,

            // Professional information
            'bidang' => $this->bidang,
            'sub_bidang' => $this->sub_bidang,
            'jabatan' => $this->jabatan,
            'tarif_sppd' => $this->tarif_sppd,
            'koefisien_lembur' => $this->koefisien_lembur,
            'is_shifting' => $this->is_shifting,
            'is_magang' => $this->is_magang,

            // Educational information
            'pendidikan_terakhir' => $this->pendidikan_terakhir,
            'jurusan' => $this->jurusan,
            'gelar' => $this->gelar,

            // Banking & Documents
            'bank' => $this->bank,
            'nama_rekening' => $this->nama_rekening,
            'nomor_rekening' => $this->nomor_rekening,
            'ktp' => $this->ktp,
            'npwp' => $this->npwp,
            'kk' => $this->kk,

            // BPJS Information
            'bpjs' => $this->bpjs,
            'nominal_bpjs' => $this->nominal_bpjs,
            'bpjs_active_date' => $this->bpjs_active_date,

            // DLPK Information
            'dlpk' => $this->dlpk,
            'cif' => $this->cif,
            'nominal_dlpk' => $this->nominal_dlpk,
            'dlpk_active_date' => $this->dlpk_active_date,

            // Contract information
            'status_kontrak' => $this->status_kontrak,
            'nomor_kontrak' => $this->nomor_kontrak,
            'tanggal_penerimaan' => $this->tanggal_penerimaan,
            'tanggal_aktif_bekerja' => $this->tanggal_aktif_bekerja,
            'tanggal_keluar' => $this->tanggal_keluar,
        ];
    }

    public function render()
    {
        $offices = Offices::where('is_active', true)->orderBy('name')->get();

        $managers = User::where('user_type', 'MANAGER')
            ->when($this->office_id, function ($query) {
                return $query->where('office_id', $this->office_id);
            })
            ->when($this->isEdit, function ($query) {
                return $query->where('id', '!=', $this->userId);
            })
            ->orderBy('name')
            ->get();

        return view('livewire.admin.user.user-form', [
            'offices' => $offices,
            'managers' => $managers,
        ]);
    }
}
