<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        app(\App\Http\Controllers\HomeController::class)->navigation_data();
        return view('layouts.app');
    }
}
