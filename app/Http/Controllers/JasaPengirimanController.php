<?php

namespace App\Http\Controllers;

use App\Models\JasaPengiriman;
use Illuminate\Http\Request;

class JasaPengirimanController extends Controller
{
    public function index()
    {
        $services = JasaPengiriman::latest()->get();
        return view('admin.jasa-pengiriman.index', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:50',
            'biaya_dasar' => 'nullable|numeric|min:0',
        ]);

        JasaPengiriman::create([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'biaya_dasar' => $request->biaya_dasar ?? 0,
            'status' => true
        ]);

        return back()->with('success', 'Jasa Pengiriman berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:50',
            'biaya_dasar' => 'nullable|numeric|min:0',
        ]);

        $service = JasaPengiriman::findOrFail($id);
        $service->update([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'biaya_dasar' => $request->biaya_dasar ?? 0,
        ]);

        return back()->with('success', 'Jasa Pengiriman berhasil diperbarui');
    }

    public function destroy($id)
    {
        JasaPengiriman::findOrFail($id)->delete();
        return back()->with('success', 'Jasa Pengiriman berhasil dihapus');
    }

    public function toggleStatus($id)
    {
        $item = JasaPengiriman::findOrFail($id);
        $item->status = !$item->status;
        $item->save();
        return back()->with('success', 'Status berhasil diubah');
    }
}
