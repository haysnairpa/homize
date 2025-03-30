<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Merchant;
use Symfony\Component\HttpFoundation\Response;

class PreventMerchantReregistration
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $merchant = Merchant::where('id_user', Auth::id())->first();
            
            if ($merchant) {
                // Jika request ke dashboard merchant, biarkan lanjut
                if ($request->routeIs('merchant.dashboard')) {
                    return $next($request);
                }
                
                return redirect()->route('merchant.dashboard')
                    ->with('info', 'Anda sudah terdaftar sebagai merchant');
            }
        }

        return $next($request);
    }
} 