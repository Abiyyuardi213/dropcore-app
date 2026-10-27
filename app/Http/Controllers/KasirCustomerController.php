<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirCustomerController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'in:distributor,konsumen'],
        ]);
        $distributors = DB::table('distributor')->select('kode_distributor as kode', 'nama_distributor as nama', 'telepon', 'email', 'alamat', 'status')
            ->selectRaw("'distributor' as jenis");
        $consumers = DB::table('pengeluaran_barang')->where('tipe_penerima', 'konsumen')->where('status', 'completed')
            ->whereNotNull('nama_konsumen')->whereRaw("TRIM(nama_konsumen) <> ''")
            ->selectRaw("NULL as kode, TRIM(nama_konsumen) as nama, TRIM(COALESCE(telepon_konsumen, '')) as telepon, NULL as email, TRIM(COALESCE(alamat_konsumen, '')) as alamat, 'recorded' as status, 'konsumen' as jenis")
            ->distinct();
        $query = DB::query()->fromSub($distributors->unionAll($consumers), 'pelanggan');
        if ($request->filled('search')) {
            $search = '%'.trim($request->search).'%';
            $query->where(fn ($q) => $q->where('nama', 'like', $search)->orWhere('kode', 'like', $search)->orWhere('telepon', 'like', $search)->orWhere('email', 'like', $search));
        }
        if ($request->filled('type')) $query->where('jenis', $request->type);
        $customers = $query->orderBy('nama')->orderBy('jenis')->orderBy('kode')->orderBy('telepon')->orderBy('alamat')
            ->paginate(15)->withQueryString();
        return view('kasir.pelanggan', compact('customers'));
    }
}
