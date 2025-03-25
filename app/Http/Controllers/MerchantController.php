<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\SubKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Layanan;
use App\Models\TarifLayanan;
use App\Models\Aset;
use App\Models\Sertifikasi;
use App\Models\JamOperasional;
use App\Models\Hari;
use App\Models\LayananMerchant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateLayananRequest;

class MerchantController extends Controller
{
    public function index()
    {
        $merchant = Merchant::where('id_user', Auth::id())->first();
        
        if ($merchant) {
            return redirect()->route('merchant.dashboard');
        }
        
        return view('merchant');
    }

    public function step1()
    {
        $subKategori = SubKategori::all();
        return view('merchant.register.step1', compact('subKategori'));
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'id_sub_kategori' => 'required|exists:sub_kategori,id',
            'profile_url' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $profilePath = $request->file('profile_url')->store('merchant-profiles', 'public');

        $merchant = new Merchant();
        $merchant->id_user = Auth::id();
        $merchant->nama_usaha = $validated['nama_usaha'];
        $merchant->id_sub_kategori = $validated['id_sub_kategori'];
        $merchant->profile_url = $profilePath;
        $merchant->alamat = '';
        $merchant->media_sosial = json_encode([]);
        $merchant->save();

        return redirect()->route('merchant.register.step2', $merchant->id);
    }

    public function step2($id)
    {
        $merchant = Merchant::findOrFail($id);
        return view('merchant.register.step2', compact('merchant'));
    }

    public function storeStep2(Request $request, $id)
    {
        $validated = $request->validate([
            'alamat' => 'required|string',
            'instagram' => 'nullable|string',
            'facebook' => 'nullable|string',
            'whatsapp' => 'required|string',
        ]);

        $merchant = Merchant::findOrFail($id);
        $merchant->alamat = $validated['alamat'];
        $merchant->media_sosial = json_encode([
            'instagram' => $validated['instagram'],
            'facebook' => $validated['facebook'],
            'whatsapp' => $validated['whatsapp']
        ]);
        $merchant->save();

        return redirect()->route('merchant.dashboard');
    }

    public function dashboard()
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();
        return view('merchant.dashboard', compact('merchant'));
    }

    public function storeLayanan(CreateLayananRequest $request)
    {
        try {
            DB::beginTransaction();
            
            // Validasi merchant
            $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();
            
            // Validasi data sebelum create
            if (!isset($request->jam_operasional['hari']) || !isset($request->jam_operasional['jam_buka']) || !isset($request->jam_operasional['jam_tutup'])) {
                throw new \Exception('Data jam operasional tidak lengkap');
            }

            // Create jam operasional
            $jamOperasional = JamOperasional::create([
                'id_hari' => $request->jam_operasional['hari'],
                'jam_buka' => $request->jam_operasional['jam_buka'],
                'jam_tutup' => $request->jam_operasional['jam_tutup']
            ]);

            // Create layanan
            $layanan = Layanan::create([
                'id_merchant' => $merchant->id,
                'id_jam_operasional' => $jamOperasional->id,
                'id_sub_kategori' => $merchant->id_sub_kategori,
                'nama_layanan' => $request->nama_layanan,
                'deskripsi_layanan' => $request->deskripsi_layanan,
                'pengalaman' => $request->pengalaman
            ]);

            // Create layanan merchant
            LayananMerchant::create([
                'id_layanan' => $layanan->id,
                'id_merchant' => $merchant->id
            ]);

            // Create tarif layanan
            TarifLayanan::create([
                'id_layanan' => $layanan->id,
                'id_revisi' => 1, // Default revision
                'harga' => $request->harga,
                'satuan' => $request->satuan,
                'durasi' => $request->durasi,
                'tipe_durasi' => $request->tipe_durasi
            ]);

            // Handle aset uploads
            if ($request->hasFile('aset_layanan')) {
                foreach ($request->file('aset_layanan') as $file) {
                    $path = $file->store('layanan-assets', 'public');
                    Aset::create([
                        'id_layanan' => $layanan->id,
                        'deskripsi' => $file->getClientOriginalName(),
                        'media_url' => $path
                    ]);
                }
            }

            // Handle sertifikasi uploads
            if ($request->has('sertifikasi')) {
                foreach ($request->sertifikasi as $sertifikasi) {
                    if (isset($sertifikasi['file'])) {
                        $path = $sertifikasi['file']->store('layanan-certificates', 'public');
                        Sertifikasi::create([
                            'id_layanan' => $layanan->id,
                            'nama_sertifikasi' => $sertifikasi['nama'],
                            'media_url' => $path
                        ]);
                    }
                }
            }

            DB::commit();
            
            // Log success untuk debugging
            Log::info('Layanan berhasil dibuat', [
                'id_merchant' => $merchant->id,
                'nama_layanan' => $request->nama_layanan
            ]);

            return redirect()->route('merchant.dashboard')
                ->with('success', 'Layanan berhasil ditambahkan! Silakan cek di daftar layanan Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating layanan: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan layanan: ' . $e->getMessage());
        }
    }
}