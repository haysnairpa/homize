<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    /**
     * Display the contact page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the same navigation data that HomeController uses
        $kategori = DB::select("SELECT nama, id FROM kategori");
        $sub_kategori = DB::select("SELECT s.nama, s.seri_sub_kategori, s.id, s.id_kategori
                                    FROM sub_kategori s");
        $navigation = DB::select("SELECT j.id, j.nama AS jasa_name, GROUP_CONCAT(c.nama
        ORDER BY c.nama SEPARATOR ', ') AS category_names
        FROM kategori j
        JOIN sub_kategori c ON c.id_kategori = j.id
        GROUP BY j.id, j.nama;
        ");

        $ids = DB::select("SELECT `id` FROM `sub_kategori`;");

        $bottomNavigation = DB::select("SELECT c.nama AS category_name, id 
                                        FROM sub_kategori c");

        // Ambil semua kategori untuk filter
        $allKategori = Kategori::all();

        $wishlists = [];
        if (Auth::check()) {
            $wishlists = DB::table('wishlists as w')
                ->select([
                    'w.id_layanan',
                    'w.id_user',
                    'l.nama_layanan',
                    'l.deskripsi_layanan',
                    'm.nama_usaha',
                    'm.profile_url',
                    'tl.harga',
                    'tl.satuan',
                    'tl.tipe_durasi',
                    'tl.durasi'
                ])
                ->leftJoin('layanan as l', 'w.id_layanan', '=', 'l.id')
                ->leftJoin('merchant as m', 'l.id_merchant', '=', 'm.id')
                ->leftJoin('tarif_layanan as tl', 'l.id', '=', 'tl.id_layanan')
                ->where('w.id_user', Auth::id())
                ->get();
        }

        // Share the navigation data with all views
        $sharedData = [
            'kategori' => $kategori,
            'sub_kategori' => $sub_kategori,
            'navigation' => $navigation,
            'bottomNavigation' => $bottomNavigation,
            'ids' => $ids,
            'wishlists' => $wishlists,
            'allKategori' => $allKategori,
        ];

        return view('contact.index', $sharedData);
    }

    /**
     * Handle contact form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            // Send email to service.homize@gmail.com
            Mail::to('service.homize@gmail.com')
                ->send(new ContactFormMail(
                    $validated['name'],
                    $validated['email'],
                    $validated['subject'],
                    $validated['message']
                ));
            
            return redirect()->route('contact.index')->with('success', 'Pesan Anda telah berhasil dikirim. Kami akan menghubungi Anda segera.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error sending contact email: ' . $e->getMessage());
            
            return redirect()->route('contact.index')->with('error', 'Maaf, terjadi kesalahan saat mengirim pesan Anda. Silakan coba lagi nanti.');
        }
    }
}
