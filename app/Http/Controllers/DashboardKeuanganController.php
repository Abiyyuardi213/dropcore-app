<?php

namespace App\Http\Controllers;

use App\Models\KasPusat;
use App\Models\Keuangan;
use Illuminate\Http\Request;

class DashboardKeuanganController extends Controller
{
    public function index()
    {
        // 1. Total Liquidity & Breakdown
        $totalSaldo = \App\Models\SumberKeuangan::sum('saldo');
        $saldoBank = \App\Models\SumberKeuangan::where('jenis', 'bank')->sum('saldo');
        $saldoTunai = \App\Models\SumberKeuangan::where('jenis', 'tunai')->sum('saldo');
        $saldoEwallet = \App\Models\SumberKeuangan::where('jenis', 'e-wallet')->sum('saldo');

        // 2. Total In/Out (All Time) - or Filtered by Year if needed
        $totalPemasukkan = Keuangan::where('jenis_transaksi', 'pemasukkan')->sum('jumlah');
        $totalPengeluaran = Keuangan::where('jenis_transaksi', 'pengeluaran')->sum('jumlah');

        // 3. Chart Data (Monthly this year)
        $dataBulanan = Keuangan::selectRaw("
            DATE_FORMAT(tanggal_transaksi, '%Y-%m') AS bulan,
            SUM(CASE WHEN jenis_transaksi = 'pemasukkan' THEN jumlah ELSE 0 END) AS total_pemasukkan,
            SUM(CASE WHEN jenis_transaksi = 'pengeluaran' THEN jumlah ELSE 0 END) AS total_pengeluaran
        ")
            ->whereYear('tanggal_transaksi', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // 4. Expense by Category (For Pie Chart)
        $expenseByCategory = Keuangan::selectRaw('kategori_keuangan.nama as kategori, SUM(keuangan.jumlah) as total')
            ->join('kategori_keuangan', 'keuangan.kategori_keuangan_id', '=', 'kategori_keuangan.id')
            ->where('keuangan.jenis_transaksi', 'pengeluaran')
            ->groupBy('kategori_keuangan.nama')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('dashboard-keuangan', compact(
            'totalSaldo',
            'saldoBank',
            'saldoTunai',
            'saldoEwallet',
            'totalPemasukkan',
            'totalPengeluaran',
            'dataBulanan',
            'expenseByCategory'
        ));
    }
}
