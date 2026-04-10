<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class LegalLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.legal');
    }
}
