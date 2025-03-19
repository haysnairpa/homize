<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\JasaController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', [HomeController::class, 'navigation_data'])->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/transactions', function () {
        return view('transactions');
    })->name('transactions');

    Route::get('/merchant', function () {
        return view('merchant');
    })->name('merchant');
});

Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/jasa/{jasa}', [JasaController::class, 'get_jasa'])->name('jasa');
