<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Profile extends Component
{
    public $user;

    public function mount()
    {
        $this->user = Auth::user();
    }
    public function render()
    {
        return view('livewire.profile.profile');
    }
}