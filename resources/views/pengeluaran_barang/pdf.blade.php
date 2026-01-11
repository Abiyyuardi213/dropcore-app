<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pengeluaran Barang - {{ $pengeluaran->no_pengeluaran }}</title>

    <style>
        /*
         * STYLING CSS MONOKROMATIK (HITAM PUTIH) UNTUK LAPORAN PDF
         */

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 40px;
            color: #333;
            /* Hitam gelap */
            line-height: 1.5;
        }

        /* --- Header Section --- */
        .header {
            width: 100%;
            margin-bottom: 25px;
            border-bottom: 3px solid #333;
            /* Garis pemisah hitam tebal */
            padding-bottom: 10px;
        }

        .header .logo {
            width: 100px;
        }

        .header-right {
            text-align: right;
            font-size: 11px;
            line-height: 1.6;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-top: 5px;
            margin-bottom: 0;
        }

        /* --- Section Title --- */
        .section-title {
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 8px;
            font-size: 13px;
            color: #333;
            border-bottom: 1px solid #333;
            padding-bottom: 3px;
        }

        /* --- Info Blocks (Penerima & Keterangan) --- */
        .info-table {
            width: 100%;
            margin-top: 5px;
            margin-bottom: 20px;
        }

        .info-block {
            font-size: 11px;
            padding: 10px;
            border: 1px solid #ccc;
            /* Border abu-abu terang */
            border-left: 5px solid #666;
            /* Garis vertikal menonjol */
            border-radius: 2px;
            background-color: #f9f9f9;
            /* Latar belakang abu-abu sangat terang */
        }

        .info-block strong {
            display: block;
            font-size: 12px;
            color: #333;
            margin-bottom: 5px;
        }

        /* --- Product Table (Detail Barang) --- */
        table.product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .product-table th {
            background: #666;
            /* Header tabel abu-abu gelap */
            color: white;
            font-weight: 600;
            padding: 8px;
            border: 1px solid #666;
            text-align: left;
        }

        .product-table td {
            padding: 7px 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        .product-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* --- Total Section --- */
        .total-box {
            margin-top: 15px;
            width: 100%;
        }

        .total-box td {
            padding: 6px 0;
            font-size: 12px;
        }

        .total-label {
            font-weight: bold;
            text-align: right;
            width: 150px;
            padding-right: 10px;
            color: #333;
        }

        .total-value {
            font-weight: bold;
            text-align: right;
            border-top: 3px solid #333;
            /* Garis tebal hitam di atas nilai total */
            padding-top: 5px;
            font-size: 14px;
            width: 150px;
            color: #333;
        }

        /* --- Footer --- */
        .footer {
            margin-top: 70px;
            border-top: 1px solid #aaa;
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>

    <table class="header">
        <tr>
            <td style="width: 50%;">
                <img src="{{ public_path('image/garuda-fiber.png') }}" alt="Logo Perusahaan" class="logo"><br>
                <div class="title">LAPORAN PENGELUARAN BARANG</div>
            </td>
            <td class="header-right" style="width: 50%;">
                <strong>No Pengeluaran:</strong> <span style="color:#333;">{{ $pengeluaran->no_pengeluaran }}</span><br>
                <strong>Tanggal Keluar:</strong>
                {{ \Carbon\Carbon::parse($pengeluaran->tanggal_pengeluaran)->format('d F Y') }}<br>
                <strong>Dicetak Pada:</strong> {{ date('d/m/Y H:i') }}
            </td>
        </tr>
    </table>

    <div class="section-title">Informasi Transaksi</div>

    <table class="info-table">
        <tr>
            <td style="width: 48%;">
                <div class="info-block">
                    <strong>Penerima Barang</strong><br>
                    @if ($pengeluaran->tipe_penerima === 'distributor')
                        **{{ optional($pengeluaran->distributor)->nama_distributor ?? 'N/A' }}**<br>
                        Tipe: Distributor (Kode: {{ optional($pengeluaran->distributor)->kode_distributor ?? '-' }})
                    @else
                        **{{ $pengeluaran->nama_konsumen ?? 'N/A' }}**<br>
                        Tipe: Konsumen<br>
                        Telp: {{ $pengeluaran->telepon_konsumen ?? '-' }}<br>
                        Alamat: {{ $pengeluaran->alamat_konsumen ?? '-' }}
                    @endif
                </div>
            </td>

            <td style="width: 4%;">
            </td>

            <td style="width: 48%;">
                <div class="info-block">
                    <strong>Keterangan Pengeluaran</strong><br>
                    {{ $pengeluaran->keterangan ?? 'Tidak ada keterangan tambahan.' }}
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Detail Barang yang Dikeluarkan</div>

    <table class="product-table">
        <thead>
            <tr>
                <th style="width: 25%;">Produk</th>
                <th style="width: 15%;">Gudang</th>
                <th style="width: 10%;">Area</th>
                <th style="width: 10%;">Rak</th>
                <th class="text-right" style="width: 10%;">Qty</th>
                <th class="text-right" style="width: 15%;">Harga Satuan</th>
                <th class="text-right" style="width: 15%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach ($pengeluaran->details as $d)
                @php $total += $d->subtotal; @endphp
                <tr>
                    <td>{{ $d->produk->name ?? '-' }}</td>
                    <td>{{ $d->gudang->nama_gudang ?? '-' }}</td>
                    <td>{{ $d->area->nama_area ?? '-' }}</td>
                    <td>{{ $d->rak->kode_rak ?? '-' }}</td>
                    <td class="text-right">{{ $d->qty }}</td>
                    <td class="text-right">Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="total-box">
        <tr>
            <td style="width: 70%;"></td>
            <td class="total-label">TOTAL PENGELUARAN:</td>
            <td class="total-value">Rp {{ number_format($total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table style="width: 100%; margin-top: 50px;">
        <tr>
            <td style="width: 50%; text-align: center;">
                <p>Disiapkan Oleh:</p>
                <br><br><br>
                <p>_________________________</p>
            </td>
            <td style="width: 50%; text-align: center;">
                <p>Disetujui Oleh:</p>
                <br><br><br>
                <p>_________________________</p>
            </td>
        </tr>
    </table>

    <div class="footer">
        Laporan Pengeluaran ini otomatis dihasilkan oleh Sistem. Harap simpan sebagai bukti transaksi.<br>
        Sistem Manajemen Informasi – PT Garuda Fiber
    </div>

</body>

</html>
