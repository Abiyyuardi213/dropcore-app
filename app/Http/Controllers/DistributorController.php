<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use App\Models\Kota;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    public function index()
    {
        $distributors = Distributor::orderBy('created_at', 'asc')->get();
        return view('distributor.index', compact('distributors'));
    }

    public function create()
    {
        $kotas = Kota::orderBy('kota', 'asc')->get();
        return view('distributor.create', compact('kotas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_distributor' => 'required|string|max:50|unique:distributors,kode_distributor',
            'nama_distributor' => 'required|string|max:255|unique:distributors,name',
            'telepon'          => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:255',
            'alamat'           => 'nullable|string',
            'kota_id'          => 'nullable|exists:kota,id',
        ]);

        Distributor::createDistributor($request->all());

        return redirect()->route('distributor.index')
                         ->with('success', 'Distributor berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $distributor = Distributor::findOrFail($id);
        return view('distributor.edit', compact('distributor'));
    }

    public function update(Request $request, $id)
    {
        $distributor = Distributor::findOrFail($id);

        $request->validate([
            'kode_distributor' => 'required|string|max:50|unique:distributors,kode_distributor,' . $id,
            'name'             => 'required|string|max:255|unique:distributors,name,' . $id,
            'telepon'          => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:255',
            'alamat'           => 'nullable|string',
            'kota_id'          => 'nullable|exists:kota,id',
        ]);

        $distributor->updateDistributor($request->all());

        return redirect()->route('distributor.index')
                         ->with('success', 'Distributor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $distributor = Distributor::findOrFail($id);
        $distributor->deleteDistributor();

        return redirect()->route('distributor.index')
                         ->with('success', 'Distributor berhasil dihapus.');
    }

    // public function toggleStatus($id)
    // {
    //     try {
    //         $distributor = Distributor::findOrFail($id);
    //         $distributor->toggleStatus();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Status distributor berhasil diperbarui.'
    //         ]);

    //     } catch (\Exception $e) {

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Gagal memperbarui status.'
    //         ], 500);
    //     }
    // }
}
