<?php
namespace App\Http\Controllers;
use App\Models\{KasirSale, KasirSaleItem};
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class KasirDashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = CarbonImmutable::now('Asia/Jakarta');
        $base = KasirSale::completed()->where('user_id', $request->user()->id);
        $periods = [
            'Hari Ini' => [$today->toDateString(), $today->toDateString()],
            'Bulan Ini' => [$today->startOfMonth()->toDateString(), $today->endOfMonth()->toDateString()],
            'Tahun Ini' => [$today->startOfYear()->toDateString(), $today->endOfYear()->toDateString()],
            'Seluruh Periode' => null,
        ];
        $summary = [];
        foreach ($periods as $period => $dates) {
            $query = clone $base;
            if ($dates) $query->whereDate('sale_date', '>=', $dates[0])->whereDate('sale_date', '<=', $dates[1]);
            $summary[$period] = ['total' => (float) (clone $query)->sum('total'), 'profit' => (clone $query)->whereNull('profit')->exists() ? null : (float) (clone $query)->sum('profit')];
        }
        $items = KasirSaleItem::whereIn('sale_id', (clone $base)->select('kasir_sales.id'));
        $stats = [
            'Produk Terjual' => (clone $items)->distinct()->count('product_id'),
            'Kategori Terjual' => (clone $items)->distinct()->count('category_id'),
            'Pelanggan' => (clone $base)->where('customer_name', '!=', 'Pelanggan Umum')->distinct()->count('customer_name'),
            'Transaksi Hari Ini' => (clone $base)->whereDate('sale_date', $today)->count(),
            'Unit Terjual' => (int) (clone $items)->sum('quantity'), 'Jumlah Invoice' => (clone $base)->count(),
        ];
        $daily = (clone $base)->whereDate('sale_date', '>=', $today->subDays(6)->toDateString())->whereDate('sale_date', '<=', $today->toDateString())
            ->selectRaw('sale_date, SUM(total) as amount')->groupBy('sale_date')->get()->keyBy(fn ($row) => $row->sale_date->format('Y-m-d'));
        $monthly = (clone $base)->whereDate('sale_date', '>=', $today->startOfYear()->toDateString())->whereDate('sale_date', '<=', $today->endOfYear()->toDateString())
            ->selectRaw('SUBSTR(sale_date, 1, 7) as month, SUM(total) as amount')->groupByRaw('SUBSTR(sale_date, 1, 7)')->get()->keyBy('month');
        $dailyLabels = []; $dailyData = [];
        foreach (range(6, 0) as $day) { $date = $today->subDays($day); $dailyLabels[] = $date->format('d/m'); $dailyData[] = (float) ($daily->get($date->toDateString())->amount ?? 0); }
        $monthlyData = [];
        foreach (range(1, 12) as $month) $monthlyData[] = (float) ($monthly->get($today->year.'-'.str_pad($month,2,'0',STR_PAD_LEFT))->amount ?? 0);
        return view('kasir.index', compact('summary','stats','dailyLabels','dailyData','monthlyData'));
    }
}
