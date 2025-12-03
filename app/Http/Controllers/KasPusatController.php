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
                'saldo' => 0,
            ]);
        }

        return view('keuangan.kas_pusat.index', compact('kas'));
    }

    public function edit()
    {
        $kas = KasPusat::first();

        return view('keuangan.kas_pusat.edit', compact('kas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'saldo' => 'required|numeric|min:0',
        ]);

        $kas = KasPusat::findOrFail($id);

        $kas->saldo = $request->saldo;
        $kas->save();

        return redirect()->route('kas-pusat.index')
            ->with('success', 'Saldo kas pusat berhasil diperbarui.');
    }
}
