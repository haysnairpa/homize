<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\SubKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}