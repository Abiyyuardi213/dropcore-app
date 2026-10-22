@extends('layouts.dashboard-master')

@section('content-header')
<div class="row mb-2 align-items-center">
    <div class="col-md-7"><h1 class="m-0">Dashboard Transaksi</h1><p class="text-muted mb-0 mt-1">Rekap pembelian dan penjualan barang.</p></div>
    <div class="col-md-5">
        <form method="GET" action="{{ route('dashboard-transaksi') }}" class="d-flex align-items-end justify-content-md-end mt-2">
            <div class="mr-2"><label for="tanggal" class="mb-1">Tanggal Rekap</label><input type="date" name="tanggal" id="tanggal" class="form-control form-control-sm" value="{{ $tanggal }}" required></div>
            <button type="submit" class="btn btn-primary btn-sm mr-2">Tampilkan</button>
            <a href="{{ route('dashboard-transaksi') }}" class="btn btn-default btn-sm">Hari Ini</a>
        </form>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid pb-3">
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <p class="mb-3">Rekap {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }} <span class="badge badge-secondary ml-2">Draft: {{ $ringkasan['draft'] }}</span> <span class="badge badge-secondary">Dibatalkan: {{ $ringkasan['batal'] }}</span></p>
    <div class="row">
        @foreach ([['Penerimaan Selesai', $ringkasan['penerimaan'], 'fas fa-boxes', 'info'], ['Pengeluaran Selesai', $ringkasan['pengeluaran'], 'fas fa-truck-loading', 'warning'], ['Nilai Pembelian', 'Rp '.number_format($ringkasan['pembelian'], 2, ',', '.'), 'fas fa-shopping-cart', 'danger'], ['Nilai Penjualan', 'Rp '.number_format($ringkasan['penjualan'], 2, ',', '.'), 'fas fa-receipt', 'success']] as [$label, $value, $icon, $color])
        <div class="col-md-6 col-xl-3"><div class="info-box shadow-sm h-100">
            <span class="info-box-icon bg-{{ $color }}"><i class="{{ $icon }}"></i></span>
            <div class="info-box-content"><span class="info-box-text">{{ $label }}</span><span class="info-box-number" style="overflow-wrap:anywhere">{{ $value }}</span></div>
        </div></div>
        @endforeach
    </div>
    <p class="text-muted small mt-2">Nilai dihitung dari transaksi barang selesai, termasuk yang belum dibayar. Pembelian mengikuti total invoice termasuk PPN; penjualan mengikuti total detail barang. Angka ini bukan arus kas atau laba.</p>
    <div class="card card-outline card-primary">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-calendar-alt mr-2"></i>Rekap Harian - 7 Hari</h3></div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-striped mb-0">
                <thead><tr><th>Tanggal</th><th class="text-right">Penerimaan</th><th class="text-right">Pengeluaran</th><th class="text-right">Nilai Pembelian</th><th class="text-right">Nilai Penjualan</th><th class="text-right">Draft</th><th class="text-right">Dibatalkan</th></tr></thead>
                <tbody>@foreach($harian as $day)<tr class="{{ $day['tanggal'] === $tanggal ? 'font-weight-bold' : '' }}">
                    <td class="text-nowrap">{{ \Carbon\Carbon::parse($day['tanggal'])->format('d/m/Y') }}</td><td class="text-right">{{ $day['penerimaan'] }}</td><td class="text-right">{{ $day['pengeluaran'] }}</td>
                    <td class="text-right text-nowrap">Rp {{ number_format($day['pembelian'], 2, ',', '.') }}</td><td class="text-right text-nowrap">Rp {{ number_format($day['penjualan'], 2, ',', '.') }}</td><td class="text-right">{{ $day['draft'] }}</td><td class="text-right">{{ $day['batal'] }}</td>
                </tr>@endforeach</tbody>
            </table>
        </div>
    </div>
    <div class="card card-outline card-secondary">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-history mr-2"></i>Transaksi Terbaru</h3></div>
        <div class="card-body">
            <p class="text-muted small">Seluruh tanggal, diurutkan berdasarkan waktu pencatatan terbaru. Klik nomor transaksi untuk melihat detail.</p>
            <div class="table-responsive"><table class="table table-hover">
                <thead><tr><th>No. Transaksi</th><th>Tanggal</th><th>Jenis</th><th>Status Barang</th><th>Pembayaran</th><th class="text-right">Nilai</th></tr></thead>
                <tbody>@forelse($terbaru as $item)<tr>
                    <td class="text-nowrap"><a href="{{ route($item->jenis.'-barang.show', $item->id) }}">{{ $item->nomor }}</a></td>
                    <td class="text-nowrap">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $item->jenis === 'penerimaan' ? 'Pembelian / Penerimaan' : 'Penjualan / Pengeluaran' }}</td>
                    <td><span class="badge badge-{{ $item->status === 'completed' ? 'success' : ($item->status === 'canceled' ? 'danger' : 'secondary') }}">{{ ['completed'=>'Selesai','pending'=>'Draft','canceled'=>'Dibatalkan'][$item->status] ?? $item->status }}</span></td>
                    <td>{{ ['paid'=>'Lunas','unpaid'=>'Belum dibayar','reversed'=>'Dibalik'][$item->payment_status] ?? '-' }}</td>
                    <td class="text-right text-nowrap">Rp {{ number_format($item->nilai, 2, ',', '.') }}</td>
                </tr>@empty<tr><td colspan="6" class="text-center text-muted py-4">Belum ada transaksi barang.</td></tr>@endforelse</tbody>
            </table></div>
            <div class="mt-3">{{ $terbaru->links('pagination::bootstrap-4') }}</div>
        </div>
    </div>
</div>
@endsection
