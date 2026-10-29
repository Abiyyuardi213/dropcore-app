<?php

namespace App\Http\Controllers;

use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use App\Models\Gudang;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockOpnameController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'date_from'=>'nullable|date_format:Y-m-d',
            'date_to'=>'nullable|date_format:Y-m-d'.($request->filled('date_from') ? '|after_or_equal:date_from' : ''),
            'gudang_id'=>'nullable|string|max:255',
            'user_id'=>'nullable|string|max:255',
            'status'=>'nullable|in:draft,processed',
            'result'=>'nullable|in:difference,matched,incomplete',
        ]);
        $query = StockOpname::with(['gudang','user']);
        if ($request->filled('date_from')) $query->whereDate('tanggal','>=',$filters['date_from']);
        if ($request->filled('date_to')) $query->whereDate('tanggal','<=',$filters['date_to']);
        foreach (['gudang_id','user_id','status'] as $field) {
            if ($request->filled($field)) $query->where($field,$filters[$field]);
        }
        if (($filters['result'] ?? '') === 'difference') {
            $query->whereHas('details',fn ($q) => $q->whereNotNull('qty_fisik')->whereColumn('qty_fisik','<>','qty_sistem'));
        } elseif (($filters['result'] ?? '') === 'matched') {
            $query->has('details')->whereDoesntHave('details',fn ($q) => $q->whereNull('qty_fisik')->orWhereColumn('qty_fisik','<>','qty_sistem'));
        } elseif (($filters['result'] ?? '') === 'incomplete') {
            $query->where(fn ($q) => $q->doesntHave('details')->orWhereHas('details',fn ($detail) => $detail->whereNull('qty_fisik')));
        }
        $data = $query->orderByDesc('tanggal')->latest()->get();
        $gudangs = Gudang::orderBy('nama_gudang')->get(['id','nama_gudang']);
        $petugas = \App\Models\User::whereIn('id',StockOpname::select('user_id')->whereNotNull('user_id'))->orderBy('name')->get(['id','name']);
        return view('stock_opname.index', compact('data','filters','gudangs','petugas'));
    }

    public function create()
    {
        $gudangs = Gudang::orderBy('nama_gudang')->get();
        return view('stock_opname.create', compact('gudangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'   => 'required|date',
            'gudang_id' => 'required|exists:gudang,id',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // 1. Create Header
            $opname = StockOpname::create([
                'tanggal'    => $request->tanggal,
                'gudang_id'  => $request->gudang_id,
                'user_id'    => Auth::id(),
                'status'     => 'draft',
                'keterangan' => $request->keterangan,
            ]);

            // 2. Snapshot Stock (Qty Sistem)
            $stocks = Stok::where('gudang_id', $request->gudang_id)->get();

            if ($stocks->isEmpty()) {
                throw new \Exception("Gudang ini tidak memiliki stok data untuk di-opname.");
            }

            foreach ($stocks as $stok) {
                StockOpnameDetail::create([
                    'opname_id'  => $opname->id,
                    'produk_id'  => $stok->produk_id,
                    'area_id'    => $stok->area_id,
                    'rak_id'     => $stok->rak_id,
                    'kondisi_id' => $stok->kondisi_id,
                    'qty_sistem' => $stok->quantity,
                    'qty_fisik'  => null, // Belum dihitung
                    'selisih'    => null,
                ]);
            }

            DB::commit();
            return redirect()->route('stock-opname.show', $opname->id)->with('success', 'Sesi Stock Opname dimulai. Silakan input hasil perhitungan fisik.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $opname = StockOpname::with([
            'gudang',
            'user',
            'details.produk',
            'details.area',
            'details.rak',
            'details.kondisi'
        ])->findOrFail($id);

        return view('stock_opname.show', compact('opname'));
    }

    public function update(Request $request, $id)
    {
        $opname = StockOpname::findOrFail($id);

        if ($opname->status !== 'draft') {
            return back()->with('error', 'Opname sudah selesai, data tidak bisa diubah.');
        }

        // Handle "Save Draft" or "Finalize"
        $action = $request->input('action', 'save_draft');
        $details = $request->input('details', []);

        try {
            DB::beginTransaction();

            // Update Details
            foreach ($details as $detailId => $val) {
                $detail = StockOpnameDetail::where('opname_id', $id)->where('id', $detailId)->first();
                if ($detail) {
                    $qtyFisik = isset($val['qty_fisik']) ? (int)$val['qty_fisik'] : 0;
                    $detail->qty_fisik = $qtyFisik;
                    $detail->selisih = $qtyFisik - $detail->qty_sistem;
                    $detail->catatan = $val['catatan'] ?? null;
                    $detail->save();
                }
            }

            if ($action === 'finalize') {
                // Apply Adjustment to Real Stock
                $opnameDetails = StockOpnameDetail::where('opname_id', $id)->get();

                foreach ($opnameDetails as $d) {
                    // Update main Stok table
                    // We directly set quantity to match physical count (Absolute Sync)
                    $stok = Stok::where('produk_id', $d->produk_id)
                        ->where('gudang_id', $opname->gudang_id)
                        ->where('area_id', $d->area_id)
                        ->where('rak_id', $d->rak_id)
                        ->where('kondisi_id', $d->kondisi_id)
                        ->first();

                    if ($stok) {
                        $stok->quantity = $d->qty_fisik ?? 0;
                        $stok->save();
                    }
                }

                $opname->status = 'processed';
                $opname->save();
                $msg = 'Stock Opname selesai! Stok sistem telah diperbarui sesuai fisik.';
            } else {
                $msg = 'Draft disimpan.';
            }

            DB::commit();

            if ($action === 'finalize') {
                return redirect()->route('stock-opname.index')->with('success', $msg);
            } else {
                return back()->with('success', $msg);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
