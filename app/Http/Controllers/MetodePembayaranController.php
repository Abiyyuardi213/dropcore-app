<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use Illuminate\Http\Request;

class MetodePembayaranController extends Controller
{
    public function index()
    {
        $methods = MetodePembayaran::latest()->get();
        return view('admin.metode-pembayaran.index', compact('methods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        MetodePembayaran::create($request->all());

        return back()->with('success', 'Metode Pembayaran berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:255',
        ]);

        $method = MetodePembayaran::findOrFail($id);
        $method->update($request->all());

        return back()->with('success', 'Metode Pembayaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        MetodePembayaran::findOrFail($id)->delete();
        return back()->with('success', 'Metode Pembayaran berhasil dihapus');
    }

    public function toggleStatus($id)
    {
        $item = MetodePembayaran::findOrFail($id);
        $item->status = !$item->status;
        $item->save();
        return back()->with('success', 'Status berhasil diubah');
    }
}
