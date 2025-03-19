<?php

namespace App\Http\Controllers;

use App\Models\Services;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function show(Services $service)
    {
        return view('services.show', compact('service'));
    }
}
