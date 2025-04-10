<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class ProfileEdit extends Component
{
    use WithFileUploads;

    public $user;
    public $name;
    public $email;
    public $phone;
    public $position;
    public $department;
    public $address;
    public $emergency_contact;
    public $date_of_birth;
    public $photo;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'position' => 'nullable|string|max:100',
        'department' => 'nullable|string|max:100',
        'address' => 'nullable|string|max:500',
        'emergency_contact' => 'nullable|string|max:255',
        'date_of_birth' => 'nullable|date',
        'photo' => 'nullable|image|max:1024',
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->position = $this->user->position;
        $this->department = $this->user->department;
        $this->address = $this->user->address;
        $this->emergency_contact = $this->user->emergency_contact;
        $this->date_of_birth = $this->user->date_of_birth ? $this->user->date_of_birth->format('Y-m-d') : null;
    }

    public function updateProfile()
    {
        $this->validate();

        // Ensure email is unique except for the current user
        $this->validate([
            'email' => 'unique:users,email,' . $this->user->id,
        ]);

        $this->user->name = $this->name;
        $this->user->email = $this->email;
        $this->user->phone = $this->phone;
        $this->user->position = $this->position;
        $this->user->department = $this->department;
        $this->user->address = $this->address;
        $this->user->emergency_contact = $this->emergency_contact;
        $this->user->date_of_birth = $this->date_of_birth;

        if ($this->photo) {
            // Delete old photo if exists
            if ($this->user->profile_photo_path) {
                Storage::delete('public/' . $this->user->profile_photo_path);
            }

            // Store new photo
            $path = $this->photo->store('profile-photos', 'public');
            $this->user->profile_photo_path = $path;
        }

        $this->user->save();

        session()->flash('message', 'Profile updated successfully.');

        return redirect()->back();
    }

    public function render()
    {
        return view('livewire.profile.profile-edit');
    }
}