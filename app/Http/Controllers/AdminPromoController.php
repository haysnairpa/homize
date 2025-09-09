<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KodePromo;
use App\Models\Kategori;
use App\Models\Layanan;
use App\Services\PromoCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;

class AdminPromoController extends Controller
{
    protected $promoService;

    public function __construct(PromoCodeService $promoService)
    {
        $this->promoService = $promoService;
    }

    /**
     * Display a listing of promo codes
     */
    public function index(Request $request)
    {
        $sortField = $request->query('sort', 'created_at');
        $sortDirection = $request->query('direction', 'desc');
        $statusFilter = $request->query('status');
        $typeFilter = $request->query('type');

        $query = KodePromo::query();

        // Apply filters
        if ($statusFilter) {
            if ($statusFilter === 'active') {
                $query->active();
            } elseif ($statusFilter === 'inactive') {
                $query->where('status_aktif', false);
            } elseif ($statusFilter === 'expired') {
                $query->where('tanggal_berakhir', '<', Carbon::now());
            }
        }

        if ($typeFilter) {
            $query->where('target_type', $typeFilter);
        }

        $promos = $query->orderBy($sortField, $sortDirection)->paginate(15);

        // Get statistics
        $totalPromos = KodePromo::count();
        $activePromos = KodePromo::active()->count();
        $expiredPromos = KodePromo::where('tanggal_berakhir', '<', Carbon::now())->count();
        $exclusivePromos = KodePromo::where('is_exclusive', true)->count();

        return view('admin.promo.index', compact(
            'promos',
            'sortField',
            'sortDirection',
            'statusFilter',
            'typeFilter',
            'totalPromos',
            'activePromos',
            'expiredPromos',
            'exclusivePromos'
        ));
    }

    /**
     * Show the form for creating a new promo code
     */
    public function create()
    {
        $categories = Kategori::all();
        $services = Layanan::with('merchant')->get();

        return view('admin.promo.create', compact('categories', 'services'));
    }

    /**
     * Store a newly created promo code
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:50|unique:kode_promo,kode',
            'nama' => 'required|string|max:255',
            'tipe_diskon' => 'required|in:percentage,fixed',
            'nilai_diskon' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
            'batas_penggunaan_global' => 'nullable|integer|min:1',
            'batas_penggunaan_per_user' => 'required|integer|min:1',
            'is_exclusive' => 'boolean',
            'target_type' => 'required|in:all,category,service',
            'target_id' => 'nullable|integer',
            'deskripsi' => 'nullable|string',
            'minimum_pembelian' => 'nullable|numeric|min:0',
            'maksimum_diskon' => 'nullable|numeric|min:0',
            'status_aktif' => 'boolean'
        ], [
            'kode.required' => 'Kode promo wajib diisi',
            'kode.unique' => 'Kode promo sudah digunakan',
            'nama.required' => 'Nama promo wajib diisi',
            'tipe_diskon.required' => 'Tipe diskon wajib dipilih',
            'nilai_diskon.required' => 'Nilai diskon wajib diisi',
            'nilai_diskon.min' => 'Nilai diskon tidak boleh negatif',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh kurang dari hari ini',
            'tanggal_berakhir.required' => 'Tanggal berakhir wajib diisi',
            'tanggal_berakhir.after' => 'Tanggal berakhir harus setelah tanggal mulai',
            'batas_penggunaan_per_user.required' => 'Batas penggunaan per user wajib diisi',
            'target_type.required' => 'Target promo wajib dipilih'
        ]);

        // Additional validation
        $validator->after(function ($validator) use ($request) {
            // Validate percentage discount
            if ($request->tipe_diskon === 'percentage' && $request->nilai_diskon > 100) {
                $validator->errors()->add('nilai_diskon', 'Persentase diskon tidak boleh lebih dari 100%');
            }

            // Validate target_id requirement
            if (in_array($request->target_type, ['category', 'service']) && !$request->target_id) {
                $validator->errors()->add('target_id', 'Target ID wajib diisi untuk tipe target yang dipilih');
            }

            // Validate target_id existence
            if ($request->target_type === 'category' && $request->target_id) {
                if (!Kategori::find($request->target_id)) {
                    $validator->errors()->add('target_id', 'Kategori yang dipilih tidak ditemukan');
                }
            }

            if ($request->target_type === 'service' && $request->target_id) {
                if (!Layanan::find($request->target_id)) {
                    $validator->errors()->add('target_id', 'Layanan yang dipilih tidak ditemukan');
                }
            }

            // Validate maximum discount for percentage type
            if ($request->tipe_diskon === 'percentage' && $request->maksimum_diskon && $request->minimum_pembelian) {
                $maxPossibleDiscount = ($request->minimum_pembelian * $request->nilai_diskon) / 100;
                if ($request->maksimum_diskon > $maxPossibleDiscount) {
                    // This is just a warning, not an error
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            
            // Set target_id to null if target_type is 'all'
            if ($data['target_type'] === 'all') {
                $data['target_id'] = null;
            }

            // Convert boolean fields
            $data['is_exclusive'] = $request->has('is_exclusive');
            $data['status_aktif'] = $request->has('status_aktif');

            KodePromo::create($data);

            return redirect()->route('admin.promo.index')
                ->with('success', 'Kode promo berhasil dibuat!');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membuat kode promo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified promo code
     */
    public function show($id)
    {
        $promo = KodePromo::findOrFail($id);
        
        // Get usage statistics
        $statsResponse = $this->promoService->getPromoStatistics($id);
        $stats = $statsResponse['success'] ? $statsResponse['data'] : [];
        
        return view('admin.promo.show', compact('promo', 'stats'));
    }

