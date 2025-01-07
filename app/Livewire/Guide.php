<?php

namespace App\Livewire;

use App\Models\Guide as GuideModel;
use App\Models\GuideCategory;
use App\Models\Link;
use App\Models\SiteSetting;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Guide extends Component
{
    public $categories;
    public $links;

    public function mount()
    {
        $this->loadCategories();
        $this->links = Link::orderBy('order')->get();
    }

    public function loadCategories()
    {
        $this->categories = GuideCategory::with(['guides' => function($query) {
            $query->orderBy('order');
        }])
        ->orderBy('order')
        ->get();
    }

    public function render()
    {
        return view('livewire.guide.index');
    }
}
