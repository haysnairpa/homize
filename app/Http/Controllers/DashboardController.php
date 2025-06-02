<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use App\Http\Controllers\PembayaranController;

class DashboardController extends Controller
{
    public function mainDashboard()
    {
        $userId = Auth::id();
        $bookings = DB::select("SELECT sk.seri_sub_kategori, l.nama_layanan, b.status_proses, b.updated_at, p.amount, p.status_pembayaran, bs.waktu_mulai, bs.waktu_selesai, b.id
                                FROM booking b
                                JOIN layanan l ON l.id = b.id_layanan
                                JOIN sub_kategori sk ON sk.id = l.id_sub_kategori
                                JOIN booking_schedule bs ON bs.id = b.id_booking_schedule
                                JOIN pembayaran p ON p.id_booking = b.id
                                WHERE b.id_user = ?", [$userId]);

        $totalPesanan = DB::select("SELECT COUNT(*) as total_pesanan FROM booking WHERE id_user = ?", [$userId]);
        $totalPesananAktif = DB::select("SELECT COUNT(*) as total_pesanan_aktif FROM booking WHERE id_user = ? AND status_proses = 'aktif'", [$userId]);
        $totalPesananSelesai = DB::select("SELECT COUNT(*) as total_pesanan_selesai FROM booking WHERE id_user = ? AND status_proses = 'selesai'", [$userId]);
        $totalPesananBatal = DB::select("SELECT COUNT(*) as total_pesanan_batal FROM booking WHERE id_user = ? AND status_proses = 'batal'", [$userId]);
        return view('dashboard', compact('bookings', 'totalPesanan', 'totalPesananAktif', 'totalPesananSelesai', 'totalPesananBatal'));
    }
    public function transactions()
    {
        $userId = Auth::id();
        $transactions = DB::select("SELECT 
                                    b.id, 
                                    sk.seri_sub_kategori, 
                                    sk.nama as nama_sub_kategori, 
                                    l.nama_layanan, 
                                    l.deskripsi_layanan, 
                                    m.nama_usaha, 
                                    m.profile_url, 
                                    m.alamat as alamat_merchant, 
                                    b.status_proses,
                                    b.created_at AS tanggal_booking,
                                    b.updated_at AS tanggal_selesai, 
                                    b.updated_at, 
                                    p.amount,
                                    p.status_pembayaran,
                                    bs.waktu_mulai, 
                                    bs.waktu_selesai, 
                                    b.alamat_pembeli, 
                                    b.catatan, 
                                    b.latitude, 
                                    b.longitude,
                                    b.customer_approval_status,
                                    b.customer_approval_date,
                                    b.protest_reason,
                                    b.protest_date
                                FROM booking b
                                JOIN layanan l ON l.id = b.id_layanan
                                JOIN sub_kategori sk ON sk.id = l.id_sub_kategori
                                JOIN merchant m ON m.id = l.id_merchant
                                JOIN booking_schedule bs ON bs.id = b.id_booking_schedule
                                JOIN pembayaran p ON p.id_booking = b.id
                                WHERE b.id_user = ?
                                ORDER BY b.created_at DESC", [$userId]);
        
        // Get orders awaiting approval
        $ordersNeedingApproval = $this->getOrdersNeedingApproval($userId);

        return view('user.transactions', compact('transactions', 'ordersNeedingApproval'));
    }

    public function filterTransactions(Request $request)
    {
        $userId = Auth::id();
        $status = $request->query('status', 'all');

        // Log for debugging
        Log::info('Filtering transactions', ['user_id' => $userId, 'status' => $status]);

        $query = "SELECT 
                    b.id, 
                    sk.seri_sub_kategori, 
                    sk.nama as nama_sub_kategori, 
                    l.nama_layanan, 
                    l.deskripsi_layanan, 
                    m.nama_usaha, 
                    m.profile_url, 
                    m.alamat as alamat_merchant, 
                    b.status_proses,
                    b.tanggal_booking, 
                    b.updated_at, 
                    p.amount, 
                    p.status_pembayaran,
                    bs.waktu_mulai, 
                    bs.waktu_selesai, 
                    b.alamat_pembeli, 
                    b.catatan, 
                    b.latitude, 
                    b.longitude,
                    b.created_at,
                    b.updated_at AS tanggal_selesai
                FROM booking b
                JOIN layanan l ON l.id = b.id_layanan
                JOIN sub_kategori sk ON sk.id = l.id_sub_kategori
                JOIN merchant m ON m.id = l.id_merchant         
                JOIN booking_schedule bs ON bs.id = b.id_booking_schedule
                JOIN pembayaran p ON p.id_booking = b.id
                WHERE b.id_user = ?";

        $params = [$userId];

        // Map the status parameter to the actual status value in the database
        if ($status !== 'all') {
            // Map numeric status codes to text values
            $statusMap = [
                '1' => 'Pending',
                '2' => 'Dikonfirmasi',
                '3' => 'Sedang diproses',
                '4' => 'Selesai',
                '5' => 'Dibatalkan'
            ];
            
            if (isset($statusMap[$status])) {
                $query .= " AND b.status_proses = ?";
                $params[] = $statusMap[$status];
                Log::info('Mapped status', ['from' => $status, 'to' => $statusMap[$status]]);
            }
        }

        $query .= " ORDER BY b.created_at DESC";

        try {
            $transactions = DB::select($query, $params);
            Log::info('Transactions found', ['count' => count($transactions)]);
            
            if ($request->ajax()) {
                $html = view('partials.transaction-list-dashboard', compact('transactions'))->render();
                return response()->json(['success' => true, 'html' => $html]);
            }
            
            return view('user.transactions', compact('transactions'));
        } catch (\Exception $e) {
            Log::error('Error filtering transactions', ['error' => $e->getMessage()]);
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error loading transactions: ' . $e->getMessage()]);
            }
            
            return view('user.transactions', ['transactions' => []]);
        }
    }

    public function filterTransactionsByDate(Request $request)
    {
        $userId = Auth::id();
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        Log::info('Filtering transactions by date', ['user_id' => $userId, 'start_date' => $startDate, 'end_date' => $endDate]);

        $query = "SELECT 
                    b.id, 
                    sk.seri_sub_kategori, 
                    sk.nama as nama_sub_kategori, 
                    l.nama_layanan, 
                    l.deskripsi_layanan, 
                    m.nama_usaha, 
                    m.profile_url, 
                    m.alamat as alamat_merchant, 
                    b.status_proses,
                    b.tanggal_booking, 
                    b.updated_at, 
                    p.amount, 
                    p.status_pembayaran,
                    bs.waktu_mulai, 
                    bs.waktu_selesai, 
                    b.alamat_pembeli, 
                    b.catatan, 
                    b.latitude, 
                    b.longitude,
                    b.created_at,
                    b.updated_at AS tanggal_selesai
                FROM booking b
                JOIN layanan l ON l.id = b.id_layanan
                JOIN sub_kategori sk ON sk.id = l.id_sub_kategori
                JOIN merchant m ON m.id = l.id_merchant
                JOIN booking_schedule bs ON bs.id = b.id_booking_schedule
                JOIN pembayaran p ON p.id_booking = b.id
                WHERE b.id_user = ? AND b.tanggal_booking BETWEEN ? AND ?
                ORDER BY b.created_at DESC";

        try {
            $transactions = DB::select($query, [$userId, $startDate, $endDate]);
            Log::info('Date filtered transactions found', ['count' => count($transactions)]);

            if ($request->ajax()) {
                $html = view('partials.transaction-list-dashboard', compact('transactions'))->render();
                return response()->json(['success' => true, 'html' => $html]);
            }

            return view('user.transactions', compact('transactions'));
        } catch (\Exception $e) {
            Log::error('Error filtering transactions by date', ['error' => $e->getMessage()]);
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error loading transactions: ' . $e->getMessage()]);
            }
            
            return view('user.transactions', ['transactions' => []]);
        }
    }

