<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AdminLayout extends Component
{
    
    public function render(): View
    {
        return view('layouts.admin');
    }
} 