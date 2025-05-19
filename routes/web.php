<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExploreServicesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JasaController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\Merchant\RegisterController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Merchant\DashboardController as MerchantDashboardController;
use App\Http\Controllers\Merchant\OrderController;
use App\Http\Controllers\Merchant\LayananController as MerchantLayananController;
use App\Http\Controllers\Merchant\DetailController;
use App\Http\Controllers\Merchant\AnalyticController as MerchantAnalyticController;
use App\Http\Controllers\TokoFavoritController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\XenditCallbackController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Merchant\PenarikanController;
use App\Http\Controllers\Admin\PenarikanController as AdminPenarikanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MerchantController as AdminMerchantController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

Route::get('/', function () {
    return redirect()->route('home');
});

// Xendit webhook at root URL - completely bypass web middleware
Route::post('/', [XenditCallbackController::class, 'handleWebhook'])
    ->middleware([])
    ->withoutMiddleware('web')
    ->name('webhook.root');

// Xendit webhook at /webhooks/xendit - also bypass web middleware
Route::post('/webhooks/xendit', [XenditCallbackController::class, 'handleWebhook'])
    ->middleware([])
    ->withoutMiddleware('web')
    ->name('webhook.xendit');

Route::get('/home', [HomeController::class, 'navigation_data'])->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // User Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'mainDashboard'])->name('dashboard');
    
    // User Profile Photo Upload
    Route::post('/user/profile-photo', [\App\Http\Controllers\UserProfilePhotoController::class, 'update'])->name('user-profile-photo.update');

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
    Route::get('/pembayaran/{id}/qris-static', [PembayaranController::class, 'showStaticQris'])->name('pembayaran.qris-static');
    Route::post('/pembayaran/{id}/save-order', [PembayaranController::class, 'saveOrder'])->name('pembayaran.save-order');

    // Xendit callback - without middleware required
    Route::post('pembayaran/callback', [XenditCallbackController::class, 'handle'])
        ->withoutMiddleware([
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class
        ])
        ->name('pembayaran.callback');
        
    // Alternative route
    Route::post('webhook/xendit', [XenditCallbackController::class, 'handle'])
        ->withoutMiddleware([
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class
        ]);

    // Reset OTP

    // Tambahkan route ini di grup yang sesuai
    Route::get('/pembayaran/{id}/reset-otp', [PembayaranController::class, 'resetOtpAttempts'])->name('pembayaran.reset-otp');

    // Tambahkan route ini
    Route::get('/pembayaran/check/{id}', [PembayaranController::class, 'checkStatus'])->name('pembayaran.check');
    Route::get('/pembayaran/force-check/{id}', [PembayaranController::class, 'forceCheckStatus'])->name('pembayaran.force-check');

    Route::get('/pembayaran/{id}/get-token', [PembayaranController::class, 'getToken'])->name('pembayaran.get-token');
    Route::get('/pembayaran/{id}/check-status', [PembayaranController::class, 'checkStatus'])->name('pembayaran.check-status');

    Route::get('/pembayaran/{id}/va-number', [PembayaranController::class, 'getVaNumber'])->name('pembayaran.va-number');
});

Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/jasa/{jasa}', [JasaController::class, 'get_jasa'])->name('jasa');
Route::get('/show_services/{service}', [ExploreServicesController::class, 'show_services'])->name('service');
Route::get('/layanan/{id}', [LayananController::class, 'show'])->name('layanan.detail');

Route::middleware(['auth', \App\Http\Middleware\PreventMerchantReregistration::class])->group(function () {
    // Merchant Registration Routes
    Route::get('/merchant', [RegisterController::class, 'index'])->name('merchant');
    Route::get('/merchant/register/step1', [RegisterController::class, 'step1'])->name('merchant.register.step1');
    Route::post('/merchant/register/step1', [RegisterController::class, 'storeStep1'])->name('merchant.register.storeStep1');
    Route::post('/merchant/register/step1', [RegisterController::class, 'storeStep1'])->name('merchant.register.step1.store');
    Route::get('/merchant/register/step2', [RegisterController::class, 'step2'])->name('merchant.register.step2');
    Route::post('/merchant/register/step2', [RegisterController::class, 'storeStep2'])->name('merchant.register.storeStep2');
    Route::post('/merchant/register/step2', [RegisterController::class, 'storeStep2'])->name('merchant.register.step2.store');
    Route::post('/merchant/register/back-to-step1', [RegisterController::class, 'backToStep1'])->name('merchant.register.backToStep1');
    Route::get('/merchant/verification-status', [MerchantDashboardController::class, 'verificationStatus'])->name('merchant.verification-status');
    Route::post('/merchant/retry-verification', [MerchantDashboardController::class, 'retryVerification'])->name('merchant.retryVerification');
});

