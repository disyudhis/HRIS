<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $user_type = 'employee';
    public $manager_id;
    public $position;
    public $department;
    public $employee_id;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public $showModal = false;
    public $editMode = false;
    public $userId;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|confirmed|min:8',
        'user_type' => 'required|in:admin,manager,employee',
        'manager_id' => 'nullable|exists:users,id',
        'position' => 'nullable|string|max:255',
        'department' => 'nullable|string|max:255',
        'employee_id' => 'nullable|string|max:255|unique:users',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->user_type = 'employee';
        $this->manager_id = null;
        $this->position = '';
        $this->department = '';
        $this->employee_id = '';
        $this->userId = null;
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'user_type' => $this->user_type,
            'manager_id' => $this->manager_id,
            'position' => $this->position,
            'department' => $this->department,
            'employee_id' => $this->employee_id,
        ]);

        session()->flash('message', 'User created successfully.');
        $this->closeModal();
        $this->resetPage();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->user_type = $user->user_type;
        $this->manager_id = $user->manager_id;
        $this->position = $user->position;
        $this->department = $user->department;
        $this->employee_id = $user->employee_id;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->userId,
            'user_type' => 'required|in:admin,manager,employee',
            'manager_id' => 'nullable|exists:users,id',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:255|unique:users,employee_id,' . $this->userId,
        ]);

        $user = User::findOrFail($this->userId);

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'user_type' => $this->user_type,
            'manager_id' => $this->manager_id,
            'position' => $this->position,
            'department' => $this->department,
            'employee_id' => $this->employee_id,
        ];

        // Update password only if provided
        if (!empty($this->password)) {
            $this->validate([
                'password' => 'required|confirmed|min:8',
            ]);

            $userData['password'] = Hash::make($this->password);
        }

        $user->update($userData);

        session()->flash('message', 'User updated successfully.');
        $this->closeModal();
    }

    public function delete($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'User deleted successfully.');
    }

    public function render()
    {
        $managers = User::where('user_type', 'MANAGER')
            ->when($this->userId, function ($query) {
                return $query->where('id', '!=', $this->userId);
            })
            ->orderBy('name')
            ->get();

        $users = User::when($this->search, function ($query) {
            return $query->where(function ($q) {
                $q->where('name', 'ilike', '%' . $this->search . '%')->orWhere('email', 'ilike', '%' . $this->search . '%');
                // Jika ingin menambahkan pencarian department:
                // ->orWhere('department', 'ilike', '%' . $this->search . '%');
            });
        })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users,
            'managers' => $managers,
        ]);
    }
}