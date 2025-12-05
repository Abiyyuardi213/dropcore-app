<?php

namespace App\Http\Controllers;

use App\Models\KasPusat;
use App\Models\Keuangan;
use Illuminate\Http\Request;

class DashboardKeuanganController extends Controller
{
    public function index()
{
    $kas = KasPusat::first();
    $totalSaldo = $kas ? $kas->saldo_saat_ini : 0;

    $totalPemasukkan = Keuangan::where('jenis_transaksi', 'pemasukkan')->sum('jumlah');
    $totalPengeluaran = Keuangan::where('jenis_transaksi', 'pengeluaran')->sum('jumlah');

    $dataBulanan = Keuangan::selectRaw("
        DATE_FORMAT(tanggal_transaksi, '%Y-%m') AS bulan,
        SUM(CASE WHEN jenis_transaksi = 'pemasukkan' THEN jumlah ELSE 0 END) AS total_pemasukkan,
        SUM(CASE WHEN jenis_transaksi = 'pengeluaran' THEN jumlah ELSE 0 END) AS total_pengeluaran
    ")
    ->groupBy('bulan')
    ->orderBy('bulan')
    ->limit(12)
    ->get();

    return view('dashboard-keuangan', compact(
        'totalSaldo',
        'totalPemasukkan',
        'totalPengeluaran',
        'dataBulanan'
    ));
}
}
