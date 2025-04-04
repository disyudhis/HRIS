<?php

namespace App\Livewire\Admin\Office;

use App\Models\Offices;
use App\Models\User;
use Livewire\Component;

class OfficeForm extends Component  // Form properties
{
    public $officeId;
    public $name;
    public $address;
    public $city;
    public $state;
    public $postal_code;
    public $country;
    public $latitude;
    public $longitude;
    public $check_in_radius = 100;
    public $description;
    public $is_active       = true;
    public $manager_id;

      // Edit mode flag
    public $editMode = false;

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
        'manager_id'      => 'nullable|exists:users,id',
    ];

    protected $listeners = ['mapLocationSelected'];

    public function mount($office = null)
    {
        if ($office) {
            $this->officeId        = $office->id;
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
            $this->editMode   = true;
        }
    }

    public function mapLocationSelected($lat, $lng)
    {
        $this->latitude  = $lat;
        $this->longitude = $lng;
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
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
        } else {
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
                'work_start_time' => $this->work_start_time,
                'work_end_time'   => $this->work_end_time,
                'is_active'       => $this->is_active,
            ]);

            session()->flash('message', 'Office created successfully.');
        }

        return redirect()->route('admin.offices.index');
    }

    public function render()
    {
        $managers = User::where('user_type', 'manager')->orderBy('name')->get();

        return view('livewire.admin.office.office-form', [
            'managers' => $managers,
        ]);
    }
}