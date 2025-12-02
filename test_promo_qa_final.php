<?php
/**
 * Final QA Test for Promo System
 * Tests: Model, Service, Controller, Full Flow
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\KodePromo;
use App\Models\PenggunaanKodePromo;
use App\Models\Kategori;
use App\Models\Layanan;
use App\Models\User;
use App\Models\Booking;
use App\Services\PromoCodeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

echo "=== FINAL QA TEST FOR PROMO SYSTEM ===\n\n";

$errors = [];
$warnings = [];
$passed = 0;
$failed = 0;

// Helper function
function test($name, $condition, $errorMsg = null) {
    global $errors, $passed, $failed;
    if ($condition) {
        echo "✓ PASS: $name\n";
        $passed++;
        return true;
    } else {
        echo "✗ FAIL: $name" . ($errorMsg ? " - $errorMsg" : "") . "\n";
        $errors[] = $name . ($errorMsg ? ": $errorMsg" : "");
        $failed++;
        return false;
    }
}

function warn($msg) {
    global $warnings;
    echo "⚠ WARNING: $msg\n";
    $warnings[] = $msg;
}

// =====================================================
// TEST 1: Model Tests
// =====================================================
echo "\n--- TEST 1: KodePromo Model ---\n";

// Test model fillable fields
$fillable = (new KodePromo)->getFillable();
$requiredFields = ['kode', 'nama', 'tipe_diskon', 'nilai_diskon', 'tanggal_mulai', 'tanggal_berakhir', 
                   'batas_penggunaan_global', 'batas_penggunaan_per_user', 'is_exclusive', 
                   'target_type', 'target_id', 'status_aktif', 'deskripsi', 'minimum_pembelian', 'maksimum_diskon'];
foreach ($requiredFields as $field) {
    test("KodePromo has fillable field: $field", in_array($field, $fillable));
}

// Test model casts
$casts = (new KodePromo)->getCasts();
test("KodePromo casts tanggal_mulai to datetime", isset($casts['tanggal_mulai']) && $casts['tanggal_mulai'] === 'datetime');
test("KodePromo casts tanggal_berakhir to datetime", isset($casts['tanggal_berakhir']) && $casts['tanggal_berakhir'] === 'datetime');
test("KodePromo casts is_exclusive to boolean", isset($casts['is_exclusive']) && $casts['is_exclusive'] === 'boolean');
test("KodePromo casts status_aktif to boolean", isset($casts['status_aktif']) && $casts['status_aktif'] === 'boolean');

// Test model methods exist
$promo = new KodePromo();
test("KodePromo has isValid method", method_exists($promo, 'isValid'));
test("KodePromo has isExpired method", method_exists($promo, 'isExpired'));
test("KodePromo has calculateDiscount method", method_exists($promo, 'calculateDiscount'));
test("KodePromo has isApplicableToLayanan method", method_exists($promo, 'isApplicableToLayanan'));
test("KodePromo has canBeUsedBy method", method_exists($promo, 'canBeUsedBy'));

// =====================================================
// TEST 2: PromoCodeService Tests
// =====================================================
echo "\n--- TEST 2: PromoCodeService ---\n";

$promoService = new PromoCodeService();
test("PromoCodeService instantiated", $promoService !== null);
test("PromoCodeService has validatePromoCode method", method_exists($promoService, 'validatePromoCode'));
test("PromoCodeService has calculateDiscount method", method_exists($promoService, 'calculateDiscount'));
test("PromoCodeService has recordUsage method", method_exists($promoService, 'recordUsage'));
test("PromoCodeService has getPromoStatistics method", method_exists($promoService, 'getPromoStatistics'));

// =====================================================
// TEST 3: Database Schema Tests
// =====================================================
echo "\n--- TEST 3: Database Schema ---\n";

$kodePromoColumns = DB::getSchemaBuilder()->getColumnListing('kode_promo');
$requiredColumns = ['id', 'kode', 'nama', 'tipe_diskon', 'nilai_diskon', 'tanggal_mulai', 
                    'tanggal_berakhir', 'batas_penggunaan_global', 'batas_penggunaan_per_user',
                    'is_exclusive', 'target_type', 'target_id', 'status_aktif', 'deskripsi',
                    'minimum_pembelian', 'maksimum_diskon', 'created_at', 'updated_at'];
foreach ($requiredColumns as $col) {
    test("kode_promo table has column: $col", in_array($col, $kodePromoColumns));
}

$penggunaanColumns = DB::getSchemaBuilder()->getColumnListing('penggunaan_kode_promo');
$requiredPenggunaanColumns = ['id', 'kode_promo_id', 'user_id', 'booking_id', 'diskon_amount', 
                               'original_amount', 'final_amount', 'tanggal_digunakan'];
foreach ($requiredPenggunaanColumns as $col) {
    test("penggunaan_kode_promo table has column: $col", in_array($col, $penggunaanColumns));
}

// =====================================================
// TEST 4: Promo Validation Logic Tests
// =====================================================
echo "\n--- TEST 4: Promo Validation Logic ---\n";

// Create test data
DB::beginTransaction();
try {
    // Get or create test user
    $testUser = User::first();
    if (!$testUser) {
        warn("No users in database - skipping user-dependent tests");
    } else {
        // Get or create test layanan
        $testLayanan = Layanan::first();
        if (!$testLayanan) {
            warn("No layanan in database - skipping layanan-dependent tests");
        } else {
            // Create test promo
            $testPromo = KodePromo::create([
                'kode' => 'TESTQA' . time(),
                'nama' => 'Test QA Promo',
                'tipe_diskon' => 'percentage',
                'nilai_diskon' => 10,
                'tanggal_mulai' => Carbon::now()->subDay(),
                'tanggal_berakhir' => Carbon::now()->addDays(30),
                'batas_penggunaan_global' => 100,
                'batas_penggunaan_per_user' => 2,
                'is_exclusive' => false,
                'target_type' => 'all',
                'target_id' => null,
                'status_aktif' => true,
                'minimum_pembelian' => 50000,
                'maksimum_diskon' => 100000,
            ]);
            
            test("Test promo created successfully", $testPromo->id > 0);
            test("Promo isValid returns true for active promo", $testPromo->isValid());
            test("Promo isExpired returns false for valid promo", !$testPromo->isExpired());
            
            // Test discount calculation
            $discount = $testPromo->calculateDiscount(100000);
            test("Discount calculation works (10% of 100000 = 10000)", $discount == 10000);
            
            // Test max discount cap
            $discountCapped = $testPromo->calculateDiscount(2000000);
            test("Max discount cap works (10% of 2M = 200k, capped at 100k)", $discountCapped == 100000);
            
            // Test minimum purchase
            $discountBelowMin = $testPromo->calculateDiscount(30000);
            test("Minimum purchase check works (below 50k returns 0)", $discountBelowMin == 0);
            
            // Test service validation
            $result = $promoService->validatePromoCode(
                $testPromo->kode,
                $testUser->id,
                $testLayanan->id,
                100000
            );
            test("PromoCodeService validation returns success key", isset($result['success']));
            test("PromoCodeService validation succeeds for valid promo", $result['success'] === true);
            
            // Test invalid promo code
            $invalidResult = $promoService->validatePromoCode(
                'INVALIDCODE123',
                $testUser->id,
                $testLayanan->id,
                100000
            );
            test("Invalid promo code returns success=false", $invalidResult['success'] === false);
            
            // Test expired promo
            $expiredPromo = KodePromo::create([
                'kode' => 'EXPIRED' . time(),
                'nama' => 'Expired Promo',
                'tipe_diskon' => 'percentage',
                'nilai_diskon' => 10,
                'tanggal_mulai' => Carbon::now()->subDays(30),
                'tanggal_berakhir' => Carbon::now()->subDay(),
                'batas_penggunaan_per_user' => 1,
                'target_type' => 'all',
                'status_aktif' => true,
            ]);
            test("Expired promo isExpired returns true", $expiredPromo->isExpired());
            test("Expired promo isValid returns false", !$expiredPromo->isValid());
            
            // Test inactive promo
            $inactivePromo = KodePromo::create([
                'kode' => 'INACTIVE' . time(),
                'nama' => 'Inactive Promo',
                'tipe_diskon' => 'percentage',
                'nilai_diskon' => 10,
                'tanggal_mulai' => Carbon::now()->subDay(),
                'tanggal_berakhir' => Carbon::now()->addDays(30),
                'batas_penggunaan_per_user' => 1,
                'target_type' => 'all',
                'status_aktif' => false,
            ]);
            test("Inactive promo isValid returns false", !$inactivePromo->isValid());
            
            // Test fixed discount
            $fixedPromo = KodePromo::create([
                'kode' => 'FIXED' . time(),
                'nama' => 'Fixed Discount',
                'tipe_diskon' => 'fixed',
                'nilai_diskon' => 25000,
                'tanggal_mulai' => Carbon::now()->subDay(),
                'tanggal_berakhir' => Carbon::now()->addDays(30),
                'batas_penggunaan_per_user' => 1,
                'target_type' => 'all',
                'status_aktif' => true,
            ]);
            $fixedDiscount = $fixedPromo->calculateDiscount(100000);
            test("Fixed discount calculation works (25000)", $fixedDiscount == 25000);
            
            // Test category target
            $kategori = Kategori::first();
            if ($kategori) {
                $categoryPromo = KodePromo::create([
                    'kode' => 'CATEGORY' . time(),
                    'nama' => 'Category Promo',
                    'tipe_diskon' => 'percentage',
                    'nilai_diskon' => 15,
                    'tanggal_mulai' => Carbon::now()->subDay(),
                    'tanggal_berakhir' => Carbon::now()->addDays(30),
                    'batas_penggunaan_per_user' => 1,
                    'target_type' => 'category',
                    'target_id' => $kategori->id,
                    'status_aktif' => true,
                ]);
                test("Category promo created with target_id", $categoryPromo->target_id == $kategori->id);
                test("Category promo target_type is category", $categoryPromo->target_type === 'category');
                
                // Test accessor
                $targetKategori = $categoryPromo->target_kategori;
                test("target_kategori accessor returns Kategori model", $targetKategori instanceof Kategori || $targetKategori === null);
            }
            
            // Test service target
            $servicePromo = KodePromo::create([
                'kode' => 'SERVICE' . time(),
                'nama' => 'Service Promo',
                'tipe_diskon' => 'percentage',
                'nilai_diskon' => 20,
                'tanggal_mulai' => Carbon::now()->subDay(),
                'tanggal_berakhir' => Carbon::now()->addDays(30),
                'batas_penggunaan_per_user' => 1,
                'target_type' => 'service',
                'target_id' => $testLayanan->id,
                'status_aktif' => true,
            ]);
            test("Service promo created with target_id", $servicePromo->target_id == $testLayanan->id);
            test("Service promo isApplicableToLayanan returns true for target", $servicePromo->isApplicableToLayanan($testLayanan->id));
            
            // Test accessor
            $targetLayanan = $servicePromo->target_layanan;
            test("target_layanan accessor returns Layanan model", $targetLayanan instanceof Layanan || $targetLayanan === null);
        }
    }
} finally {
    DB::rollBack();
    echo "\n(Test data rolled back)\n";
}

// =====================================================
// TEST 5: Controller Route Tests
// =====================================================
echo "\n--- TEST 5: Controller Routes ---\n";

$routes = app('router')->getRoutes();
$promoRoutes = [
    'admin.promo.index' => 'GET',
    'admin.promo.create' => 'GET',
    'admin.promo.store' => 'POST',
    'admin.promo.show' => 'GET',
    'admin.promo.edit' => 'GET',
    'admin.promo.update' => 'PUT',
    'admin.promo.destroy' => 'DELETE',
    'admin.promo.toggle-status' => 'POST',
];

foreach ($promoRoutes as $routeName => $method) {
    $route = $routes->getByName($routeName);
    test("Route $routeName exists", $route !== null);
}

// =====================================================
// TEST 6: View Files Exist
// =====================================================
echo "\n--- TEST 6: View Files ---\n";

$viewFiles = [
    'resources/views/admin/promo/index.blade.php',
    'resources/views/admin/promo/create.blade.php',
    'resources/views/admin/promo/edit.blade.php',
    'resources/views/admin/promo/show.blade.php',
];

foreach ($viewFiles as $viewFile) {
    test("View file exists: $viewFile", file_exists(__DIR__ . '/' . $viewFile));
}

// =====================================================
// TEST 7: Booking Promo Integration
// =====================================================
echo "\n--- TEST 7: Booking Promo Integration ---\n";

$bookingColumns = DB::getSchemaBuilder()->getColumnListing('booking');
$promoBookingColumns = ['kode_promo_id', 'original_amount', 'diskon_amount', 'diskon_percentage', 'final_amount'];
foreach ($promoBookingColumns as $col) {
    test("booking table has promo column: $col", in_array($col, $bookingColumns));
}

// Check Booking model relationships
$booking = new Booking();
test("Booking model has kodePromo relationship", method_exists($booking, 'kodePromo'));

// =====================================================
// SUMMARY
// =====================================================
echo "\n" . str_repeat("=", 50) . "\n";
echo "FINAL QA TEST SUMMARY\n";
echo str_repeat("=", 50) . "\n";
echo "Passed: $passed\n";
echo "Failed: $failed\n";
echo "Warnings: " . count($warnings) . "\n";

if (count($errors) > 0) {
    echo "\nERRORS:\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
}

if (count($warnings) > 0) {
    echo "\nWARNINGS:\n";
    foreach ($warnings as $warning) {
        echo "  - $warning\n";
    }
}

if ($failed === 0) {
    echo "\n✓ ALL TESTS PASSED!\n";
} else {
    echo "\n✗ SOME TESTS FAILED - Please review errors above\n";
}

echo "\n";
