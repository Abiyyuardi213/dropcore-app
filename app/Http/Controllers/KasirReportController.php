<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use App\Models\{KasirSale, KasirSaleItem};

class KasirReportController extends Controller
{
    public function index(Request $request)
    {
        $today = CarbonImmutable::now('Asia/Jakarta');
        $data = array_merge([
            'tanggal_awal' => $today->startOfMonth()->toDateString(),
            'tanggal_akhir' => $today->toDateString(),
        ], $request->only('tanggal_awal', 'tanggal_akhir'));
        $validated = validator($data, [
            'tanggal_awal' => ['required', 'date_format:Y-m-d'],
            'tanggal_akhir' => ['required', 'date_format:Y-m-d', 'after_or_equal:tanggal_awal'],
        ])->validate();
        $tanggalAwal = $validated['tanggal_awal'];
        $tanggalAkhir = $validated['tanggal_akhir'];
        $query = KasirSale::completed()->where('user_id', $request->user()->id)->whereDate('sale_date', '>=', $tanggalAwal)->whereDate('sale_date', '<=', $tanggalAkhir);
        $count = (clone $query)->count(); $total = (clone $query)->sum('total');
        $quantity = KasirSaleItem::whereIn('sale_id', (clone $query)->select('kasir_sales.id'))->sum('quantity');
        $transactions = $query->withSum('items', 'quantity')->orderByDesc('sale_date')->orderByDesc('created_at')->orderBy('id')->paginate(20)->withQueryString();
        return view('kasir.laporan', compact('tanggalAwal', 'tanggalAkhir', 'transactions', 'count', 'total', 'quantity'));
    }
}