    public function transactionDetail($id)
    {
        $userId = Auth::id();
        $transaction = DB::selectOne("SELECT 
                                        b.id, 
                                        sk.seri_sub_kategori, 
                                        sk.nama as nama_sub_kategori, 
                                        l.nama_layanan, 
                                        l.deskripsi_layanan, 
                                        m.nama_usaha, 
                                        m.profile_url, 
                                        m.alamat as alamat_merchant, 
                                        b.status_proses,
                                        b.tanggal_booking, 
                                        p.amount, 
                                        p.unique_code,
                                        p.rejection_reason,
                                        p.status_pembayaran,
                                        bs.waktu_mulai, 
                                        bs.waktu_selesai,
                                        b.alamat_pembeli, 
                                        b.catatan, 
                                        b.latitude, 
                                        b.longitude,
                                        b.customer_approval_status,
                                        b.customer_approval_date,
                                        b.protest_reason,
                                        b.protest_date
                                    FROM booking b
                                    JOIN layanan l ON l.id = b.id_layanan
                                    JOIN sub_kategori sk ON sk.id = l.id_sub_kategori
                                    JOIN merchant m ON m.id = l.id_merchant
                                    JOIN booking_schedule bs ON bs.id = b.id_booking_schedule
                                    JOIN pembayaran p ON p.id_booking = b.id
                                    WHERE b.id = ? AND b.id_user = ?", [$id, $userId]);

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        return view('user.transaction-detail', compact('transaction'));
    }
    
    /**
     * Get orders that need customer approval (status 'Selesai' but not yet approved)
     *
     * @param int $userId
     * @return array
     */
    private function getOrdersNeedingApproval($userId)
    {
        return DB::select("SELECT 
                            b.id, 
                            l.nama_layanan, 
                            m.nama_usaha, 
                            b.updated_at AS tanggal_selesai, 
                            p.amount
                        FROM booking b
                        JOIN layanan l ON l.id = b.id_layanan
                        JOIN merchant m ON m.id = l.id_merchant
                        JOIN pembayaran p ON p.id_booking = b.id
                        WHERE b.id_user = ? 
                        AND b.status_proses = 'Selesai' 
                        AND b.customer_approval_status IS NULL
                        ORDER BY b.updated_at DESC", [$userId]);
    }
}
