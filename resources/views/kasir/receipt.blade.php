<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Struk {{ $sale->invoice }}</title>
<style>
@page { size: 80mm auto; margin: 0; }
* { box-sizing: border-box; }
body { margin: 0; color: #000; background: #eee; font: 12px/1.4 'Courier New', monospace; }
.receipt { width: 80mm; padding: 4mm; background: #fff; margin: 12px auto; overflow-wrap: anywhere; }
.center { text-align: center; } .logo { width: 38mm; height: auto; }
table { width: 100%; border-collapse: collapse; table-layout: fixed; } td { vertical-align: top; padding: 2px 0; }
.right { text-align: right; } .divider { border-top: 1px dashed #000; margin: 8px 0; }
.item { break-inside: avoid; margin-bottom: 7px; } .total { font-weight: bold; font-size: 14px; }
.actions { text-align: center; padding: 12px; } button { padding: 8px 16px; cursor: pointer; }
@media print { html, body { width: 80mm; background: #fff; } .receipt { margin: 0; } .actions { display: none; } }
</style></head><body>
<div class="actions"><button type="button" onclick="window.print()">Cetak Struk 80 mm</button></div>
<main class="receipt">
    <div class="center"><img class="logo" src="{{ asset('image/garuda-fiber.png') }}" alt="Garuda Fiber"><br><strong>STRUK PENJUALAN</strong></div>
    <div class="divider"></div>
    <div>{{ $sale->invoice }}</div>
    <div>Tanggal: {{ $sale->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}</div>
    <div>Kasir: {{ $sale->cashier_name }}</div><div>Pelanggan: {{ $sale->customer_name }}</div>
    @if($sale->outgoing?->status !== 'completed')<div class="center"><strong>DIBATALKAN</strong></div>@endif
    <div class="divider"></div>
    @foreach($sale->items as $item)
    <div class="item"><strong>{{ $item->product_name }}</strong><div>{{ $item->sku }}</div><table><tr><td style="width:55%">{{ $item->quantity }} {{ $item->unit }} × {{ number_format($item->unit_price, 2, ',', '.') }}</td><td class="right">{{ number_format($item->unit_price * $item->quantity, 2, ',', '.') }}</td></tr></table></div>
    @endforeach
    <div class="divider"></div>
    <table>
    @foreach(['Subtotal'=>'subtotal','Diskon ('.$sale->discount_percent.'%)'=>'discount_amount','Total Bayar'=>'total','Dibayar'=>'paid_amount','Kembalian'=>'change_amount'] as $label=>$field)
        <tr class="{{ $field === 'total' ? 'total' : '' }}"><td style="width:48%">{{ $label }}</td><td class="right">{{ number_format($sale->$field, 2, ',', '.') }}</td></tr>
    @endforeach
    </table>
    <div>Pembayaran: {{ $sale->account->nama_sumber ?? '-' }}</div>
    <div class="divider"></div><div class="center">Terima kasih atas pembelian Anda.<br>Simpan struk ini sebagai bukti transaksi.</div>
</main></body></html>
