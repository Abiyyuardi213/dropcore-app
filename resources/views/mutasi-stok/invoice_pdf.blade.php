<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice Mutasi | DropCore</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
            color: #333;
        }

        .header {
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }

        .header-title {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin: 0;
            display: inline-block;
        }

        .header-date {
            float: right;
            font-size: 12px;
            margin-top: 5px;
            color: #555;
        }

        .info-section {
            width: 100%;
            margin-bottom: 30px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            vertical-align: top;
            width: 33.33%;
            padding: 5px;
            border: 0;
        }

        .box-title {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
            color: #999;
            margin-bottom: 5px;
            display: block;
        }

        .address-box strong {
            font-size: 16px;
            display: block;
            margin-bottom: 5px;
        }

        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        .table-items th,
        .table-items td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .table-items th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }

        .table-items td {
            font-size: 13px;
        }

        .signatures {
            width: 100%;
            margin-top: 60px;
        }

        .signatures td {
            text-align: center;
            width: 33.33%;
            vertical-align: top;
        }

        .signature-line {
            display: inline-block;
            width: 80%;
            border-top: 1px solid #333;
            margin-top: 60px;
        }

        .badge {
            display: inline-block;
            padding: 3px 6px;
            font-size: 11px;
            font-weight: bold;
            color: #fff;
            background-color: #6c757d;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 class="header-title">PT. Garuda Lintas Nusa</h1>
        <span class="header-date">Tanggal: {{ \Carbon\Carbon::parse($mutasi->tanggal_mutasi)->format('d F Y') }}</span>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td>
                    <span class="box-title">Dari (Asal)</span>
                    <div class="address-box">
                        @if (in_array($mutasi->jenis_mutasi, ['keluar', 'pindah']))
                            <strong>{{ $mutasi->gudangAsal->nama_gudang ?? 'Gudang Utama' }}</strong>
                            {{ $mutasi->gudangAsal->lokasi ?? '-' }}<br>
                            Area: {{ $mutasi->areaAsal->kode_area ?? '-' }}<br>
                            Rak: {{ $mutasi->rakAsal->kode_rak ?? '-' }}
                        @else
                            <strong>Pihak Luar (Supplier)</strong>
                            Inbound Source
                        @endif
                    </div>
                </td>
                <td>
                    <span class="box-title">Ke (Tujuan)</span>
                    <div class="address-box">
                        @if (in_array($mutasi->jenis_mutasi, ['masuk', 'pindah']))
                            <strong>{{ $mutasi->gudangTujuan->nama_gudang ?? 'Gudang Tujuan' }}</strong>
                            {{ $mutasi->gudangTujuan->lokasi ?? '-' }}<br>
                            Area: {{ $mutasi->areaTujuan->kode_area ?? '-' }}<br>
                            Rak: {{ $mutasi->rakTujuan->kode_rak ?? '-' }}
                        @else
                            <strong>Pihak Luar (Konsumen/Usage)</strong>
                            Outbound Destination
                        @endif
                    </div>
                </td>
                <td>
                    <span class="box-title">Detail Invoice</span>
                    <div class="address-box">
                        <strong>#{{ str_pad($mutasi->id, 6, '0', STR_PAD_LEFT) }}</strong>
                        Ref: {{ $mutasi->referensi ?? '-' }}<br>
                        Jenis: {{ strtoupper($mutasi->jenis_mutasi) }}<br>
                        User: {{ $mutasi->user->name ?? 'System' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="table-items">
        <thead>
            <tr>
                <th>Qty</th>
                <th>Produk</th>
                <th>Serial / Kode</th>
                <th>Kondisi</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;"><strong>{{ $mutasi->quantity }}</strong></td>
                <td>
                    <strong>{{ $mutasi->produk->name }}</strong><br>
                    <small style="color: #666;">Unit: {{ $mutasi->produk->uom->name ?? 'Pcs' }}</small>
                </td>
                <td>{{ $mutasi->produk->sku ?? '-' }}</td>
                <td>{{ $mutasi->kondisi->nama_kondisi ?? 'Standard' }}</td>
                <td>{{ $mutasi->keterangan ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td>
                Admin Gudang
                <div class="signature-line"></div>
            </td>
            <td>
                Pengirim / Pembawa
                <div class="signature-line"></div>
            </td>
            <td>
                Penerima / Mengetahui
                <div class="signature-line"></div>
            </td>
        </tr>
    </table>
</body>

</html>
