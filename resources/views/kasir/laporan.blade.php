@extends('layouts.kasir')
@section('title', 'Laporan Kasir | Garuda Fiber')
@section('heading', 'Laporan Kasir')

@push('styles')
<style>
    @media print {
        .main-header, .main-footer, .content-header, .report-controls { display: none !important; }
        body { padding-top: 0 !important; background: #fff !important; }
        .content-wrapper { min-height: 0 !important; }
        .content-wrapper > .content > .container-fluid { max-width: none; padding: 0 !important; }
        .cashier-report { box-shadow: none !important; border: 0; }
    }
</style>
@endpush

@section('content')
<div class="card card-outline card-primary shadow-sm cashier-report" style="border-radius:0">
    <div class="card-header d-flex align-items-center"><h2 class="card-title font-weight-bold"><i class="fas fa-file-lines mr-2" aria-hidden="true"></i>Laporan Penjualan Kasir</h2><button type="button" onclick="window.print()" class="btn btn-default btn-sm ml-auto report-controls"><i class="fas fa-print mr-1" aria-hidden="true"></i>Cetak</button></div>
    <div class="card-body">
        @if($errors->any())<div class="alert alert-danger report-controls" role="alert">{{ $errors->first() }}</div>@endif
        <form method="GET" action="{{ route('kasir.laporan') }}" class="report-controls mb-4">
            <div class="row align-items-end">
                <div class="col-md-4 form-group"><label for="tanggalAwal">Tanggal Awal</label><input id="tanggalAwal" name="tanggal_awal" type="date" class="form-control" value="{{ $tanggalAwal }}" required></div>
                <div class="col-md-4 form-group"><label for="tanggalAkhir">Tanggal Akhir</label><input id="tanggalAkhir" name="tanggal_akhir" type="date" class="form-control" value="{{ $tanggalAkhir }}" required></div>
                <div class="col-md-4 form-group"><button type="submit" class="btn btn-primary mr-2">Tampilkan</button><a href="{{ route('kasir.laporan') }}" class="btn btn-default">Bulan Ini</a></div>
            </div>
        </form>
        <div class="mb-3"><strong>{{ auth()->user()->name }}</strong><p class="text-muted small mb-0">Periode {{ \Carbon\Carbon::parse($tanggalAwal)->format('d/m/Y') }} &ndash; {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}</p></div>
        <div class="row">
            <div class="col-md-4"><div class="info-box shadow-none border"><span class="info-box-icon bg-primary"><i class="fas fa-file-invoice" aria-hidden="true"></i></span><div class="info-box-content"><span class="info-box-text">Jumlah Transaksi</span><span class="info-box-number">{{ $count }}</span></div></div></div>
            <div class="col-md-4"><div class="info-box shadow-none border"><span class="info-box-icon bg-success"><i class="fas fa-coins" aria-hidden="true"></i></span><div class="info-box-content"><span class="info-box-text">Total Penjualan</span><span class="info-box-number">Rp {{ number_format($total, 2, ',', '.') }}</span></div></div></div>
            <div class="col-md-4"><div class="info-box shadow-none border"><span class="info-box-icon bg-info"><i class="fas fa-box" aria-hidden="true"></i></span><div class="info-box-content"><span class="info-box-text">Unit Terjual</span><span class="info-box-number">{{ $quantity }}</span></div></div></div>
        </div>
        <div class="table-responsive"><table class="table table-bordered table-striped"><thead><tr><th>No.</th><th>No. Invoice</th><th>Tanggal</th><th>Pelanggan</th><th>Kasir</th><th class="text-right">Sub Total</th><th class="text-right">Diskon (%)</th><th class="text-right">Total Bayar</th><th class="text-right" title="Total harga beli barang saat transaksi dikali jumlah terjual">Modal</th><th class="text-right" title="Total bayar setelah diskon dikurangi modal; belum dikurangi biaya operasional">Laba</th></tr></thead><tbody>
            @forelse($transactions as $transaction)<tr>
                <td>{{ $transactions->firstItem() + $loop->index }}</td>
                <td><a href="{{ route('kasir.penjualan.show', $transaction->id) }}">{{ $transaction->invoice }}</a></td>
                <td>{{ $transaction->sale_date->format('d/m/Y') }}</td>
                <td>{{ $transaction->customer_name }}</td>
                <td>{{ $transaction->cashier_name }}</td>
                <td class="text-right text-nowrap">Rp {{ number_format($transaction->subtotal, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($transaction->discount_percent, 2, ',', '.') }}%</td>
                <td class="text-right text-nowrap">Rp {{ number_format($transaction->total, 2, ',', '.') }}</td>
                @if($transaction->profit !== null)
                    <td class="text-right text-nowrap">Rp {{ number_format($transaction->total - $transaction->profit, 2, ',', '.') }}</td>
                    <td class="text-right text-nowrap">Rp {{ number_format($transaction->profit, 2, ',', '.') }}</td>
                @else
                    <td class="text-right" title="Harga beli sebagian barang belum tersedia saat transaksi">Belum tersedia</td>
                    <td class="text-right" title="Harga beli sebagian barang belum tersedia saat transaksi">Belum tersedia</td>
                @endif
            </tr>
            @empty<tr><td colspan="10" class="text-center text-muted py-4"><i class="fas fa-file-invoice fa-2x mb-3 d-block" aria-hidden="true"></i>Belum ada transaksi kasir pada periode ini.</td></tr>@endforelse
        </tbody></table></div>
        <div class="report-controls">{{ $transactions->links('pagination::bootstrap-4') }}</div>
        <p class="text-muted small mb-0">Laporan penjualan selesai milik akun kasir ini. Transaksi yang dibatalkan tidak termasuk.</p>
    </div>
</div>
@endsection
