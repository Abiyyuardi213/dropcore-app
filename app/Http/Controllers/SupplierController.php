<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
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
        $wilayahs = Wilayah::where('status_wilayah', 1)->get();
        $provinsis = Provinsi::where('status_provinsi', 1)
            ->orderBy('provinsi', 'asc')
            ->get();
        $kotas = Kota::all();
        $kecamatans = Kecamatan::all();
        $kelurahans = Kelurahan::all();

        return view('supplier.create', compact(
            'wilayahs',
            'provinsis',
            'kotas',
            'kecamatans',
            'kelurahans',
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
            'website' => 'nullable|url',
            'keterangan' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tipe_supplier' => 'nullable|string',
            'wilayah_id' => 'required|uuid',
            'provinsi_id' => 'required|uuid',
            'kota_id' => 'required|uuid',
            'kecamatan_id' => 'required|uuid',
            'kelurahan_id' => 'required|uuid',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('public/suppliers');
            $data['logo'] = Storage::url($path);
        }

        Supplier::createSupplier($data);

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $wilayahs = Wilayah::where('status_wilayah', 1)->get();
        $provinsis = Provinsi::where('status_provinsi', 1)->orderBy('provinsi')->get();
        $kotas = Kota::all();
        $kecamatans = Kecamatan::all();
        $kelurahans = Kelurahan::all();

        return view('supplier.edit', compact(
            'supplier',
            'wilayahs',
            'provinsis',
            'kotas',
            'kecamatans',
            'kelurahans'
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
            'website' => 'nullable|url',
            'keterangan' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tipe_supplier' => 'nullable|string',
            'wilayah_id' => 'required|uuid',
            'provinsi_id' => 'required|uuid',
            'kota_id' => 'required|uuid',
            'kecamatan_id' => 'required|uuid',
            'kelurahan_id' => 'required|uuid',
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
        $supplier = Supplier::with(['wilayah', 'provinsi', 'kota', 'kecamatan', 'kelurahan'])->findOrFail($id);
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
