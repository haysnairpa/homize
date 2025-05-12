<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penarikan;
use Illuminate\Support\Facades\DB;

class PenarikanController extends Controller
{
    // Tampilkan daftar penarikan seluruh merchant
    public function index()
    {
        $penarikanDiproses = Penarikan::with(['merchant', 'rekening_merchant'])
            ->whereIn('status', ['diterima', 'ditolak'])
            ->orderByDesc('created_at')
            ->get();
        $penarikanMenunggu = Penarikan::with(['merchant', 'rekening_merchant'])
            ->where('status', 'menunggu')
            ->orderByDesc('created_at')
            ->get();
        return view('admin.penarikan', compact('penarikanDiproses', 'penarikanMenunggu'));
    }

    // Proses update status penarikan oleh admin
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
            'catatan' => $request->status === 'ditolak' ? 'required|string|max:255' : 'nullable|string|max:255',
        ], [
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status tidak valid',
            'catatan.required' => 'Catatan wajib diisi jika penarikan ditolak',
        ]);

        $penarikan = Penarikan::with(['merchant', 'rekening_merchant'])->findOrFail($id);

        if ($penarikan->status !== 'menunggu') {
            return back()->with('error', 'Status penarikan sudah diproses.');
        }

        DB::beginTransaction();
        try {
            $penarikan->status = $request->status;
            $penarikan->catatan = $request->catatan;
            $penarikan->save();

            if ($request->status === 'ditolak') {
                // Kembalikan saldo merchant
                $merchant = $penarikan->merchant;
                if ($merchant) {
                    $merchant->saldo += $penarikan->jumlah;
                    $merchant->save();
                }
            }
            // Jika diterima, saldo tidak berubah (sudah dipotong saat pengajuan)

            DB::commit();
            return back()->with('success', 'Status penarikan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses penarikan.');
        }
    }
}
