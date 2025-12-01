<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranBarang;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PengeluaranBarangController extends Controller
{
    public function index()
    {
        $data = PengeluaranBarang::with('distributor')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pengeluaran_barang.index', compact('data'));
    }

    public function create()
    {
        $distributors = Distributor::orderBy('nama_distributor')->get();
        $no_pengeluaran = PengeluaranBarang::generateNomorPengeluaran();

        return view('pengeluaran_barang.create', compact('distributors', 'no_pengeluaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_penerima'       => 'required|in:distributor,konsumen',
            'tanggal_pengeluaran' => 'required|date',
            'keterangan'          => 'nullable|string',

            'distributor_id'      => 'nullable|required_if:tipe_penerima,distributor|exists:distributor,id',

            'nama_konsumen'       => 'nullable|required_if:tipe_penerima,konsumen|string',
            'telepon_konsumen'    => 'nullable|required_if:tipe_penerima,konsumen|string',
            'alamat_konsumen'     => 'nullable|required_if:tipe_penerima,konsumen|string',
        ]);

        $pengeluaran = PengeluaranBarang::createPengeluaran([
            'tipe_penerima'      => $request->tipe_penerima,
            'distributor_id'     => $request->distributor_id,
            'nama_konsumen'      => $request->nama_konsumen,
            'telepon_konsumen'   => $request->telepon_konsumen,
            'alamat_konsumen'    => $request->alamat_konsumen,
            'tanggal_pengeluaran'=> $request->tanggal_pengeluaran,
            'keterangan'         => $request->keterangan,
        ]);

        return redirect()->route('pengeluaran-barang.detail', $pengeluaran->id)
            ->with('success', 'Pengeluaran barang berhasil dibuat, silakan tambahkan produk.');
    }

    public function edit($id)
    {
        $pengeluaran  = PengeluaranBarang::findOrFail($id);
        $distributors = Distributor::orderBy('nama_distributor')->get();

        return view('pengeluaran_barang.edit', compact('pengeluaran', 'distributors'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tipe_penerima'       => 'required|in:distributor,konsumen',
            'tanggal_pengeluaran' => 'required|date',
            'keterangan'          => 'nullable|string',

            'distributor_id'      => 'nullable|required_if:tipe_penerima,distributor|exists:distributor,id',

            'nama_konsumen'       => 'nullable|required_if:tipe_penerima,konsumen|string',
            'telepon_konsumen'    => 'nullable|required_if:tipe_penerima,konsumen|string',
            'alamat_konsumen'     => 'nullable|required_if:tipe_penerima,konsumen|string',
        ]);

        $pengeluaran = PengeluaranBarang::findOrFail($id);

        $pengeluaran->updatePengeluaran([
            'tipe_penerima'      => $request->tipe_penerima,
            'distributor_id'     => $request->distributor_id,
            'nama_konsumen'      => $request->nama_konsumen,
            'telepon_konsumen'   => $request->telepon_konsumen,
            'alamat_konsumen'    => $request->alamat_konsumen,
            'tanggal_pengeluaran'=> $request->tanggal_pengeluaran,
            'keterangan'         => $request->keterangan,
        ]);

        return redirect()->route('pengeluaran.index')
            ->with('success', 'Pengeluaran barang berhasil diperbarui.');
    }

    public function show($id)
    {
        $pengeluaran = PengeluaranBarang::with([
            'distributor',
            'details.produk',
            'details.gudang',
            'details.area',
            'details.rak'
        ])->findOrFail($id);

        return view('pengeluaran_barang.show', compact('pengeluaran'));
    }

    public function destroy($id)
    {
        $pengeluaran = PengeluaranBarang::findOrFail($id);
        $pengeluaran->deletePengeluaran();

        return redirect()->route('pengeluaran.index')
            ->with('success', 'Pengeluaran barang berhasil dihapus.');
    }

    public function generatePDF($id)
    {
        $pengeluaran = PengeluaranBarang::with([
            'distributor',
            'details.produk',
            'details.gudang',
            'details.area',
            'details.rak'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pengeluaran_barang.pdf', compact('pengeluaran'));

        return $pdf->stream('Pengeluaran_'.$pengeluaran->no_pengeluaran.'.pdf');
    }
}
