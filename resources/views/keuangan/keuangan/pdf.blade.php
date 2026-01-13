<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bukti Transaksi - {{ $data->no_transaksi }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ddd;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #0056b3;
            margin-bottom: 5px;
        }

        .address {
            font-size: 10px;
            color: #666;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .table-info {
            width: 100%;
            margin-bottom: 20px;
        }

        .table-info td {
            padding: 5px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            width: 120px;
        }

        .amount-box {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
        }

        .amount-value {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }

        .amount-value.expense {
            color: #dc3545;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .signature-section {
            margin-top: 50px;
            width: 100%;
        }

        .signature-box {
            width: 40%;
            float: right;
            text-align: center;
        }

        .signature-line {
            margin-top: 60px;
            border-bottom: 1px solid #333;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="logo">GARUDA FIBER</div>
        <div class="address">
            Jalan Raya Example No. 123, Kota, Provinsi<br>
            Telp: (021) 123-4567 | Email: finance@garudafiber.com
        </div>
    </div>

    <div class="title">BUKTI TRANSAKSI KEUANGAN</div>

    <table class="table-info">
        <tr>
            <td class="label">No. Transaksi</td>
            <td>: {{ $data->no_transaksi }}</td>
            <td class="label">Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($data->tanggal_transaksi)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Jenis</td>
            <td>: {{ ucfirst($data->jenis_transaksi) }}</td>
            <td class="label">Status</td>
            <td>: {{ ucfirst($data->status) }}</td>
        </tr>
        <tr>
            <td class="label">Kategori</td>
            <td>: {{ $data->kategori ? $data->kategori->nama : '-' }}</td>
            <td class="label">Dibuat Oleh</td>
            <td>: {{ $data->user ? $data->user->name : 'System' }}</td>
        </tr>
        <tr>
            <td class="label">Akun</td>
            <td colspan="3">: {{ $data->sumber ? $data->sumber->nama_sumber : '-' }}</td>
        </tr>
    </table>

    <div class="amount-box">
        <div>Total Nominal</div>
        <div class="amount-value {{ $data->jenis_transaksi == 'pengeluaran' ? 'expense' : '' }}">
            Rp {{ number_format($data->jumlah, 0, ',', '.') }}
        </div>
    </div>

    @if ($data->keterangan)
        <div style="margin-bottom: 20px;">
            <strong>Keterangan:</strong><br>
            <div style="background: #fff; border: 1px solid #eee; padding: 10px; margin-top: 5px;">
                {{ $data->keterangan }}
            </div>
        </div>
    @endif

    <div class="signature-section">
        <div class="signature-box">
            <div>Disetujui Oleh,</div>
            <div class="signature-line"></div>
            <div>( Manager Keuangan )</div>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="footer">
        Dicetak pada: {{ date('d F Y H:i:s') }}<br>
        Dokumen ini dibuat secara otomatis oleh sistem dan sah tanpa tanda tangan basah jika terdapat validasi digital.
    </div>

</body>

</html>
