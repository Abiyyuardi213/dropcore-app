<?php

namespace App\Http\Controllers;

use App\Models\PenerimaanBarang;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PenerimaanBarangController extends Controller
{
    public function index()
    {
        $data = PenerimaanBarang::with('supplier')->orderBy('created_at', 'desc')->get();

        return view('penerimaan_barang.index', compact('data'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('nama_supplier')->get();
        $no_penerimaan = PenerimaanBarang::generateNomorPenerimaan();

        return view('penerimaan_barang.create', compact('suppliers', 'no_penerimaan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'        => 'required|exists:suppliers,id',
            'tanggal_penerimaan' => 'required|date',
            'keterangan'         => 'nullable|string',
        ]);

        $penerimaan = PenerimaanBarang::createPenerimaan([
            'supplier_id'        => $request->supplier_id,
            'tanggal_penerimaan' => $request->tanggal_penerimaan,
            'keterangan'         => $request->keterangan,
        ]);

        return redirect()->route('penerimaan-barang.detail', $penerimaan->id)
            ->with('success', 'Penerimaan barang berhasil ditambahkan, silakan tambahkan produk.');
    }

    public function edit($id)
    {
        $penerimaan = PenerimaanBarang::findOrFail($id);
        $suppliers  = Supplier::orderBy('nama_supplier')->get();

        return view('penerimaan_barang.edit', compact('penerimaan', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_id'        => 'required|exists:suppliers,id',
            'tanggal_penerimaan' => 'required|date',
            'keterangan'         => 'nullable|string',
        ]);

        $penerimaan = PenerimaanBarang::findOrFail($id);

        $penerimaan->updatePenerimaan([
            'supplier_id'        => $request->supplier_id,
            'tanggal_penerimaan' => $request->tanggal_penerimaan,
            'keterangan'         => $request->keterangan,
        ]);

        return redirect()->route('penerimaan.index')
            ->with('success', 'Penerimaan barang berhasil diperbarui.');
    }

    // public function show($id)
    // {
    //     $penerimaan = PenerimaanBarang::with('supplier')->findOrFail($id);

    //     return view('penerimaan_barang.show', compact('penerimaan'));
    // }

    public function show($id)
    {
        $penerimaan = PenerimaanBarang::with([
            'details.produk',
            'details.gudang',
            'details.area',
            'details.rak'
        ])->findOrFail($id);

        return view('penerimaan_barang.show', compact('penerimaan'));
    }

    public function destroy($id)
    {
        $penerimaan = PenerimaanBarang::findOrFail($id);
        $penerimaan->deletePenerimaan();

        return redirect()->route('penerimaan.index')
            ->with('success', 'Penerimaan barang berhasil dihapus.');
    }

    public function generatePDF($id)
    {
        $penerimaan = PenerimaanBarang::with([
            'supplier',
            'details.produk',
            'details.gudang',
            'details.area',
            'details.rak'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('penerimaan_barang.pdf', compact('penerimaan'));

        return $pdf->stream('Penerimaan_'.$penerimaan->no_penerimaan.'.pdf');
    }
}
