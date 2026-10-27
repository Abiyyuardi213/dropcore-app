@extends('layouts.kasir')
@section('title', 'Penjualan Kasir | Garuda Fiber')
@section('heading', 'Penjualan Kasir')

@push('styles')
<style>
    .pos-sheet { border-radius: 0; }
    .pos-sheet .card-body { padding: 1.25rem; }
    .pos-sheet label { font-size: .875rem; margin-bottom: .3rem; }
    .pos-sheet .form-control, .pos-sheet .btn { border-radius: 2px; }
    .pos-sheet .form-control[readonly] { background-color: #e9ecef; }
    .pos-sheet .sale-success-actions { display: flex; flex-wrap: wrap; gap: .5rem; }
    .pos-sheet .alert-success .sale-document-button,
    .pos-sheet .alert-success .sale-document-button:visited { color: #001f3f; background: #fff; border-color: #fff; text-decoration: none; font-weight: 600; }
    .pos-sheet .alert-success .sale-document-button:hover,
    .pos-sheet .alert-success .sale-document-button:focus { color: #001f3f; background: #e9ecef; border-color: #001f3f; text-decoration: none; }
    .pos-section-title { font-size: 1.4rem; font-weight: 400; margin-bottom: .75rem; }
    .pos-workspace { display: grid; grid-template-columns: minmax(0, 1fr) 300px; grid-template-rows: max-content 1fr; grid-template-areas: 'entry payment' 'list payment'; gap: 1rem 1.5rem; margin: 0; align-items: start; }
    .pos-workspace > * { width: 100%; max-width: none; min-width: 0; padding: 0; }
    .pos-entry { grid-area: entry; display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(0, 1.2fr) minmax(0, 1.2fr) auto; column-gap: .75rem; align-items: start; }
    .pos-entry .form-group { margin-bottom: 0; min-width: 0; }
    .pos-entry .pos-section-title { font-size: 1.1rem; margin-bottom: .65rem; }
    .pos-entry .pos-section-title, .pos-entry > small { grid-column: 1 / -1; }
    .pos-entry .btn-block { margin-top: 1.6rem; }
    .pos-entry > small { margin-top: .35rem; }
    .pos-list { grid-area: list; }
    .pos-payment { grid-area: payment; }
    .pos-table { width: 100%; table-layout: fixed; }
    .pos-table th:nth-child(1) { width: 23%; }
    .pos-table th:nth-child(2) { width: 22%; }
    .pos-table th:nth-child(3), .pos-table th:nth-child(5) { width: 17%; }
    .pos-table th:nth-child(4) { width: 12%; }
    .pos-table th:nth-child(6) { width: 9%; }
    .pos-table td { overflow-wrap: anywhere; }
    .pos-table .text-nowrap { white-space: normal !important; }
    .pos-table input[type=number] { width: 100%; min-width: 0; padding: .25rem; }
    .pos-table th { white-space: nowrap; }
    .pos-table th, .pos-table td { vertical-align: middle; padding: .65rem .6rem; }
    .pos-table tfoot td { background: #d9edf7; }
    .pos-summary { position: sticky; top: 80px; border: 1px solid #dce2e8; border-radius: 4px; overflow: hidden; background: #fff; }
    .pos-summary-total { background: #001f3f; color: #fff; padding: 1.25rem; }
    .pos-summary-total h2 { font-size: .95rem; margin-bottom: .5rem; }
    .pos-summary-amount { display: block; font-size: clamp(1.6rem, 2.3vw, 2.5rem); font-weight: 700; overflow-wrap: anywhere; font-variant-numeric: tabular-nums; }
    .pos-summary-details { padding: 1rem; }
    .pos-summary td, .pos-summary th { padding: .65rem .25rem; vertical-align: middle; }
    .pos-summary td { overflow-wrap: anywhere; }
    .pos-bottom { display: flex; flex-wrap: wrap; gap: .75rem; align-items: center; justify-content: space-between; }
    .pos-code-search { display: flex; gap: .5rem; align-items: center; }
    .pos-code-search .form-control { min-width: 0; }
    .pos-code-search .btn { flex-shrink: 0; }
    @media (min-width: 992px) and (max-width: 1199.98px) { .pos-entry { grid-template-columns: repeat(2, minmax(0, 1fr)); row-gap: .5rem; } }
    @media (max-width: 991.98px) { .pos-workspace { grid-template-columns: minmax(0, 1fr); grid-template-rows: auto; grid-template-areas: 'entry' 'list' 'payment'; } .pos-summary { position: static; } }
    @media (max-width: 575.98px) { .pos-entry { grid-template-columns: minmax(0, 1fr); } .pos-entry .btn-block { margin-top: 0; } .pos-table { font-size: .75rem; } .pos-table th, .pos-table td { padding: .4rem .2rem; } .pos-table th { white-space: normal; } }
</style>
@endpush

@section('content')
<div class="card card-outline card-primary shadow-sm pos-sheet"><div class="card-body">
    @if(session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}
            @if(session('completed_sale_id'))
                <div class="mt-2 sale-success-actions"><a class="btn btn-light btn-sm sale-document-button" target="_blank" rel="noopener" href="{{ route('kasir.penjualan.struk', session('completed_sale_id')) }}"><i class="fas fa-print mr-1" aria-hidden="true"></i>Cetak Struk (80 mm)</a>
                <a class="btn btn-light btn-sm sale-document-button" href="{{ route('kasir.penjualan.invoice', session('completed_sale_id')) }}"><i class="fas fa-file-pdf mr-1" aria-hidden="true"></i>Simpan Invoice (PDF)</a></div>
            @endif
        </div>
    @endif
    @if ($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0 pl-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <div id="saleMessage" class="alert alert-warning" role="status" hidden></div>
    @if($accounts->isEmpty())<div class="alert alert-warning">Belum ada akun penerimaan aktif. Administrator perlu menambahkan akun keuangan sebelum transaksi dapat disimpan.</div>@endif
    <form id="saleForm" action="{{ route('kasir.penjualan.prepare') }}" method="POST">
        @csrf
        <input type="hidden" name="submission_key" value="{{ $submissionKey }}">
        <div class="row">
            <div class="col-md-3 form-group"><label for="cashierName">Kasir</label><input id="cashierName" class="form-control form-control-sm" value="{{ auth()->user()->name }}" readonly></div>
            <div class="col-md-3 form-group"><label for="invoiceNumber">No. Invoice</label><input id="invoiceNumber" class="form-control form-control-sm" value="Otomatis saat disimpan" readonly></div>
            <div class="col-md-3 form-group"><label for="invoiceDate">Tanggal Invoice</label><input id="invoiceDate" class="form-control form-control-sm" value="{{ now('Asia/Jakarta')->format('d/m/Y') }}" readonly></div>
            <div class="col-md-3 form-group"><label for="customerName">Pelanggan</label><input id="customerName" name="customer_name" class="form-control form-control-sm" placeholder="Pelanggan Umum" maxlength="255" value="{{ old('customer_name', $draft['customer_name'] ?? '') }}"></div>
        </div>
        <hr class="mt-2 mb-4">
        <div class="row pos-workspace">
            <section class="pos-entry" aria-labelledby="addItemTitle">
                <h2 class="pos-section-title" id="addItemTitle">Tambah Barang</h2>
                <div class="form-group"><label for="productCode">Kode Produk / Nama</label><div class="pos-code-search"><input id="productCode" type="search" class="form-control form-control-sm" placeholder="Ketik SKU atau nama produk" aria-describedby="productSearchStatus" autocomplete="off"><button id="findProduct" class="btn btn-primary btn-sm" type="button"><i class="fas fa-search" aria-hidden="true"></i> Cari</button></div><small id="productSearchStatus" class="form-text text-muted" role="status" aria-live="polite">Ketik nama atau SKU, lalu Enter untuk menambahkan 1 barang. Jika hasil lebih dari satu, pilih produknya dahulu.</small></div>
                <div class="form-group"><label for="productSelect">Produk</label><select id="productSelect" class="form-control form-control-sm"><option value="">Cari produk terlebih dahulu</option></select></div>
                <div class="form-group"><label for="stockSelect">Lokasi / Kondisi Stok</label><select id="stockSelect" class="form-control form-control-sm" disabled><option value="">Pilih produk</option></select><small id="stockHint" class="form-text text-muted"></small></div>
                <div class="sr-only"><label for="productPrice">Harga Jual</label><input id="productPrice" class="form-control form-control-sm" readonly tabindex="-1"></div>
                <button id="addItem" type="button" class="btn btn-primary btn-sm btn-block" disabled>TAMBAHKAN</button><small class="form-text text-muted">Ubah jumlah langsung di tabel Daftar Belanja.</small>
            </section>
            <section class="col-lg-9 col-xl-6 pos-list" aria-labelledby="purchaseListTitle">
                <h2 id="purchaseListTitle" class="pos-section-title">Daftar Belanja</h2>
                <div class="table-responsive"><table class="table table-bordered table-striped table-sm pos-table"><thead><tr><th>SKU / Lokasi</th><th>Produk</th><th class="text-right">Harga</th><th>Jumlah</th><th class="text-right">Total</th><th>Opsi</th></tr></thead><tbody id="cartBody"></tbody><tfoot><tr><th colspan="3" class="text-right">Total</th><th id="cartQuantity">0</th><th id="cartGross" class="text-right">Rp 0</th><th></th></tr></tfoot></table></div>
                <div id="cartInputs"></div>
            </section>
            <aside class="col-xl-3 pos-payment" aria-labelledby="paymentSummaryTitle">
                <div class="pos-summary shadow-sm">
                    <div class="pos-summary-total" role="status" aria-live="polite" aria-atomic="true">
                        <h2 id="paymentSummaryTitle">Total Bayar</h2>
                        <output id="grandTotal" class="pos-summary-amount">Rp 0</output>
                    </div>
                    <div class="pos-summary-details">
                        <table class="table table-sm mb-3"><tbody>
                            <tr><th>Subtotal</th><td id="subtotal" class="text-right">Rp 0</td></tr>
                            <tr><th><label for="discount">Diskon (%)</label></th><td><input id="discount" name="discount" type="number" min="0" max="100" step="0.01" value="{{ old('discount', $draft['discount'] ?? 0) }}" class="form-control form-control-sm" required></td></tr>
                            <tr><th>Potongan</th><td id="discountAmount" class="text-right">Rp 0</td></tr>
                        </tbody></table>
                        <div class="form-group"><label for="customerPhone">Telepon Pelanggan (opsional)</label><input id="customerPhone" name="customer_phone" class="form-control form-control-sm" maxlength="30" value="{{ old('customer_phone', $draft['customer_phone'] ?? '') }}"></div>
                        <small class="text-muted">Harga mengikuti master produk. Diskon dihitung per satuan; total tidak ditambah pajak terpisah.</small>
                    </div>
                </div>
            </aside>
        </div>
        <hr class="my-4"><div class="pos-bottom"><button id="resetSale" type="button" class="btn btn-danger btn-sm">Batalkan Transaksi</button><button id="saveSale" type="submit" class="btn btn-success btn-sm" disabled>Proses Bayar <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i></button></div>
    </form>
</div></div>
@endsection
@push('scripts')
@php $oldItems = old('items', $draft['items'] ?? []); @endphp
<script>
window.cashierSaleConfig = { hasAccounts: @json($accounts->isNotEmpty()), productsUrl: @json(route('kasir.penjualan.produk')), oldItems: @json($oldItems) };
</script>
<script src="{{ asset('resources/js/KasirSale.js') }}" defer></script>
@endpush
