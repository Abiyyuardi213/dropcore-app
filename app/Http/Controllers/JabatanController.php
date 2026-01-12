<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JabatanController extends Controller
{
    public function index()
    {
        $data = Jabatan::with('divisi')->latest()->get();
        return view('jabatan.index', compact('data'));
    }

    public function create()
    {
        $divisis = Divisi::orderBy('name')->get();
        return view('jabatan.create', compact('divisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_jabatan' => 'required|string|unique:jabatan,kode_jabatan|max:50',
            'name' => 'required|string|max:255',
            'divisi_id' => 'nullable|exists:divisi,id',
            'deskripsi' => 'nullable|string',
            'tanggung_jawab' => 'nullable|string',
            'kualifikasi' => 'nullable|string',
            'gaji_pokok' => 'nullable|numeric|min:0',
            'tunjangan' => 'nullable|numeric|min:0',
        ]);

        Jabatan::create($request->all());

        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $divisis = Divisi::orderBy('name')->get();
        return view('jabatan.edit', compact('jabatan', 'divisis'));
    }

    public function update(Request $request, $id)
    {
        $jabatan = Jabatan::findOrFail($id);

        $request->validate([
            'kode_jabatan' => ['required', 'string', 'max:50', Rule::unique('jabatan', 'kode_jabatan')->ignore($jabatan->id)],
            'name' => 'required|string|max:255',
            'divisi_id' => 'nullable|exists:divisi,id',
            'deskripsi' => 'nullable|string',
            'tanggung_jawab' => 'nullable|string',
            'kualifikasi' => 'nullable|string',
            'gaji_pokok' => 'nullable|numeric|min:0',
            'tunjangan' => 'nullable|numeric|min:0',
        ]);

        $jabatan->update($request->all());

        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Jabatan::findOrFail($id)->delete();
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil dihapus.');
    }
}
