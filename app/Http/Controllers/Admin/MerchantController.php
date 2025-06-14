<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Merchant;
use App\Models\RiwayatSaldoMerchant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MerchantController extends Controller
{
    
    public function merchants(Request $request)
    {
        $sortField = $request->query('sort', 'id');
        $sortDirection = $request->query('direction', 'asc');

        $merchants = Merchant::with('user')
            ->where('verification_status', 'approved')
            ->when($sortField === 'pemilik', function ($query) use ($sortDirection) {
                return $query->join('users', 'merchant.id_user', '=', 'users.id')
                    ->orderBy('users.nama', $sortDirection)
                    ->select('merchant.*');
            }, function ($query) use ($sortField, $sortDirection) {
                return $query->orderBy($sortField, $sortDirection);
            })
            ->get();

        return view('admin.merchants', compact('merchants', 'sortField', 'sortDirection'));
    }

    public function getMerchantDetail($id)
    {
        $merchant = Merchant::with([
            'user', 
            'kategori', 
            'layanan', 
            'rekening_merchant'
        ])->findOrFail($id);
        
        return response()->json($merchant);
    }

    public function destroy($id)
    {
        $merchant = Merchant::findOrFail($id);
        $merchant->delete();
        return response()->json(['success' => true]);
    }
    
    public function adjustBalance(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:penambahan,pengurangan',
            'description' => 'nullable|string|max:255',
        ]);
        
        $merchant = Merchant::findOrFail($id);
        $amount = (float) $request->amount;
        $currentBalance = (float) $merchant->saldo;
        
        // Check if there's enough balance for subtraction
        if ($request->type === 'pengurangan' && $currentBalance < $amount) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo merchant tidak mencukupi untuk pengurangan ini.'
            ], 400);
        }
        
        DB::beginTransaction();
        try {
            // Calculate new balance
            $newBalance = $request->type === 'penambahan' 
                ? $currentBalance + $amount 
                : $currentBalance - $amount;
            
            // Update merchant balance
            $merchant->saldo = $newBalance;
            $merchant->save();
            
            // Create transaction history
            RiwayatSaldoMerchant::create([
                'id_merchant' => $merchant->id,
                'jumlah' => $request->type === 'penambahan' ? $amount : -$amount,
                'saldo_sebelum' => $currentBalance,
                'saldo_sesudah' => $newBalance,
                'tipe' => $request->type,
                'keterangan' => $request->description,
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Saldo merchant berhasil ' . ($request->type === 'penambahan' ? 'ditambahkan' : 'dikurangi') . '.',
                'new_balance' => $newBalance
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get merchant transaction history
     */
    public function getTransactionHistory($id, Request $request)
    {
        $limit = $request->query('limit', 5);
        
        $merchant = Merchant::findOrFail($id);
        
        $transactions = RiwayatSaldoMerchant::where('id_merchant', $merchant->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'amount' => abs($transaction->jumlah),
                    'type' => $transaction->tipe,
                    'description' => $transaction->keterangan,
                    'notes' => $transaction->keterangan,
                    'balance_before' => $transaction->saldo_sebelum,
                    'balance_after' => $transaction->saldo_sesudah,
                    'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
                    'formatted_date' => $transaction->created_at->format('d M Y H:i'),
                ];
            });
        
        return response()->json([
            'success' => true,
            'transactions' => $transactions
        ]);
    }

    public function showTransactionHistory($id)
    {
        $merchant = Merchant::with('user')->findOrFail($id);
        $transactions = RiwayatSaldoMerchant::where('id_merchant', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.merchant-transactions', compact('merchant', 'transactions'));
    }
}