    /**
     * Show the form for editing the specified promo code
     */
    public function edit($id)
    {
        $promo = KodePromo::findOrFail($id);
        $categories = Kategori::all();
        $services = Layanan::with('merchant')->get();

        return view('admin.promo.edit', compact('promo', 'categories', 'services'));
    }

    /**
     * Update the specified promo code
     */
    public function update(Request $request, $id)
    {
        $promo = KodePromo::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:50|unique:kode_promo,kode,' . $id,
            'nama' => 'required|string|max:255',
            'tipe_diskon' => 'required|in:percentage,fixed',
            'nilai_diskon' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
            'batas_penggunaan_global' => 'nullable|integer|min:1',
            'batas_penggunaan_per_user' => 'required|integer|min:1',
            'is_exclusive' => 'boolean',
            'target_type' => 'required|in:all,category,service',
            'target_id' => 'nullable|integer',
            'deskripsi' => 'nullable|string',
            'minimum_pembelian' => 'nullable|numeric|min:0',
            'maksimum_diskon' => 'nullable|numeric|min:0',
            'status_aktif' => 'boolean'
        ], [
            'kode.required' => 'Kode promo wajib diisi',
            'kode.unique' => 'Kode promo sudah digunakan',
            'nama.required' => 'Nama promo wajib diisi',
            'tipe_diskon.required' => 'Tipe diskon wajib dipilih',
            'nilai_diskon.required' => 'Nilai diskon wajib diisi',
            'nilai_diskon.min' => 'Nilai diskon tidak boleh negatif',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi',
            'tanggal_berakhir.required' => 'Tanggal berakhir wajib diisi',
            'tanggal_berakhir.after' => 'Tanggal berakhir harus setelah tanggal mulai',
            'batas_penggunaan_per_user.required' => 'Batas penggunaan per user wajib diisi',
            'target_type.required' => 'Target promo wajib dipilih'
        ]);

        // Additional validation (same as store method)
        $validator->after(function ($validator) use ($request) {
            if ($request->tipe_diskon === 'percentage' && $request->nilai_diskon > 100) {
                $validator->errors()->add('nilai_diskon', 'Persentase diskon tidak boleh lebih dari 100%');
            }

            if (in_array($request->target_type, ['category', 'service']) && !$request->target_id) {
                $validator->errors()->add('target_id', 'Target ID wajib diisi untuk tipe target yang dipilih');
            }

            if ($request->target_type === 'category' && $request->target_id) {
                if (!Kategori::find($request->target_id)) {
                    $validator->errors()->add('target_id', 'Kategori yang dipilih tidak ditemukan');
                }
            }

            if ($request->target_type === 'service' && $request->target_id) {
                if (!Layanan::find($request->target_id)) {
                    $validator->errors()->add('target_id', 'Layanan yang dipilih tidak ditemukan');
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            
            // Set target_id to null if target_type is 'all'
            if ($data['target_type'] === 'all') {
                $data['target_id'] = null;
            }

            // Convert boolean fields
            $data['is_exclusive'] = $request->has('is_exclusive');
            $data['status_aktif'] = $request->has('status_aktif');

            $promo->update($data);

            return redirect()->route('admin.promo.index')
                ->with('success', 'Kode promo berhasil diperbarui!');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui kode promo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified promo code
     */
    public function destroy($id)
    {
        try {
            $promo = KodePromo::findOrFail($id);
            
            // Check if promo has been used
            if ($promo->penggunaan()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus kode promo yang sudah pernah digunakan.');
            }

            $promo->delete();

            return redirect()->route('admin.promo.index')
                ->with('success', 'Kode promo berhasil dihapus!');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus kode promo: ' . $e->getMessage());
        }
    }

    /**
     * Toggle promo code status
     */
    public function toggleStatus($id)
    {
        try {
            $promo = KodePromo::findOrFail($id);
            $promo->update(['status_aktif' => !$promo->status_aktif]);

            $status = $promo->status_aktif ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->back()
                ->with('success', "Kode promo berhasil {$status}!");

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah status kode promo: ' . $e->getMessage());
        }
    }

    /**
     * Get services by category (AJAX)
     */
    public function getServicesByCategory($categoryId)
    {
        try {
            $services = Layanan::whereHas('subKategori', function($query) use ($categoryId) {
                $query->where('id_kategori', $categoryId);
            })->with('merchant')->get();

            return response()->json([
                'success' => true,
                'services' => $services
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data layanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate promo code (AJAX)
     */
    public function validatePromo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string',
            'user_id' => 'required|integer',
            'layanan_id' => 'required|integer',
            'amount' => 'nullable|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->promoService->validatePromoCode(
            $request->kode,
            $request->user_id,
            $request->layanan_id,
            $request->amount
        );

        return response()->json($result);
    }
}