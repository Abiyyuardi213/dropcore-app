<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DivisiController extends Controller
{
    public function index()
    {
        $data = Divisi::latest()->get();
        return view('divisi.index', compact('data'));
    }

    public function create()
    {
        return view('divisi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|unique:divisi,kode|max:50',
            'name' => 'required|string|max:255',
            'kepala_divisi' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Divisi::create($request->all());

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $divisi = Divisi::findOrFail($id);
        return view('divisi.edit', compact('divisi'));
    }

    public function update(Request $request, $id)
    {
        $divisi = Divisi::findOrFail($id);

        $request->validate([
            'kode' => ['required', 'string', 'max:50', Rule::unique('divisi', 'kode')->ignore($divisi->id)],
            'name' => 'required|string|max:255',
            'kepala_divisi' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $divisi->update($request->all());

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Divisi::findOrFail($id)->delete();
        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil dihapus.');
    }
}
