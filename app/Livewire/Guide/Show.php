<?php

namespace App\Livewire\Guide;

use App\Models\Guide;
use App\Models\GuideCategory;
use App\Models\Link;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Show extends Component
{
    public $guide;
    public $categories;
    public $links;
    public $slug;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->guide = Guide::where('slug', $slug)->firstOrFail();
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
        return view('livewire.guide.show');
    }
} 