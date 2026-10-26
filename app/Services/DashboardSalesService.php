<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class DashboardSalesService
{
    public function trend(?CarbonImmutable $today = null): array
    {
        $month = ($today ?? CarbonImmutable::now('Asia/Jakarta'))->startOfMonth();
        $start = $month->subMonths(5)->toDateTimeString();
        $end = $month->addMonth()->toDateTimeString();
        // Keep the existing active-order definition. Orders include their own totals.
        $orders = DB::table('orders')->where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $start)->where('created_at', '<', $end)
            ->selectRaw('SUBSTR(created_at, 1, 7) as bulan, SUM(total_price) as nilai')
            ->groupByRaw('SUBSTR(created_at, 1, 7)')->get()->keyBy('bulan');

        $detailTotals = DB::table('pengeluaran_barang_detail')->select('pengeluaran_id')
            ->selectRaw('SUM(subtotal) as nilai')->groupBy('pengeluaran_id');
        $outgoing = DB::table('pengeluaran_barang as p')->where('p.status', 'completed')
            ->where('p.tanggal_pengeluaran', '>=', substr($start, 0, 10))->where('p.tanggal_pengeluaran', '<', substr($end, 0, 10));
        $counts = (clone $outgoing)->selectRaw('SUBSTR(p.tanggal_pengeluaran, 1, 7) as bulan, COUNT(*) as jumlah')
            ->groupByRaw('SUBSTR(p.tanggal_pengeluaran, 1, 7)')->get()->keyBy('bulan');
        $directSales = (clone $outgoing)->leftJoinSub($detailTotals, 'detail_totals', 'detail_totals.pengeluaran_id', '=', 'p.id')
            // An order-generated goods issue is already represented by its order amount,
            // even if the order and shipping dates fall in different months.
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')->from('orders as o')->whereColumn('o.order_number', 'p.referensi')->where('o.status', '!=', 'cancelled');
            })
            ->selectRaw('SUBSTR(p.tanggal_pengeluaran, 1, 7) as bulan, SUM(COALESCE(detail_totals.nilai, 0)) as nilai')
            ->groupByRaw('SUBSTR(p.tanggal_pengeluaran, 1, 7)')->get()->keyBy('bulan');

        $result = ['salesChartLabels' => [], 'salesChartData' => [], 'pengeluaranChartData' => []];
        for ($offset = 5; $offset >= 0; $offset--) {
            $date = $month->subMonths($offset); $key = $date->format('Y-m');
            $result['salesChartLabels'][] = $date->translatedFormat('M Y');
            $result['salesChartData'][] = round((float) ($orders->get($key)->nilai ?? 0) + (float) ($directSales->get($key)->nilai ?? 0), 2);
            $result['pengeluaranChartData'][] = (int) ($counts->get($key)->jumlah ?? 0);
        }
        $result['totalPenjualanBulanIni'] = $result['salesChartData'][5];
        return $result;
    }
}
