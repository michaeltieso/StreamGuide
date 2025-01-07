<?php

namespace App\Livewire;

use App\Models\Link;
use Livewire\Component;

class Links extends Component
{
    public function render()
    {
        $links = Link::where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('livewire.links', [
            'links' => $links
            ])->layout('layouts.app');
    }
}
