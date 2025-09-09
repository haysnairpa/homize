# Homize Platform - Comprehensive Technical Documentation

## Table of Contents
1. [System Overview](#system-overview)
2. [Architecture & Technology Stack](#architecture--technology-stack)
3. [Database Schema](#database-schema)
4. [Authentication & Authorization](#authentication--authorization)
5. [User Flows & Journeys](#user-flows--journeys)
6. [Frontend Architecture](#frontend-architecture)
7. [Backend API Structure](#backend-api-structure)
8. [Payment Integration](#payment-integration)
9. [File Management](#file-management)
10. [Security Implementation](#security-implementation)
11. [Performance & Optimization](#performance--optimization)
12. [Deployment & Infrastructure](#deployment--infrastructure)

## System Overview

Homize is a comprehensive home services marketplace platform built with Laravel 12, connecting customers with verified service providers (merchants) across multiple categories including household services, repairs, education, health & beauty, creative services, event organization, and equipment rental.

### Core Features
- **Multi-role Authentication**: Customer, Merchant, Admin with distinct dashboards
- **Service Marketplace**: Browse, search, and book services with real-time availability
- **Payment Processing**: Integrated Xendit payment gateway with multiple payment methods
- **Review System**: Customer ratings and reviews with media upload support
- **Merchant Management**: Complete merchant onboarding, verification, and service management
- **Admin Panel**: Comprehensive system oversight with analytics and user management
- **Progressive Web App**: Offline support with service worker implementation

## Architecture & Technology Stack

### Backend Framework
- **Laravel 12** with PHP 8.2+
- **Laravel Jetstream** for authentication scaffolding
- **Laravel Livewire** for reactive components
- **Laravel Sanctum** for API authentication
- **Laravel Socialite** for OAuth integration (Google)

### Frontend Technologies
- **Blade Templates** with component-based architecture
- **Alpine.js 3.14.9** for reactive JavaScript interactions
- **Tailwind CSS 3.4** for utility-first styling
- **Vite 6.0** for asset bundling and hot module replacement
- **Chart.js** for analytics visualization

### Database & Storage
- **MySQL** primary database
- **Cloudinary** for media storage and optimization
- **Redis** for caching and session management (production)

### External Integrations
- **Xendit** payment gateway
- **Google OAuth** for social authentication
- **Google Maps API** for location services
- **Email services** for notifications

## Database Schema

### Core Tables

#### Users Table
```sql
- id (primary key)
- nama (string) - Full name
- email (unique)
- email_verified_at
- password
- phone
- alamat (text)
- tanggal_lahir (date)
- jenis_kelamin (enum: 'L', 'P')
- profile_photo_path
- google_id (nullable)
- is_admin (boolean, default: false)
- timestamps
```

#### Merchants Table
```sql
- id (primary key)
- user_id (foreign key to users)
- nama_usaha (string)
- alamat_merchant (text)
- nomor_telepon (string)
- email_merchant (string)
- deskripsi_usaha (text)
- kategori_usaha (string)
- media_sosial (json)
- jam_operasional (json)
- profile_url (string)
- verification_status (enum: 'pending', 'approved', 'rejected')
- verification_message (text, nullable)
- balance (decimal, default: 0)
- bank_name (string, nullable)
- bank_account_number (string, nullable)
- bank_account_name (string, nullable)
- timestamps
```

#### Layanan (Services) Table
```sql
- id (primary key)
- merchant_id (foreign key)
- kategori_id (foreign key)
- nama_layanan (string)
- deskripsi_layanan (text)
- harga (decimal)
- durasi (integer)
- tipe_durasi (enum: 'menit', 'jam', 'hari')
- profile_url (string)
- sertifikasi (json, nullable)
- is_active (boolean, default: true)
- timestamps
```

#### Bookings Table
```sql
- id (primary key)
- user_id (foreign key)
- layanan_id (foreign key)
- tanggal_booking (datetime)
- alamat_booking (text)
- latitude (decimal, nullable)
- longitude (decimal, nullable)
- contact_email (string)
- contact_phone (string)
- catatan (text, nullable)
- status (enum: 'pending', 'confirmed', 'in_progress', 'completed', 'cancelled')
- total_harga (decimal)
- payment_status (enum: 'pending', 'paid', 'failed', 'refunded')
- payment_method (string, nullable)
- xendit_invoice_id (string, nullable)
- customer_approved (boolean, default: false)
- timestamps
```

#### Transactions Table
```sql
- id (primary key)
- merchant_id (foreign key)
- booking_id (foreign key, nullable)
- type (enum: 'credit', 'debit')
- amount (decimal)
- description (text)
- status (enum: 'pending', 'completed', 'failed')
- timestamps
```

#### Reviews Table
```sql
- id (primary key)
- booking_id (foreign key)
- user_id (foreign key)
- layanan_id (foreign key)
- rate (integer, 1-5)
- message (text, nullable)
- media_url (string, nullable)
- timestamps
```

## Authentication & Authorization

### Multi-Guard Authentication System

#### User Authentication
- **Guard**: `web` (default)
- **Provider**: `users`
- **Model**: `App\Models\User`
- **Features**: Registration, login, password reset, email verification, Google OAuth

#### Admin Authentication
- **Guard**: `admin`
- **Provider**: `admins`
- **Model**: `App\Models\Admin`
- **Features**: Separate login system, admin-only access

### Middleware Stack

#### Core Middleware
- `AdminMiddleware`: Restricts access to admin routes
- `MerchantMiddleware`: Validates merchant authentication and verification status
- `auth`: Standard Laravel authentication
- `verified`: Email verification requirement

### Role-Based Access Control

#### Customer Permissions
- Browse and search services
- Create bookings and make payments
- Leave reviews and manage wishlist
- View transaction history

#### Merchant Permissions
- Manage service listings
- Process orders and update status
- View analytics and earnings
- Manage withdrawal requests
- Access merchant dashboard

#### Admin Permissions
- User and merchant management
- System oversight and analytics
- Transaction monitoring
- Merchant verification and approval
- Content moderation

## User Flows & Journeys

### Customer Journey

#### 1. Registration & Authentication
```
Guest → Registration Form → Email Verification → Dashboard
     ↘ Google OAuth → Automatic Account Creation → Dashboard
     ↘ Login Form → Dashboard
```

#### 2. Service Discovery & Booking
```
Home Page → Category Browse/Search → Service Details → Booking Form → Payment → Confirmation
         ↘ Filter & Sort → Service List → Service Selection
```

#### 3. Order Management
```
Dashboard → My Bookings → Order Details → Status Tracking → Review Submission
```

### Merchant Journey

#### 1. Merchant Registration
```
Registration → Multi-step Form → Document Upload → Verification Pending → Admin Review → Approval/Rejection
```

#### 2. Service Management
```
Merchant Dashboard → Add Service → Service Details → Pricing → Certification Upload → Publish
                  ↘ Manage Services → Edit/Delete → Status Toggle
```

#### 3. Order Processing
```
New Order Notification → Order Review → Accept/Reject → Status Updates → Completion → Payment Processing
```

#### 4. Financial Management
```
Earnings Dashboard → Balance Overview → Withdrawal Request → Bank Details → Admin Processing → Transfer
```

### Admin Journey

#### 1. System Oversight
```
Admin Dashboard → User Management → Merchant Verification → Transaction Monitoring → System Analytics
```

#### 2. Merchant Verification Process
```
Pending Merchants → Document Review → Background Check → Approval Decision → Notification → Status Update
```

## Frontend Architecture

### Layout System

#### Base Layouts
- **`layouts/app.blade.php`**: Main customer layout with navigation and footer
- **`layouts/guest.blade.php`**: Minimal layout for authentication pages
- **`layouts/merchant.blade.php`**: Merchant dashboard layout
- **`layouts/admin.blade.php`**: Admin panel layout

#### Component Architecture

##### Navigation Components
- **`components/navigation.blade.php`**: Main navigation with search, categories, and user menu
- **`components/footer.blade.php`**: Site footer with company information and links

##### Reusable Components
- **`components/authentication-card.blade.php`**: Authentication form wrapper
- **`components/service-reviews.blade.php`**: Review display with ratings and media
- **`components/category-browse.blade.php`**: Category grid with icons and descriptions
- **`components/main-tabs.blade.php`**: Tabbed interface for services, merchants, and wishlist

##### Modal Components
- **`components/merchant-detail-modal.blade.php`**: Comprehensive merchant information modal
- **`components/modal.blade.php`**: Base modal component with Alpine.js integration

### Alpine.js Integration

#### State Management
```javascript
// Search functionality
window.searchMobile = function() {
    return {
        searchQuery: '',
        searchResults: [],
        showResults: false,
        isLoading: false,
        selectedIndex: -1,
        // Methods for search, navigation, and selection
    }
}

// Main tabs component
function mainTabsComponent() {
    return {
        activeTab: 'services',
        kategori_id: '',
        min_price: '',
        max_price: '',
        sort_by: 'newest',
        isLoading: false,
        // Filter and data loading methods
    }
}
```

#### Dynamic Behaviors
- **Real-time search**: Debounced API calls with keyboard navigation
- **Filter systems**: Category, price range, and sorting with AJAX updates
- **Modal management**: Alpine.js-powered modals with event handling
- **Form interactions**: Dynamic form validation and submission
- **Image lazy loading**: Intersection Observer API implementation

### CSS Architecture

#### Tailwind Configuration
```javascript
// Custom color palette
colors: {
    'homize-blue': '#30A0E0',
    'homize-blue-second': '#1c91c4',
    'homize-orange': '#FFC973',
    'homize-white': '#FFFFFF',
    'homize-gray': '#F4F4F4'
}
```

#### Component Styles
- **Utility-first approach** with Tailwind CSS
- **Custom component classes** for complex UI elements
- **Responsive design** with mobile-first methodology
- **Dark mode support** preparation

### JavaScript Interactions

#### Core Functionality
- **AJAX requests** for dynamic content loading
- **Form handling** with validation and error display
- **Geolocation services** for booking location
- **Chart rendering** with Chart.js for analytics
- **Date/time pickers** with Flatpickr integration

#### Progressive Web App Features
- **Service Worker** registration for offline support
- **Manifest file** for app-like experience
- **Offline detection** with automatic redirects
- **Cache management** for improved performance

## Backend API Structure

### Route Organization

#### Public Routes
```php
// Home and service discovery
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/service/{kategori}', [ServiceController::class, 'index'])->name('service');
Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');

// Search and filtering
Route::get('/api/search', [SearchController::class, 'search']);
Route::post('/home/filter', [HomeController::class, 'filter'])->name('home.filter');
```

#### Authenticated User Routes
```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('bookings', BookingController::class);
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});
```

#### Merchant Routes
```php
Route::middleware(['auth', 'merchant'])->prefix('merchant')->name('merchant.')->group(function () {
    Route::get('/dashboard', [Merchant\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('services', Merchant\LayananController::class);
    Route::resource('orders', Merchant\OrderController::class);
    Route::get('/analytics', [Merchant\AnalyticsController::class, 'index'])->name('analytics');
    Route::resource('penarikan', Merchant\PenarikanController::class);
});
```

#### Admin Routes
```php
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/merchants', [Admin\MerchantController::class, 'index'])->name('merchants');
    Route::post('/merchants/{id}/approve', [AdminController::class, 'approveMerchant'])->name('merchants.approve');
    Route::post('/merchants/{id}/reject', [AdminController::class, 'rejectMerchant'])->name('merchants.reject');
});
```

### Controller Architecture

#### Base Controller Structure
- **Resource controllers** for CRUD operations
- **Service layer pattern** for business logic
- **Repository pattern** for data access
- **Event-driven architecture** for notifications

#### Key Controllers

##### HomeController
- Service discovery and filtering
- Category-based browsing
- Search functionality
- Popular services display

##### BookingController
- Booking creation and management
- Payment processing integration
- Status tracking and updates
- Customer communication

##### Merchant Controllers
- **DashboardController**: Analytics and overview
- **LayananController**: Service management
- **OrderController**: Order processing
- **PenarikanController**: Withdrawal management

##### Admin Controllers
- **AdminController**: System overview and user management
- **MerchantController**: Merchant verification and management
- **TransactionController**: Financial oversight

### API Response Structure

#### Standard Response Format
```json
{
    "success": true,
    "data": {},
    "message": "Operation completed successfully",
    "errors": null
}
```

#### Error Handling
```json
{
    "success": false,
    "data": null,
    "message": "Validation failed",
    "errors": {
        "field_name": ["Error message"]
    }
}
```

## Payment Integration

### Xendit Integration

#### Payment Flow
```
Booking Creation → Xendit Invoice Generation → Payment Page Redirect → Webhook Processing → Status Update
```

#### Supported Payment Methods
- Credit/Debit Cards
- Bank Transfers
- E-wallets (OVO, DANA, LinkAja)
- Virtual Accounts
- Retail Outlets

#### Webhook Handling
```php
// Payment webhook processing
Route::post('/webhooks/xendit', [PaymentController::class, 'handleWebhook']);

// Webhook verification and status updates
public function handleWebhook(Request $request)
{
    // Verify webhook signature
    // Update booking payment status
    // Trigger merchant notification
    // Process merchant balance update
}
```

### Financial Management

#### Merchant Balance System
- Automatic balance updates on successful payments
- Transaction history tracking
- Withdrawal request processing
- Admin approval workflow

#### Transaction Types
- **Credit**: Payment received from customers
- **Debit**: Withdrawals and fees
- **Pending**: Awaiting confirmation
- **Failed**: Failed transactions

## File Management

### Cloudinary Integration

#### Media Upload Flow
```php
// Service for file uploads
class CloudinaryService
{
    public function uploadImage($file, $folder = 'homize')
    {
        return Cloudinary::upload($file->getRealPath(), [
            'folder' => $folder,
            'transformation' => [
                'quality' => 'auto',
                'fetch_format' => 'auto'
            ]
        ]);
    }
}
```

#### File Types and Usage
- **Profile Photos**: User and merchant avatars
- **Service Images**: Service showcase photos
- **Certification Documents**: Merchant credentials
- **Review Media**: Customer review photos
- **System Assets**: Icons, logos, banners

### File Validation
- **Image formats**: JPEG, PNG, WebP
- **File size limits**: 5MB for images, 10MB for documents
- **Security scanning**: Malware detection
- **Automatic optimization**: Compression and format conversion

## Security Implementation

### Authentication Security

#### Password Security
- **Bcrypt hashing** with configurable rounds
- **Password complexity requirements**
- **Rate limiting** on login attempts
- **Account lockout** after failed attempts

#### Session Management
- **Secure session configuration**
- **CSRF protection** on all forms
- **Session regeneration** on authentication
- **Automatic logout** on inactivity

### Data Protection

#### Input Validation
- **Laravel validation rules** for all inputs
- **SQL injection prevention** with Eloquent ORM
- **XSS protection** with Blade templating
- **File upload validation** and sanitization

#### API Security
- **Rate limiting** on API endpoints
- **CORS configuration** for cross-origin requests
- **API token authentication** with Sanctum
- **Request/response logging** for audit trails

### Infrastructure Security

#### Environment Configuration
- **Environment variables** for sensitive data
- **SSL/TLS encryption** for data transmission
- **Database connection encryption**
- **Secure headers** implementation

## Performance & Optimization

### Database Optimization

#### Indexing Strategy
```sql
-- Key indexes for performance
INDEX idx_bookings_user_status (user_id, status)
INDEX idx_layanan_kategori_active (kategori_id, is_active)
INDEX idx_merchants_verification (verification_status)
INDEX idx_transactions_merchant_type (merchant_id, type)
```

#### Query Optimization
- **Eager loading** for related models
- **Query caching** for frequently accessed data
- **Database connection pooling**
- **Pagination** for large datasets

### Frontend Performance

#### Asset Optimization
- **Vite bundling** for JavaScript and CSS
- **Code splitting** for reduced initial load
- **Image lazy loading** with Intersection Observer
- **CDN integration** for static assets

#### Caching Strategy
- **Browser caching** with appropriate headers
- **Service worker caching** for offline support
- **API response caching** with Redis
- **View caching** for static content

### Application Performance

#### Laravel Optimizations
- **Route caching** for production
- **Config caching** for faster bootstrap
- **View compilation** caching
- **Autoloader optimization**

#### Monitoring and Analytics
- **Application performance monitoring**
- **Database query analysis**
- **Error tracking and logging**
- **User behavior analytics**

## Deployment & Infrastructure

### Environment Configuration

#### Development Environment
```bash
# Local development setup
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
```

#### Production Environment
```bash
# Production deployment
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Server Requirements

#### Minimum Requirements
- **PHP 8.2+** with required extensions
- **MySQL 8.0+** or MariaDB 10.3+
- **Redis 6.0+** for caching
- **Nginx/Apache** web server
- **SSL certificate** for HTTPS

#### Recommended Specifications
- **CPU**: 2+ cores
- **RAM**: 4GB+ for production
- **Storage**: SSD with 20GB+ free space
- **Bandwidth**: Unlimited or high quota

### Deployment Pipeline

#### CI/CD Process
1. **Code commit** triggers automated testing
2. **Test suite execution** (PHPUnit, feature tests)
3. **Security scanning** and code quality checks
4. **Build process** for assets and dependencies
5. **Deployment** to staging/production environment
6. **Health checks** and monitoring activation

### Monitoring and Maintenance

#### System Monitoring
- **Application uptime** monitoring
- **Database performance** tracking
- **Error rate** and response time monitoring
- **Resource usage** (CPU, memory, disk)

#### Backup Strategy
- **Daily database backups** with retention policy
- **File system backups** for uploaded media
- **Configuration backups** for environment settings
- **Disaster recovery** procedures

---

## Conclusion

This technical documentation provides a comprehensive overview of the Homize platform architecture, implementation details, and operational procedures. The system is designed for scalability, security, and maintainability, with clear separation of concerns and modern development practices.

For specific implementation details or troubleshooting, refer to the individual controller files, model definitions, and configuration files within the codebase.

**Last Updated**: January 2025
**Version**: 1.0
**Maintainer**: Development Team