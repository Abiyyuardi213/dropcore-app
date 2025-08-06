<?php

namespace App\Http\Controllers;

use App\Models\RiwayatAktivitasProduk;
use Illuminate\Http\Request;

class RiwayatAktivitasProdukController extends Controller
{
    public function index()
    {
        $riwayats = RiwayatAktivitasProduk::with(['produk', 'user'])->latest()->get();
        return view('riwayat-aktivitas-produk.index', compact('riwayats'));
    }

    public function show($id)
    {
        $riwayat = RiwayatAktivitasProduk::with(['produk', 'user'])->findOrFail($id);
        return view('riwayat-aktivitas-produk.show', compact('riwayat'));
    }
}
