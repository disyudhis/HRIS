<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Toast extends Component
{

    public $message;
    public $type;
    public $duration;
    /**
     * Create a new component instance.
     */
    public function __construct($message = '', $type = 'info', $duration = 5000)
    {
        $this->message = $message;
        $this->type = $type; // info, success, warning, error
        $this->duration = $duration;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.toast');
    }
}