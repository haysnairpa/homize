<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\SubKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();
        $pesananAktif = DB::select("SELECT COUNT(*) as total FROM booking b WHERE b.id_merchant = ? AND b.status_proses IN ('Dikonfirmasi', 'Sedang diproses')", [$merchant->id]);
        $pendapatan = DB::select("SELECT SUM(p.amount) as total FROM booking b JOIN pembayaran p ON p.id_booking = b.id WHERE b.id_merchant = ? AND p.status_pembayaran = 'Selesai' AND MONTH(p.payment_date) = MONTH(CURRENT_DATE()) AND YEAR(p.payment_date) = YEAR(CURRENT_DATE())", [$merchant->id]);
        $pelanggan = DB::select("SELECT COUNT(DISTINCT b.id_user) as total FROM booking b WHERE b.id_merchant = ? AND MONTH(b.created_at) = MONTH(CURRENT_DATE()) AND YEAR(b.created_at) = YEAR(CURRENT_DATE())", [$merchant->id]);
        $rating = DB::select("SELECT COALESCE(AVG(r.rate), 0) as avg_rating FROM rating r JOIN layanan l ON r.id_layanan = l.id WHERE l.id_merchant = ?", [$merchant->id]);
        $recentOrders = DB::select("SELECT b.id, u.nama as nama_user, l.nama_layanan, b.status_proses, p.status_pembayaran, b.tanggal_booking, p.amount, bs.waktu_mulai, bs.waktu_selesai FROM booking b JOIN users u ON u.id = b.id_user JOIN layanan l ON l.id = b.id_layanan JOIN booking_schedule bs ON bs.id = b.id_booking_schedule JOIN pembayaran p ON p.id_booking = b.id WHERE b.id_merchant = ? ORDER BY b.created_at DESC LIMIT 5", [$merchant->id]);
        $stats = [
            'pesananAktif' => $pesananAktif[0]->total ?? 0,
            'pendapatan' => $pendapatan[0]->total ?? 0,
            'pelanggan' => $pelanggan[0]->total ?? 0,
            'rating' => $rating[0]->avg_rating ?? 0
        ];
        return view('merchant.dashboard', compact('merchant', 'stats', 'recentOrders'));
    }

    public function profile()
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();
        $subKategori = SubKategori::all();
        $mediaSosial = json_decode($merchant->media_sosial, true) ?? [];

        return view('merchant.profile', compact('merchant', 'subKategori', 'mediaSosial'));
    }

    public function updateProfile(Request $request)
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'id_kategori' => 'required|exists:kategori,id',
            'alamat' => 'required|string',
            'instagram' => 'nullable|string',
            'facebook' => 'nullable|string',
            'whatsapp' => 'required|string',
            'profile_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('profile_url')) {
            $uploadedFile = $request->file('profile_url');
            $cloudinary = new \Cloudinary\Cloudinary(config('cloudinary.cloud_url'));
            $result = $cloudinary->uploadApi()->upload(
                $uploadedFile->getRealPath(),
                [
                    'upload_preset' => config('cloudinary.upload_preset'),
                ]
            );
            $merchant->profile_url = $result['secure_url'];
        }

        $merchant->nama_usaha = $validated['nama_usaha'];
        $merchant->id_kategori = $validated['id_kategori'];
        $merchant->alamat = $validated['alamat'];
        $merchant->media_sosial = json_encode([
            'instagram' => $validated['instagram'] ?? '',
            'facebook' => $validated['facebook'] ?? '',
            'whatsapp' => $validated['whatsapp']
        ]);

        $merchant->save();
        return redirect()->route('merchant.profile')->with('success', 'Profil berhasil diperbarui');
    }

    public function verificationStatus()
    {
        $merchant = Auth::user()->merchant;
        if (!$merchant) {
            return redirect()->route('merchant.register.step1');
        }

        if ($merchant->verification_status === 'approved') {
            return redirect()->route('merchant.dashboard');
        }

        return view('merchant.verification-status', ['merchant' => $merchant]);
    }

    public function retryVerification()
    {
        $merchant = Auth::user()->merchant;
        if (!$merchant || $merchant->verification_status === 'approved') {
            return redirect()->route('merchant.dashboard');
        }

        $merchant->delete();

        return redirect()->route('merchant.register.step1')
            ->with('info', 'Silakan daftar ulang sebagai merchant');
    }
}
