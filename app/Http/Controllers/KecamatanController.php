<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kecamatan;
use App\Models\Kota;
use App\Models\Provinsi;
use App\Models\Wilayah;

class KecamatanController extends Controller
{
    public function index()
    {
        $kotas = Kota::all();
        $kecamatans = Kecamatan::with('kota.provinsi.wilayah')->orderBy('created_at')->get();
        return view('kecamatan.index', compact('kecamatans', 'kotas'));
    }

    public function create()
    {
        $kotas = Kota::all();
        return view('kecamatan.create', compact('kotas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kota_id' => 'required|exists:kota,id',
            'kecamatan' => 'required|string|max:255|unique:kecamatan,kecamatan',
        ]);

        Kecamatan::createKecamatan($request->all());

        return redirect()->route('kecamatan.index')->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        $kotas = Kota::all();
        return view('kecamatan.index', compact('kecamatan', 'kotas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kota_id' => 'required|exists:kota,id',
            'kecamatan' => 'required|string|max:255|unique:kecamatan,kecamatan,' . $id . ',id',
        ]);

        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->updateKecamatan($request->all());

        return redirect()->route('kecamatan.index')->with('success', 'Kecamatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->deleteKecamatan();

        return redirect()->route('kecamatan.index')->with('success', 'Kecamatan berhasil dihapus.');
    }
}
