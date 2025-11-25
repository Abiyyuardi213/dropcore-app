<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penerimaan Barang - {{ $penerimaan->no_penerimaan }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 40px;
            color: #333;
        }

        /* Header */
        .header {
            width: 100%;
            margin-bottom: 25px;
        }
        .header .logo {
            width: 120px;
        }
        .header-right {
            text-align: right;
            font-size: 12px;
            line-height: 1.5;
        }
        .title {
            font-size: 22px;
            font-weight: bold;
            margin-top: 5px;
        }

        /* Section Title */
        .section-title {
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 8px;
            font-size: 14px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 3px;
        }

        /* Info Blocks */
        .info-table {
            width: 100%;
            margin-top: 5px;
            margin-bottom: 10px;
        }
        .info-block {
            font-size: 12px;
            line-height: 1.6;
        }

        /* Product Table */
        table.product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .product-table th {
            background: #f5f5f5;
            font-weight: bold;
            padding: 8px;
            border-bottom: 1px solid #ccc;
            color: #444;
        }
        .product-table td {
            padding: 7px 8px;
            border-bottom: 1px solid #eee;
        }

        .text-right {
            text-align: right;
        }

        /* Total Section */
        .total-box {
            margin-top: 15px;
            width: 100%;
        }
        .total-box td {
            padding: 6px 0;
            font-size: 13px;
        }
        .total-label {
            font-weight: bold;
            text-align: right;
        }
        .total-value {
            font-weight: bold;
            text-align: right;
            border-top: 2px solid #333;
            padding-top: 5px;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header">
        <tr>
            <td>
                <img src="{{ public_path('image/garuda-fiber.png') }}" alt="Logo Perusahaan" class="logo">
                <div class="title">Laporan Penerimaan Barang</div>
            </td>
            <td class="header-right">
                <strong>No Penerimaan:</strong> {{ $penerimaan->no_penerimaan }}<br>
                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($penerimaan->tanggal_penerimaan)->format('d/m/Y') }}<br>
                <strong>Dicetak:</strong> {{ date('d/m/Y H:i') }}
            </td>
        </tr>
    </table>

    <!-- Informasi Supplier dan Penerimaan -->
    <div class="section-title">Informasi Penerimaan</div>

    <table class="info-table">
        <tr>
            <td width="50%">
                <div class="info-block">
                    <strong>Supplier</strong><br>
                    {{ $penerimaan->supplier->nama_supplier }}<br>
                    {{ $penerimaan->supplier->alamat ?? '-' }}<br>
                    Telp: {{ $penerimaan->supplier->telepon ?? '-' }}
                </div>
            </td>

            <td width="50%">
                <div class="info-block">
                    <strong>Keterangan</strong><br>
                    {{ $penerimaan->keterangan ?? '-' }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Detail Barang -->
    <div class="section-title">Detail Barang</div>

    <table class="product-table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Gudang</th>
                <th>Area</th>
                <th>Rak</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($penerimaan->details as $d)
                @php $total += $d->subtotal; @endphp
                <tr>
                    <td>{{ $d->produk->name ?? '-' }}</td>
                    <td>{{ $d->gudang->nama_gudang ?? '-' }}</td>
                    <td>{{ $d->area->nama_area ?? '-' }}</td>
                    <td>{{ $d->rak->kode_rak ?? '-' }}</td>
                    <td class="text-right">{{ $d->qty }}</td>
                    <td class="text-right">{{ number_format($d->harga,0,',','.') }}</td>
                    <td class="text-right">{{ number_format($d->subtotal,0,',','.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total -->
    <table class="total-box">
        <tr>
            <td width="70%"></td>
            <td class="total-label">Total:</td>
            <td class="total-value">{{ number_format($total,0,',','.') }}</td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        Sistem Manajemen Informasi – PT DropCore Indonesia
    </div>

</body>
</html>
