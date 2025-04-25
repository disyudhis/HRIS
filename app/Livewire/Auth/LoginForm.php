<?php

namespace App\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginForm extends Component
{
    public $email;
    public $password;
    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    protected $messages = [
        'email.required' => 'Email harus diisi',
        'email.email' => 'Format email tidak valid',
        'password.required' => 'Password harus diisi',
        'password.min' => 'Password minimal 8 karakter',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            return $this->redirect(route(Auth::user()->isAdmin() ? 'admin.offices.index' : 'dashboard.check-in'), navigate: true);
        } else {
            $this->dispatch('toast', [
                'message' => 'Email atau password salah',
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