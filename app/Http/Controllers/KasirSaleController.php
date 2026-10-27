<?php
namespace App\Http\Controllers;
use App\Models\{Products, Stok, KasirSale, SumberKeuangan};
use App\Services\KasirSaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\ValidationException;

class KasirSaleController extends Controller
{
    public function create(Request $request)
    {
        $accounts = SumberKeuangan::where('is_active', true)->orderBy('nama_sumber')->get(['id', 'nama_sumber', 'jenis']);
        $key = (string) $request->query('draft', '');
        $draft = Str::isUuid($key) ? $request->session()->get($this->draftKey($request, $key), []) : [];
        $submissionKey = old('submission_key', $draft['submission_key'] ?? (string) Str::uuid());
        return view('kasir.penjualan', compact('accounts', 'submissionKey', 'draft'));
    }
    private function draftKey(Request $request, string $key): string
    {
        return 'cashier_checkout.'.$request->user()->id.'.'.$key;
    }
    private function quote(array $draft): array
    {
        $stocks = Stok::with(['produk.uom', 'gudang', 'area', 'rak'])->whereIn('id', array_column($draft['items'], 'stock_id'))->get()->keyBy('id');
        $lines = []; $subtotal = 0; $total = 0; $prices = [];
        foreach ($draft['items'] as $item) {
            $stock = $stocks->get($item['stock_id']);
            if (!$stock?->produk || $stock->quantity < $item['quantity']) {
                throw ValidationException::withMessages(['items' => 'Stok berubah atau tidak mencukupi. Kembali ke penjualan untuk memperbaiki keranjang.']);
            }
            $price = (int) round((float) $stock->produk->price * 100);
            if ($price < 0) throw ValidationException::withMessages(['items' => 'Harga produk tidak valid.']);
            $net = (int) round($price * (100 - (float) $draft['discount']) / 100);
            $subtotal += $price * $item['quantity']; $total += $net * $item['quantity'];
            $prices[$stock->id] = $price;
            $lines[] = ['name' => $stock->produk->name, 'sku' => $stock->produk->sku, 'unit' => $stock->produk->uom->name ?? 'Unit',
                'quantity' => $item['quantity'], 'price' => $price, 'total' => $net * $item['quantity'],
                'location' => ($stock->gudang->nama_gudang ?? '-').' / '.($stock->area->kode_area ?? '-').' / '.($stock->rak->kode_rak ?? '-')];
        }
        return compact('lines', 'subtotal', 'total', 'prices');
    }
    public function prepare(Request $request)
    {
        $draft = $request->validate([
            'submission_key' => 'required|uuid', 'customer_name' => 'nullable|string|max:255', 'customer_phone' => 'nullable|string|max:30',
            'discount' => 'required|numeric|min:0|max:100|decimal:0,2',
            'items' => 'required|array|min:1|max:100', 'items.*.stock_id' => 'required|string|distinct|exists:stok,id',
            'items.*.quantity' => 'required|integer|min:1|max:100000',
        ]);
        $draft['customer_name'] = trim($draft['customer_name'] ?? '') ?: 'Pelanggan Umum';
        $existing = KasirSale::where('user_id', $request->user()->id)->where('submission_key', $draft['submission_key'])->first();
        if ($existing) return $this->completed($existing);
        $this->quote($draft);
        $request->session()->put($this->draftKey($request, $draft['submission_key']), $draft);
        return redirect()->route('kasir.penjualan.bayar', $draft['submission_key']);
    }
    public function checkout(Request $request, string $key)
    {
        $draft = $request->session()->get($this->draftKey($request, $key));
        if (!$draft) return redirect()->route('kasir.penjualan')->withErrors(['transaksi' => 'Keranjang pembayaran tidak tersedia. Silakan pilih barang kembali.']);
        try { $quote = $this->quote($draft); }
        catch (ValidationException $error) { return redirect()->route('kasir.penjualan', ['draft' => $key])->withErrors($error->errors()); }
        $draft['expected_prices'] = $quote['prices'];
        $request->session()->put($this->draftKey($request, $key), $draft);
        $accounts = SumberKeuangan::where('is_active', true)->orderBy('nama_sumber')->get();
        return view('kasir.bayar', compact('draft', 'quote', 'accounts'));
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
        $request->validate(['submission_key' => 'required|uuid']);
        $existing = KasirSale::where('user_id', $request->user()->id)->where('submission_key', $request->submission_key)->first();
        if ($existing) return $this->completed($existing);
        $draft = $request->session()->get($this->draftKey($request, $request->submission_key));
        if (!$draft || !isset($draft['expected_prices'])) return redirect()->route('kasir.penjualan')->withErrors(['transaksi' => 'Buka Proses Bayar terlebih dahulu sebelum menyelesaikan transaksi.']);
        $payment = $request->validate([
            'sumber_id' => 'required|exists:sumber_keuangan,id',
            'paid_amount' => 'required|numeric|min:0|max:99999999999999|decimal:0,2',
        ]);
        $sale = $service->store($request->user(), array_merge($draft, $payment));
        $request->session()->forget($this->draftKey($request, $request->submission_key));
        return $this->completed($sale);
    }
    private function completed(KasirSale $sale)
    {
        return redirect()->route('kasir.penjualan')->with('success', 'Transaksi '.$sale->invoice.' berhasil dibuat.')->with('completed_sale_id', $sale->id);
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
