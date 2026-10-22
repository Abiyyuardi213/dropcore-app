<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan Pengeluaran - {{ $pengeluaran->no_pengeluaran }}</title>
    <style>
        @page { size: A4 portrait; margin: 32px 34px 40px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #222; line-height: 1.5; margin: 0; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        td, th { vertical-align: top; overflow-wrap: break-word; }
        .masthead { margin-bottom: 20px; }
        .logo { width: 108px; height: auto; margin-bottom: 8px; }
        .muted { color: #666; }
        .document-title { background: #222; color: #fff; padding: 12px 14px; text-align: center; font-size: 19px; font-weight: bold; letter-spacing: 1px; }
        .document-subtitle { text-align: right; margin-top: 7px; font-size: 9px; }
        .section-bar { background: #eee; padding: 7px 10px; border: 1px solid #222; font-weight: bold; color: #222; }
        .info { border: 1px solid #222; margin-bottom: 16px; }
        .info > tbody > tr > td { padding: 10px; }
        .info .split { border-left: 1px solid #222; }
        .label { font-size: 8px; color: #666; text-transform: uppercase; letter-spacing: .6px; margin-bottom: 4px; }
        .sender { font-size: 11px; font-weight: bold; }
        .meta td { padding: 2px 0; }
        .items { border: 1px solid #222; }
        .items th { background: #eee; border: 1px solid #222; padding: 8px 5px; font-size: 8px; }
        .items td { border: 1px solid #aaa; padding: 9px 5px; font-size: 8px; }
        .items thead { display: table-header-group; }
        .items tr { page-break-inside: avoid; }
        .right { text-align: right; }
        .center { text-align: center; }
        .sku { font-size: 7px; color: #666; margin-top: 4px; }
        .location { font-size: 7px; color: #666; margin-top: 4px; }
        .closing { margin-top: 14px; page-break-inside: avoid; }
        .notes { padding-right: 18px; }
        .totals td { padding: 6px 8px; border-bottom: 1px solid #ddd; }
        .grand td { background: #eee; border-top: 2px solid #222; font-weight: bold; font-size: 11px; }
        .signatures { margin-top: 30px; page-break-inside: avoid; }
        .signatures td { text-align: center; width: 33.33%; padding: 0 12px; }
        .signature-name { margin-top: 55px; padding-top: 5px; border-top: 1px solid #777; }
        .footer { margin-top: 24px; border-top: 1px solid #ddd; padding-top: 8px; font-size: 7px; color: #666; }
    </style>
</head>
<body>
    @php
        $isDistributor = $pengeluaran->tipe_penerima === 'distributor';
        $recipientName = $isDistributor ? ($pengeluaran->distributor->nama_distributor ?? '-') : ($pengeluaran->nama_konsumen ?: '-');
        $recipientAddress = $isDistributor ? ($pengeluaran->distributor->alamat ?? '-') : ($pengeluaran->alamat_konsumen ?: '-');
        $recipientPhone = $isDistributor ? ($pengeluaran->distributor->telepon ?? '-') : ($pengeluaran->telepon_konsumen ?: '-');
        $recipientEmail = $isDistributor ? ($pengeluaran->distributor->email ?? '-') : null;
        $subtotal = $pengeluaran->details->sum('subtotal');
        $total = $subtotal;
    @endphp
    <table class="masthead">
        <tr>
            <td style="width: 61%;">
                <img src="{{ public_path('image/garuda-fiber.png') }}" class="logo" alt="Logo Garuda Fiber">
                <div class="muted">Administrasi Gudang &amp; Pengeluaran Barang</div>
            </td>
            <td style="width: 39%; padding-top: 12px;">
                <div class="document-title">SURAT JALAN</div>
                <div class="document-subtitle">PENGELUARAN BARANG<br><strong>{{ $pengeluaran->no_pengeluaran }}</strong></div>
            </td>
        </tr>
    </table>

    <div class="section-bar">INFORMASI TRANSAKSI</div>
    <table class="info">
        <tr>
            <td style="width: 55%;">
                <div class="label">Penerima / {{ $isDistributor ? 'Distributor' : 'Konsumen' }}</div>
                <div class="sender">{{ $recipientName }}</div>
                <div>{{ $recipientAddress }}</div>
                <div>Telepon: {{ $recipientPhone }}</div>
                @if ($recipientEmail)<div>Email: {{ $recipientEmail }}</div>@endif
            </td>
            <td class="split" style="width: 45%;">
                <table class="meta">
                    <tr><td style="width: 40%;">Tanggal kirim</td><td>: {{ \Carbon\Carbon::parse($pengeluaran->tanggal_pengeluaran)->format('d/m/Y') }}</td></tr>
                    <tr><td>No. referensi</td><td>: {{ $pengeluaran->referensi ?: '-' }}</td></tr>
                    <tr><td>Status</td><td>: {{ ['completed' => 'Selesai', 'pending' => 'Draft / Pending', 'canceled' => 'Dibatalkan'][$pengeluaran->status] ?? $pengeluaran->status }}</td></tr>
                    <tr><td>Pembayaran</td><td>: {{ ['paid' => 'Lunas', 'unpaid' => 'Belum dibayar', 'reversed' => 'Dibalik'][$pengeluaran->payment_status] ?? 'Belum dibayar' }}</td></tr>
                    <tr><td>Petugas</td><td>: {{ $pengeluaran->user->name ?? 'System' }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 43%;">Deskripsi Barang / SKU</th>
                <th style="width: 10%;">Kondisi</th>
                <th style="width: 7%;">Qty</th>
                <th style="width: 17%;">Harga Satuan (Rp)</th>
                <th style="width: 18%;">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengeluaran->details as $detail)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $detail->produk->name ?? 'Produk tidak tersedia' }}</strong>
                        <div class="sku">SKU: {{ $detail->produk->sku ?? '-' }}</div>
                        <div class="location">{{ $detail->gudang->nama_gudang ?? '-' }} / {{ $detail->area->kode_area ?? '-' }} / {{ $detail->rak->kode_rak ?? '-' }}</div>
                    </td>
                    <td class="center">{{ $detail->kondisi->nama_kondisi ?? '-' }}</td>
                    <td class="right">{{ number_format($detail->qty, 0, ',', '.') }}</td>
                    <td class="right">{{ number_format($detail->harga, 2, ',', '.') }}</td>
                    <td class="right">{{ number_format($detail->subtotal, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="center">Tidak ada detail barang.</td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="closing">
        <tr>
            <td class="notes" style="width: 55%;">
                <div class="label">Catatan Pengeluaran</div>
                <div>{{ $pengeluaran->keterangan ?: 'Tidak ada catatan tambahan.' }}</div>
                <p class="muted">Dokumen ini merupakan bukti pencatatan pengeluaran barang oleh PT Garuda Fiber.</p>
            </td>
            <td style="width: 45%;">
                <table class="totals">
                    <tr><td>Subtotal</td><td class="right">Rp {{ number_format($subtotal, 2, ',', '.') }}</td></tr>
                    <tr class="grand"><td>Total Bayar</td><td class="right">Rp {{ number_format($total, 2, ',', '.') }}</td></tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="signatures">
        <tr>
            <td>Pengirim<div class="signature-name">{{ $pengeluaran->user->name ?? 'Petugas Gudang' }}</div></td>
            <td>Diterima oleh<div class="signature-name">{{ $recipientName }}</div></td>
            <td>Disetujui oleh<div class="signature-name">Manajer Keuangan</div></td>
        </tr>
    </table>
    <div class="footer">PT GARUDA FIBER &nbsp; | &nbsp; {{ $pengeluaran->no_pengeluaran }} &nbsp; | &nbsp; Simpan dokumen ini sebagai arsip pengeluaran barang.</div>
</body>
</html>
