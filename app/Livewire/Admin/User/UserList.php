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

    public function confirmDelete($userId)
    {
        $this->confirmingDeletion = true;
        $this->userToDelete = $userId;
    }

    public function cancelDelete()
    {
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

    public function updatingFilterRole()
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
            if ($user->user_type === 'MANAGER' && $user->employees->count() > 0) {
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

        $users = User::with(['office', 'user_details', 'manager']) // Eager loading untuk optimasi
            ->where('id', '!=', auth()->id()) // Tidak menampilkan admin yang sedang login
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';

                return $query->where(function ($q) use ($searchTerm) {
                    // Pencarian di tabel users
                    $q->where('name', 'like', $searchTerm)
                        ->orWhere('full_name', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm)
                        ->orWhere('employee_id', 'like', $searchTerm)
                        ->orWhere('phone', 'like', $searchTerm)
                        // Pencarian di relasi user_details
                        ->orWhereHas('user_details', function ($detailQuery) use ($searchTerm) {
                            $detailQuery->where('bidang', 'like', $searchTerm)->orWhere('sub_bidang', 'like', $searchTerm)->orWhere('address', 'like', $searchTerm)->orWhere('kota', 'like', $searchTerm)->orWhere('provinsi', 'like', $searchTerm)->orWhere('pendidikan_terakhir', 'like', $searchTerm)->orWhere('jurusan', 'like', $searchTerm)->orWhere('status_kontrak', 'like', $searchTerm);
                        })
                        // Pencarian di relasi office
                        ->orWhereHas('office', function ($officeQuery) use ($searchTerm) {
                            $officeQuery->where('name', 'like', $searchTerm);
                        });
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
            'offices' => $offices,
        ]);
    }
}
