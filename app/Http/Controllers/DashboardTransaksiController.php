<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardTransaksiController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['tanggal' => ['nullable', 'date_format:Y-m-d']]);
        $tanggal = $request->input('tanggal') ?: now('Asia/Jakarta')->toDateString();
        $end = CarbonImmutable::parse($tanggal, 'Asia/Jakarta');
        $start = $end->subDays(6)->toDateString();
        $saleTotals = DB::table('pengeluaran_barang_detail')->select('pengeluaran_id')->selectRaw('SUM(subtotal) as nilai')->groupBy('pengeluaran_id');

        $receipts = DB::table('penerimaan_barang')->whereBetween('tanggal_penerimaan', [$start, $tanggal])
            ->selectRaw("DATE(tanggal_penerimaan) as hari, SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as selesai,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as draft,
                SUM(CASE WHEN status = 'canceled' THEN 1 ELSE 0 END) as batal,
                SUM(CASE WHEN status = 'completed' THEN total_amount ELSE 0 END) as nilai")
            ->groupByRaw('DATE(tanggal_penerimaan)')->get()->keyBy('hari');
        $sales = DB::table('pengeluaran_barang as p')->leftJoinSub($saleTotals, 'totals', 'totals.pengeluaran_id', '=', 'p.id')
            ->whereBetween('p.tanggal_pengeluaran', [$start, $tanggal])
            ->selectRaw("DATE(p.tanggal_pengeluaran) as hari, SUM(CASE WHEN p.status = 'completed' THEN 1 ELSE 0 END) as selesai,
                SUM(CASE WHEN p.status = 'pending' THEN 1 ELSE 0 END) as draft,
                SUM(CASE WHEN p.status = 'canceled' THEN 1 ELSE 0 END) as batal,
                SUM(CASE WHEN p.status = 'completed' THEN COALESCE(totals.nilai, 0) ELSE 0 END) as nilai")
            ->groupByRaw('DATE(p.tanggal_pengeluaran)')->get()->keyBy('hari');
        $harian = collect(range(0, 6))->map(function ($offset) use ($end, $receipts, $sales) {
            $day = $end->subDays(6 - $offset)->toDateString();
            $in = $receipts->get($day); $out = $sales->get($day);
            return [
                'tanggal' => $day, 'penerimaan' => (int) ($in->selesai ?? 0), 'pengeluaran' => (int) ($out->selesai ?? 0),
                'pembelian' => (float) ($in->nilai ?? 0), 'penjualan' => (float) ($out->nilai ?? 0),
                'draft' => (int) ($in->draft ?? 0) + (int) ($out->draft ?? 0),
                'batal' => (int) ($in->batal ?? 0) + (int) ($out->batal ?? 0),
            ];
        });
        $ringkasan = $harian->last();
        $incoming = DB::table('penerimaan_barang')->select('id', 'no_penerimaan as nomor', 'tanggal_penerimaan as tanggal', 'status', 'payment_status', 'created_at', 'total_amount as nilai')->selectRaw("'penerimaan' as jenis");
        $outgoing = DB::table('pengeluaran_barang as p')->leftJoinSub($saleTotals, 'totals', 'totals.pengeluaran_id', '=', 'p.id')
            ->select('p.id', 'p.no_pengeluaran as nomor', 'p.tanggal_pengeluaran as tanggal', 'p.status', 'p.payment_status', 'p.created_at')
            ->selectRaw("COALESCE(totals.nilai, 0) as nilai, 'pengeluaran' as jenis");
        $terbaru = DB::query()->fromSub($incoming->unionAll($outgoing), 'transaksi')
            ->orderByDesc('created_at')->orderByDesc('id')->orderBy('jenis')->paginate(15)->withQueryString();

        return view('dashboard-transaksi', compact('tanggal', 'ringkasan', 'harian', 'terbaru'));
    }
}
