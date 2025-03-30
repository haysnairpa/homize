<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        
        // Get total transactions count
        $transactionCount = \App\Models\Pembayaran::count();

        // Get total transaction amount
        $totalAmount = \App\Models\Pembayaran::sum('amount');

        return view('admin.dashboard', compact(
            'userCount', 
            'merchantCount', 
            'regularUserCount',
            'transactionCount',
            'totalAmount'
        ));
    }

    public function users(Request $request)
    {
        $sortField = $request->query('sort', 'id');
        $sortDirection = $request->query('direction', 'asc');

        $users = \App\Models\User::whereNotIn('id', function($query) {
            $query->select('id_user')
                ->from('merchant');
        })
        ->orderBy($sortField, $sortDirection)
        ->get();

        return view('admin.users', compact('users', 'sortField', 'sortDirection'));
    }

    public function merchants(Request $request)
    {
        $sortField = $request->query('sort', 'id');
        $sortDirection = $request->query('direction', 'asc');

        $merchants = \App\Models\Merchant::with('user')
            ->when($sortField === 'pemilik', function($query) use ($sortDirection) {
                return $query->join('users', 'merchant.id_user', '=', 'users.id')
                            ->orderBy('users.nama', $sortDirection)
                            ->select('merchant.*');
            }, function($query) use ($sortField, $sortDirection) {
                return $query->orderBy($sortField, $sortDirection);
            })
            ->get();

        return view('admin.merchants', compact('merchants', 'sortField', 'sortDirection'));
    }

    public function transactions(Request $request)
    {
        // Get total transaction amount
        $totalAmount = \App\Models\Pembayaran::sum('amount');
        $transactionCount = \App\Models\Pembayaran::count();

        // Get min and max transaction amounts
        $minAmount = 0;
        $maxAmount = \App\Models\Pembayaran::max('amount');

        // Get range interval from request or use default
        $rangeInterval = $request->interval ? (int)$request->interval : 15000;

        // Calculate number of ranges needed
        $numberOfRanges = ceil(($maxAmount - $minAmount) / $rangeInterval);
        
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
        $rangeData = collect($ranges)->map(function($range) {
            return [
                'label' => $range['label'],
                'count' => \App\Models\Pembayaran::where('amount', '>=', $range['min'])
                                               ->where('amount', '<', $range['max'])
                                               ->count()
            ];
        });

        // Get recent transactions
        $transactions = \App\Models\Pembayaran::with(['booking.user', 'booking.merchant'])
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(10);

        return view('admin.transactions', compact(
            'totalAmount',
            'transactionCount',
            'rangeData',
            'transactions',
            'rangeInterval',
            'minAmount',
            'maxAmount'
        ));
    }
}