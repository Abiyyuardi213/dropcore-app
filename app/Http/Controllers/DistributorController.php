<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use App\Models\Kota;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    public function index()
    {
        $distributors = Distributor::with('kota')->orderBy('created_at', 'asc')->get();
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
            'nama_distributor' => 'required|string|max:255|unique:distributor,nama_distributor',
            'tipe_distributor' => 'required|string|in:Principal,Distributor,Reseller',
            'status'           => 'required|string|in:active,inactive,blacklisted',
            'telepon'          => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:255',
            'alamat'           => 'nullable|string',
            'kota_id'          => 'nullable|exists:kota,id',
            'pic_nama'         => 'nullable|string|max:255',
            'pic_telepon'      => 'nullable|string|max:20',
            'npwp'             => 'nullable|string|max:30',
            'website'          => 'nullable|url|max:255',
            'keterangan'       => 'nullable|string',
            'latitude'         => 'nullable|numeric|between:-90,90',
            'longitude'        => 'nullable|numeric|between:-180,180',
        ]);

        Distributor::createDistributor($request->all());

        return redirect()->route('distributor.index')
            ->with('success', 'Distributor berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $distributor = Distributor::findOrFail($id);
        $kotas = Kota::orderBy('kota', 'asc')->get();
        return view('distributor.edit', compact('distributor', 'kotas'));
    }

    public function update(Request $request, $id)
    {
        $distributor = Distributor::findOrFail($id);

        $request->validate([
            // 'kode_distributor' => 'required|string|max:50|unique:distributor,kode_distributor,' . $id, // Kode biasanya tidak diubah
            'nama_distributor' => 'required|string|max:255|unique:distributor,nama_distributor,' . $id,
            'tipe_distributor' => 'required|string|in:Principal,Distributor,Reseller',
            'status'           => 'required|string|in:active,inactive,blacklisted',
            'telepon'          => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:255',
            'alamat'           => 'nullable|string',
            'kota_id'          => 'nullable|exists:kota,id',
            'pic_nama'         => 'nullable|string|max:255',
            'pic_telepon'      => 'nullable|string|max:20',
            'npwp'             => 'nullable|string|max:30',
            'website'          => 'nullable|url|max:255',
            'keterangan'       => 'nullable|string',
            'latitude'         => 'nullable|numeric|between:-90,90',
            'longitude'        => 'nullable|numeric|between:-180,180',
        ]);

        $distributor->updateDistributor($request->all());

        return redirect()->route('distributor.index')
            ->with('success', 'Distributor berhasil diperbarui.');
    }

    public function show($id)
    {
        $distributor = Distributor::with('kota')->findOrFail($id);
        return view('distributor.show', compact('distributor'));
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
