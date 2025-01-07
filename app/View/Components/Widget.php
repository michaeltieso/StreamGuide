<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Widget as WidgetModel;

class Widget extends Component
{
    public $widget;
    public $type;
    public $content;

    public function __construct(WidgetModel $widget)
    {
        $this->widget = $widget;
        $this->type = $widget->type;
        $this->content = $widget->content;
    }

    public function render()
    {
        return view('components.widgets.' . $this->type);
    }
}
