<?php

namespace App\Livewire\Admin;

use Illuminate\View\View;

class About extends AdminComponent
{
    public function render(): View
    {
        return view('livewire.admin.about');
    }
} 