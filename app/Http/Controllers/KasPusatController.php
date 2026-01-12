<?php

namespace App\Http\Controllers;

use App\Models\KasPusat;
use Illuminate\Http\Request;

class KasPusatController extends Controller
{
    public function index()
    {
        // Calculate Total Liquidity from all accounts
        $totalLiquidity = \App\Models\SumberKeuangan::sum('saldo');

        // Breakdown by Type
        $bankTotal = \App\Models\SumberKeuangan::where('jenis', 'bank')->sum('saldo');
        $cashTotal = \App\Models\SumberKeuangan::where('jenis', 'tunai')->sum('saldo');
        $ewalletTotal = \App\Models\SumberKeuangan::where('jenis', 'e-wallet')->sum('saldo');

        // Recent Transactions
        $recentTransactions = \App\Models\Keuangan::with(['sumber', 'kategori'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('keuangan.kas_pusat.index', compact('totalLiquidity', 'bankTotal', 'cashTotal', 'ewalletTotal', 'recentTransactions'));
    }
}
