<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\SumberKeuangan;
use App\Models\KasPusat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Keuangan::with(['sumber', 'kategori', 'user'])->orderBy('tanggal_transaksi', 'desc');

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal_transaksi', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        if ($request->filled('kategori_keuangan_id')) {
            $query->where('kategori_keuangan_id', $request->kategori_keuangan_id);
        }

        if ($request->filled('sumber_id')) {
            $query->where('sumber_id', $request->sumber_id);
        }

        $data = $query->get();

        $sumberKeuangan = SumberKeuangan::orderBy('nama_sumber', 'asc')->get();
        $categories = \App\Models\KategoriKeuangan::orderBy('nama', 'asc')->get();

        return view('keuangan.keuangan.index', compact('data', 'sumberKeuangan', 'categories'));
    }

    public function create()
    {
        $sumberKeuangan = SumberKeuangan::where('is_active', true)->orderBy('nama_sumber', 'asc')->get();
        $categories = \App\Models\KategoriKeuangan::orderBy('nama', 'asc')->get();
        return view('keuangan.keuangan.create', compact('sumberKeuangan', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_transaksi' => 'required|in:pemasukkan,pengeluaran',
            'jumlah' => 'required|numeric|min:0.01',
            'sumber_id' => 'required|exists:sumber_keuangan,id',
            'kategori_keuangan_id' => 'required|exists:kategori_keuangan,id',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'nullable|string',
            'bukti_transaksi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $data = $request->all();

            $date = date('Ymd');
            $count = Keuangan::whereDate('created_at', today())->count() + 1;
            $data['no_transaksi'] = 'TRX-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
            $data['user_id'] = auth()->user()->id;
            $data['status'] = 'approved';

            if ($request->hasFile('bukti_transaksi')) {
                $file = $request->file('bukti_transaksi');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/keuangan'), $filename);
                $data['bukti_transaksi'] = $filename;
            }

            $transaksi = Keuangan::create($data);

            $akun = SumberKeuangan::findOrFail($request->sumber_id);
            if ($request->jenis_transaksi == 'pemasukkan') {
                $akun->increment('saldo', $request->jumlah);
            } else {
                $akun->decrement('saldo', $request->jumlah);
            }
        });

        return redirect()->route('keuangan.index')
            ->with('success', 'Transaksi berhasil dicatat dan saldo akun diperbarui.');
    }

    public function edit($id)
    {
        $data = Keuangan::findOrFail($id);
        $sumberKeuangan = SumberKeuangan::where('is_active', true)->orderBy('nama_sumber', 'asc')->get();
        $categories = \App\Models\KategoriKeuangan::orderBy('nama', 'asc')->get();

        return view('keuangan.keuangan.edit', compact('data', 'sumberKeuangan', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_transaksi' => 'required|in:pemasukkan,pengeluaran',
            'jumlah' => 'required|numeric|min:0.01',
            'sumber_id' => 'required|exists:sumber_keuangan,id',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'nullable|string',
            'bukti_transaksi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        DB::transaction(function () use ($request, $id) {
            $transaksi = Keuangan::findOrFail($id);
            $oldAmount = $transaksi->jumlah;
            $oldType = $transaksi->jenis_transaksi;
            $oldAccount = SumberKeuangan::findOrFail($transaksi->sumber_id);

            // Revert Old Balance
            if ($oldType == 'pemasukkan') {
                $oldAccount->decrement('saldo', $oldAmount);
            } else {
                $oldAccount->increment('saldo', $oldAmount);
            }

            $data = $request->all();

            if ($request->hasFile('bukti_transaksi')) {
                // Delete old file
                if ($transaksi->bukti_transaksi && file_exists(public_path('uploads/keuangan/' . $transaksi->bukti_transaksi))) {
                    unlink(public_path('uploads/keuangan/' . $transaksi->bukti_transaksi));
                }

                $file = $request->file('bukti_transaksi');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/keuangan'), $filename);
                $data['bukti_transaksi'] = $filename;
            }

            $transaksi->update($data);

            // Apply New Balance
            $newAccount = SumberKeuangan::findOrFail($request->sumber_id);
            if ($request->jenis_transaksi == 'pemasukkan') {
                $newAccount->increment('saldo', $request->jumlah);
            } else {
                $newAccount->decrement('saldo', $request->jumlah);
            }
        });

        return redirect()->route('keuangan.index')
            ->with('success', 'Transaksi berhasil diperbarui dan saldo akun disesuaikan.');
    }

    public function show($id)
    {
        $data = Keuangan::with(['sumber', 'kategori', 'user'])->findOrFail($id);
        return view('keuangan.keuangan.show', compact('data'));
    }

    public function print($id)
    {
        $data = Keuangan::with(['sumber', 'kategori', 'user'])->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('keuangan.keuangan.pdf', compact('data'));
        return $pdf->stream('Bukti-Transaksi-' . $data->no_transaksi . '.pdf');
    }

    public function destroy($id)
    {
        $transaksi = Keuangan::find($id);

        if (!$transaksi) {
            return redirect()->route('keuangan.index')
                ->with('error', 'Data transaksi tidak ditemukan.');
        }

        DB::transaction(function () use ($transaksi) {
            $akun = SumberKeuangan::find($transaksi->sumber_id);

            // Revert Balance only if source account still exists
            if ($akun) {
                if ($transaksi->jenis_transaksi == 'pemasukkan') {
                    $akun->decrement('saldo', $transaksi->jumlah);
                } else {
                    $akun->increment('saldo', $transaksi->jumlah);
                }
            }

            // Safe Check: Delete file only if it looks like a file and exists
            if ($transaksi->bukti_transaksi) {
                $filePath = public_path('uploads/keuangan/' . $transaksi->bukti_transaksi);
                // Check if file exists AND it's not just a UUID string (files usually have dots)
                if (str_contains($transaksi->bukti_transaksi, '.') && file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $transaksi->delete();
        });

        return redirect()->route('keuangan.index')
            ->with('success', 'Transaksi berhasil dihapus dan saldo akun dikembalikan.');
    }
}
