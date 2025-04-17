<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Profile extends Component
{
    public $user;
    public $user_type;
    public function mount()
    {
        $this->user = Auth::user();
        $this->user_type = $this->user->user_type;
    }
    public function render()
    {
        return view('livewire.profile.profile');
    }
}