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

        $totalPemasukkan = Keuangan::where('jenis_transaksi', 'pemasukkan')
                                ->sum('jumlah');

        $totalPengeluaran = Keuangan::where('jenis_transaksi', 'pengeluaran')
                                ->sum('jumlah');

        return view('dashboard-keuangan', compact(
            'totalSaldo',
            'totalPemasukkan',
            'totalPengeluaran',
        ));
    }
}
