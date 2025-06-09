<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;

class KelurahanController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::all();
        $kelurahans = Kelurahan::with('kecamatan.kota.provinsi.wilayah')->orderBy('created_at')->get();
        return view('kelurahan.index', compact('kelurahans', 'kecamatans'));
    }

    public function create()
    {
        $kecamatans = Kecamatan::all();
        return view('kelurahan.create', compact('kecamatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'kelurahan' => 'required|string|max:255|unique:kelurahan,kelurahan',
        ]);

        Kelurahan::createKelurahan($request->all());

        return redirect()->route('kelurahan.index')->with('success', 'Kelurahan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kelurahan = Kelurahan::findOrFail($id);
        $kecamatans = Kecamatan::all();
        return view('kelurahan.index', compact('kelurahan', 'kecamatans'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'kelurahan' => 'required|string|max:255|unique:kelurahan,kelurahan,' . $id . ',id',
        ]);

        $kelurahan = Kelurahan::findOrFail($id);
        $kelurahan->updateKelurahan($request->all());

        return redirect()->route('kelurahan.index')->with('success', 'Kelurahan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kelurahan = Kelurahan::findOrFail($id);
        $kelurahan->deleteKelurahan();

        return redirect()->route('kelurahan.index')->with('success', 'Kelurahan berhasil dihapus.');
    }
}
