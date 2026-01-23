<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Pajak - {{ $order->tax_invoice_number ?? $order->order_number }}</title>
    <style>
        body {
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 11pt;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 30px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18pt;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0;
            font-size: 10pt;
        }

        .info-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .info-column {
            width: 48%;
        }

        .info-title {
            font-weight: bold;
            margin-bottom: 5px;
            text-decoration: underline;
            font-size: 10pt;
        }

        .info-row {
            margin-bottom: 3px;
            font-size: 10pt;
        }

        .info-label {
            display: inline-block;
            width: 100px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10pt;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th {
            background-color: #f0f0f0;
            padding: 8px;
            text-align: center;
        }

        td {
            padding: 8px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals-row td {
            border-top: 2px solid #000;
            font-weight: bold;
        }

        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            margin-top: 60px;
            border-bottom: 1px solid #000;
        }

        @media print {
            body {
                padding: 0;
            }

            .container {
                border: none;
                max-width: 100%;
                width: 100%;
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: right; max-width: 800px; margin: 0 auto 10px auto;">
        <button onclick="window.print()"
            style="padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; border-radius: 4px;">Print
            Faktur</button>
    </div>

    <div class="container">
        <div class="header">
            <h1>Faktur Pajak</h1>
            <p>Lembar 1: Untuk Pembeli BKP / Penerima JKP sebagai bukti Pajak Masukan</p>
        </div>

        <div class="info-grid">
            <div class="info-column">
                <div class="info-title">Pengusaha Kena Pajak (Penjual)</div>
                <div class="info-row"><span class="info-label">Nama:</span> PT. GARUDA FIBER INDONESIA</div>
                <div class="info-row"><span class="info-label">Alamat:</span> Jl. Teknologi Fiber Optic No. 88, Jakarta
                    Selatan</div>
                <div class="info-row"><span class="info-label">NPWP:</span> 01.234.567.8-011.000</div>
            </div>
            <div class="info-column">
                <div class="info-title">Pembeli Barang Kena Pajak / Penerima Jasa</div>
                <div class="info-row"><span class="info-label">Nama:</span> {{ $order->user->name }}</div>
                <div class="info-row"><span class="info-label">Alamat:</span>
                    {{ $order->shipping_address == 'Alamat belum diatur' && $order->user->alamat ? $order->user->alamat : $order->shipping_address }}
                </div>
                <div class="info-row"><span class="info-label">NPWP:</span> {{ $order->user->npwp ?? '-' }}</div>
                <div class="info-row"><span class="info-label">NIK/Paspor:</span> {{ $order->user->nik ?? '-' }}</div>
            </div>
        </div>

        <div class="info-row" style="margin-bottom: 15px;">
            <span class="info-label" style="width: auto; margin-right: 10px;">Nomor Ref:</span>
            {{ $order->order_number }}
        </div>

        <div class="info-row" style="margin-bottom: 15px;">
            <span class="info-label" style="width: auto; margin-right: 10px;">Tanggal Faktur:</span>
            {{ $order->updated_at->format('d/m/Y') }}
            <!-- Usually faktur date is when it is completed/processed -->
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th>Nama Barang Kena Pajak / Jasa Kena Pajak</th>
                    <th style="width: 20%;">Harga Jual / Penggantian / Uang Muka / Termin (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            {{ $item->product->name }}<br>
                            <small>Qty: {{ $item->quantity }} x @ Rp
                                {{ number_format($item->price, 0, ',', '.') }}</small>
                        </td>
                        <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php
                    // Fallback calculation for legacy orders
                    $dpp = $order->tax_base > 0 ? $order->tax_base : $order->total_price / 1.11;
                    $ppn = $order->tax_amount > 0 ? $order->tax_amount : $dpp * 0.11;
                @endphp
                <tr>
                    <td colspan="2" class="text-right"><strong>Harga Jual / Penggantian</strong></td>
                    <td class="text-right">{{ number_format($dpp, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right"><strong>Dikurangi Potongan Harga</strong></td>
                    <td class="text-right">0</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right"><strong>Dikurangi Uang Muka</strong></td>
                    <td class="text-right">0</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right"><strong>Dasar Pengenaan Pajak (DPP)</strong></td>
                    <td class="text-right">{{ number_format($dpp, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right"><strong>PPN (11%)</strong></td>
                    <td class="text-right">{{ number_format($ppn, 0, ',', '.') }}</td>
                </tr>
                <tr style="background-color: #eee;">
                    <td colspan="2" class="text-right"><strong>Total Bayar</strong></td>
                    <td class="text-right"><strong>{{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <div style="width: 60%;">
                <p style="font-size: 9pt;">
                    Sesuai dengan ketentuan peraturan perundang-undangan perpajakan yang berlaku,<br>
                    faktur pajak ini telah ditandatangani secara elektronik sehingga tidak diperlukan tanda tangan
                    basah.
                </p>
            </div>
            <div class="signature">
                <p>Jakarta, {{ $order->updated_at->format('d F Y') }}</p>
                <p>Direktur Keuangan</p>
                <div class="signature-line"></div>
                <p>Budi Santoso</p>
            </div>
        </div>
    </div>
</body>

</html>