// Merchant verification status routes
Route::middleware(['auth'])->group(function () {
    Route::get('/merchant/verification-status', [MerchantDashboardController::class, 'verificationStatus'])->name('merchant.verification-status');
    Route::post('/merchant/verification-retry', [MerchantDashboardController::class, 'retryVerification'])->name('merchant.verification.retry');
});

// Merchant Dashboard Routes (pastikan tidak menggunakan middleware prevent-merchant-reregistration)
Route::middleware(['auth', \App\Http\Middleware\MerchantMiddleware::class])->prefix('merchant')->name('merchant.')->group(function () {
    // Penarikan merchant
    Route::get('/penarikan', [PenarikanController::class, 'index'])->name('penarikan');
    Route::get('/penarikan/riwayat', [PenarikanController::class, 'riwayat'])->name('penarikan.riwayat');
    Route::post('/penarikan/ajukan', [PenarikanController::class, 'ajukan'])->name('penarikan.ajukan');
    Route::post('/penarikan/tambah-rekening', [PenarikanController::class, 'tambahRekening'])->name('penarikan.tambahRekening');
    Route::get('/dashboard', [MerchantDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [MerchantDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [MerchantDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/services', [MerchantLayananController::class, 'services'])->name('services');
    Route::get('/orders', [OrderController::class, 'orders'])->name('orders');
    Route::get('/orders/filter-by-date', [OrderController::class, 'filterByDate'])->name('orders.filterByDate');
    Route::post('/orders/{id}/update-status', [OrderController::class, 'updateOrderStatus'])->name('orders.update-status');
    Route::get('/analytics', [MerchantAnalyticController::class, 'analytics'])->name('analytics');
    Route::post('/layanan', [MerchantLayananController::class, 'storeLayanan'])->name('layanan.store');
    Route::get('/layanan/{id}/edit', [MerchantLayananController::class, 'editLayanan'])->name('layanan.edit');
    Route::put('/layanan/{id}', [MerchantLayananController::class, 'updateLayanan'])->name('layanan.update');
    Route::delete('/layanan/{id}', [MerchantLayananController::class, 'destroy'])->name('layanan.delete');
    Route::get('/orders/{id}/detail', [OrderController::class, 'orderDetail'])->name('merchant.orders.detail');
    Route::get('/merchant/analytics/data', [MerchantAnalyticController::class, 'getAnalyticsData'])->name('merchant.analytics.data');
});

Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/api/search', [SearchController::class, 'apiSearch'])->name('api.search');

Route::post('/toko-favorit/toggle', [TokoFavoritController::class, 'toggle'])->name('toko-favorit.toggle');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::get('/wishlist/content', [WishlistController::class, 'getContent'])->name('wishlist.content');

Route::get('/merchant/{id}', [DetailController::class, 'getMerchantDetail'])->name('merchant.detail');
Route::get('/merchant/{id}/sort', [MerchantLayananController::class, 'sortLayanan'])->name('merchant.sort');

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

// Tambah rekening merchant (POST)


// Contact us routes
Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

// Google Login Routes
Route::get('auth/google', [App\Http\Controllers\SocialiteController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [App\Http\Controllers\SocialiteController::class, 'handleGoogleCallback']);

// Complete phone after Google login
Route::get('auth/complete-phone', [App\Http\Controllers\SocialiteController::class, 'showCompletePhoneForm'])->name('complete.phone');
Route::post('auth/complete-phone', [App\Http\Controllers\SocialiteController::class, 'submitCompletePhone'])->name('complete.phone.submit');


Route::prefix('admin')->name('admin.')->group(function () {
    // Penarikan admin
    Route::get('/penarikan', [AdminPenarikanController::class, 'index'])->name('penarikan');
    Route::put('/penarikan/{id}', [AdminPenarikanController::class, 'update'])->name('penarikan.update');

    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.post');

    // Protected admin routes
    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/merchants', [AdminMerchantController::class, 'merchants'])->name('merchants');
        Route::get('/merchants/{id}/detail', [AdminMerchantController::class, 'getMerchantDetail'])->name('merchants.detail');
        Route::post('/merchants/{id}/adjust-balance', [AdminMerchantController::class, 'adjustBalance'])->name('merchants.adjust-balance');
        Route::get('/merchants/{id}/transactions', [AdminMerchantController::class, 'getTransactionHistory'])->name('merchants.transactions');
        Route::post('/merchant/{id}/approve', [AdminController::class, 'approveMerchant'])->name('merchant.approve');
        Route::post('/merchant/{id}/reject', [AdminController::class, 'rejectMerchant'])->name('merchant.reject');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
        Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
        // Admin Merchant Deletion Route
        Route::delete('/merchants/{id}', [AdminMerchantController::class, 'destroy'])->name('merchants.destroy');

        // Admin User Deletion Route
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        
        // Admin Payment Routes
        Route::post('/payment/{id}/approve', [PembayaranController::class, 'approvePayment'])->name('payment.approve');
        Route::put('/payment/{id}/reject', [PembayaranController::class, 'rejectPayment'])->name('payment.reject');
    });
});
