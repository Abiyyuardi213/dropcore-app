<?php
namespace App\Http\Controllers;
use App\Models\{Products, Stok, KasirSale, SumberKeuangan};
use App\Services\KasirSaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class KasirSaleController extends Controller
{
    public function create()
    {
        $accounts = SumberKeuangan::where('is_active', true)->orderBy('nama_sumber')->get(['id', 'nama_sumber', 'jenis']);
        $submissionKey = old('submission_key', (string) Str::uuid());
        return view('kasir.penjualan', compact('accounts', 'submissionKey'));
    }
    public function products(Request $request)
    {
        $request->validate(['search' => 'nullable|string|max:100', 'stock_id' => 'nullable|string|max:36']);
        $query = Products::select(['id','name','sku','price','uom_id'])->with('uom:id,name')
            ->with(['stok' => fn ($q) => $q->where('quantity', '>', 0)->with(['gudang', 'area', 'rak', 'kondisi'])->orderBy('id')])
            ->whereHas('stok', fn ($q) => $q->where('quantity', '>', 0));
        if ($request->filled('stock_id')) $query->whereHas('stok', fn ($q) => $q->where('id', $request->stock_id));
        if ($request->filled('search')) {
            $term = '%'.trim($request->search).'%';
            $query->where(fn ($q) => $q->where('name','like',$term)->orWhere('sku','like',$term));
        }
        $products = $query->orderBy('name')->limit(20)->get()->map(fn ($p) => [
            'id' => $p->id, 'name' => $p->name, 'sku' => $p->sku, 'price' => (float) $p->price, 'unit' => $p->uom->name ?? 'Unit',
            'stocks' => $p->stok->map(fn ($s) => ['id' => $s->id, 'quantity' => (int) $s->quantity,
                'label' => ($s->gudang->nama_gudang ?? '-').' / '.($s->area->kode_area ?? '-').' / '.($s->rak->kode_rak ?? '-').' / '.($s->kondisi->nama_kondisi ?? '-'),
            ])->values(),
        ]);
        return response()->json($products);
    }
    public function store(Request $request, KasirSaleService $service)
    {
        $data = $request->validate([
            'submission_key' => 'required|uuid', 'customer_name' => 'nullable|string|max:255', 'customer_phone' => 'nullable|string|max:30',
            'sumber_id' => 'required|exists:sumber_keuangan,id', 'discount' => 'required|numeric|min:0|max:100|decimal:0,2',
            'paid_amount' => 'required|numeric|min:0|max:99999999999999|decimal:0,2',
            'items' => 'required|array|min:1|max:100', 'items.*.stock_id' => 'required|string|distinct|exists:stok,id',
            'items.*.quantity' => 'required|integer|min:1|max:100000',
        ]);
        $data['customer_name'] = trim($data['customer_name'] ?? '') ?: 'Pelanggan Umum';
        $sale = $service->store($request->user(), $data);
        return redirect()->route('kasir.penjualan.show', $sale->id)->with('success', 'Penjualan berhasil disimpan. Stok dan saldo akun telah diperbarui.');
    }
    public function show(Request $request, string $id)
    {
        $sale = KasirSale::with(['items','outgoing','account'])->where('user_id', $request->user()->id)->findOrFail($id);
        return view('kasir.struk', compact('sale'));
    }
    public function receipt(Request $request, string $id)
    {
        $sale = KasirSale::with(['items','outgoing','account'])->where('user_id', $request->user()->id)->findOrFail($id);
        return view('kasir.receipt', compact('sale'));
    }
    public function invoice(Request $request, string $id)
    {
        $sale = KasirSale::with(['items','outgoing','account'])->where('user_id', $request->user()->id)->findOrFail($id);
        return Pdf::loadView('kasir.invoice', compact('sale'))->setPaper('a4', 'portrait')->download('Invoice-'.$sale->invoice.'.pdf');
    }
}
