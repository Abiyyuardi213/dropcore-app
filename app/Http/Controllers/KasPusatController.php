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

        // Lists for Transfer Modal
        $listKas = \App\Models\SumberKeuangan::where('jenis', 'tunai')->where('is_active', 1)->get();
        $listBank = \App\Models\SumberKeuangan::where('jenis', 'bank')->where('is_active', 1)->get();

        return view('keuangan.kas_pusat.index', compact(
            'totalLiquidity',
            'bankTotal',
            'cashTotal',
            'ewalletTotal',
            'recentTransactions',
            'listKas',
            'listBank'
        ));
    }

    public function storeTransfer(Request $request)
    {
        $request->validate([
            'sumber_asal_id' => 'required|exists:sumber_keuangan,id',
            'sumber_tujuan_id' => 'required|exists:sumber_keuangan,id|different:sumber_asal_id',
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string'
        ]);

        $sumberAsal = \App\Models\SumberKeuangan::findOrFail($request->sumber_asal_id);
        $sumberTujuan = \App\Models\SumberKeuangan::findOrFail($request->sumber_tujuan_id);

        if ($sumberAsal->saldo < $request->jumlah) {
            return back()->with('error', 'Saldo kas tidak mencukupi untuk transfer.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $sumberAsal, $sumberTujuan) {
            // Generate Transaction Number
            $noTransaksi = 'TRF-' . date('YmdHis');
            $user_id = \Illuminate\Support\Facades\Auth::user()->id; // Explicitly get ID

            // 1. Catat di tabel TransferSaldo (Record Utama)
            $transfer = \App\Models\TransferSaldo::create([
                'no_transaksi' => $noTransaksi,
                'sumber_asal_id' => $sumberAsal->id,
                'sumber_tujuan_id' => $sumberTujuan->id,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan,
                'user_id' => $user_id,
            ]);

            // 2. Update Saldo
            $sumberAsal->decrement('saldo', $request->jumlah);
            $sumberTujuan->increment('saldo', $request->jumlah);

            // 3. Catat Log Keuangan (Agar muncul di dashboard/history)

            // Pengeluaran dari Asal
            \App\Models\Keuangan::create([
                'no_transaksi' => $noTransaksi . '-OUT', // Suffix beda agar unik jika no_transaksi harus unik
                'jenis_transaksi' => 'pengeluaran',
                'sumber_id' => $sumberAsal->id,
                'jumlah' => $request->jumlah,
                'tanggal_transaksi' => now(),
                'status' => 'selesai',
                'keterangan' => 'Transfer Keluar ke ' . $sumberTujuan->nama_sumber . '. ' . $request->keterangan,
                'user_id' => $user_id,
                'bukti_transaksi' => $transfer->id // Link ke ID transfer
            ]);

            // Pemasukkan ke Tujuan
            \App\Models\Keuangan::create([
                'no_transaksi' => $noTransaksi . '-IN',
                'jenis_transaksi' => 'pemasukkan',
                'sumber_id' => $sumberTujuan->id,
                'jumlah' => $request->jumlah,
                'tanggal_transaksi' => now(),
                'status' => 'selesai',
                'keterangan' => 'Transfer Masuk dari ' . $sumberAsal->nama_sumber . '. ' . $request->keterangan,
                'user_id' => $user_id,
                'bukti_transaksi' => $transfer->id
            ]);
        });

        return redirect()->route('kas-pusat.index')->with('success', 'Transfer antar kas berhasil dilakukan.');
    }
}
