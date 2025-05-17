<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang dimasukkan salah.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Berhasil logout dari sistem admin.');
    }

    public function dashboard()
    {
        // Get total users
        $userCount = \App\Models\User::count();

        // Get users who are merchants
        $merchantCount = \App\Models\Merchant::count();

        // Get regular users (users who are not merchants)
        $regularUserCount = $userCount - $merchantCount;

        // Get only confirmed transactions
        $transactionCount = \App\Models\Pembayaran::where('status_pembayaran', 'Selesai')->count();
        $totalAmount = \App\Models\Pembayaran::where('status_pembayaran', 'Selesai')->sum('amount');

        // Get pending merchants for verification
        $pendingMerchants = \App\Models\Merchant::with('user')
            ->where('verification_status', 'pending')
            ->latest()
            ->get();

        return view('admin.dashboard', compact(
            'userCount',
            'merchantCount',
            'regularUserCount',
            'transactionCount',
            'totalAmount',
            'pendingMerchants'
        ));
    }

    public function users(Request $request)
    {
        $sortField = $request->query('sort', 'id');
        $sortDirection = $request->query('direction', 'asc');

        $users = \App\Models\User::orderBy($sortField, $sortDirection)->get();

        return view('admin.users', compact('users', 'sortField', 'sortDirection'));
    }

    public function merchants(Request $request)
    {
        $sortField = $request->query('sort', 'id');
        $sortDirection = $request->query('direction', 'asc');

        $merchants = \App\Models\Merchant::with('user')
            ->when($sortField === 'pemilik', function ($query) use ($sortDirection) {
                return $query->join('users', 'merchant.id_user', '=', 'users.id')
                    ->orderBy('users.nama', $sortDirection)
                    ->select('merchant.*');
            }, function ($query) use ($sortField, $sortDirection) {
                return $query->orderBy($sortField, $sortDirection);
            })
            ->get();

        return view('admin.merchants', compact('merchants', 'sortField', 'sortDirection'));
    }

    public function transactions(Request $request)
    {
        // Filter by status if provided
        $statusFilter = $request->query('status');
        $transactionsQuery = \App\Models\Pembayaran::query();
        
        if ($statusFilter) {
            $transactionsQuery->where('status_pembayaran', $statusFilter);
        }
        
        // Get total transaction amount (including unique codes)
        $totalAmount = \App\Models\Pembayaran::sum('amount');
        $transactionCount = \App\Models\Pembayaran::count();

        // Get min and max transaction amounts
        $minAmount = 0;
        $maxAmount = \App\Models\Pembayaran::max('amount');

        // Hitung total transaksi pending dan selesai
        $paymentPendingCount = \App\Models\Pembayaran::where('status_pembayaran', 'Pending')->count();
        $paymentConfirmedCount = \App\Models\Pembayaran::where('status_pembayaran', 'Selesai')->count();
        $paymentRejectedCount = \App\Models\Pembayaran::where('status_pembayaran', 'Dibatalkan')->count();

        // Hitung total amount pending dan selesai
        $paymentPendingAmount = \App\Models\Pembayaran::where('status_pembayaran', 'Pending')
            ->sum('amount');
        $paymentConfirmedAmount = \App\Models\Pembayaran::where('status_pembayaran', 'Selesai')
            ->sum('amount');

        // Get range interval from request or use default
        $rangeInterval = $request->interval ? (int)$request->interval : 15000;
        if ($rangeInterval <= 0) {
            $rangeInterval = 1; // Prevent division by zero
        }

        // Calculate number of ranges needed
        $numberOfRanges = ($rangeInterval > 0) ? ceil(($maxAmount - $minAmount) / $rangeInterval) : 0;

        // Generate ranges dynamically
        $ranges = [];
        for ($i = 0; $i < $numberOfRanges; $i++) {
            $min = $minAmount + ($i * $rangeInterval);
            $max = $min + $rangeInterval;

            // For the last range, ensure it includes the max amount
            if ($i === $numberOfRanges - 1) {
                $max = $maxAmount + 1; // Add 1 to include the max amount
            }

            $ranges[] = [
                'min' => $min,
                'max' => $max,
                'label' => $i === $numberOfRanges - 1
                    ? sprintf('> Rp %s', number_format($min, 0, ',', '.'))
                    : sprintf('Rp %s - Rp %s', number_format($min, 0, ',', '.'), number_format($max, 0, ',', '.'))
            ];
        }

        // Count transactions for each range
        $rangeData = collect($ranges)->map(function ($range) {
            return [
                'label' => $range['label'],
                'count' => \App\Models\Pembayaran::where('amount', '>=', $range['min'])->where('amount', '<', $range['max'])->count()
            ];
        });

        // Get transactions with related data
        $transactions = $transactionsQuery->with(['booking.user', 'booking.merchant', 'booking.layanan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.transactions', compact(
            'totalAmount',
            'transactionCount',
            'rangeData',
            'transactions',
            'rangeInterval',
            'minAmount',
            'maxAmount',
            'paymentPendingCount',
            'paymentConfirmedCount',
            'paymentPendingAmount',
            'paymentConfirmedAmount',
            'paymentRejectedCount',
            'statusFilter'
        ));
    }

    public function approveMerchant($id)
    {
        $merchant = \App\Models\Merchant::findOrFail($id);

        if ($merchant->verification_status !== 'pending') {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Merchant sudah diverifikasi atau ditolak.'], 400);
            }
            return back()->with('error', 'Merchant sudah diverifikasi atau ditolak.');
        }

        $merchant->update([
            'verification_status' => 'approved',
            'verified_at' => now(),
            'rejection_reason' => null
        ]);

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Merchant berhasil disetujui.']);
        }
        return back()->with('success', 'Merchant berhasil disetujui.');
    }

    public function rejectMerchant(Request $request, $id)
    {
        $validator = validator($request->all(), [
            'rejection_reason' => 'required|string|max:255'
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi',
            'rejection_reason.max' => 'Alasan penolakan maksimal 255 karakter'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $merchant = \App\Models\Merchant::findOrFail($id);

        if ($merchant->verification_status !== 'pending') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Merchant sudah diverifikasi atau ditolak.'], 400);
            }
            return back()->with('error', 'Merchant sudah diverifikasi atau ditolak.');
        }

        $merchant->update([
            'verification_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'verified_at' => now()
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Merchant berhasil ditolak.']);
        }
        return back()->with('success', 'Merchant berhasil ditolak.');
    }
}
