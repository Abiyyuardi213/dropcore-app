<?php

namespace App\Http\Controllers;

use App\Models\RiwayatAktivitasProduk;
use Illuminate\Http\Request;

class RiwayatAktivitasProdukController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'date_from'=>'nullable|date_format:Y-m-d',
            'date_to'=>'nullable|date_format:Y-m-d'.($request->filled('date_from') ? '|after_or_equal:date_from' : ''),
            'produk_id'=>'nullable|string|max:255',
            'tipe_aktivitas'=>'nullable|string|max:255',
            'user_id'=>'nullable|string|max:255',
        ]);
        $query = RiwayatAktivitasProduk::with(['produk','user']);
        if ($request->filled('date_from')) $query->whereDate('created_at','>=',$filters['date_from']);
        if ($request->filled('date_to')) $query->whereDate('created_at','<=',$filters['date_to']);
        if (($filters['produk_id'] ?? '') === '__deleted') $query->whereDoesntHave('produk');
        elseif ($request->filled('produk_id')) $query->where('produk_id',$filters['produk_id']);
        if ($request->filled('tipe_aktivitas')) $query->where('tipe_aktivitas',$filters['tipe_aktivitas']);
        if (($filters['user_id'] ?? '') === '__system') $query->whereDoesntHave('user');
        elseif ($request->filled('user_id')) $query->where('user_id',$filters['user_id']);
        $riwayats = $query->latest()->get();
        $products = \App\Models\Products::whereIn('id',RiwayatAktivitasProduk::select('produk_id'))->orderBy('name')->get(['id','name','sku']);
        $users = \App\Models\User::whereIn('id',RiwayatAktivitasProduk::select('user_id'))->orderBy('name')->get(['id','name']);
        $activityTypes = RiwayatAktivitasProduk::whereNotNull('tipe_aktivitas')->where('tipe_aktivitas','<>','')->distinct()->orderBy('tipe_aktivitas')->pluck('tipe_aktivitas');
        return view('riwayat-aktivitas-produk.index', compact('riwayats','filters','products','users','activityTypes'));
    }

    public function show($id)
    {
        $riwayat = RiwayatAktivitasProduk::with(['produk', 'user'])->findOrFail($id);
        $relatedActivities = $riwayat->produk_id
            ? RiwayatAktivitasProduk::with('user')->where('produk_id',$riwayat->produk_id)->latest()->limit(5)->get()
            : collect();
        return view('riwayat-aktivitas-produk.show', compact('riwayat','relatedActivities'));
    }
}
