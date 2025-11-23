<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    // public function index()
    // {
    //     $jabatans = Jabatan::orderBy('created_at', 'asc')->get();
    //     return view('jabatan.index', compact('jabatans'));
    // }

    public function index()
    {
        $jabatans = Jabatan::with('divisi')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('jabatan.index', compact('jabatans'));
    }

    // public function create()
    // {
    //     return view('jabatan.create');
    // }

    public function create()
    {
        $divisis = Divisi::orderBy('name', 'asc')->get();
        return view('jabatan.create', compact('divisis'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         // 'kode_jabatan'      => 'required|string|max:50|unique:jabatan,kode_jabatan',
    //         'name'              => 'required|string|max:255|unique:jabatan,name',
    //         'deskripsi'         => 'nullable|string',
    //         'status'            => 'required|boolean',
    //     ]);

    //     Jabatan::createJabatan($request->all());

    //     return redirect()->route('jabatan.index')
    //                      ->with('success', 'Jabatan berhasil ditambahkan.');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255|unique:jabatan,name',
            'deskripsi' => 'nullable|string',
            'status'    => 'required|boolean',
            'divisi_id' => 'nullable|exists:divisi,id',
        ]);

        Jabatan::createJabatan($request->all());

        return redirect()->route('jabatan.index', ['page' => $request->page])
                 ->with('success', 'Jabatan berhasil ditambahkan.');
    }

    // public function edit($id)
    // {
    //     $jabatan = Jabatan::findOrFail($id);
    //     return view('jabatan.edit', compact('jabatan'));
    // }

    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $divisis = Divisi::orderBy('name', 'asc')->get();

        return view('jabatan.edit', compact('jabatan', 'divisis'));
    }

    // public function update(Request $request, $id)
    // {
    //     $jabatan = Jabatan::findOrFail($id);

    //     $request->validate([
    //         // 'kode_jabatan'      => 'required|string|max:50|unique:jabatan,kode,' . $id,
    //         'name'              => 'required|string|max:255|unique:jabatan,name,' . $id,
    //         'deskripsi'         => 'nullable|string',
    //         'status'            => 'required|boolean',
    //     ]);

    //     $jabatan->updateJabatan($request->all());

    //     return redirect()->route('jabatan.index')
    //                      ->with('success', 'Jabatan berhasil diperbarui.');
    // }

    public function update(Request $request, $id)
    {
        $jabatan = Jabatan::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255|unique:jabatan,name,' . $id,
            'deskripsi' => 'nullable|string',
            'status'    => 'required|boolean',
            'divisi_id' => 'nullable|exists:divisi,id',
        ]);

        $jabatan->updateJabatan($request->all());

        return redirect()->route('jabatan.index', ['page' => $request->page])
                 ->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->deleteJabatan();

        return redirect()->route('jabatan.index', ['page' => request('page')])
                 ->with('success', 'Jabatan berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        try {
            $jabatan = Jabatan::findOrFail($id);
            $jabatan->toggleStatus();

            return response()->json([
                'success' => true,
                'message' => 'Status jabatan berhasil diperbarui.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status.'
            ], 500);
        }
    }
}
