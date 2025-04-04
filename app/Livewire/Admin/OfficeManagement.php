<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Offices;
use Livewire\Component;
use Livewire\WithPagination;

class OfficeManagement extends Component
{
    use WithPagination;

      // Form properties
    public $name;
    public $address;
    public $city;
    public $state;
    public $postal_code;
    public $country;
    public $latitude;
    public $longitude;
    public $check_in_radius = 10;
    public $description;
    public $is_active       = true;

      // UI state properties
    public $search    = '';
    public $showModal = false;
    public $editMode  = false;
    public $officeId;
    public $confirmingDeletion = false;
    public $deletingId;

      // Map properties
    public $mapCenter = [
        'lat' => 0,
        'lng' => 0,
    ];

    protected $rules = [
        'name'            => 'required|string|max:255',
        'address'         => 'required|string|max:255',
        'city'            => 'required|string|max:255',
        'state'           => 'nullable|string|max:255',
        'postal_code'     => 'nullable|string|max:20',
        'country'         => 'required|string|max:255',
        'latitude'        => 'required|numeric|between:-90,90',
        'longitude'       => 'required|numeric|between:-180,180',
        'check_in_radius' => 'required|numeric|min:10|max:1000',
        'description'     => 'nullable|string|max:1000',
        'is_active'       => 'boolean',
    ];

    protected $listeners = ['mapLocationSelected', 'refreshOffices' => '$refresh'];

    public function mapLocationSelected($lat, $lng)
    {
        $this->latitude  = $lat;
        $this->longitude = $lng;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->showModal = true;
        $this->editMode = false;
        $this->mapCenter = [
            'lat' => 0,
            'lng' => 0,
        ];
        $this->latitude = null;
        $this->longitude = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->name            = '';
        $this->address         = '';
        $this->city            = '';
        $this->state           = '';
        $this->postal_code     = '';
        $this->country         = '';
        $this->latitude        = null;
        $this->longitude       = null;
        $this->description     = '';
        $this->check_in_radius = 10;
        $this->is_active       = true;
        $this->manager_id      = null;
        $this->officeId        = null;
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        $office = Offices::create([
            'name'            => $this->name,
            'address'         => $this->address,
            'city'            => $this->city,
            'state'           => $this->state,
            'postal_code'     => $this->postal_code,
            'country'         => $this->country,
            'latitude'        => $this->latitude,
            'longitude'       => $this->longitude,
            'check_in_radius' => $this->check_in_radius,
            'description'     => $this->description,
            'is_active'       => $this->is_active,
        ]);

        session()->flash('message', 'Office created successfully.');
        $this->closeModal();
        $this->resetPage();
    }

    public function edit($id)
    {
        $office                = Offices::findOrFail($id);
        $this->officeId        = $id;
        $this->name            = $office->name;
        $this->address         = $office->address;
        $this->city            = $office->city;
        $this->state           = $office->state;
        $this->postal_code     = $office->postal_code;
        $this->country         = $office->country;
        $this->latitude        = $office->latitude;
        $this->longitude       = $office->longitude;
        $this->check_in_radius = $office->check_in_radius;
        $this->description     = $office->description;
        $this->is_active       = $office->is_active;

          // Find the manager of this office
        $manager = User::where('office_id', $office->id)->where('user_type', 'manager')->first();

        $this->manager_id = $manager ? $manager->id : null;

          // Set map center to office location
        $this->mapCenter = [
            'lat' => $office->latitude,
            'lng' => $office->longitude,
        ];

        $this->editMode  = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        $office = Offices::findOrFail($this->officeId);
        $office->update([
            'name'            => $this->name,
            'address'         => $this->address,
            'city'            => $this->city,
            'state'           => $this->state,
            'postal_code'     => $this->postal_code,
            'country'         => $this->country,
            'latitude'        => $this->latitude,
            'longitude'       => $this->longitude,
            'check_in_radius' => $this->check_in_radius,
            'description'     => $this->description,
            'work_start_time' => $this->work_start_time,
            'work_end_time'   => $this->work_end_time,
            'is_active'       => $this->is_active,
        ]);

          // Update office manager
          // First, remove any existing manager associations with this office
        User::where('office_id', $office->id)
            ->where('user_type', 'manager')
            ->update(['office_id' => null]);

          // Then, assign the new manager if one is selected
        if ($this->manager_id) {
            $manager            = User::find($this->manager_id);
            $manager->office_id = $office->id;
            $manager->save();
        }

        session()->flash('message', 'Office updated successfully.');
        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeletion = true;
        $this->deletingId         = $id;
    }

    public function delete()
    {
        $office = Offices::findOrFail($this->deletingId);

          // Remove office association from users
        User::where('office_id', $office->id)->update(['office_id' => null]);

          // Delete the office
        $office->delete();

        session()->flash('message', 'Office deleted successfully.');
        $this->confirmingDeletion = false;
        $this->deletingId         = null;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
        $this->deletingId         = null;
    }

    public function render()
    {
        $managers = User::where('user_type', 'manager')->orderBy('name')->get();

        $offices = Offices::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('city', 'like', '%' . $this->search . '%')
            ->orWhere('country', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.office-management', [
            'offices'  => $offices,
            'managers' => $managers,
        ]);
    }
}