<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExploreServicesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JasaController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\TokoFavoritController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\UserController;
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
    // User Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'mainDashboard'])->name('dashboard');
    Route::get('/transactions', [UserController::class, 'transactions'])->name('transactions');
    Route::get('/transaction/{id}', [UserController::class, 'transactionDetail'])->name('user.transaction.detail');
    
    // Merchant Routes
    Route::get('/merchant', [MerchantController::class, 'index'])->name('merchant');
});

Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/jasa/{jasa}', [JasaController::class, 'get_jasa'])->name('jasa');
Route::get('/show_services/{service}', [ExploreServicesController::class, 'show_services'])->name('service');
Route::get('/layanan/{id}', [LayananController::class, 'show'])->name('layanan.detail');

Route::middleware(['auth'])->group(function () {
    // Merchant Registration Routes
    Route::get('/merchant/register/step1', [MerchantController::class, 'step1'])->name('merchant.register.step1');
    Route::post('/merchant/register/step1', [MerchantController::class, 'storeStep1'])->name('merchant.register.step1.store');
    Route::get('/merchant/register/step2/{id}', [MerchantController::class, 'step2'])->name('merchant.register.step2');
    Route::post('/merchant/register/step2/{id}', [MerchantController::class, 'storeStep2'])->name('merchant.register.step2.store');
    
    // Merchant Dashboard Routes (with merchant middleware)
    Route::middleware([\App\Http\Middleware\MerchantMiddleware::class])->prefix('merchant')->name('merchant.')->group(function () {
        Route::get('/dashboard', [MerchantController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [MerchantController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [MerchantController::class, 'updateProfile'])->name('profile.update');
        Route::get('/services', [MerchantController::class, 'services'])->name('services');
        Route::get('/orders', [MerchantController::class, 'orders'])->name('orders');
        Route::post('/orders/{id}/update-status', [MerchantController::class, 'updateOrderStatus'])->name('orders.update-status');
        Route::get('/analytics', [MerchantController::class, 'analytics'])->name('analytics');
        Route::post('/layanan', [MerchantController::class, 'storeLayanan'])->name('layanan.store');
        Route::get('/orders/{id}/detail', [MerchantController::class, 'orderDetail'])->name('merchant.orders.detail');
    });

    // Booking routes
    Route::get('/booking/{id}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

    // Pembayaran routes
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::get('/pembayaran/{id}/process', [PembayaranController::class, 'process'])->name('pembayaran.process');
    
    // Midtrans callback
    Route::post('/payment/callback', [PembayaranController::class, 'callback'])->name('payment.callback');
});

Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/api/search', [SearchController::class, 'apiSearch'])->name('api.search');

Route::post('/toko-favorit/toggle', [TokoFavoritController::class, 'toggle'])->name('toko-favorit.toggle');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::get('/wishlist/content', [WishlistController::class, 'getContent'])->name('wishlist.content');

Route::get('/pembayaran/{id}/get-token', [PembayaranController::class, 'getToken'])->name('pembayaran.get-token');
Route::get('/pembayaran/{id}/check-status', [PembayaranController::class, 'checkStatus'])->name('pembayaran.check-status');

// Route::post('/merchant/orders/{id}/update-status', [App\Http\Controllers\MerchantController::class, 'updateOrderStatus'])->name('merchant.orders.update-status')->middleware(['auth', 'merchant']);
