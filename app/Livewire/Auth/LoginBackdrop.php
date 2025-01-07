<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Services\TmdbService;

class LoginBackdrop extends Component
{
    public $backdrop;

    public function mount(TmdbService $tmdbService)
    {
        $this->backdrop = $tmdbService->getRandomBackdrop();
    }

    public function render()
    {
        return view('livewire.auth.login-backdrop');
    }
}
