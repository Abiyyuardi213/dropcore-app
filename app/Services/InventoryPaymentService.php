<?php

namespace App\Services;

use App\Models\{Keuangan, KategoriKeuangan, Products, Stok, SumberKeuangan};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class InventoryPaymentService
{
    private function fail(string $message): never
    {
        throw ValidationException::withMessages(['transaksi' => $message]);
    }

    public function lockProducts(array $items): void
    {
        Products::whereIn('id', array_unique(array_column($items, 'produk_id')))->orderBy('id')->lockForUpdate()->get();
    }

    public function validateLocation(array $item): void
    {
        if (!DB::table('area_gudang')->where('id', $item['area_id'])->where('gudang_id', $item['gudang_id'])->exists()
            || !DB::table('rak_gudang')->where('id', $item['rak_id'])->where('area_id', $item['area_id'])->where('gudang_id', $item['gudang_id'])->exists()) {
            $this->fail('Gudang, area, dan rak harus berada pada satu lokasi yang sesuai.');
        }
    }

    public function total(Model $document): float
    {
        $subtotal = round((float) $document->details()->sum('subtotal'), 2);
        return $document->getTable() === 'penerimaan_barang'
            ? round((float) ($document->total_amount > 0 ? $document->total_amount : $subtotal + $document->ppn_amount), 2)
            : $subtotal;
    }

    public function pay(Model $document, string $accountId, string $date): void
    {
        DB::transaction(function () use ($document, $accountId, $date) {
            $document = $document->newQuery()->lockForUpdate()->findOrFail($document->id);
            if ($document->status !== 'completed') $this->fail('Hanya transaksi barang selesai yang dapat dilunasi.');
            if ($document->payment_status === 'paid') $this->fail('Transaksi ini sudah lunas.');
            $account = SumberKeuangan::where('is_active', true)->lockForUpdate()->findOrFail($accountId);
            $purchase = $document->getTable() === 'penerimaan_barang';
            $amount = $this->total($document);
            if ($purchase && (float) $account->saldo < $amount) $this->fail('Saldo akun pembayaran tidak mencukupi.');
            if ($amount > 0) {
                $category = KategoriKeuangan::firstOrCreate(['nama' => $purchase ? 'Pembelian Stok' : 'Penjualan Stok'],
                    ['tipe' => $purchase ? 'pengeluaran' : 'pemasukkan', 'deskripsi' => 'Pembayaran transaksi barang']);
                Keuangan::create([
                    'no_transaksi' => 'INV-'.Str::upper((string) Str::uuid()),
                    'jenis_transaksi' => $purchase ? 'pengeluaran' : 'pemasukkan',
                    'kategori_keuangan_id' => $category->id, 'sumber_id' => $account->id,
                    'referensi_id' => $document->id, 'referensi_tabel' => $document->getTable(),
                    'jumlah' => $amount, 'tanggal_transaksi' => $date,
                    'keterangan' => ($purchase ? 'Pembelian Stok Ref: ' : 'Penjualan Stok Ref: ').($document->no_penerimaan ?? $document->no_pengeluaran),
                    'status' => 'approved', 'user_id' => auth()->id(),
                ]);
                $account->increment('saldo', $purchase ? -$amount : $amount);
            }
            $document->update(['payment_status' => 'paid', 'paid_at' => $date]);
        });
    }

    public function cancel(Model $document): void
    {
        DB::transaction(function () use ($document) {
            $document = $document->newQuery()->lockForUpdate()->findOrFail($document->id);
            if ($document->status === 'canceled' || $document->canceled_at) return;
            $purchase = $document->getTable() === 'penerimaan_barang';
            $details = $document->details()->get();
            $this->lockProducts($details->toArray());
            if ($document->status === 'completed') {
                foreach ($details as $detail) {
                    $key = $detail->only(['produk_id', 'gudang_id', 'area_id', 'rak_id', 'kondisi_id']);
                    $stock = Stok::where($key)->lockForUpdate()->first();
                    if ($purchase && (!$stock || $stock->quantity < $detail->qty)) {
                        $this->fail('Pembelian tidak dapat dibatalkan karena stok sudah digunakan atau lokasi stok tidak tersedia.');
                    }
                    if (!$stock) $stock = Stok::create($key + ['quantity' => 0]);
                    $stock->increment('quantity', $purchase ? -$detail->qty : $detail->qty);
                }
                $entries = Keuangan::where('referensi_tabel', $document->getTable())->where('referensi_id', $document->id)
                    ->where('status', 'approved')->orderBy('sumber_id')->lockForUpdate()->get();
                foreach ($entries as $entry) {
                    $account = SumberKeuangan::lockForUpdate()->find($entry->sumber_id);
                    if (!$account) $this->fail('Akun pembayaran asal tidak tersedia; pembatalan tidak dapat membalik saldo.');
                    $income = $entry->jenis_transaksi === 'pemasukkan';
                    $account->increment('saldo', $income ? -(float) $entry->jumlah : (float) $entry->jumlah);
                    $category = KategoriKeuangan::firstOrCreate(['nama' => $purchase ? 'Pembalikan Pembelian Stok' : 'Pembalikan Penjualan Stok'],
                        ['tipe' => $income ? 'pengeluaran' : 'pemasukkan', 'deskripsi' => 'Pembatalan pembayaran transaksi barang']);
                    Keuangan::create([
                        'no_transaksi' => 'REV-'.Str::upper((string) Str::uuid()),
                        'jenis_transaksi' => $income ? 'pengeluaran' : 'pemasukkan',
                        'kategori_keuangan_id' => $category->id,
                        'sumber_id' => $account->id, 'referensi_id' => $document->id,
                        'referensi_tabel' => $document->getTable().'_reversal',
                        'jumlah' => $entry->jumlah, 'tanggal_transaksi' => now()->toDateString(),
                        'keterangan' => 'Pembalikan '.$entry->no_transaksi.' / '.($document->no_penerimaan ?? $document->no_pengeluaran),
                        'status' => 'approved', 'user_id' => auth()->id(),
                    ]);
                }
            }
            $document->update(['status' => 'canceled', 'canceled_at' => now(),
                'payment_status' => $document->payment_status === 'paid' ? 'reversed' : 'unpaid']);
        });
    }
}
