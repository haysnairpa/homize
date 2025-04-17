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
use App\Http\Controllers\MidtransCallbackController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\AdminController;
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

    Route::get('/transactions', [DashboardController::class, 'transactions'])->name('transactions');
    Route::get('/transactions/filter', [DashboardController::class, 'filterTransactions'])->name('transactions.filter');
    Route::get('/transactions/filter-by-date', [DashboardController::class, 'filterByDateRange'])->name('transactions.filter-by-date');

    Route::get('/transactions/{id}/detail', [DashboardController::class, 'transactionDetail'])->name('user.transaction.detail');

    Route::get('/merchant', function () {
        return view('merchant');
    })->name('merchant');

    // Transaction routes
    Route::get('/user/transactions', [DashboardController::class, 'transactions'])->name('user.transactions');
    Route::get('/user/transactions/filter', [DashboardController::class, 'filterTransactions'])->name('user.transactions.filter');
    Route::get('/user/transactions/filter-by-date', [DashboardController::class, 'filterTransactionsByDate'])->name('user.transactions.filter-by-date');
    Route::get('/user/transactions/{id}', [DashboardController::class, 'transactionDetail'])->name('user.transaction.detail');

    // Booking routes
    Route::get('/booking/{id}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

    // Pembayaran routes
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::get('/pembayaran/{id}/process', [PembayaranController::class, 'process'])->name('pembayaran.process');

    // Midtrans callback
    Route::post('pembayaran/callback', [MidtransCallbackController::class, 'handle'])->name('pembayaran.callback');

    // Tambahkan route ini di grup yang sesuai
    Route::get('/pembayaran/{id}/reset-otp', [PembayaranController::class, 'resetOtpAttempts'])->name('pembayaran.reset-otp');

    // Tambahkan route ini
    Route::get('/pembayaran/check/{id}', [PembayaranController::class, 'checkStatus'])->name('pembayaran.check');
    Route::get('/pembayaran/force-check/{id}', [PembayaranController::class, 'forceCheckStatus'])->name('pembayaran.force-check');

    Route::get('/pembayaran/{id}/get-token', [PembayaranController::class, 'getToken'])->name('pembayaran.get-token');
    Route::get('/pembayaran/{id}/check-status', [PembayaranController::class, 'checkStatus'])->name('pembayaran.check-status');

    Route::get('/pembayaran/{id}/va-number', [PembayaranController::class, 'getVaNumber'])->name('pembayaran.va-number');
    Route::get('/pembayaran/{id}/qris', [PembayaranController::class, 'showQris'])->name('pembayaran.qris');
});

Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/jasa/{jasa}', [JasaController::class, 'get_jasa'])->name('jasa');
Route::get('/show_services/{service}', [ExploreServicesController::class, 'show_services'])->name('service');
Route::get('/layanan/{id}', [LayananController::class, 'show'])->name('layanan.detail');

Route::middleware(['auth', \App\Http\Middleware\PreventMerchantReregistration::class])->group(function () {
    // Merchant Registration Routes
    Route::get('/merchant', [MerchantController::class, 'index'])->name('merchant');
    Route::get('/merchant/register/step1', [MerchantController::class, 'step1'])->name('merchant.register.step1');
    Route::post('/merchant/register/step1', [MerchantController::class, 'storeStep1'])->name('merchant.register.step1.store');
    Route::get('/merchant/register/step2', [MerchantController::class, 'step2'])->name('merchant.register.step2');
    Route::post('/merchant/register/step2', [MerchantController::class, 'storeStep2'])->name('merchant.register.step2.store');
});

// Merchant verification status routes
Route::middleware(['auth'])->group(function () {
    Route::get('/merchant/verification-status', [MerchantController::class, 'verificationStatus'])->name('merchant.verification-status');
    Route::post('/merchant/verification-retry', [MerchantController::class, 'retryVerification'])->name('merchant.verification.retry');
});

// Merchant Dashboard Routes (pastikan tidak menggunakan middleware prevent-merchant-reregistration)
Route::middleware(['auth', \App\Http\Middleware\MerchantMiddleware::class])->prefix('merchant')->name('merchant.')->group(function () {
    Route::get('/dashboard', [MerchantController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [MerchantController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [MerchantController::class, 'updateProfile'])->name('profile.update');
    Route::get('/services', [MerchantController::class, 'services'])->name('services');
    Route::get('/orders', [MerchantController::class, 'orders'])->name('orders');
    Route::post('/orders/{id}/update-status', [MerchantController::class, 'updateOrderStatus'])->name('orders.update-status');
    Route::get('/analytics', [MerchantController::class, 'analytics'])->name('analytics');
    Route::post('/layanan', [MerchantController::class, 'storeLayanan'])->name('layanan.store');
    Route::get('/layanan/{id}/edit', [MerchantController::class, 'editLayanan'])->name('layanan.edit');
    Route::put('/layanan/{id}', [MerchantController::class, 'updateLayanan'])->name('layanan.update');
    Route::delete('/layanan/{id}', [MerchantController::class, 'deleteLayanan'])->name('layanan.delete');
    Route::get('/orders/{id}/detail', [MerchantController::class, 'orderDetail'])->name('merchant.orders.detail');
    Route::get('/merchant/analytics/data', [MerchantController::class, 'getAnalyticsData'])->name('merchant.analytics.data');
});


Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/api/search', [SearchController::class, 'apiSearch'])->name('api.search');

Route::post('/toko-favorit/toggle', [TokoFavoritController::class, 'toggle'])->name('toko-favorit.toggle');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::get('/wishlist/content', [WishlistController::class, 'getContent'])->name('wishlist.content');

Route::get('/merchant/{id}', [MerchantController::class, 'getMerchantDetail'])->name('merchant.detail');

Route::get('/merchant/{id}/sort', [MerchantController::class, 'sortLayanan'])->name('merchant.sort');

// Route::post('/merchant/orders/{id}/update-status', [App\Http\Controllers\MerchantController::class, 'updateOrderStatus'])->name('merchant.orders.update-status')->middleware(['auth', 'merchant']);

// Rating routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/rating/{id}', [RatingController::class, 'create'])->name('user.rating.create');
    Route::post('/rating/{id}', [RatingController::class, 'store'])->name('user.rating.store');
});

// Tambahkan route ini di bawah route home
Route::post('/home/filter', [App\Http\Controllers\HomeController::class, 'filterLayanan'])->name('home.filter');

// Offline fallback route
Route::get('/offline', [App\Http\Controllers\HomeController::class, 'offline'])->name('offline');

// Google Login Routes
Route::get('auth/google', [App\Http\Controllers\SocialiteController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [App\Http\Controllers\SocialiteController::class, 'handleGoogleCallback']);


Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.post');

    // Protected admin routes
    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/merchants', [AdminController::class, 'merchants'])->name('merchants');
        Route::post('/merchant/{id}/approve', [AdminController::class, 'approveMerchant'])->name('merchant.approve');
        Route::post('/merchant/{id}/reject', [AdminController::class, 'rejectMerchant'])->name('merchant.reject');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
        Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
    });
});
