<?php

namespace App\Http\Controllers;

use App\Models\KasPusat;
use Illuminate\Http\Request;

class KasPusatController extends Controller
{
    public function index()
    {
        $kas = KasPusat::first();

        if (!$kas) {
            $kas = KasPusat::create([
                'saldo_awal' => 0,
                'saldo_saat_ini' => 0,
            ]);
        }

        return view('keuangan.kas_pusat.index', compact('kas'));
    }

    public function edit()
    {
        $kas = KasPusat::first();

        return view('keuangan.kas_pusat.edit', compact('kas'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'saldo_awal' => 'required|numeric|min:0',
        ]);

        $kas = KasPusat::first();

        $kas->saldo_awal = $request->saldo_awal;
        $kas->saldo_saat_ini = $request->saldo_awal;
        $kas->save();

        return redirect()->route('kas-pusat.index')
            ->with('success', 'Saldo kas pusat berhasil diperbarui.');
    }
}
