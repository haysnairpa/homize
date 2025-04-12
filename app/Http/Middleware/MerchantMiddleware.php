<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MerchantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $merchant = Auth::user()->merchant;
        if (!$merchant) {
            return redirect()->route('merchant.register.step1')
                ->with('error', 'Anda harus mendaftar sebagai merchant terlebih dahulu');
        }

        if ($merchant->verification_status !== 'approved') {
            return redirect()->route('merchant.verification-status');
        }

        return $next($request);
    }
} 