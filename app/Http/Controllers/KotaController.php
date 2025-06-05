<?php

namespace App\Http\Controllers;

use App\Models\Kota;
use Illuminate\Http\Request;
use App\Models\Provinsi;

class KotaController extends Controller
{
    public function index()
    {
        $provinsis = Provinsi::where('status_provinsi', 1)->get();
        $kotas = Kota::with('provinsi.wilayah')->orderBy('created_at')->get();
        return view('kota.index', compact('kotas', 'provinsis'));
    }

    public function create()
    {
        $provinsis = Provinsi::where('status_provinsi', 1)->get();
        return view('kota.create', compact('provinsis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'provinsi_id' => 'required|exists:provinsi,id',
            'kota' => 'required|string|max:255|unique:kota,kota',
        ]);

        Kota::createKota($request->all());

        return redirect()->route('kota.index')->with('success', 'Kota berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kota = Kota::findOrFail($id);
        $provinsis = Provinsi::all();
        return view('kota.index', compact('kota', 'provinsis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'provinsi_id' => 'required|exists:provinsi,id',
            'kota' => 'required|string|max:255|unique:kota,kota,' . $id . ',id',
        ]);

        $kota = Kota::findOrFail($id);
        $kota->updateKota($request->all());

        return redirect()->route('kota.index')->with('success', 'Kota berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kota = Kota::findOrFail($id);
        $kota->deleteKota();

        return redirect()->route('kota.index')->with('success', 'Kota berhasil dihapus.');
    }
}
