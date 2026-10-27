<?php
namespace App\Services;
use App\Models\{KasirSale, User, Products, Stok, PengeluaranBarang, PengeluaranBarangDetail, SumberKeuangan};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class KasirSaleService
{
    private function paymentCents(string $amount): int
    {
        $parts = explode('.', $amount, 2);
        return ((int) $parts[0] * 100) + (int) str_pad($parts[1] ?? '', 2, '0');
    }
    private function decimalAmount(int $cents): string
    {
        return intdiv($cents, 100).'.'.str_pad((string) ($cents % 100), 2, '0', STR_PAD_LEFT);
    }
    private function fail(string $message): never { throw ValidationException::withMessages(['transaksi' => $message]); }
    public function store(User $user, array $data): KasirSale
    {
        return DB::transaction(function () use ($user, $data) {
            User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $existing = KasirSale::where('user_id', $user->id)->where('submission_key', $data['submission_key'])->first();
            if ($existing) return $existing;
            $account = SumberKeuangan::where('is_active', true)->find($data['sumber_id']);
            if (!$account) $this->fail('Akun penerimaan tidak aktif atau tidak ditemukan.');
            $ids = array_column($data['items'], 'stock_id');
            $stockRows = Stok::whereIn('id', $ids)->get();
            $inventory = app(InventoryPaymentService::class);
            $inventory->lockProducts($stockRows->map(fn ($s) => ['produk_id' => $s->produk_id])->all());
            $stocks = Stok::with(['produk.uom', 'gudang', 'area', 'rak'])->whereIn('id', $ids)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $lines = []; $subtotal = 0; $total = 0; $profit = 0; $knownCost = true;
            foreach ($data['items'] as $line) {
                $stock = $stocks->get($line['stock_id']); $qty = (int) $line['quantity'];
                if (!$stock || !$stock->produk || $qty > $stock->quantity) $this->fail('Stok berubah atau tidak mencukupi. Pilih ulang produk dan jumlahnya.');
                $inventory->validateLocation($stock->only(['gudang_id', 'area_id', 'rak_id']));
                $product = $stock->produk;
                $price = (int) round((float) $product->price * 100);
                if (isset($data['expected_prices']) && ($data['expected_prices'][$stock->id] ?? null) !== $price) $this->fail('Harga produk berubah. Muat ulang Proses Bayar dan periksa total terbaru sebelum menyelesaikan pembayaran.');
                if ($price < 0) $this->fail('Harga produk tidak valid.');
                $netPrice = (int) round($price * (100 - (float) $data['discount']) / 100);
                $subtotal += $price * $qty; $total += $netPrice * $qty;
                if ($product->purchase_price === null) $knownCost = false;
                else $profit += ($netPrice - (int) round((float) $product->purchase_price * 100)) * $qty;
                $lines[] = compact('stock', 'qty', 'price', 'netPrice', 'product');
            }
            if ($total > 9999999999999999 || $subtotal > 9999999999999999) $this->fail('Nilai transaksi terlalu besar.');
            $paid = $this->paymentCents((string) $data['paid_amount']);
            if ($paid < $total) $this->fail('Nominal pembayaran kurang dari total belanja.');
            if ($account->jenis !== 'tunai' && $paid !== $total) $this->fail('Pembayaran nontunai harus sama dengan total belanja.');
            $date = now('Asia/Jakarta')->toDateString(); $invoice = 'POS-'.now('Asia/Jakarta')->format('Ymd').'-'.strtoupper(bin2hex(random_bytes(5)));
            $outgoing = PengeluaranBarang::create([
                'no_pengeluaran' => $invoice, 'user_id' => $user->id, 'tipe_penerima' => 'konsumen',
                'nama_konsumen' => $data['customer_name'] ?: 'Pelanggan Umum', 'telepon_konsumen' => $data['customer_phone'] ?? null,
                'tanggal_pengeluaran' => $date, 'referensi' => $invoice, 'keterangan' => 'Penjualan Kasir '.$invoice,
                'status' => 'completed', 'payment_status' => 'unpaid',
            ]);
            $sale = KasirSale::create([
                'id' => (string) Str::uuid(), 'invoice' => $invoice, 'submission_key' => $data['submission_key'],
                'user_id' => $user->id, 'cashier_name' => $user->name, 'pengeluaran_id' => $outgoing->id, 'sumber_id' => $account->id,
                'customer_name' => $data['customer_name'] ?: 'Pelanggan Umum', 'customer_phone' => $data['customer_phone'] ?? null,
                'sale_date' => $date, 'payment_type' => $account->jenis, 'discount_percent' => $data['discount'],
                'subtotal' => $subtotal / 100, 'discount_amount' => ($subtotal - $total) / 100, 'total' => $total / 100,
                'paid_amount' => $this->decimalAmount($paid), 'change_amount' => $this->decimalAmount($paid - $total), 'profit' => $knownCost ? $profit / 100 : null,
            ]);
            foreach ($lines as $line) {
                extract($line);
                PengeluaranBarangDetail::create([
                    'pengeluaran_id' => $outgoing->id, 'produk_id' => $product->id, 'stok_id' => $stock->id,
                    'qty' => $qty, 'harga' => $netPrice / 100, 'subtotal' => $netPrice * $qty / 100,
                    'gudang_id' => $stock->gudang_id, 'area_id' => $stock->area_id, 'rak_id' => $stock->rak_id, 'kondisi_id' => $stock->kondisi_id,
                ]);
                $sale->items()->create([
                    'product_id' => $product->id, 'stock_id' => $stock->id, 'category_id' => $product->category_id,
                    'product_name' => $product->name, 'sku' => $product->sku, 'unit' => $product->uom->name ?? null,
                    'location' => ($stock->gudang->nama_gudang ?? '-').' / '.($stock->area->kode_area ?? '-').' / '.($stock->rak->kode_rak ?? '-'),
                    'quantity' => $qty, 'unit_price' => $price / 100, 'sale_unit_price' => $netPrice / 100,
                    'purchase_price' => $product->purchase_price, 'total' => $netPrice * $qty / 100,
                ]);
                $stock->decrement('quantity', $qty);
            }
            $inventory->pay($outgoing, $account->id, $date);
            return $sale;
        });
    }
}
