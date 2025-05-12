<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DetailController extends Controller
{
    public function getMerchantDetail($id)
    {
        $merchant = DB::selectOne("SELECT m.id, m.nama_usaha, m.profile_url, m.alamat, m.media_sosial, COALESCE(AVG(r.rate), 0) as rating_avg, COUNT(DISTINCT r.id) as rating_count, COUNT(DISTINCT b.id) as transaction_count, COUNT(DISTINCT tf.id) as follower_count FROM merchant m LEFT JOIN layanan l ON l.id_merchant = m.id LEFT JOIN rating r ON r.id_layanan = l.id LEFT JOIN booking b ON b.id_merchant = m.id LEFT JOIN toko_favorit tf ON tf.id_merchant = m.id WHERE m.id = ? GROUP BY m.id, m.nama_usaha, m.profile_url, m.alamat, m.media_sosial", [$id]);
        if (!$merchant) {
            return redirect()->route('home')->with('error', 'Merchant tidak ditemukan');
        }
        $layanan = DB::select("SELECT l.id, l.nama_layanan, l.deskripsi_layanan, l.pengalaman, tl.harga, a.media_url AS url_layanan, sk.nama AS nama_sub_kategori, COALESCE((SELECT AVG(rate) FROM rating r WHERE r.id_layanan = l.id), 0) AS rating_avg, (SELECT COUNT(*) FROM rating r WHERE r.id_layanan = l.id) AS rating_count, (SELECT COUNT(*) FROM booking b WHERE b.id_layanan = l.id) AS transaction_count FROM layanan l LEFT JOIN sub_kategori sk ON sk.id = l.id_sub_kategori LEFT JOIN tarif_layanan tl ON tl.id_layanan = l.id LEFT JOIN aset a ON a.id_layanan = l.id WHERE l.id_merchant = ? ORDER BY l.created_at DESC;", [$id]);
        $kategori = DB::select("SELECT nama, id FROM kategori");
        $sub_kategori = DB::select("SELECT s.nama, s.seri_sub_kategori, s.id, s.id_kategori FROM sub_kategori s");
        $ids = DB::select("SELECT `id` FROM `sub_kategori`;");
        return view('merchant.detail_merchant', compact('merchant', 'layanan', 'kategori', 'sub_kategori', 'ids'));
    }
}
