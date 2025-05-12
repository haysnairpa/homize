<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Cloudinary\Cloudinary;

class RegisterController extends Controller
{
    public function index()
    {
        $merchant = Merchant::where('id_user', Auth::id())->first();
        if ($merchant) {
            return redirect()->route('merchant.dashboard')
                ->with('info', 'Anda sudah terdaftar sebagai merchant');
        }
        return view('merchant');
    }

    public function step1()
    {
        $merchant = Merchant::where('id_user', Auth::id())->first();
        if ($merchant) {
            return redirect()->route('merchant.dashboard')
                ->with('info', 'Anda sudah terdaftar sebagai merchant');
        }
        $kategori = Kategori::all();
        $oldData = Session::get('merchant_registration', []);
        return view('merchant.register.step1', compact('kategori', 'oldData'));
    }

    public function storeStep1(Request $request)
    {
        // If returning from step 2 for testing, ensure session is clean and not corrupted
        if ($request->has('reset') || (Session::has('merchant_registration') && !is_array(Session::get('merchant_registration')))) {
            Session::forget('merchant_registration');
        }

        // If session is missing when coming back from step 2, redirect with error
        if ($request->isMethod('post') && !Session::has('merchant_registration') && $request->has('back_from_step2')) {
            return redirect()->route('merchant.register.step1')->with('error', 'Session pendaftaran tidak ditemukan. Silakan mulai ulang.');
        }

        $oldData = Session::get('merchant_registration', []);
        $rules = [
            'nama_usaha' => 'required|string|max:255',
            'id_kategori' => 'required|exists:kategori,id',
        ];
        if (empty($oldData['profile_url'])) {
            $rules['profile_url'] = 'required|image|mimes:jpeg,png,jpg,webp|max:3072';
        } else {
            $rules['profile_url'] = 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072';
        }
        $validated = $request->validate($rules, [
            'nama_usaha.required' => 'Nama usaha wajib diisi',
            'id_kategori.required' => 'Kategori usaha wajib dipilih',
            'profile_url.required' => 'Foto profil usaha wajib diunggah',
            'profile_url.image' => 'File harus berupa gambar',
            'profile_url.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp',
            'profile_url.max' => 'Ukuran gambar maksimal 3MB',
        ]);

        // Upload ke Cloudinary jika ada upload baru, jika tidak pakai dari session
        $uploadedFileUrl = $oldData['profile_url'] ?? null;
        if ($request->hasFile('profile_url')) {
            $uploadedFile = $request->file('profile_url');
            $cloudinary = new Cloudinary(config('cloudinary.cloud_url'));
            $result = $cloudinary->uploadApi()->upload(
                $uploadedFile->getRealPath(),
                [
                    'upload_preset' => config('cloudinary.upload_preset'), 
                ],
            );
            $uploadedFileUrl = $result['secure_url'];
        }

        $merchantData = [   
            'nama_usaha' => $validated['nama_usaha'],
            'id_kategori' => $validated['id_kategori'],
            'profile_url' => $uploadedFileUrl,
        ];
        Session::put('merchant_registration', $merchantData);
        return redirect()->route('merchant.register.step2');
    }

    public function step2()
    {
        if (!Session::has('merchant_registration')) {
            return redirect()->route('merchant.register.step1')
                ->with('error', 'Silakan isi informasi dasar terlebih dahulu');
        }
        $merchantData = Session::get('merchant_registration');
        return view('merchant.register.step2', compact('merchantData'));
    }

    public function storeStep2(Request $request)
    {
        if (!Session::has('merchant_registration')) {
            return redirect()->route('merchant.register.step1')
                ->with('error', 'Silakan isi informasi dasar terlebih dahulu');
        }
        $validated = $request->validate([
            'alamat' => 'required|string',
            'instagram' => 'nullable|string',
            'facebook' => 'nullable|string',
            'whatsapp' => 'required|string',
        ], [
            'alamat.required' => 'Alamat wajib diisi',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi',
        ]);
        $merchantData = Session::get('merchant_registration');
        $merchant = new Merchant();
        $merchant->id_user = Auth::id();
        $merchant->nama_usaha = $merchantData['nama_usaha'];
        $merchant->id_kategori = $merchantData['id_kategori'];
        $merchant->profile_url = $merchantData['profile_url'];
        $merchant->alamat = $validated['alamat'];
        $merchant->media_sosial = json_encode([
            'instagram' => $validated['instagram'] ?? '',
            'facebook' => $validated['facebook'] ?? '',
            'whatsapp' => $validated['whatsapp']
        ]);
        $merchant->save();
        Session::forget('merchant_registration');
        return redirect()->route('merchant.verification-status')
            ->with('success', 'Pendaftaran merchant berhasil! Silakan tunggu verifikasi.');
    }

    public function backToStep1()
    {
        return redirect()->route('merchant.register.step1');
    }
}
