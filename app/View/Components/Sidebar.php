<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Sidebar extends Component
{
    public $categories;
    public $links;

    public function __construct($categories, $links)
    {
        $this->categories = $categories;
        $this->links = $links;
    }

    public function render()
    {
        return view('components.sidebar');
    }
} 