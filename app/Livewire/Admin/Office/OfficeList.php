<?php

namespace App\Livewire\Admin\Office;

use App\Models\User;
use App\Models\Offices;
use Livewire\Component;
use Livewire\WithPagination;

class OfficeList extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDeletion = false;
    public $officeToDelete = null;

    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($officeId)
    {
        $this->confirmingDeletion = true;
        $this->officeToDelete = $officeId;
    }

    public function deleteOffice()
    {
        $office = Offices::findOrFail($this->officeToDelete);

        // Remove office association from users
        User::where('office_id', $office->id)->update(['office_id' => null]);

        // Delete the office
        $office->delete();

        $this->confirmingDeletion = false;
        $this->officeToDelete = null;

        session()->flash('message', 'Office deleted successfully.');
    }

    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
        $this->officeToDelete = null;
    }

    public function render()
    {
        $offices = Offices::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('city', 'like', '%' . $this->search . '%')
            ->orWhere('country', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.office.office-list', [
            'offices' => $offices,
        ]);
    }
}