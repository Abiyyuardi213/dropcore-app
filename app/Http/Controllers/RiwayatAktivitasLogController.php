<?php

namespace App\Http\Controllers;

use App\Models\RiwayatAktivitasLog;
use Illuminate\Http\Request;

class RiwayatAktivitasLogController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'date_from'=>'nullable|date_format:Y-m-d',
            'date_to'=>'nullable|date_format:Y-m-d'.($request->filled('date_from') ? '|after_or_equal:date_from' : ''),
            'module'=>'nullable|string|max:255',
            'action'=>'nullable|string|max:255',
            'user_id'=>'nullable|string|max:255',
            'q'=>'nullable|string|max:255',
        ]);
        $query = RiwayatAktivitasLog::with('user');
        if ($request->filled('date_from')) $query->whereDate('created_at','>=',$filters['date_from']);
        if ($request->filled('date_to')) $query->whereDate('created_at','<=',$filters['date_to']);
        foreach (['module','action'] as $field) {
            if ($request->filled($field)) $query->where($field,$filters[$field]);
        }
        if (($filters['user_id'] ?? '') === '__system') $query->whereDoesntHave('user');
        elseif ($request->filled('user_id')) $query->where('user_id',$filters['user_id']);
        if ($request->filled('q')) $query->where('description','like','%'.$filters['q'].'%');
        $logs = $query->latest()->orderByDesc('id')->paginate(50)->withQueryString();
        $modules = RiwayatAktivitasLog::whereNotNull('module')->where('module','<>','')->distinct()->orderBy('module')->pluck('module');
        $actions = RiwayatAktivitasLog::whereNotNull('action')->where('action','<>','')->distinct()->orderBy('action')->pluck('action');
        $users = \App\Models\User::whereIn('id',RiwayatAktivitasLog::select('user_id'))->orderBy('name')->get(['id','name']);
        return view('riwayat-log.index', compact('logs','filters','modules','actions','users'));
    }

    public function show($id)
    {
        $log = RiwayatAktivitasLog::with('user')->findOrFail($id);
        return view('riwayat-log.show', compact('log'));
    }

    public function destroyAll()
    {
        RiwayatAktivitasLog::truncate();
        return back()->with('success', 'Seluruh riwayat log berhasil dihapus.');
    }
}
