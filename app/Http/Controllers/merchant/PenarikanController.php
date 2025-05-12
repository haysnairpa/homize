<?php

// All database operations in this controller use Eloquent ORM or Laravel Query Builder,
// which are protected against SQL injection by design.
namespace App\Http\Controllers\merchant;

use App\Http\Controllers\Controller;
use App\Models\Penarikan;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenarikanController extends Controller
{
    // Menampilkan halaman penarikan
    public function index()
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();
        $saldo = $merchant->saldo;
        $rekenings = $merchant->rekening_merchant()->get();
        $penarikans = \App\Models\Penarikan::whereIn('rekening_merchant_id', $rekenings->pluck('id'))->latest()->get();
        return view('merchant.penarikan', compact('saldo', 'rekenings', 'penarikans'));
    }

    public function tambahRekening(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50',
            'nama_pemilik' => 'required|string|max:100',
        ], [
            'nama_bank.required' => 'Tolong isi nama bank, ya!',
            'nama_bank.string' => 'Nama bank harus berupa teks.',
            'nama_bank.max' => 'Nama bank terlalu panjang, maksimal 100 karakter.',
            'nomor_rekening.required' => 'Nomor rekening tidak boleh kosong.',
            'nomor_rekening.string' => 'Nomor rekening harus berupa angka atau teks.',
            'nomor_rekening.max' => 'Nomor rekening maksimal 50 karakter.',
            'nama_pemilik.required' => 'Siapa pemilik rekeningnya? Mohon diisi.',
            'nama_pemilik.string' => 'Nama pemilik harus berupa teks.',
            'nama_pemilik.max' => 'Nama pemilik terlalu panjang, maksimal 100 karakter.',
        ]);

        $merchant = \App\Models\Merchant::where('id_user', Auth::id())->firstOrFail();
        // Cek duplikasi rekening (opsional)
        $existing = $merchant->rekening_merchant()->where([
            ['nama_bank', $request->nama_bank],
            ['nomor_rekening', $request->nomor_rekening],
        ])->first();
        if ($existing) {
            return back()->with('error', 'Rekening sudah terdaftar.');
        }

        $merchant->rekening_merchant()->create([
            'nama_bank' => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'nama_pemilik' => $request->nama_pemilik,
        ]);

        return back()->with('success', 'Rekening berhasil ditambahkan.');
    }

    // Proses pengajuan penarikan saldo ke rekening yang dipilih
    public function ajukan(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:20000',
            'rekening_id' => 'required|exists:rekening_merchant,id',
        ], [
            'jumlah.required' => 'Silakan masukkan jumlah penarikan.',
            'jumlah.numeric' => 'Jumlah penarikan harus berupa angka.',
            'jumlah.min' => 'Minimal penarikan adalah Rp 20.000.',
            'rekening_id.required' => 'Pilih rekening tujuan penarikan, ya!',
            'rekening_id.exists' => 'Rekening yang dipilih tidak ditemukan.',
        ]);

        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();
        $rekening = $merchant->rekening_merchant()->where('id', $request->rekening_id)->first();
        if (!$rekening) {
            return back()->with('error', 'Rekening tidak ditemukan.');
        }
        if ($merchant->saldo < $request->jumlah) {
            return back()->with('error', 'Maaf, saldo tidak mencukupi.');
        }

        DB::transaction(function() use ($merchant, $rekening, $request) {
            $merchant->saldo -= $request->jumlah;
            $merchant->save();
            Penarikan::create([
                'rekening_merchant_id' => $rekening->id,
                'jumlah' => $request->jumlah,
                'status' => 'Menunggu',
            ]);
        });

        return redirect()->back()->with('success', 'Penarikan berhasil diajukan.');
    }


    // Admin memproses penarikan (terima/tolak)
    public function proses(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Selesai,Ditolak',
            'catatan' => 'nullable|string',
        ]);
        $penarikan = Penarikan::findOrFail($id);
        $penarikan->status = $request->status;
        $penarikan->catatan = $request->catatan;
        $penarikan->save();
        return back()->with('success', 'Status penarikan diperbarui.');
    }

    // Daftar penarikan (merchant)
    public function riwayat()
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();
        // Ambil semua penarikan dari seluruh rekening merchant milik merchant ini
        $rekeningIds = $merchant->rekening_merchant()->pluck('id');
        $penarikans = Penarikan::whereIn('rekening_merchant_id', $rekeningIds)->latest()->get();
        return view('merchant.penarikan.riwayat', compact('penarikans'));
    }

    // Daftar penarikan untuk admin
    public function daftarAdmin()
    {
        $penarikans = Penarikan::with('merchant')->latest()->get();
        return view('admin.penarikan.index', compact('penarikans'));
    }

}
