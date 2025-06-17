<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Layanan;
use App\Models\SubKategori;
use App\Models\JamOperasional;
use App\Models\Hari;
use App\Models\Sertifikasi;
use App\Models\Aset;
use App\Models\TarifLayanan;
use App\Models\Revisi;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Cloudinary\Cloudinary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LayananController extends Controller
{
    public function services()
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();
        $layanan = Layanan::where('id_merchant', $merchant->id)->get();
        $subKategori = SubKategori::where('id_kategori', $merchant->id_kategori)->get();
        return view('merchant.services', compact('merchant', 'layanan', 'subKategori'));
    }

    public function storeLayanan(Request $request)
    {
        try {
            DB::beginTransaction();

            // Get the merchant
            $merchant = Auth::user()->merchant;

            // Validate sub-kategori belongs to merchant's kategori
            $subKategori = SubKategori::findOrFail($request->id_sub_kategori);
            if ($subKategori->id_kategori !== $merchant->id_kategori) {
                throw new \Exception('Sub kategori tidak sesuai dengan kategori merchant Anda.');
            }

            // Create jam_operasional record
            $jamOperasional = JamOperasional::create([
                'jam_buka' => $request->jam_operasional['jam_buka'],
                'jam_tutup' => $request->jam_operasional['jam_tutup']
            ]);

            // Attach multiple hari to jam_operasional
            if (isset($request->jam_operasional['hari']) && is_array($request->jam_operasional['hari'])) {
                $jamOperasional->hari()->attach($request->jam_operasional['hari']);
            }

            // Create layanan record with id_merchant
            $layanan = Layanan::create([
                'nama_layanan' => $request->nama_layanan,
                'deskripsi_layanan' => $request->deskripsi_layanan,
                'id_sub_kategori' => $request->id_sub_kategori,
                'id_jam_operasional' => $jamOperasional->id,
                'id_merchant' => $merchant->id,
                'pengalaman' => $request->pengalaman ?? 0
            ]);

            Sertifikasi::create([
                'id_layanan' => $layanan->id,
                'nama_sertifikasi' => $request->nama_sertifikasi,
                'media_url' => $request->file_sertifikasi ? (new Cloudinary(config('cloudinary.cloud_url')))
                    ->uploadApi()->upload(
                        $request->file_sertifikasi->getRealPath(),
                        [
                            'upload_preset' => config('cloudinary.upload_preset'),
                        ]
                    )['secure_url'] : null,
            ]);

            // Handle revisi if enabled
            $revisiId = null;
            if ($request->has('enable_revisi') && $request->enable_revisi) {
                $revisi = Revisi::create([
                    'id_layanan' => $layanan->id,
                    'harga' => $request->revisi_harga,
                    'durasi' => $request->revisi_durasi,
                    'tipe_durasi' => $request->revisi_tipe_durasi
                ]);
                $revisiId = $revisi->id;
            }

            // Validate satuan as string value
            $request->validate([
                'satuan' => 'required|in:kilogram,unit,pcs,pertemuan',
            ]);

            // Create tarif_layanan record with id_revisi
            TarifLayanan::create([
                'id_layanan' => $layanan->id,
                'harga' => $request->harga,
                'durasi' => $request->durasi,
                'tipe_durasi' => $request->tipe_durasi,
                'satuan' => $request->satuan,
                'id_revisi' => $revisiId
            ]);

            // Handle aset (images)
            if ($request->hasFile('aset_layanan')) {
                foreach ($request->file('aset_layanan') as $index => $file) {
                    if ($file->isValid()) {
                        if ($file->getSize() > 1000000) {
                            throw new \Exception("Gambar anda lebih besar dari 1MB!");
                        }

                        $cloudinary = new Cloudinary(config('cloudinary.cloud_url'));
                        $result = $cloudinary->uploadApi()->upload(
                            $file->getRealPath(),
                            [
                                'upload_preset' => config('cloudinary.layanan_upload_preset', config('cloudinary.upload_preset')),
                            ]
                        );
                        $cloudinaryUrl = $result['secure_url'];

                        Aset::create([
                            'id_layanan' => $layanan->id,
                            'media_url' => $cloudinaryUrl,
                            'deskripsi' => $request->nama_layanan
                        ]);
                    } else {
                        throw new \Exception("Gambar anda bukan tipe yang valid (.png, .jpeg, dll)");
                    }
                }
            } else {
                Aset::create([
                    'id_layanan' => $layanan->id,
                    'media_url' => null,
                    'deskripsi' => "Penjual ini tidak mempunyai foto toko",
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Layanan berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function editLayanan($id)
    {
        try {
            $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();
            $layanan = Layanan::with(['tarif_layanan', 'jam_operasional', 'jam_operasional.hari', 'aset', 'sertifikasi'])
                ->where('id', $id)
                ->where('id_merchant', $merchant->id)
                ->firstOrFail();

            $subKategori = SubKategori::where('id_kategori', $merchant->id_kategori)->get();
            $hari = Hari::all();

            return response()->json([
                'success' => true,
                'layanan' => $layanan,
                'subKategori' => $subKategori,
                'hari' => $hari,
                'selectedHari' => $layanan->jam_operasional->hari->pluck('id')->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function updateLayanan(Request $request, $id)
    {

        try {
            DB::beginTransaction();

            // Get the merchant and validate ownership
            $merchant = Auth::user()->merchant;
            $layanan = Layanan::with(['jam_operasional', 'jam_operasional.hari', 'aset', 'sertifikasi'])
                ->where('id', $id)
                ->where('id_merchant', $merchant->id)
                ->firstOrFail();

            // Validate sub-kategori belongs to merchant's kategori
            $subKategori = SubKategori::findOrFail($request->id_sub_kategori);
            if ($subKategori->id_kategori !== $merchant->id_kategori) {
                throw new \Exception('Sub kategori tidak sesuai dengan kategori merchant Anda.');
            }

            // Update jam operasional
            $jamOperasional = $layanan->jam_operasional;
            $jamOperasional->update([
                'jam_buka' => $request->jam_operasional['jam_buka'],
                'jam_tutup' => $request->jam_operasional['jam_tutup']
            ]);

            // Update hari
            if (isset($request->jam_operasional['hari']) && is_array($request->jam_operasional['hari'])) {
                $jamOperasional->hari()->sync($request->jam_operasional['hari']);
            }

            // Update layanan
            $layanan->update([
                'nama_layanan' => $request->nama_layanan,
                'deskripsi_layanan' => $request->deskripsi_layanan,
                'id_sub_kategori' => $request->id_sub_kategori,
                'pengalaman' => $request->pengalaman ?? 0
            ]);

            // Validate satuan as string value
            $request->validate([
                'satuan' => 'required|in:kilogram,unit,pcs,pertemuan',
            ]);

            // Update tarif layanan
            $tarifLayanan = $layanan->tarif_layanan;
            if ($tarifLayanan) {
                $tarifLayanan->update([
                    'harga' => $request->harga,
                    'durasi' => $request->durasi,
                    'tipe_durasi' => $request->tipe_durasi,
                    'satuan' => $request->satuan
                ]);
            }

            // Handle aset (images) if new ones are uploaded
            if ($request->hasFile('aset_layanan')) {
                // Delete existing assets
                Aset::where('id_layanan', $layanan->id)->delete();

                foreach ($request->file('aset_layanan') as $file) {
                    $cloudinary = new Cloudinary(config('cloudinary.cloud_url'));
                    $result = $cloudinary->uploadApi()->upload(
                        $file->getRealPath(),
                        [
                            'upload_preset' => config('cloudinary.layanan_upload_preset', config('cloudinary.upload_preset')),
                        ]
                    );
                    $cloudinaryUrl = $result['secure_url'];
                    Aset::create([
                        'id_layanan' => $layanan->id,
                        'media_url' => $cloudinaryUrl,
                        'deskripsi' => $request->nama_layanan
                    ]);
                }
            }

            // Update sertifikasi if provided
            if ($request->has('nama_sertifikasi')) {
                $sertifikasi = Sertifikasi::where('id_layanan', $layanan->id)->first();
                if ($sertifikasi) {
                    $mediaUrl = $sertifikasi->media_url;
                    if ($request->hasFile('file_sertifikasi')) {
                        $cloudinary = new Cloudinary(config('cloudinary.cloud_url'));
                        $result = $cloudinary->uploadApi()->upload(
                            $request->file('file_sertifikasi')->getRealPath(),
                            [
                                'upload_preset' => config('cloudinary.sertifikasi_upload_preset'),
                            ]
                        );
                        $mediaUrl = $result['secure_url'];
                    }
                    $sertifikasi->update([
                        'nama_sertifikasi' => $request->nama_sertifikasi,
                        'media_url' => $mediaUrl
                    ]);
                } else {
                    Sertifikasi::create([
                        'id_layanan' => $layanan->id,
                        'nama_sertifikasi' => $request->nama_sertifikasi,
                        'media_url' => $request->hasFile('file_sertifikasi') ? (function () use ($request) {
                            $cloudinary = new Cloudinary(config('cloudinary.cloud_url'));
                            $result = $cloudinary->uploadApi()->upload(
                                $request->file('file_sertifikasi')->getRealPath(),
                                [
                                    'upload_preset' => config('cloudinary.upload_preset'),
                                ]
                            );
                            return $result['secure_url'];
                        })() : null
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('merchant.services')->with('success', 'Layanan berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function orderDetail($id)
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();

        // Ambil detail pesanan
        $order = DB::selectOne(
            "SELECT b.id, u.nama as nama_user, u.email,
                            l.nama_layanan, l.deskripsi_layanan, b.status_proses,
                            b.tanggal_booking, p.amount, bs.waktu_mulai, bs.waktu_selesai,
                            b.alamat_pembeli, b.catatan, b.longitude, b.latitude,
                            tl.harga, tl.durasi, tl.tipe_durasi
                            FROM booking b
                            JOIN users u ON u.id = b.id_user
                            JOIN layanan l ON l.id = b.id_layanan
                            JOIN booking_schedule bs ON bs.id = b.id_booking_schedule
                            JOIN pembayaran p ON p.id_booking = b.id
                            JOIN tarif_layanan tl ON tl.id_layanan = l.id
                            WHERE b.id = ? AND b.id_merchant = ?",
            [$id, $merchant->id]
        );

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        // Format data untuk ditampilkan
        $orderDetail = [
            'id' => $order->id,
            'tanggal' => Carbon::parse($order->tanggal_booking)->format('d/m/Y'),
            'pelanggan' => [
                'nama' => $order->nama_user,
                'email' => $order->email,
                'alamat' => $order->alamat_pembeli
            ],
            'layanan' => [
                'nama' => $order->nama_layanan,
                'harga' => $order->harga,
                'durasi' => $order->durasi . ' ' . $order->tipe_durasi
            ],
            'jadwal' => [
                'mulai' => Carbon::parse($order->waktu_mulai)->format('H:i'),
                'selesai' => Carbon::parse($order->waktu_selesai)->format('H:i')
            ],
            'catatan' => $order->catatan,
            'total' => $order->amount,
            'status' => $order->status_proses
        ];

        return response()->json([
            'success' => true,
            'data' => $orderDetail
        ]);
    }

    public function getMerchantDetail($id)
    {
        // Get merchant details with ratings and stats
        $merchant = DB::selectOne(
            "SELECT m.id, m.nama_usaha, m.profile_url, m.alamat, m.media_sosial,
            COALESCE(AVG(r.rate), 0) as rating_avg,
            COUNT(DISTINCT r.id) as rating_count,
            COUNT(DISTINCT b.id) as transaction_count,
            COUNT(DISTINCT tf.id) as follower_count
        FROM merchant m
        LEFT JOIN layanan l ON l.id_merchant = m.id
        LEFT JOIN rating r ON r.id_layanan = l.id
        LEFT JOIN booking b ON b.id_merchant = m.id
        LEFT JOIN toko_favorit tf ON tf.id_merchant = m.id
        WHERE m.id = ?
        GROUP BY m.id, m.nama_usaha, m.profile_url, m.alamat, m.media_sosial",
            [$id]
        );

        if (!$merchant) {
            return redirect()->route('home')->with('error', 'Merchant tidak ditemukan');
        }

        // Get merchant's services with ratings and transaction counts
        $layanan = DB::select(
            "SELECT 
                l.id, 
                l.nama_layanan, 
                l.deskripsi_layanan, 
                l.pengalaman, 
                tl.harga,
                a.media_url AS url_layanan,
                sk.nama AS nama_sub_kategori, COALESCE((
                SELECT AVG(rate)
                FROM rating r
                WHERE r.id_layanan = l.id), 0) AS rating_avg, 
                (
                SELECT COUNT(*)
                FROM rating r
                WHERE r.id_layanan = l.id) AS rating_count, 
                (
                SELECT COUNT(*)
                FROM booking b
                WHERE b.id_layanan = l.id) AS transaction_count
                FROM layanan l
                LEFT JOIN sub_kategori sk ON sk.id = l.id_sub_kategori
                LEFT JOIN tarif_layanan tl ON tl.id_layanan = l.id
                LEFT JOIN aset a ON a.id_layanan = l.id
                WHERE l.id_merchant = ?
                ORDER BY l.created_at DESC;
                ",
            [$id]
        );

        $kategori = DB::select("SELECT nama, id FROM kategori");
        $sub_kategori = DB::select("SELECT s.nama, s.seri_sub_kategori, s.id, s.id_kategori FROM sub_kategori s");
        $ids = DB::select("SELECT `id` FROM `sub_kategori`;");

        return view('merchant.detail_merchant', compact('merchant', 'layanan', 'kategori', 'sub_kategori', 'ids'));
    }

    public function sortLayanan($id, Request $request)
    {
        $sort = $request->query('sort', 'newest');
        $query = "SELECT 
            l.id, 
            l.nama_layanan, 
            l.deskripsi_layanan, 
            l.pengalaman,
            l.created_at,
            tl.harga,
            a.media_url AS url_layanan,
            sk.nama AS nama_sub_kategori,
            COALESCE((
                SELECT AVG(rate)
                FROM rating r
                WHERE r.id_layanan = l.id), 0) AS rating_avg,
            (
                SELECT COUNT(*)
                FROM rating r
                WHERE r.id_layanan = l.id) AS rating_count,
            (
                SELECT COUNT(*)
                FROM booking b
                WHERE b.id_layanan = l.id) AS transaction_count
            FROM layanan l
            LEFT JOIN sub_kategori sk ON sk.id = l.id_sub_kategori
            LEFT JOIN tarif_layanan tl ON tl.id_layanan = l.id
            LEFT JOIN aset a ON a.id_layanan = l.id
            WHERE l.id_merchant = ?";

        // Add sorting based on the sort parameter
        switch ($sort) {
            case 'highest_rating':
                $query .= " ORDER BY rating_avg DESC, rating_count DESC";
                break;
            case 'price_asc':
                $query .= " ORDER BY tl.harga ASC";
                break;
            case 'price_desc':
                $query .= " ORDER BY tl.harga DESC";
                break;
            default: // newest
                $query .= " ORDER BY l.created_at DESC";
        }

        $layanan = DB::select($query, [$id]);

        $html = view('merchant.partials.layanan-grid', compact('layanan'))->render();

        return response()->json(['html' => $html]);
    }

    public function showAddLayananForm()
    {
        $merchant = Auth::user()->merchant;
        $subKategori = SubKategori::where('id_kategori', $merchant->id_kategori)->get();
        return view('merchant.components.add-layanan', compact('subKategori', 'merchant'));
    }

    /**
     * Remove the specified layanan from storage (AJAX/JSON).
     */
    public function destroy($id)
    {
        try {
            $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();
            $layanan = Layanan::where('id', $id)->where('id_merchant', $merchant->id)->firstOrFail();

            // Optionally, check for related bookings or constraints here
            if (Booking::where('id_layanan', $layanan->id)->exists()) {
                return response()->json(['success' => false, 'message' => 'Layanan masih memiliki booking aktif.'], 400);
            }

            $layanan->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Delete Layanan Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus layanan: ' . $e->getMessage()
            ], 500);
        }
    }
}
