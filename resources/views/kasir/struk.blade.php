@extends('layouts.kasir')
@section('title', 'Struk Penjualan Kasir | Garuda Fiber')
@section('heading', 'Struk Penjualan')
@push('styles')
<style>@media print { .main-header,.main-footer,.content-header,.receipt-actions,.alert {display:none!important} body{padding:0!important}.content-wrapper{min-height:0!important}.content-wrapper>.content>.container-fluid{padding:0!important;max-width:none}.card{box-shadow:none!important;border:0!important} }</style>
@endpush
@section('content')
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card card-outline card-primary shadow-sm"><div class="card-body">
    <div class="d-flex justify-content-between flex-wrap mb-4"><img src="{{ asset('image/garuda-fiber.png') }}" alt="Garuda Fiber" style="width:140px;height:auto;object-fit:contain"><div class="text-right"><h2 class="h5">{{ $sale->invoice }}</h2><span>{{ $sale->sale_date->format('d/m/Y') }}</span></div></div>
    <p>Kasir: <strong>{{ $sale->cashier_name }}</strong><br>Pelanggan: {{ $sale->customer_name }} @if($sale->customer_phone) ({{ $sale->customer_phone }}) @endif<br>Status: {{ $sale->outgoing?->status === 'completed' ? 'Selesai / Lunas' : 'Dibatalkan' }}</p>
    <div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Produk / SKU</th><th>Qty</th><th class="text-right">Harga</th><th class="text-right">Jumlah setelah diskon</th></tr></thead><tbody>@foreach($sale->items as $item)<tr><td>{{ $item->product_name }}<small class="d-block text-muted">{{ $item->sku }} &middot; {{ $item->location }}</small></td><td>{{ $item->quantity }} {{ $item->unit }}</td><td class="text-right">Rp {{ number_format($item->unit_price,2,',','.') }}</td><td class="text-right">Rp {{ number_format($item->total,2,',','.') }}</td></tr>@endforeach</tbody></table></div>
    <div class="row"><div class="col-md-6 ml-auto"><table class="table table-sm"><tbody>
        @foreach(['Subtotal'=>'subtotal','Diskon ('.$sale->discount_percent.'%)'=>'discount_amount','Total Bayar'=>'total','Dibayar'=>'paid_amount','Kembalian'=>'change_amount'] as $label=>$field)<tr><th>{{ $label }}</th><td class="text-right">Rp {{ number_format($sale->$field,2,',','.') }}</td></tr>@endforeach
        <tr><th>Akun Penerimaan</th><td class="text-right">{{ $sale->account->nama_sumber ?? '-' }}</td></tr>
    </tbody></table></div></div>
    <div class="receipt-actions d-flex flex-wrap" style="gap:.5rem"><a class="btn btn-primary" href="{{ route('kasir.penjualan.struk', $sale->id) }}" target="_blank" rel="noopener">Cetak Struk (80 mm)</a><a class="btn btn-success" href="{{ route('kasir.penjualan.invoice', $sale->id) }}">Simpan Invoice (PDF)</a><a class="btn btn-default" href="{{ route('kasir.penjualan') }}">Transaksi Baru</a><a class="btn btn-default" href="{{ route('kasir.laporan') }}">Lihat Laporan</a></div>
</div></div>
@endsection
