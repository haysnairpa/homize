<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function create($id)
    {
        $booking = Booking::with(['layanan', 'merchant'])
            ->where('id', $id)
            ->where('id_user', Auth::id())
            ->first();
            
        if (!$booking) {
            return redirect()->route('dashboard')->with('error', 'Pesanan tidak ditemukan');
        }
        
        // Cek apakah status pesanan sudah selesai
        if ($booking->status_proses !== 'Selesai') {
            return redirect()->route('dashboard')->with('error', 'Anda hanya dapat memberikan rating untuk pesanan yang telah selesai');
        }
        
        // Cek apakah sudah pernah memberikan rating
        $existingRating = Rating::where('id_user', Auth::id())
            ->where('id_layanan', $booking->id_layanan)
            ->first();
            
        if ($existingRating) {
            return redirect()->route('dashboard')->with('error', 'Anda sudah memberikan rating untuk layanan ini');
        }
        
        return view('user.rating', compact('booking'));
    }
    
    public function store(Request $request, $id)
    {
        $booking = Booking::with(['layanan'])
            ->where('id', $id)
            ->where('id_user', Auth::id())
            ->first();
            
        if (!$booking) {
            return redirect()->route('dashboard')->with('error', 'Pesanan tidak ditemukan');
        }
        
        $validated = $request->validate([
            'rate' => 'required|integer|min:1|max:5',
            'message' => 'nullable|string|max:500',
            'media_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $mediaPath = null;
        if ($request->hasFile('media_url')) {
            $mediaPath = $request->file('media_url')->store('rating-images', 'public');
        }
        
        Rating::create([
            'id_user' => Auth::id(),
            'id_layanan' => $booking->id_layanan,
            'rate' => $validated['rate'],
            'message' => $validated['message'] ?? null,
            'media_url' => $mediaPath,
        ]);
        
        return redirect()->route('dashboard')->with('success', 'Terima kasih atas ulasan Anda');
    }
} 