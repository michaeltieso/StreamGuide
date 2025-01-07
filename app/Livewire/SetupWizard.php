<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Rules\Password;

class SetupWizard extends Component
{
    public $step = 1;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $appName = 'StreamGuide';

    protected $rules = [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'confirmed'],
        'appName' => ['required', 'string', 'max:255'],
    ];

    public function mount()
    {
        // Redirect if users already exist
        if (User::count() > 0) {
            return redirect()->route('login');
        }
    }

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validateOnly('name');
            $this->validateOnly('email');
            $this->validateOnly('password');
        }

        if ($this->step < 3) {
            $this->step++;
        }
    }

    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function finish()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);

        // Save app name to settings
        SiteSetting::set('app_name', $this->appName);

        Auth::login($user);

        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.setup-wizard')
            ->layout('layouts.guest');
    }
}
