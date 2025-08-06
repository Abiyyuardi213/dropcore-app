<?php

namespace App\Http\Controllers;

use App\Models\RiwayatAktivitasLog;
use Illuminate\Http\Request;

class RiwayatAktivitasLogController extends Controller
{
    public function index()
    {
        $logs = RiwayatAktivitasLog::with('user')->latest()->get();
        return view('riwayat-log.index', compact('logs'));
    }

    public function show($id)
    {
        $log = RiwayatAktivitasLog::with('user')->findOrFail($id);
        return view('riwayat-log.show', compact('log'));
    }

    public function destroyAll()
    {
        RiwayatAktivitasLog::truncate();
        return back()->with('success','Seluruh riwayat log berhasil dihapus.');
    }
}
