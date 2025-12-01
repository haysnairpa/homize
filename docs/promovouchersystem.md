# Homize Promo/Voucher System Documentation

> **Complete Technical Documentation for the Promo Code (Kode Promo) System**
> 
> Last Updated: November 2024

---

## Table of Contents

1. [System Overview](#1-system-overview)
2. [Database Schema](#2-database-schema)
3. [File Structure](#3-file-structure)
4. [Models](#4-models)
5. [Services](#5-services)
6. [Controllers](#6-controllers)
7. [Routes](#7-routes)
8. [Views](#8-views)
9. [User Flow](#9-user-flow)
10. [Admin Flow](#10-admin-flow)
11. [Business Logic](#11-business-logic)
12. [API Endpoints](#12-api-endpoints)
13. [Validation Rules](#13-validation-rules)
14. [Error Handling](#14-error-handling)

---

## 1. System Overview

The Promo/Voucher system in Homize allows administrators to create discount codes that users can apply during the booking process. The system supports:

- **Percentage-based discounts** (e.g., 10% off)
- **Fixed amount discounts** (e.g., Rp 50,000 off)
- **Target-specific promos** (all services, specific category, or specific service)
- **Usage limits** (global and per-user)
- **Exclusive promos** (cannot be combined with other promos)
- **Date-based validity periods**
- **Minimum purchase requirements**
- **Maximum discount caps**

### Key Terminology

| Indonesian Term | English Translation | Description |
|----------------|---------------------|-------------|
| `kode_promo` | Promo Code | The discount code table |
| `penggunaan_kode_promo` | Promo Code Usage | Tracks when/how codes are used |
| `tipe_diskon` | Discount Type | percentage or fixed |
| `nilai_diskon` | Discount Value | The discount amount/percentage |
| `batas_penggunaan_global` | Global Usage Limit | Max total uses |
| `batas_penggunaan_per_user` | Per-User Usage Limit | Max uses per user |
| `is_exclusive` | Is Exclusive | Cannot combine with other promos |
| `status_aktif` | Active Status | Whether promo is enabled |

---

## 2. Database Schema

### 2.1 Table: `kode_promo`

**Migration File:** `database/migrations/2024_01_15_000001_create_kode_promo_table.php`

```sql
CREATE TABLE kode_promo (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(50) UNIQUE NOT NULL COMMENT 'Kode promo unik',
    nama VARCHAR(255) NOT NULL COMMENT 'Nama/deskripsi promo',
    tipe_diskon ENUM('percentage', 'fixed') NOT NULL COMMENT 'Tipe diskon',
    nilai_diskon DECIMAL(15,2) NOT NULL COMMENT 'Nilai diskon (% atau nominal)',
    tanggal_mulai DATETIME NOT NULL COMMENT 'Tanggal mulai berlaku',
    tanggal_berakhir DATETIME NOT NULL COMMENT 'Tanggal berakhir',
    batas_penggunaan_global INT NULL COMMENT 'Batas penggunaan total (null = unlimited)',
    batas_penggunaan_per_user INT DEFAULT 1 COMMENT 'Batas penggunaan per user',
    is_exclusive BOOLEAN DEFAULT FALSE COMMENT 'Promo eksklusif (tidak bisa digabung)',
    target_type ENUM('all', 'category', 'service') DEFAULT 'all' COMMENT 'Target promo',
    target_id BIGINT UNSIGNED NULL COMMENT 'ID target (kategori_id atau layanan_id)',
    status_aktif BOOLEAN DEFAULT TRUE COMMENT 'Status aktif promo',
    deskripsi TEXT NULL COMMENT 'Deskripsi detail promo',
    minimum_pembelian DECIMAL(15,2) NULL COMMENT 'Minimum pembelian',
    maksimum_diskon DECIMAL(15,2) NULL COMMENT 'Maksimum nominal diskon (untuk percentage)',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_kode_status (kode, status_aktif),
    INDEX idx_tanggal (tanggal_mulai, tanggal_berakhir),
    INDEX idx_target (target_type, target_id)
);
```

### 2.2 Table: `penggunaan_kode_promo`

**Migration File:** `database/migrations/2024_01_15_000002_create_penggunaan_kode_promo_table.php`

```sql
CREATE TABLE penggunaan_kode_promo (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_promo_id BIGINT UNSIGNED NOT NULL COMMENT 'ID kode promo yang digunakan',
    user_id BIGINT UNSIGNED NOT NULL COMMENT 'ID user yang menggunakan',
    booking_id BIGINT UNSIGNED NOT NULL COMMENT 'ID booking terkait',
    diskon_amount DECIMAL(15,2) NOT NULL COMMENT 'Nominal diskon yang diberikan',
    original_amount DECIMAL(15,2) NOT NULL COMMENT 'Harga asli sebelum diskon',
    final_amount DECIMAL(15,2) NOT NULL COMMENT 'Harga final setelah diskon',
    tanggal_digunakan DATETIME NOT NULL COMMENT 'Tanggal dan waktu penggunaan',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (kode_promo_id) REFERENCES kode_promo(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES booking(id) ON DELETE CASCADE,
    
    INDEX idx_promo_user (kode_promo_id, user_id),
    INDEX idx_user_date (user_id, tanggal_digunakan),
    INDEX idx_booking (booking_id),
    UNIQUE KEY unique_booking (booking_id)
);
```

### 2.3 Booking Table Modifications

**Migration File:** `database/migrations/2024_01_15_000003_add_promo_fields_to_booking_table.php`

Added columns to `booking` table:

| Column | Type | Description |
|--------|------|-------------|
| `kode_promo_id` | BIGINT UNSIGNED NULL | FK to kode_promo |
| `original_amount` | DECIMAL(15,2) NULL | Original price before discount |
| `diskon_amount` | DECIMAL(15,2) DEFAULT 0 | Discount amount applied |
| `diskon_percentage` | DECIMAL(5,2) DEFAULT 0 | Discount percentage |
| `final_amount` | DECIMAL(15,2) NULL | Final price after discount |

---

## 3. File Structure

```
homize/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AdminPromoController.php      # Admin promo management
│   │       └── BookingController.php         # Uses PromoCodeService
│   │
│   ├── Models/
│   │   ├── KodePromo.php                     # Promo code model
│   │   ├── PenggunaanKodePromo.php           # Usage tracking model
│   │   └── Booking.php                       # Modified with promo fields
│   │
│   └── Services/
│       └── PromoCodeService.php              # Core promo business logic
│
├── database/
│   └── migrations/
│       ├── 2024_01_15_000001_create_kode_promo_table.php
│       ├── 2024_01_15_000002_create_penggunaan_kode_promo_table.php
│       └── 2024_01_15_000003_add_promo_fields_to_booking_table.php
│
├── resources/
│   └── views/
│       ├── admin/
│       │   └── promo/
│       │       ├── index.blade.php           # List all promos
│       │       ├── create.blade.php          # Create new promo
│       │       ├── edit.blade.php            # Edit existing promo
│       │       └── show.blade.php            # View promo details
│       │
│       └── booking/
│           ├── create.blade.php              # Booking form
│           └── components/
│               └── cost-summary.blade.php    # Promo input UI
│
└── routes/
    └── web.php                               # Route definitions
```

---

## 4. Models

### 4.1 KodePromo Model

**File:** `app/Models/KodePromo.php`

#### Fillable Fields
```php
protected $fillable = [
    "kode", "nama", "tipe_diskon", "nilai_diskon",
    "tanggal_mulai", "tanggal_berakhir",
    "batas_penggunaan_global", "batas_penggunaan_per_user",
    "is_exclusive", "target_type", "target_id",
    "status_aktif", "deskripsi",
    "minimum_pembelian", "maksimum_diskon"
];
```

#### Relationships

| Method | Type | Related Model | Description |
|--------|------|---------------|-------------|
| `penggunaan()` | hasMany | PenggunaanKodePromo | All usage records |
| `booking()` | hasMany | Booking | All bookings using this promo |
| `targetKategori()` | belongsTo | Kategori | Target category (if type=category) |
| `targetLayanan()` | belongsTo | Layanan | Target service (if type=service) |

#### Scopes

| Scope | Description | Usage |
|-------|-------------|-------|
| `scopeActive($query)` | Only active promos | `KodePromo::active()` |
| `scopeValid($query)` | Active + within date range | `KodePromo::valid()` |
| `scopeByCode($query, $code)` | Find by code | `KodePromo::byCode('DISKON50')` |
| `scopeExclusive($query)` | Only exclusive promos | `KodePromo::exclusive()` |

#### Business Logic Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `isValid()` | bool | Checks if promo is currently valid |
| `isExpired()` | bool | Checks if promo has expired |
| `hasGlobalUsageLimit()` | bool | Has a global limit set |
| `getGlobalUsageCount()` | int | Current total usage count |
| `isGlobalUsageLimitReached()` | bool | Global limit reached |
| `getUserUsageCount($userId)` | int | Usage count for specific user |
| `isUserUsageLimitReached($userId)` | bool | User limit reached |
| `canBeUsedBy($userId)` | bool | Can this user use the promo |
| `isApplicableToLayanan($layananId)` | bool | Is promo valid for this service |
| `calculateDiscount($originalAmount)` | float | Calculate discount amount |
| `getFormattedDiscount()` | string | Human-readable discount (e.g., "10%" or "Rp 50.000") |
| `getRemainingGlobalUsage()` | int\|null | Remaining global uses (null = unlimited) |
| `getRemainingUserUsage($userId)` | int | Remaining uses for user |
| `getTargetName()` | string | Human-readable target description |

### 4.2 PenggunaanKodePromo Model

**File:** `app/Models/PenggunaanKodePromo.php`

#### Fillable Fields
```php
protected $fillable = [
    "kode_promo_id", "user_id", "booking_id",
    "diskon_amount", "original_amount", "final_amount",
    "tanggal_digunakan"
];
```

#### Relationships

| Method | Type | Related Model |
|--------|------|---------------|
| `kodePromo()` | belongsTo | KodePromo |
| `user()` | belongsTo | User |
| `booking()` | belongsTo | Booking |

#### Scopes

| Scope | Description |
|-------|-------------|
| `scopeByUser($query, $userId)` | Filter by user |
| `scopeByPromo($query, $promoId)` | Filter by promo |
| `scopeInDateRange($query, $start, $end)` | Filter by date range |

### 4.3 Booking Model (Promo-related)

**File:** `app/Models/Booking.php`

#### Promo-related Fields
```php
protected $fillable = [
    // ... other fields ...
    "kode_promo_id",
    "original_amount",
    "diskon_amount",
    "diskon_percentage",
    "final_amount",
];
```

#### Promo Relationships

| Method | Type | Related Model |
|--------|------|---------------|
| `kodePromo()` | belongsTo | KodePromo |
| `penggunaanKodePromo()` | hasOne | PenggunaanKodePromo |

---

## 5. Services

### 5.1 PromoCodeService

**File:** `app/Services/PromoCodeService.php`

This is the **core service** handling all promo code business logic.

#### Methods

##### `validatePromoCode($promoCode, $userId, $layananId, $originalAmount = null)`

Validates if a promo code can be used.

**Parameters:**
- `$promoCode` (string): The promo code to validate
- `$userId` (int): User attempting to use the code
- `$layananId` (int): Service ID the promo will be applied to
- `$originalAmount` (float|null): Original price for minimum purchase check

**Returns:** Array with structure:
```php
[
    'success' => true|false,
    'message' => 'Validation message',
    'data' => [
        'kode_promo' => KodePromo,
        'discount_amount' => float,
        'final_amount' => float,
        'discount_percentage' => float,
        'remaining_global_usage' => int|null,
        'remaining_user_usage' => int
    ]
]
```

**Validation Checks (in order):**
1. Promo code exists
2. Promo is active (`status_aktif = true`)
3. Promo is not expired
4. Promo has started (not future-dated)
5. Global usage limit not reached
6. User usage limit not reached
7. Promo is applicable to the service (target check)
8. Minimum purchase requirement met
9. Exclusive promo conflict check

##### `applyPromoToBooking($promoCode, $userId, $bookingId, $originalAmount)`

Applies a promo code to an existing booking.

**Process:**
1. Validates the promo code
2. Updates booking with promo information
3. Records usage in `penggunaan_kode_promo`
4. Uses database transaction for atomicity

##### `removePromoFromBooking($bookingId)`

Removes a promo code from a booking.

**Process:**
1. Deletes usage record from `penggunaan_kode_promo`
2. Resets booking promo fields to null/0

##### `getUserPromoHistory($userId, $limit = 10)`

Gets a user's promo usage history.

##### `getAvailablePromosForUser($userId, $layananId = null, $amount = null)`

Gets all valid promos available for a user, optionally filtered by service and amount.

##### `getPromoStatistics($promoId = null, $startDate = null, $endDate = null)`

Gets usage statistics for admin dashboard.

**Returns:**
```php
[
    'total_usage' => int,
    'total_discount_given' => float,
    'total_original_amount' => float,
    'total_final_amount' => float,
    'average_discount' => float,
    'unique_users' => int,
    'usage_by_date' => array
]
```

---

## 6. Controllers

### 6.1 AdminPromoController

**File:** `app/Http/Controllers/AdminPromoController.php`

Handles all admin promo management operations.

#### Dependencies
```php
use App\Services\PromoCodeService;
use App\Models\KodePromo;
use App\Models\Kategori;
use App\Models\Layanan;
```

#### Methods

| Method | HTTP | Route | Description |
|--------|------|-------|-------------|
| `index(Request $request)` | GET | `/admin/promo` | List all promos with filters |
| `create()` | GET | `/admin/promo/create` | Show create form |
| `store(Request $request)` | POST | `/admin/promo` | Create new promo |
| `show($id)` | GET | `/admin/promo/{id}` | View promo details |
| `edit($id)` | GET | `/admin/promo/{id}/edit` | Show edit form |
| `update(Request $request, $id)` | PUT | `/admin/promo/{id}` | Update promo |
| `destroy($id)` | DELETE | `/admin/promo/{id}` | Delete promo |
| `toggleStatus($id)` | PATCH | `/admin/promo/{id}/toggle-status` | Toggle active status |
| `getServicesByCategory($categoryId)` | GET | `/admin/promo/services-by-category/{id}` | AJAX: Get services |
| `validatePromo(Request $request)` | POST | `/admin/promo/validate` | AJAX: Validate promo |

### 6.2 BookingController (Promo-related)

**File:** `app/Http/Controllers/BookingController.php`

#### Promo-related Methods

| Method | HTTP | Route | Description |
|--------|------|-------|-------------|
| `store(Request $request)` | POST | `/booking` | Creates booking with optional promo |
| `validatePromo(Request $request)` | POST | `/booking/validate-promo` | AJAX: Validate promo during booking |
| `removePromo(Request $request)` | POST | `/booking/remove-promo` | AJAX: Remove promo from session |

#### Promo Integration in `store()`

```php
// 1. Initialize promo variables
$originalAmount = $tarifLayanan->harga;
$finalAmount = $originalAmount;
$diskonAmount = 0;
$diskonPercentage = 0;
$kodePromoId = null;

// 2. Validate promo if provided
if ($request->filled('kode_promo')) {
    $promoValidation = $this->promoService->validatePromoCode(
        $request->kode_promo,
        Auth::id(),
        $request->id_layanan,
        $originalAmount
    );
    
    if (!$promoValidation['valid']) {
        // Return error
    }
    
    // Apply discount
    $discountResult = $this->promoService->calculateDiscount(
        $promoValidation['promo'],
        $originalAmount
    );
    
    $kodePromoId = $promoValidation['promo']->id;
    $diskonAmount = $discountResult['discount_amount'];
    $diskonPercentage = $discountResult['discount_percentage'];
    $finalAmount = $discountResult['final_amount'];
}

// 3. Create booking with promo data
$booking = Booking::create([
    // ... other fields ...
    'kode_promo_id' => $kodePromoId,
    'original_amount' => $originalAmount,
    'diskon_amount' => $diskonAmount,
    'diskon_percentage' => $diskonPercentage,
    'final_amount' => $finalAmount,
]);

// 4. Record promo usage
if ($kodePromoId && $promoValidation) {
    $this->promoService->recordUsage(
        $promoValidation['promo'],
        Auth::id(),
        $booking->id,
        $originalAmount,
        $diskonAmount,
        $finalAmount
    );
}
```

---

## 7. Routes

**File:** `routes/web.php`

### Admin Promo Routes (Protected by AdminMiddleware)

```php
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {
        // Resource routes (index, create, store, show, edit, update, destroy)
        Route::resource('promo', AdminPromoController::class);
        
        // Additional routes
        Route::get('/promo/services-by-category/{categoryId}', 
            [AdminPromoController::class, 'getServicesByCategory'])
            ->name('promo.services-by-category');
            
        Route::patch('/promo/{id}/toggle-status', 
            [AdminPromoController::class, 'toggleStatus'])
            ->name('promo.toggle-status');
            
        Route::post('/promo/validate', 
            [AdminPromoController::class, 'validatePromo'])
            ->name('promo.validate');
    });
});
```

### Route Summary Table

| Route Name | Method | URI | Controller@Method |
|------------|--------|-----|-------------------|
| `admin.promo.index` | GET | `/admin/promo` | `AdminPromoController@index` |
| `admin.promo.create` | GET | `/admin/promo/create` | `AdminPromoController@create` |
| `admin.promo.store` | POST | `/admin/promo` | `AdminPromoController@store` |
| `admin.promo.show` | GET | `/admin/promo/{promo}` | `AdminPromoController@show` |
| `admin.promo.edit` | GET | `/admin/promo/{promo}/edit` | `AdminPromoController@edit` |
| `admin.promo.update` | PUT/PATCH | `/admin/promo/{promo}` | `AdminPromoController@update` |
| `admin.promo.destroy` | DELETE | `/admin/promo/{promo}` | `AdminPromoController@destroy` |
| `admin.promo.toggle-status` | PATCH | `/admin/promo/{id}/toggle-status` | `AdminPromoController@toggleStatus` |
| `admin.promo.services-by-category` | GET | `/admin/promo/services-by-category/{categoryId}` | `AdminPromoController@getServicesByCategory` |
| `admin.promo.validate` | POST | `/admin/promo/validate` | `AdminPromoController@validatePromo` |

### Booking Promo Routes (Protected by Auth)

```php
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::post('/booking/validate-promo', [BookingController::class, 'validatePromo'])
        ->name('booking.validate-promo');
    Route::post('/booking/remove-promo', [BookingController::class, 'removePromo'])
        ->name('booking.remove-promo');
});
```

---

## 8. Views

### 8.1 Admin Views

#### `admin/promo/index.blade.php`
- **Purpose:** List all promo codes with statistics
- **Features:**
  - Statistics cards (Total, Active, Expired, Exclusive)
  - Filter by status, discount type, target type
  - Sortable table
  - Pagination
  - Toggle status button
  - Delete with confirmation modal

#### `admin/promo/create.blade.php`
- **Purpose:** Create new promo code
- **Form Fields:**
  - Kode Promo (unique code)
  - Nama Promo (display name)
  - Tipe Diskon (percentage/fixed)
  - Nilai Diskon (discount value)
  - Tanggal Mulai/Berakhir (date range)
  - Batas Penggunaan Global/Per User
  - Target Type (all/category/service)
  - Target ID (dynamic based on type)
  - Is Exclusive checkbox
  - Status Aktif checkbox

#### `admin/promo/edit.blade.php`
- **Purpose:** Edit existing promo code
- **Additional Features:**
  - Shows current usage statistics
  - Pre-fills all existing values

#### `admin/promo/show.blade.php`
- **Purpose:** View promo details and statistics
- **Sections:**
  - Basic Information
  - Discount Information
  - Target Information
  - Usage & Date Information
  - Usage Statistics (if available)

### 8.2 Booking Views

#### `booking/components/cost-summary.blade.php`
- **Purpose:** Promo code input during booking
- **Features:**
  - Promo code input field
  - Apply/Remove buttons
  - Real-time validation via AJAX
  - Success/error message display
  - Dynamic price update

---

## 9. User Flow

### 9.1 Applying Promo During Booking

```
┌─────────────────────────────────────────────────────────────────┐
│                     USER BOOKING FLOW                            │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  1. User selects a service and goes to booking page             │
│     Route: GET /booking/{id}                                    │
│     Controller: BookingController@create                        │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  2. User fills booking form, sees cost summary                  │
│     View: booking/create.blade.php                              │
│     Component: booking/components/cost-summary.blade.php        │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  3. User enters promo code and clicks "Terapkan"                │
│     AJAX: POST /booking/validate-promo                          │
│     Controller: BookingController@validatePromo                 │
└─────────────────────────────────────────────────────────────────┘
                              │
              ┌───────────────┴───────────────┐
              ▼                               ▼
┌─────────────────────────┐     ┌─────────────────────────┐
│  Valid Promo            │     │  Invalid Promo          │
│  - Show success message │     │  - Show error message   │
│  - Update prices        │     │  - Keep original price  │
│  - Disable input        │     │  - Allow retry          │
└─────────────────────────┘     └─────────────────────────┘
              │
              ▼
┌─────────────────────────────────────────────────────────────────┐
│  4. User submits booking form                                   │
│     POST /booking                                               │
│     Controller: BookingController@store                         │
│     - Validates promo again (server-side)                       │
│     - Creates booking with promo data                           │
│     - Records promo usage                                       │
│     - Creates payment record with discounted amount             │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  5. Redirect to payment page                                    │
│     Route: GET /pembayaran/{id}                                 │
│     Payment amount = final_amount (after discount)              │
└─────────────────────────────────────────────────────────────────┘
```

### 9.2 Promo Validation Sequence

```
User Input          BookingController        PromoCodeService           KodePromo Model
    │                      │                        │                        │
    │  Enter code          │                        │                        │
    │─────────────────────>│                        │                        │
    │                      │  validatePromoCode()   │                        │
    │                      │───────────────────────>│                        │
    │                      │                        │  byCode($code)         │
    │                      │                        │───────────────────────>│
    │                      │                        │<───────────────────────│
    │                      │                        │                        │
    │                      │                        │  isValid()             │
    │                      │                        │───────────────────────>│
    │                      │                        │<───────────────────────│
    │                      │                        │                        │
    │                      │                        │  isGlobalUsageLimitReached()
    │                      │                        │───────────────────────>│
    │                      │                        │<───────────────────────│
    │                      │                        │                        │
    │                      │                        │  isUserUsageLimitReached($userId)
    │                      │                        │───────────────────────>│
    │                      │                        │<───────────────────────│
    │                      │                        │                        │
    │                      │                        │  isApplicableToLayanan($layananId)
    │                      │                        │───────────────────────>│
    │                      │                        │<───────────────────────│
    │                      │                        │                        │
    │                      │                        │  calculateDiscount($amount)
    │                      │                        │───────────────────────>│
    │                      │                        │<───────────────────────│
    │                      │<───────────────────────│                        │
    │  Response            │                        │                        │
    │<─────────────────────│                        │                        │
```

---

## 10. Admin Flow

### 10.1 Creating a Promo Code

```
┌─────────────────────────────────────────────────────────────────┐
│                     ADMIN CREATE PROMO FLOW                      │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  1. Admin navigates to promo list                               │
│     Route: GET /admin/promo                                     │
│     View: admin/promo/index.blade.php                           │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  2. Admin clicks "Tambah Kode Promo"                            │
│     Route: GET /admin/promo/create                              │
│     View: admin/promo/create.blade.php                          │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  3. Admin fills form:                                           │
│     - Kode: "NEWYEAR2024"                                       │
│     - Nama: "Promo Tahun Baru"                                  │
│     - Tipe: percentage                                          │
│     - Nilai: 20                                                 │
│     - Tanggal: 2024-01-01 to 2024-01-31                        │
│     - Batas Global: 100                                         │
│     - Batas Per User: 1                                         │
│     - Target: all                                               │
│     - Minimum Pembelian: 100000                                 │
│     - Maksimum Diskon: 50000                                    │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  4. Admin submits form                                          │
│     Route: POST /admin/promo                                    │
│     Controller: AdminPromoController@store                      │
│     - Validates all fields                                      │
│     - Creates KodePromo record                                  │
│     - Redirects to index with success message                   │
└─────────────────────────────────────────────────────────────────┘
```

### 10.2 Managing Existing Promos

| Action | Route | Description |
|--------|-------|-------------|
| View List | `GET /admin/promo` | See all promos with stats |
| View Details | `GET /admin/promo/{id}` | See promo details and usage stats |
| Edit | `GET /admin/promo/{id}/edit` | Modify promo settings |
| Toggle Status | `PATCH /admin/promo/{id}/toggle-status` | Enable/disable promo |
| Delete | `DELETE /admin/promo/{id}` | Remove promo (only if unused) |

---

## 11. Business Logic

### 11.1 Discount Calculation

```php
public function calculateDiscount($originalAmount)
{
    // Check minimum purchase requirement
    if ($this->minimum_pembelian && $originalAmount < $this->minimum_pembelian) {
        return 0;
    }

    $discount = 0;
    
    if ($this->tipe_diskon === 'percentage') {
        $discount = ($originalAmount * $this->nilai_diskon) / 100;
        
        // Apply maximum discount limit if set
        if ($this->maksimum_diskon && $discount > $this->maksimum_diskon) {
            $discount = $this->maksimum_diskon;
        }
    } else {
        // Fixed discount
        $discount = $this->nilai_diskon;
    }
    
    // Ensure discount doesn't exceed original amount
    return min($discount, $originalAmount);
}
```

### 11.2 Target Applicability Check

```php
public function isApplicableToLayanan($layananId)
{
    // All services
    if ($this->target_type === 'all') {
        return true;
    }
    
    // Specific service
    if ($this->target_type === 'service') {
        return $this->target_id == $layananId;
    }
    
    // Category - check if service belongs to target category
    if ($this->target_type === 'category') {
        $layanan = Layanan::find($layananId);
        if ($layanan && $layanan->subKategori) {
            return $layanan->subKategori->id_kategori == $this->target_id;
        }
    }
    
    return false;
}
```

### 11.3 Exclusive Promo Handling

Exclusive promos cannot be combined with other promos. The system checks:

```php
private function userHasActiveExclusivePromo($userId, $excludePromoId = null)
{
    $query = PenggunaanKodePromo::byUser($userId)
        ->whereHas('kodePromo', function($q) {
            $q->exclusive()->valid();
        })
        ->whereHas('booking', function($q) {
            $q->whereIn('status_proses', ['Pending', 'Dikonfirmasi', 'Sedang diproses']);
        });

    if ($excludePromoId) {
        $query->where('kode_promo_id', '!=', $excludePromoId);
    }

    return $query->exists();
}
```

---

## 12. API Endpoints

### 12.1 Booking Promo Validation

**Endpoint:** `POST /booking/validate-promo`

**Request:**
```json
{
    "kode_promo": "DISKON50",
    "id_layanan": 123,
    "amount": 500000
}
```

**Success Response:**
```json
{
    "valid": true,
    "message": "Kode promo valid!",
    "promo": {
        "nama": "Diskon 50%",
        "tipe_diskon": "percentage",
        "nilai_diskon": 50,
        "is_exclusive": false
    },
    "discount": {
        "original_amount": 500000,
        "discount_amount": 250000,
        "discount_percentage": 50,
        "final_amount": 250000
    }
}
```

**Error Response:**
```json
{
    "valid": false,
    "message": "Kode promo sudah kadaluarsa."
}
```

### 12.2 Admin Services by Category

**Endpoint:** `GET /admin/promo/services-by-category/{categoryId}`

**Response:**
```json
{
    "success": true,
    "services": [
        {
            "id": 1,
            "nama": "Layanan A",
            "merchant": { "nama_usaha": "Merchant X" }
        }
    ]
}
```

---

## 13. Validation Rules

### 13.1 Create Promo Validation

```php
$validator = Validator::make($request->all(), [
    'kode' => 'required|string|max:50|unique:kode_promo,kode',
    'nama' => 'required|string|max:255',
    'tipe_diskon' => 'required|in:percentage,fixed',
    'nilai_diskon' => 'required|numeric|min:0',
    'tanggal_mulai' => 'required|date|after_or_equal:today',
    'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
    'batas_penggunaan_global' => 'nullable|integer|min:1',
    'batas_penggunaan_per_user' => 'required|integer|min:1',
    'is_exclusive' => 'boolean',
    'target_type' => 'required|in:all,category,service',
    'target_id' => 'nullable|integer',
    'deskripsi' => 'nullable|string',
    'minimum_pembelian' => 'nullable|numeric|min:0',
    'maksimum_diskon' => 'nullable|numeric|min:0',
    'status_aktif' => 'boolean'
]);
```

### 13.2 Additional Validation Rules

- Percentage discount cannot exceed 100%
- `target_id` is required when `target_type` is 'category' or 'service'
- `target_id` must exist in the respective table (kategori or layanan)

---

## 14. Error Handling

### 14.1 Validation Error Messages (Indonesian)

| Field | Error | Message |
|-------|-------|---------|
| kode | required | Kode promo wajib diisi |
| kode | unique | Kode promo sudah digunakan |
| nama | required | Nama promo wajib diisi |
| tipe_diskon | required | Tipe diskon wajib dipilih |
| nilai_diskon | required | Nilai diskon wajib diisi |
| nilai_diskon | min | Nilai diskon tidak boleh negatif |
| nilai_diskon | max (percentage) | Persentase diskon tidak boleh lebih dari 100% |
| tanggal_mulai | required | Tanggal mulai wajib diisi |
| tanggal_mulai | after_or_equal | Tanggal mulai tidak boleh kurang dari hari ini |
| tanggal_berakhir | required | Tanggal berakhir wajib diisi |
| tanggal_berakhir | after | Tanggal berakhir harus setelah tanggal mulai |
| batas_penggunaan_per_user | required | Batas penggunaan per user wajib diisi |
| target_type | required | Target promo wajib dipilih |
| target_id | required (conditional) | Target ID wajib diisi untuk tipe target yang dipilih |

### 14.2 Runtime Error Messages

| Scenario | Message |
|----------|---------|
| Code not found | Kode promo tidak ditemukan |
| Promo inactive | Kode promo tidak aktif |
| Promo expired | Kode promo sudah kadaluarsa |
| Not yet valid | Kode promo belum berlaku |
| Global limit reached | Kode promo sudah mencapai batas penggunaan maksimal |
| User limit reached | Anda sudah mencapai batas penggunaan kode promo ini |
| Not applicable | Kode promo tidak berlaku untuk layanan ini |
| Min purchase not met | Minimum pembelian untuk kode promo ini adalah Rp X |
| Exclusive conflict | Anda sudah menggunakan kode promo eksklusif lain |
| Delete with usage | Tidak dapat menghapus kode promo yang sudah pernah digunakan |

---

## Appendix: Quick Reference

### Database Tables
- `kode_promo` - Main promo codes table
- `penggunaan_kode_promo` - Usage tracking
- `booking` - Modified with promo fields

### Key Files
- `app/Services/PromoCodeService.php` - Core business logic
- `app/Http/Controllers/AdminPromoController.php` - Admin management
- `app/Http/Controllers/BookingController.php` - User booking integration
- `app/Models/KodePromo.php` - Promo model
- `app/Models/PenggunaanKodePromo.php` - Usage model

### Main Routes
- Admin: `/admin/promo/*`
- User: `/booking/validate-promo`, `/booking/remove-promo`

### Views
- Admin: `resources/views/admin/promo/*`
- User: `resources/views/booking/components/cost-summary.blade.php`
