<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        
        // Revenue helper
        $getRevenue = function($date = null, $month = null, $year = null) {
            $pQuery = Payment::whereIn('status', ['lunas', 'sebagian_dibayar', 'sudah_dibayar']);
            $iQuery = \App\Models\Installment::where('status', 'paid');
            
            if ($date) {
                $pQuery->whereDate('updated_at', $date);
                $iQuery->whereDate('updated_at', $date);
            }
            if ($month && $year) {
                $pQuery->whereMonth('updated_at', $month)->whereYear('updated_at', $year);
                $iQuery->whereMonth('updated_at', $month)->whereYear('updated_at', $year);
            }
            if ($year && !$month) {
                $pQuery->whereYear('updated_at', $year);
                $iQuery->whereYear('updated_at', $year);
            }
            
            return $pQuery->sum('amount') + $iQuery->sum('amount');
        };

        $totalRevenue = $getRevenue();
        $ordersToday = Order::whereDate('created_at', now()->toDateString())->count();
        $revenueToday = $getRevenue(now()->toDateString());
        $revenueThisMonth = $getRevenue(null, now()->month, now()->year);

        // Status counts with mapping to handle capitalization/naming variations
        $rawStatuses = Order::selectRaw('production_status, COUNT(*) as total')
            ->groupBy('production_status')
            ->pluck('total', 'production_status');
            
        $statusCounts = [
            'menunggu_pembayaran' => 0,
            'diproses' => 0,
            'desain' => 0,
            'revisi' => 0,
            'cetak' => 0,
            'selesai' => 0,
        ];

        foreach($rawStatuses as $status => $count) {
            $s = strtolower($status);
            if(str_contains($s, 'pembayaran')) $statusCounts['menunggu_pembayaran'] += $count;
            elseif(str_contains($s, 'proses')) $statusCounts['diproses'] += $count;
            elseif(str_contains($s, 'desain')) $statusCounts['desain'] += $count;
            elseif(str_contains($s, 'revisi')) $statusCounts['revisi'] += $count;
            elseif(str_contains($s, 'cetak')) $statusCounts['cetak'] += $count;
            elseif(str_contains($s, 'selesai')) $statusCounts['selesai'] += $count;
        }

        $topProducts = Product::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalOrders',
            'totalRevenue',
            'ordersToday',
            'revenueToday',
            'revenueThisMonth',
            'statusCounts',
            'topProducts'
        ));
    }

    public function salesData(Request $request)
    {
        $level = $request->query('level', 'month'); // year, month, week, day
        $year = $request->query('year', now()->year);
        $month = $request->query('month', null);
        $week = $request->query('week', null);

        $getRevenueRaw = function($selectRaw, $groupBy, $additionalFilters = []) {
            $pQuery = Payment::whereIn('status', ['lunas', 'sebagian_dibayar', 'sudah_dibayar']);
            $iQuery = \App\Models\Installment::where('status', 'paid');
            
            foreach($additionalFilters as $col => $val) {
                if($val) {
                    $pQuery->whereRaw("$col = ?", [$val]);
                    $iQuery->whereRaw("$col = ?", [$val]);
                }
            }
            
            $pData = $pQuery->selectRaw("$selectRaw as label_key, SUM(amount) as total")->groupBy('label_key')->pluck('total', 'label_key');
            $iData = $iQuery->selectRaw("$selectRaw as label_key, SUM(amount) as total")->groupBy('label_key')->pluck('total', 'label_key');
            
            return [$pData, $iData];
        };

        if ($level === 'year') {
            // Data 5 tahun terakhir
            $data = [];
            for($i = 4; $i >= 0; $i--) {
                $y = now()->year - $i;
                [$p, $i_data] = $getRevenueRaw('YEAR(updated_at)', 'YEAR(updated_at)', ['YEAR(updated_at)' => $y]);
                $data[] = [
                    'x' => 'Tahun ' . $y,
                    'y' => (int) (($p[$y] ?? 0) + ($i_data[$y] ?? 0)),
                    'year_val' => $y
                ];
            }
        } elseif ($level === 'month') {
            $data = [];
            $monthNames = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            for($m = 1; $m <= 12; $m++) {
                [$p, $i_data] = $getRevenueRaw('MONTH(updated_at)', 'MONTH(updated_at)', ['YEAR(updated_at)' => $year, 'MONTH(updated_at)' => $m]);
                $data[] = [
                    'x' => $monthNames[$m],
                    'y' => (int) (($p[$m] ?? 0) + ($i_data[$m] ?? 0)),
                    'month_num' => $m
                ];
            }
        } elseif ($level === 'week') {
            $data = [];
            // Get weeks in month
            for($w = 1; $w <= 4; $w++) {
                $query = Payment::whereIn('status', ['lunas', 'sebagian_dibayar', 'sudah_dibayar'])
                    ->whereYear('updated_at', $year)
                    ->whereMonth('updated_at', $month);
                $iQuery = \App\Models\Installment::where('status', 'paid')
                    ->whereYear('updated_at', $year)
                    ->whereMonth('updated_at', $month);

                if ($w < 4) {
                    $query->whereRaw("FLOOR((DAY(updated_at)-1)/7) + 1 = ?", [$w]);
                    $iQuery->whereRaw("FLOOR((DAY(updated_at)-1)/7) + 1 = ?", [$w]);
                } else {
                    // Week 4 includes everything from day 22 onwards
                    $query->whereRaw("DAY(updated_at) >= 22");
                    $iQuery->whereRaw("DAY(updated_at) >= 22");
                }
                
                $pTotal = $query->sum('amount');
                $iTotal = $iQuery->sum('amount');
                
                $data[] = [
                    'x' => 'Minggu Ke-' . $w,
                    'y' => (int) ($pTotal + $iTotal),
                    'week_num' => $w
                ];
            }
        } elseif ($level === 'day') {
            $data = [];
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, (int)$month, (int)$year);
            $startDay = ($week - 1) * 7 + 1;
            $endDay = ($week == 4) ? $daysInMonth : ($week * 7);
            
            $indoDays = ['Sun' => 'Min', 'Mon' => 'Sen', 'Tue' => 'Sel', 'Wed' => 'Rab', 'Thu' => 'Kam', 'Fri' => 'Jum', 'Sat' => 'Sab'];
            $indoMonths = ["", "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];

            for($d = $startDay; $d <= $endDay; $d++) {
                $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $d);
                $time = strtotime($dateStr);
                $dayName = $indoDays[date('D', $time)];
                $monthName = $indoMonths[(int)date('m', $time)];
                
                $pTotal = Payment::whereIn('status', ['lunas', 'sebagian_dibayar', 'sudah_dibayar'])
                    ->whereDate('updated_at', $dateStr)
                    ->sum('amount');
                $iTotal = \App\Models\Installment::where('status', 'paid')
                    ->whereDate('updated_at', $dateStr)
                    ->sum('amount');
                
                $data[] = [
                    'x' => $dayName . ', ' . $d . ' ' . $monthName,
                    'y' => (int) ($pTotal + $iTotal)
                ];
            }
        } else {
            $data = [];
        }

        return response()->json($data);
    }
}
