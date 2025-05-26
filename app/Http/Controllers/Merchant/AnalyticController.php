<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticController extends Controller
{
    public function analytics()
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();

        // Default periode: 7 hari terakhir
        $period = request('period', 'week');

        // Fungsi untuk mengambil data statistik
        function getStat($query, $params = [])
        {
            $result = DB::select($query, $params);
            return $result && isset($result[0]->total) ? $result[0]->total : 0;
        }

        // Tentukan rentang tanggal berdasarkan periode
        $startDate = now();
        $endDate = now();

        switch ($period) {
            case 'week':
                $startDate = now()->subDays(7);
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                break;
            case 'month30':
                $startDate = now()->subDays(30);
                break;
                // Kasus custom akan ditangani di frontend
        }

        // Format tanggal untuk query
        $startDateStr = $startDate->format('Y-m-d');
        $endDateStr = $endDate->format('Y-m-d');

        // Statistik mingguan
        $weeklyStats = [
            'pendapatan' => getStat(
                "SELECT SUM(p.amount) as total FROM booking b JOIN pembayaran p ON p.id_booking = b.id WHERE b.id_merchant = ? AND p.status_pembayaran = 'berhasil' AND p.payment_date BETWEEN ? AND ?",
                [$merchant->id, $startDateStr, $endDateStr]
            ),
            'pesanan' => getStat(
                "SELECT COUNT(*) as total FROM booking b WHERE b.id_merchant = ? AND b.created_at BETWEEN ? AND ?",
                [$merchant->id, $startDateStr, $endDateStr]
            ),
            'views' => getStat(
                "SELECT COUNT(*) as total FROM layanan_views lv JOIN layanan l ON lv.id_layanan = l.id WHERE l.id_merchant = ? AND lv.created_at BETWEEN ? AND ?",
                [$merchant->id, $startDateStr, $endDateStr]
            ),
            'rating' => getStat(
                "SELECT COALESCE(AVG(r.rate), 0) as total FROM rating r JOIN layanan l ON r.id_layanan = l.id WHERE l.id_merchant = ? AND r.created_at BETWEEN ? AND ?",
                [$merchant->id, $startDateStr, $endDateStr]
            )
        ];

        $monthlyStats = [
            'pendapatan' => getStat(
                "SELECT SUM(p.amount) as total FROM booking b JOIN pembayaran p ON p.id_booking = b.id WHERE b.id_merchant = ? AND p.status_pembayaran = 'berhasil' AND MONTH(p.payment_date) = MONTH(CURRENT_DATE()) AND YEAR(p.payment_date) = YEAR(CURRENT_DATE())",
                [$merchant->id]
            ),
            'pesanan' => getStat(
                "SELECT COUNT(*) as total FROM booking b WHERE b.id_merchant = ? AND MONTH(b.created_at) = MONTH(CURRENT_DATE()) AND YEAR(b.created_at) = YEAR(CURRENT_DATE())",
                [$merchant->id]
            ),
            'views' => getStat(
                "SELECT COUNT(*) as total FROM layanan_views lv JOIN layanan l ON lv.id_layanan = l.id WHERE l.id_merchant = ? AND MONTH(lv.created_at) = MONTH(CURRENT_DATE()) AND YEAR(lv.created_at) = YEAR(CURRENT_DATE())",
                [$merchant->id]
            ),
            'rating' => getStat(
                "SELECT COALESCE(AVG(r.rate), 0) as total FROM rating r JOIN layanan l ON r.id_layanan = l.id WHERE l.id_merchant = ? AND MONTH(r.created_at) = MONTH(CURRENT_DATE()) AND YEAR(r.created_at) = YEAR(CURRENT_DATE())",
                [$merchant->id]
            )
        ];

        // Statistik periode sebelumnya untuk perbandingan tren
        $prevStartDate = clone $startDate;
        $prevEndDate = clone $startDate;
        $prevStartDate = $prevStartDate->subDays($startDate->diffInDays($endDate));
        $prevStartDateStr = $prevStartDate->format('Y-m-d');
        $prevEndDateStr = $prevEndDate->format('Y-m-d');

        // Tren pendapatan
        $prevPendapatan = getStat(
            "SELECT SUM(p.amount) as total FROM booking b JOIN pembayaran p ON p.id_booking = b.id WHERE b.id_merchant = ? AND p.status_pembayaran = 'berhasil' AND p.payment_date BETWEEN ? AND ?",
            [$merchant->id, $prevStartDateStr, $prevEndDateStr]
        );
        $pendapatanTrend = $prevPendapatan > 0 ? round((($weeklyStats['pendapatan'] - $prevPendapatan) / $prevPendapatan) * 100) : 0;

        // Tren pesanan
        $prevPesanan = getStat(
            "SELECT COUNT(*) as total FROM booking b WHERE b.id_merchant = ? AND b.created_at BETWEEN ? AND ?",
            [$merchant->id, $prevStartDateStr, $prevEndDateStr]
        );
        $pesananTrend = $prevPesanan > 0 ? round((($weeklyStats['pesanan'] - $prevPesanan) / $prevPesanan) * 100) : 0;

        // Tren views
        $prevViews = getStat(
            "SELECT COUNT(*) as total FROM layanan_views lv JOIN layanan l ON lv.id_layanan = l.id WHERE l.id_merchant = ? AND lv.created_at BETWEEN ? AND ?",
            [$merchant->id, $prevStartDateStr, $prevEndDateStr]
        );
        $viewsTrend = $prevViews > 0 ? round((($weeklyStats['views'] - $prevViews) / $prevViews) * 100) : 0;

        // Tren rating
        $prevRating = getStat(
            "SELECT COALESCE(AVG(r.rate), 0) as total FROM rating r JOIN layanan l ON r.id_layanan = l.id WHERE l.id_merchant = ? AND r.created_at BETWEEN ? AND ?",
            [$merchant->id, $prevStartDateStr, $prevEndDateStr]
        );
        $ratingTrend = $prevRating > 0 ? round(($weeklyStats['rating'] - $prevRating), 1) : 0;

        // Perbaiki query untuk mendapatkan data revenue
        $revenueData = DB::select(
            "SELECT DATE(p.payment_date) as date, SUM(p.amount) as total 
            FROM booking b 
            JOIN pembayaran p ON b.id = p.id_booking 
            WHERE b.id_merchant = ? 
            AND p.payment_date BETWEEN ? AND ? 
            GROUP BY DATE(p.payment_date) 
            ORDER BY date",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        // Jika tidak ada data, buat data dummy untuk 7 hari terakhir
        if (empty($revenueData)) {
            $revenueData = [];
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $revenueData[] = (object)[
                    'date' => $currentDate->format('Y-m-d'),
                    'total' => rand(100000, 1000000) // Data dummy untuk visualisasi
                ];
                $currentDate->addDay();
            }
        }

        // Data untuk grafik pesanan
        $orderData = DB::select(
            "SELECT DATE(b.created_at) as date, COUNT(*) as total 
            FROM booking b 
            WHERE b.id_merchant = ? 
            AND b.created_at BETWEEN ? AND ? 
            GROUP BY DATE(b.created_at) 
            ORDER BY date",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        // Jika tidak ada data pesanan, buat data dummy
        if (empty($orderData)) {
            $orderData = [];
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $orderData[] = (object)[
                    'date' => $currentDate->format('Y-m-d'),
                    'total' => rand(1, 10) // Data dummy untuk visualisasi
                ];
                $currentDate->addDay();
            }
        }

        // Data untuk grafik views
        $viewsData = DB::select(
            "SELECT DATE(lv.created_at) as date, COUNT(*) as total 
            FROM layanan_views lv 
            JOIN layanan l ON lv.id_layanan = l.id 
            WHERE l.id_merchant = ? 
            AND lv.created_at BETWEEN ? AND ? 
            GROUP BY DATE(lv.created_at) 
            ORDER BY date",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        // Jika tidak ada data views, buat data dummy
        if (empty($viewsData)) {
            $viewsData = [];
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $viewsData[] = (object)[
                    'date' => $currentDate->format('Y-m-d'),
                    'total' => rand(5, 50) // Data dummy untuk visualisasi
                ];
                $currentDate->addDay();
            }
        }

        // Data untuk grafik rating
        $ratingData = DB::select(
            "SELECT DATE(r.created_at) as date, AVG(r.rate) as total 
            FROM rating r 
            JOIN layanan l ON r.id_layanan = l.id 
            WHERE l.id_merchant = ? 
            AND r.created_at BETWEEN ? AND ? 
            GROUP BY DATE(r.created_at) 
            ORDER BY date",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        // Jika tidak ada data rating, buat data dummy
        if (empty($ratingData)) {
            $ratingData = [];
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $ratingData[] = (object)[
                    'date' => $currentDate->format('Y-m-d'),
                    'total' => round(rand(30, 50) / 10, 1) // Rating antara 3.0-5.0
                ];
                $currentDate->addDay();
            }
        }

        $servicePerformance = DB::select(
            "SELECT l.id, l.nama_layanan, COUNT(b.id) as total_orders, 
            SUM(p.amount) as total_revenue, AVG(r.rate) as avg_rating,
            COUNT(DISTINCT b.id_user) as unique_customers,
            (COUNT(CASE WHEN b.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) / 
             COUNT(b.id)) * 100 as growth_rate
            FROM layanan l
            LEFT JOIN booking b ON l.id = b.id_layanan
            LEFT JOIN pembayaran p ON b.id = p.id_booking
            LEFT JOIN rating r ON l.id = r.id_layanan
            WHERE l.id_merchant = ?
            GROUP BY l.id, l.nama_layanan
            ORDER BY total_revenue DESC",
            [$merchant->id]
        );

        // Data rating dan ulasan
        $avgRating = DB::selectOne(
            "SELECT COALESCE(AVG(r.rate), 0) as avg_rating 
            FROM rating r 
            JOIN layanan l ON r.id_layanan = l.id 
            WHERE l.id_merchant = ?",
            [$merchant->id]
        )->avg_rating;

        $ratingCount = DB::selectOne(
            "SELECT COUNT(*) as total 
            FROM rating r 
            JOIN layanan l ON r.id_layanan = l.id 
            WHERE l.id_merchant = ?",
            [$merchant->id]
        )->total;

        $reviewCount = DB::selectOne(
            "SELECT COUNT(*) as total 
            FROM rating r 
            JOIN layanan l ON r.id_layanan = l.id 
            WHERE l.id_merchant = ? AND r.message IS NOT NULL AND r.message != ''",
            [$merchant->id]
        )->total;

        // Distribusi rating
        $ratingStats = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingStats[$i] = DB::selectOne(
                "SELECT COUNT(*) as total 
                FROM rating r 
                JOIN layanan l ON r.id_layanan = l.id 
                WHERE l.id_merchant = ? AND r.rate = ?",
                [$merchant->id, $i]
            )->total;
        }

        // Ulasan terbaru
        $latestReviews = DB::select(
            "SELECT r.*, u.nama as nama_user 
            FROM rating r 
            JOIN layanan l ON r.id_layanan = l.id 
            JOIN users u ON r.id_user = u.id
            WHERE l.id_merchant = ? AND r.message IS NOT NULL AND r.message != '' 
            ORDER BY r.created_at DESC 
            LIMIT 5",
            [$merchant->id]
        );

        // Hitung tingkat penyelesaian dan pembatalan
        $totalOrders = getStat(
            "SELECT COUNT(*) as total FROM booking b WHERE b.id_merchant = ? AND b.created_at BETWEEN ? AND ?",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        $completedOrders = getStat(
            "SELECT COUNT(*) as total FROM booking b WHERE b.id_merchant = ? AND b.status_proses = 'selesai' AND b.created_at BETWEEN ? AND ?",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        $cancelledOrders = getStat(
            "SELECT COUNT(*) as total FROM booking b WHERE b.id_merchant = ? AND b.status_proses = 'dibatalkan' AND b.created_at BETWEEN ? AND ?",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        $completionRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100) : 0;
        $cancellationRate = $totalOrders > 0 ? round(($cancelledOrders / $totalOrders) * 100) : 0;

        // Hitung pelanggan berulang dan baru
        $totalCustomers = getStat(
            "SELECT COUNT(DISTINCT b.id_user) as total FROM booking b WHERE b.id_merchant = ? AND b.created_at 
            BETWEEN ? AND ?",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        $returningCustomers = getStat(
            "SELECT COUNT(DISTINCT b.id_user) as total 
            FROM booking b 
            WHERE b.id_merchant = ? 
            AND b.created_at BETWEEN ? AND ?
            AND b.id_user IN (
                SELECT id_user FROM booking 
                WHERE id_merchant = ? 
                AND created_at < ?
                GROUP BY id_user
            )",
            [$merchant->id, $startDateStr, $endDateStr, $merchant->id, $startDateStr]
        );

        $newCustomers = $totalCustomers - $returningCustomers;
        $returningCustomersPercentage = $totalCustomers > 0 ? round(($returningCustomers / $totalCustomers) * 100) : 0;
        $newCustomersPercentage = $totalCustomers > 0 ? round(($newCustomers / $totalCustomers) * 100) : 0;
        // Format tanggal untuk label chart
        $orderLabels = [];
        $orderDataValues = [];
        $viewsLabels = [];
        $viewsDataValues = [];
        $ratingLabels = [];
        $ratingDataValues = [];

        // Pastikan semua data memiliki format yang sama
        foreach ($revenueData as $data) {
            $data->date = \Carbon\Carbon::parse($data->date)->format('d M');
        }

        foreach ($orderData as $data) {
            $orderLabels[] = \Carbon\Carbon::parse($data->date)->format('d M');
            $orderDataValues[] = (int)$data->total;
        }

        foreach ($viewsData as $data) {
            $viewsLabels[] = \Carbon\Carbon::parse($data->date)->format('d M');
            $viewsDataValues[] = (int)$data->total;
        }

        foreach ($ratingData as $data) {
            $ratingLabels[] = \Carbon\Carbon::parse($data->date)->format('d M');
            $ratingDataValues[] = (float)$data->total;
        }

        return view('merchant.analytics', compact(
            'weeklyStats',
            'monthlyStats',
            'revenueData',
            'orderData',
            'viewsData',
            'ratingData',
            'servicePerformance',
            'pendapatanTrend',
            'pesananTrend',
            'viewsTrend',
            'ratingTrend',
            'avgRating',
            'ratingCount',
            'reviewCount',
            'ratingStats',
            'latestReviews',
            'completionRate',
            'cancellationRate',
            'returningCustomersPercentage',
            'newCustomersPercentage',
            'orderLabels',
            'orderDataValues',
            'viewsLabels',
            'viewsDataValues',
            'ratingLabels',
            'ratingDataValues'
        ));
    }

    public function getAnalyticsData(Request $request)
    {
        $merchant = Auth::user()->merchant;
        $period = $request->input('period', 'week');

        // Set tanggal berdasarkan periode
        $endDate = now();
        $startDate = clone $endDate;

        switch ($period) {
            case 'month':
                $startDate->startOfMonth();
                break;
            case 'month30':
                $startDate->subDays(30);
                break;
            case 'week':
            default:
                $startDate->subDays(7);
                break;
        }

        $startDateStr = $startDate->format('Y-m-d');
        $endDateStr = $endDate->format('Y-m-d');

        // Helper function untuk mendapatkan statistik
        $getStat = function ($query, $params) {
            $result = DB::select($query, $params);
            return $result[0]->total ?? 0;
        };

        // Data untuk grafik pendapatan
        $revenueData = DB::select(
            "SELECT DATE(p.payment_date) as date, SUM(p.amount) as total 
            FROM booking b 
            JOIN pembayaran p ON b.id = p.id_booking 
            WHERE b.id_merchant = ? 
            AND p.status_pembayaran = 'berhasil' 
            AND p.payment_date BETWEEN ? AND ? 
            GROUP BY DATE(p.payment_date) 
            ORDER BY date",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        // Jika tidak ada data, buat data dummy
        if (empty($revenueData)) {
            $revenueData = [];
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $revenueData[] = (object)[
                    'date' => $currentDate->format('Y-m-d'),
                    'total' => 0
                ];
                $currentDate->addDay();
            }
        }

        // Data untuk grafik pesanan
        $orderData = DB::select(
            "SELECT DATE(b.created_at) as date, COUNT(*) as total 
            FROM booking b 
            WHERE b.id_merchant = ? 
            AND b.created_at BETWEEN ? AND ? 
            GROUP BY DATE(b.created_at) 
            ORDER BY date",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        // Data untuk grafik views
        $viewsData = DB::select(
            "SELECT DATE(lv.created_at) as date, COUNT(*) as total 
            FROM layanan_views lv 
            JOIN layanan l ON lv.id_layanan = l.id 
            WHERE l.id_merchant = ? 
            AND lv.created_at BETWEEN ? AND ? 
            GROUP BY DATE(lv.created_at) 
            ORDER BY date",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        // Data untuk grafik rating
        $ratingData = DB::select(
            "SELECT DATE(r.created_at) as date, AVG(r.rate) as total 
            FROM rating r 
            JOIN layanan l ON r.id_layanan = l.id 
            WHERE l.id_merchant = ? 
            AND r.created_at BETWEEN ? AND ? 
            GROUP BY DATE(r.created_at) 
            ORDER BY date",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        // Format data untuk response
        $formatData = function ($data) {
            $labels = [];
            $values = [];

            foreach ($data as $item) {
                $labels[] = \Carbon\Carbon::parse($item->date)->format('d M');
                $values[] = $item->total;
            }

            return [
                'labels' => $labels,
                'data' => $values
            ];
        };

        // Hitung statistik
        $pendapatan = $getStat(
            "SELECT SUM(p.amount) as total FROM booking b JOIN pembayaran p ON p.id_booking = b.id WHERE b.id_merchant = ? AND p.status_pembayaran = 'berhasil' AND p.payment_date BETWEEN ? AND ?",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        $pesanan = $getStat(
            "SELECT COUNT(*) as total FROM booking b WHERE b.id_merchant = ? AND b.created_at BETWEEN ? AND ?",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        $views = $getStat(
            "SELECT COUNT(*) as total FROM layanan_views lv JOIN layanan l ON lv.id_layanan = l.id WHERE l.id_merchant = ? AND lv.created_at BETWEEN ? AND ?",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        $rating = $getStat(
            "SELECT COALESCE(AVG(r.rate), 0) as total FROM rating r JOIN layanan l ON r.id_layanan = l.id WHERE l.id_merchant = ? AND r.created_at BETWEEN ? AND ?",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        return response()->json([
            'revenue' => $formatData($revenueData),
            'orders' => $formatData($orderData),
            'views' => $formatData($viewsData),
            'rating' => $formatData($ratingData),
            'stats' => [
                'pendapatan' => 'Rp ' . number_format($pendapatan, 0, ',', '.'),
                'pesanan' => $pesanan,
                'views' => $views,
                'rating' => number_format($rating, 1)
            ],
            'trends' => [
                'pendapatan' => 0, // Tambahkan logika tren jika diperlukan
                'pesanan' => 0,
                'views' => 0,
                'rating' => 0
            ]
        ]);
    }
}
