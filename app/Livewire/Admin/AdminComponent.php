<?php

namespace App\Livewire\Admin;

use App\Models\GuideCategory;
use App\Models\Link;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
abstract class AdminComponent extends Component
{
    public $categories;
    public $links;

    public function mount(...$args)
    {
        $this->loadCategories();
        $this->loadLinks();
        $this->adminMount(...$args);
    }

    protected function loadCategories()
    {
        $this->categories = GuideCategory::with(['guides' => function($query) {
            $query->orderBy('order');
        }])
        ->orderBy('order')
        ->get();
    }

    protected function loadLinks()
    {
        $this->links = Link::orderBy('order')->get();
    }

    // To be implemented by child classes
    protected function adminMount(...$args)
    {
        // Child classes should implement their own mount logic
    }
} 