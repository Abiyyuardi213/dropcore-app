<?php

namespace App\Http\Controllers;

use App\Models\Kota;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Wilayah;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('created_at', 'asc')->get();
        return view('supplier.index', compact('suppliers'));
    }

    public function create()
    {
        $wilayahs = Wilayah::all();
        $provinsis = Provinsi::orderBy('name', 'asc')->get();
        $kotas = Kota::all();

        return view('supplier.create', compact(
            'wilayahs',
            'provinsis',
            'kotas'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_supplier' => 'nullable|string|unique:suppliers,kode_supplier',
            'nama_supplier' => 'required|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string',
            'email' => 'nullable|email',
            'keterangan' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tipe_supplier' => 'nullable|string',
            'wilayah_id' => 'required|string',
            'provinsi_id' => 'required|string',
            'kota_id' => 'required|string',
            // 'kecamatan_id' => 'required|uuid', // Removed
            // 'kelurahan_id' => 'required|uuid', // Removed
            'status' => 'nullable|boolean',
        ]);

        $data = $request->all();

        try {
            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('public/suppliers');
                $data['logo'] = Storage::url($path);
            }

            Supplier::createSupplier($data);

            return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan supplier: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $wilayahs = Wilayah::all();
        $provinsis = Provinsi::orderBy('name', 'asc')->get();
        $kotas = Kota::all();

        return view('supplier.edit', compact(
            'supplier',
            'wilayahs',
            'provinsis',
            'kotas'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_supplier' => 'nullable|string|unique:suppliers,kode_supplier,' . $id,
            'nama_supplier' => 'required|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string',
            'email' => 'nullable|email',
            'keterangan' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tipe_supplier' => 'nullable|string',
            'wilayah_id' => 'required|string',
            'provinsi_id' => 'required|string',
            'kota_id' => 'required|string',
            // 'kecamatan_id' => 'required|uuid', // Removed
            // 'kelurahan_id' => 'required|uuid', // Removed
            'status' => 'nullable|boolean',
        ]);

        $supplier = Supplier::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($supplier->logo) {
                $oldPath = str_replace('/storage/', 'public/', $supplier->logo);
                Storage::delete($oldPath);
            }

            $path = $request->file('logo')->store('public/suppliers');
            $data['logo'] = Storage::url($path);
        }

        $supplier->updateSupplier($data);

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->deleteSupplier();

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dihapus.');
    }

    public function show($id)
    {
        $supplier = Supplier::with(['wilayah', 'provinsi', 'kota'])->findOrFail($id); // Removed kecamatan, kelurahan
        return view('supplier.show', compact('supplier'));
    }

    public function toggleStatus($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->toggleStatus();

            return response()->json([
                'success' => true,
                'message' => 'Status supplier berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status.'
            ], 500);
        }
    }
}
