<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use App\Models\Offices;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserForm extends Component
{
    public $user;
    public $userId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $user_type = 'employee';
    public $manager_id;
    public $position;
    public $department;
    public $employee_id;
    public $office_id;

    public $isEdit = false;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'user_type' => 'required|in:MANAGER,EMPLOYEE',
            'manager_id' => 'nullable|exists:users,id',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'employee_id' => ['nullable', 'string', 'max:255', Rule::unique('users', 'employee_id')->ignore($this->userId)],
            'office_id' => 'required|exists:offices,id',
        ];

        // Password hanya wajib saat membuat user baru
        if (!$this->isEdit) {
            $rules['password'] = 'required|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|min:8|confirmed';
        }

        return $rules;
    }

    public function mount($user = null)
    {
        $this->isEdit = $user !== null;

        if ($this->isEdit) {
            $this->user = $user;
            $this->userId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->user_type = $user->user_type;
            $this->manager_id = $user->manager_id;
            $this->position = $user->position;
            $this->department = $user->department;
            $this->employee_id = $user->employee_id;
            $this->office_id = $user->office_id;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // Jika user_type berubah menjadi manager, periksa apakah kantor sudah memiliki manager
        if ($propertyName === 'user_type' && $this->user_type === 'MANAGER') {
            $this->checkOfficeManager();
        }

        // Jika office_id berubah dan user_type adalah manager, periksa apakah kantor sudah memiliki manager
        if ($propertyName === 'office_id' && $this->user_type === 'MANAGER') {
            $this->checkOfficeManager();
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

        // Periksa kembali apakah kantor sudah memiliki manager jika user_type adalah manager
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

        // Jika user_type adalah employee, pastikan manager_id sesuai dengan office_id
        if ($this->user_type === 'PEGAWAI' && $this->manager_id) {
            $manager = User::find($this->manager_id);
            if ($manager && $manager->office_id != $this->office_id) {
                $this->addError('manager_id', 'Manager must be from the same office.');
                return;
            }
        }

        if ($this->isEdit) {
            $user = $this->user;
            $user->name = $this->name;
            $user->email = $this->email;
            $user->user_type = $this->user_type;
            $user->manager_id = $this->manager_id;
            $user->position = $this->position;
            $user->department = $this->department;
            $user->employee_id = $this->employee_id;
            $user->office_id = $this->office_id;

            if ($this->password) {
                $user->password = Hash::make($this->password);
            }

            $user->save();

            session()->flash('message', 'User updated successfully.');
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'user_type' => $this->user_type,
                'manager_id' => $this->manager_id,
                'position' => $this->position,
                'department' => $this->department,
                'employee_id' => $this->employee_id,
                'office_id' => $this->office_id,
            ]);

            session()->flash('message', 'User created successfully.');
        }

        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        $offices = Offices::where('is_active', true)->orderBy('name')->get();

        $managers = User::where('user_type',  'manager')
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