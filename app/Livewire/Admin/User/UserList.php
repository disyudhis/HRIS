<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use App\Models\Offices;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $filterRole = '';
    public $filterOffice = '';

    public $confirmingDeletion = false;

    public $userToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'filterRole' => ['except' => ''],
        'filterOffice' => ['except' => ''],
    ];

    public function confirmDelete($userId) {
        $this->confirmingDeletion = true;
        $this->userToDelete = $userId;
    }


    public function cancelDelete(){
        $this->confirmingDeletion = false;
        $this->userToDelete = null;
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingfilterRole()
    {
        $this->resetPage();
    }

    public function updatingFilterOffice()
    {
        $this->resetPage();
    }

    public function deleteUser()
    {
        $user = User::find($this->userToDelete);

        if ($user) {
            // Jika user adalah manager, pastikan tidak ada employee yang terkait
            if ($user->user_type === 'manager' && $user->employees->count() > 0) {
                session()->flash('error', 'Cannot delete manager with assigned employees. Please reassign employees first.');
                return;
            }

            $user->delete();

            $this->confirmingDeletion = false;
            $this->userToDelete = null;

            session()->flash('message', 'User deleted successfully.');
        }
    }

    public function render()
    {
        $offices = Offices::orderBy('name')->get();

        $users = User::where('id', '!=', auth()->id()) // Tidak menampilkan admin
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('name', 'ilike', '%' . $this->search . '%')
                      ->orWhere('email', 'ilike', '%' . $this->search . '%')
                      ->orWhere('department', 'ilike', '%' . $this->search . '%')
                      ->orWhere('position', 'ilike', '%' . $this->search . '%')
                      ->orWhere('employee_id', 'ilike', '%' . $this->search . '%');
                });
            })
            ->when($this->filterRole, function ($query) {
                return $query->where('user_type', $this->filterRole);
            })
            ->when($this->filterOffice, function ($query) {
                return $query->where('office_id', $this->filterOffice);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.user.user-list', [
            'users' => $users,
            'offices' => $offices
        ]);
    }
}