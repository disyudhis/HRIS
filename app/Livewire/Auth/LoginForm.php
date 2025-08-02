<?php

namespace App\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginForm extends Component
{
    public $username;
    public $password;
    public $remember = false;
    protected $rules = [
        'username' => 'required|string',
        'password' => 'required',
    ];

    protected $messages = [
        'username.required' => 'Username harus diisi',
        'username.string' => 'Format username tidak valid',
        'password.required' => 'Password harus diisi',
        'password.min' => 'Password minimal 8 karakter',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['name' => $this->username, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            return redirect()->route(Auth::user()->isAdmin() ? 'admin.offices.index' : (Auth::user()->isManager() ? 'manager.attendance.index' : 'dashboard.check-in'));
        } else {
            $this->dispatch('toast', [
                'message' => 'Username atau password salah',
                'type' => 'error',
                'duration' => 5000,
            ]);

            $this->reset('password');
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function getErrorBag()
    {
        $errorBag = parent::getErrorBag();

        if ($errorBag->any()) {
            $errorMessages = [];
            foreach ($errorBag->all() as $error) {
                $errorMessages[] = $error;
            }
            $this->dispatch('toast', [
                'message' => implode(', ', $errorMessages),
                'type' => 'error',
                'duration' => 5000,
            ]);
        }

        return $errorBag;
    }

    public function render()
    {
        return view('livewire.auth.login-form');
    }
}
