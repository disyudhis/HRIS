<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Profile extends Component
{
    public $user;
    public $photo;
    public $user_type;
    public function mount()
    {
        $this->user = Auth::user();
        $this->photo = $this->photoUrl();
        $this->user_type = $this->user->user_type;
    }

    public function photoUrl()
    {
        $photo = Storage::url($this->user->profile_photo_path);
        return $photo;
    }
    public function render()
    {
        return view('livewire.profile.profile');
    }
}
