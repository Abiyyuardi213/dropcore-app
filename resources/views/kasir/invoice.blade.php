<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><title>Invoice {{ $sale->invoice }}</title>
@include('kasir.invoice-styles')
</head><body>
    <table class="masthead"><tr>
        <td style="width:61%"><img src="{{ public_path('image/garuda-fiber.png') }}" class="logo" alt="Logo Garuda Fiber"><div class="muted">Penjualan &amp; Pengeluaran Barang</div></td>
        <td style="width:39%;padding-top:12px"><div class="document-title">INVOICE</div><div class="document-subtitle">PENJUALAN KASIR<br><strong>{{ $sale->invoice }}</strong></div></td>
    </tr></table>
    <div class="section-bar">INFORMASI TRANSAKSI</div>
    <table class="info"><tr>
        <td style="width:55%"><div class="label">Pelanggan / Konsumen</div><div class="sender">{{ $sale->customer_name }}</div><div>Telepon: {{ $sale->customer_phone ?: '-' }}</div></td>
        <td class="split" style="width:45%"><table class="meta">
            <tr><td style="width:40%">Tanggal</td><td>: {{ $sale->sale_date->format('d/m/Y') }}</td></tr>
            <tr><td>No. referensi</td><td>: {{ $sale->invoice }}</td></tr>
            <tr><td>Status</td><td>: {{ $sale->outgoing?->status === 'completed' ? 'Selesai / Lunas' : 'Dibatalkan' }}</td></tr>
            <tr><td>Kasir</td><td>: {{ $sale->cashier_name }}</td></tr>
            <tr><td>Pembayaran</td><td>: {{ $sale->account->nama_sumber ?? '-' }}</td></tr>
        </table></td>
    </tr></table>
    <table class="items"><thead><tr>
        <th style="width:5%">No.</th><th style="width:40%">Deskripsi Barang / SKU</th><th style="width:10%">Satuan</th><th style="width:8%">Qty</th><th style="width:18%">Harga Satuan (Rp)</th><th style="width:19%">Jumlah (Rp)</th>
    </tr></thead><tbody>
        @foreach($sale->items as $item)<tr>
            <td class="center">{{ $loop->iteration }}</td>
            <td><strong>{{ $item->product_name }}</strong><div class="sku">SKU: {{ $item->sku ?: '-' }}</div><div class="location">{{ $item->location }}</div></td>
            <td class="center">{{ $item->unit ?: '-' }}</td><td class="right">{{ $item->quantity }}</td><td class="right">{{ number_format($item->unit_price,2,',','.') }}</td><td class="right">{{ number_format($item->unit_price * $item->quantity,2,',','.') }}</td>
        </tr>@endforeach
    </tbody></table>
    <table class="closing"><tr><td class="notes" style="width:55%"><div class="label">Catatan Penjualan</div><p>Harga barang mengikuti data saat transaksi. Diskon diperhitungkan pada total pembayaran.</p><p class="muted">Dokumen ini merupakan bukti pencatatan penjualan barang. Simpan invoice ini sebagai arsip transaksi.</p></td>
    <td style="width:45%"><table class="totals">
        @foreach(['Subtotal'=>'subtotal','Diskon ('.$sale->discount_percent.'%)'=>'discount_amount','Total Bayar'=>'total','Dibayar'=>'paid_amount','Kembalian'=>'change_amount'] as $label=>$field)
        <tr class="{{ $field === 'total' ? 'grand' : '' }}"><td>{{ $label }}</td><td class="right">Rp {{ number_format($sale->$field,2,',','.') }}</td></tr>
        @endforeach
    </table></td></tr></table>
    <table class="signatures"><tr><td>Kasir<div class="signature-name">{{ $sale->cashier_name }}</div></td><td>Diterima oleh<div class="signature-name">{{ $sale->customer_name }}</div></td><td>Disetujui oleh<div class="signature-name">Manajer Keuangan</div></td></tr></table>
    <div class="footer">PT GARUDA FIBER &nbsp; | &nbsp; {{ $sale->invoice }} &nbsp; | &nbsp; Invoice Penjualan Kasir</div>
</body></html>
