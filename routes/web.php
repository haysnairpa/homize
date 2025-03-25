<?php

use App\Http\Controllers\ExploreServicesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JasaController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\TokoFavoritController;
use App\Http\Controllers\WishlistController;
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
Route::get('/show_services/{service}', [ExploreServicesController::class, 'show_services'])->name('service');
Route::get('/layanan/{id}', [LayananController::class, 'show'])->name('layanan.detail');

Route::middleware(['auth'])->group(function () {
    Route::get('/merchant', [MerchantController::class, 'index'])->name('merchant');
    Route::get('/merchant/register/step1', [MerchantController::class, 'step1'])->name('merchant.register.step1');
    Route::post('/merchant/register/step1', [MerchantController::class, 'storeStep1'])->name('merchant.register.step1.store');
    Route::get('/merchant/register/step2/{id}', [MerchantController::class, 'step2'])->name('merchant.register.step2');
    Route::post('/merchant/register/step2/{id}', [MerchantController::class, 'storeStep2'])->name('merchant.register.step2.store');
    Route::get('/merchant/dashboard', [MerchantController::class, 'dashboard'])->name('merchant.dashboard');
    Route::post('/merchant/layanan', [MerchantController::class, 'storeLayanan'])
        ->name('merchant.layanan.store');
});

Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/api/search', [SearchController::class, 'apiSearch'])->name('api.search');

Route::post('/toko-favorit/toggle', [TokoFavoritController::class, 'toggle'])->name('toko-favorit.toggle');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::get('/wishlist/content', [WishlistController::class, 'getContent'])->name('wishlist.content');
