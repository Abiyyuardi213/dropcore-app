@extends('layouts.kasir')
@section('title', 'Dashboard Kasir | Garuda Fiber')
@section('heading', 'Dashboard Kasir')

@push('styles')
<style>
    .pos-sheet { border-radius: 0; }
    .pos-sheet .card-body { padding: 1.25rem; }
    .pos-sheet label { font-size: .875rem; margin-bottom: .3rem; }
    .pos-sheet .form-control, .pos-sheet .btn { border-radius: 2px; }
    .pos-sheet .form-control[readonly] { background-color: #e9ecef; }
    .pos-section-title { font-size: 1.4rem; font-weight: 400; margin-bottom: .75rem; }
    .pos-table { min-width: 650px; }
    .pos-table th { white-space: nowrap; }
    .pos-table th, .pos-table td { vertical-align: middle; padding: .65rem .6rem; }
    .pos-table tfoot td { background: #d9edf7; }
    .pos-summary { width: 100%; max-width: 440px; margin-top: 1.5rem; }
    .pos-summary td, .pos-summary th { padding: .65rem; vertical-align: middle; }
    .pos-bottom { display: flex; flex-wrap: wrap; gap: .75rem; align-items: center; justify-content: space-between; }
    .pos-code-search { display: flex; gap: .5rem; align-items: center; }
    .pos-code-search .form-control { min-width: 0; }
    .pos-code-search .btn { flex-shrink: 0; }
    @media (max-width: 991.98px) { .pos-list { margin-top: 1.5rem; } }
</style>
@endpush

@section('content')
<div class="card card-outline card-primary shadow-sm pos-sheet">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <span class="text-muted small">Transaksi Penjualan</span>
            <span class="badge badge-light border">Pratinjau layout &middot; Data contoh</span>
        </div>
        <div class="row">
            <div class="col-md-3 form-group"><label for="cashierName">Kasir</label><input id="cashierName" class="form-control form-control-sm" value="{{ auth()->user()->name }}" readonly></div>
            <div class="col-md-3 form-group"><label for="invoiceNumber">No. Invoice</label><input id="invoiceNumber" class="form-control form-control-sm" value="CONTOH-001" readonly></div>
            <div class="col-md-3 form-group"><label for="invoiceDate">Tanggal Invoice</label><input id="invoiceDate" type="date" class="form-control form-control-sm" value="{{ now('Asia/Jakarta')->format('Y-m-d') }}" readonly></div>
            <div class="col-md-3 form-group"><label for="customerName">Pelanggan</label><input id="customerName" class="form-control form-control-sm" placeholder="Masukkan nama pelanggan" disabled></div>
        </div>
        <hr class="mt-2 mb-4">
        <div class="row">
            <section class="col-lg-3" aria-labelledby="addItemTitle">
                <h2 class="pos-section-title" id="addItemTitle">Tambah Barang</h2>
                <div class="form-group"><label for="productCode">Kode Produk / SKU</label><div class="pos-code-search"><input id="productCode" class="form-control form-control-sm" placeholder="Masukkan kode produk" disabled><button class="btn btn-primary btn-sm" type="button" disabled><i class="fas fa-search mr-1" aria-hidden="true"></i>Cari Produk</button></div></div>
                <div class="form-group"><label for="productName">Produk</label><input id="productName" class="form-control form-control-sm" readonly></div>
                <div class="form-group"><label for="productPrice">Harga</label><input id="productPrice" class="form-control form-control-sm" readonly></div>
                <div class="form-group"><label for="productQty">Jumlah</label><input id="productQty" type="number" min="1" class="form-control form-control-sm" disabled></div>
                <div class="form-group"><label for="productTotal">Total</label><input id="productTotal" class="form-control form-control-sm" readonly></div>
                <button type="button" class="btn btn-primary btn-sm btn-block text-uppercase" disabled>Tambahkan</button>
            </section>
            <section class="col-lg-9 pos-list" aria-labelledby="purchaseListTitle">
                <h2 class="pos-section-title" id="purchaseListTitle">Daftar Belanja</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm pos-table mb-0">
                        <thead><tr><th>Kode Produk</th><th>Nama Produk</th><th class="text-right">Harga</th><th class="text-center">Jumlah</th><th class="text-right">Total</th><th class="text-center">Opsi</th></tr></thead>
                        <tbody>
                            <tr><td>CONTOH-001</td><td>Produk Contoh A</td><td class="text-right text-nowrap">Rp 5.000</td><td class="text-center">1</td><td class="text-right text-nowrap">Rp 5.000</td><td class="text-center"><button type="button" class="btn btn-danger btn-xs" disabled><i class="fas fa-times mr-1" aria-hidden="true"></i>Batal</button></td></tr>
                            <tr><td>CONTOH-002</td><td>Produk Contoh B</td><td class="text-right text-nowrap">Rp 5.000</td><td class="text-center">1</td><td class="text-right text-nowrap">Rp 5.000</td><td class="text-center"><button type="button" class="btn btn-danger btn-xs" disabled><i class="fas fa-times mr-1" aria-hidden="true"></i>Batal</button></td></tr>
                        </tbody>
                        <tfoot><tr><td colspan="2" class="text-right font-weight-bold">Total</td><td class="text-right text-nowrap">Rp 10.000</td><td class="text-center">2</td><td class="text-right text-nowrap font-weight-bold">Rp 10.000</td><td></td></tr></tfoot>
                    </table>
                </div>
                <table class="table table-striped table-sm pos-summary">
                    <tbody>
                        <tr><th style="width:50%">Subtotal Belanja</th><td>Rp 10.000</td></tr>
                        <tr><th><label for="discount" class="mb-0">Diskon</label></th><td><div class="input-group input-group-sm"><input id="discount" type="number" class="form-control" value="0" min="0" max="100" disabled><div class="input-group-append"><span class="input-group-text">%</span></div></div></td></tr>
                        <tr><th>Total Belanja</th><td class="font-weight-bold">Rp 10.000</td></tr>
                    </tbody>
                </table>
            </section>
        </div>
        <hr class="my-4">
        <div class="pos-bottom">
            <button type="button" class="btn btn-danger btn-sm" disabled><i class="fas fa-times mr-1" aria-hidden="true"></i>Batalkan Transaksi</button>
            <button type="button" class="btn btn-success btn-sm" disabled><i class="fas fa-check mr-1" aria-hidden="true"></i>Buat Transaksi</button>
        </div>
    </div>
</div>
@endsection
