@extends('layouts.kasir')
@section('title', 'Proses Bayar | Garuda Fiber')
@section('heading', 'Proses Bayar')
@push('styles')
<style>
.payment-method { cursor: pointer; border: 1px solid #dee2e6; border-radius: 4px; padding: 1rem; display: flex; align-items: center; gap: .75rem; height: 100%; }
.payment-method:has(input:checked) { border-color: #007bff; background: #eef6ff; }
.payment-method input { flex-shrink: 0; }
.payment-total { background: #001f3f; color: #fff; padding: 1.25rem; border-radius: 4px; }
.payment-total strong { display: block; font-size: clamp(1.8rem, 3vw, 2.8rem); overflow-wrap: anywhere; }
.payment-detail { position: sticky; top: 80px; }
@media(max-width:991.98px) { .payment-detail { position: static; margin-top: 1.5rem; } }
</style>
@endpush
@section('content')
<div class="card card-outline card-primary shadow-sm"><div class="card-body">
    @if($errors->any())<div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>@endif
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4"><div><h2 class="h5 mb-1">Pilih Metode Pembayaran</h2><p class="text-muted mb-0">Pelanggan: {{ $draft['customer_name'] }} &middot; Kasir: {{ auth()->user()->name }}</p></div><a class="btn btn-default mt-2" href="{{ route('kasir.penjualan', ['draft' => $draft['submission_key']]) }}">Kembali ke Penjualan</a></div>
    <form id="paymentForm" method="POST" action="{{ route('kasir.penjualan.store') }}">
        @csrf<input type="hidden" name="submission_key" value="{{ $draft['submission_key'] }}">
        <div class="row">
            <div class="col-lg-7">
                @if($accounts->isEmpty())<div class="alert alert-warning">Belum ada metode pembayaran aktif. Administrator perlu menambahkan atau mengaktifkan Sumber Keuangan.</div>@endif
                <div class="row">
                @foreach($accounts as $account)
                    <div class="col-sm-6 mb-3"><label class="payment-method" for="method-{{ $account->id }}">
                        <input id="method-{{ $account->id }}" type="radio" name="sumber_id" value="{{ $account->id }}" data-type="{{ $account->jenis }}" data-name="{{ $account->nama_sumber }}" required @checked(old('sumber_id') === $account->id)>
                        <span><i class="fas {{ $account->jenis === 'tunai' ? 'fa-money-bill-wave' : 'fa-credit-card' }} mr-1" aria-hidden="true"></i><strong>{{ $account->nama_sumber }}</strong><small class="d-block text-muted">{{ ucfirst($account->jenis) }}</small>
                        @if($account->nomor_rekening)<small class="d-block">{{ $account->nomor_rekening }}</small>@endif
                        @if($account->atas_nama)<small class="d-block">a.n. {{ $account->atas_nama }}</small>@endif</span>
                    </label></div>
                @endforeach
                </div>
                <h3 class="h6 mt-3">Rincian Belanja</h3>
                <div class="table-responsive"><table class="table table-bordered table-sm"><thead><tr><th>Produk</th><th>Jumlah</th><th class="text-right">Harga</th><th class="text-right">Total setelah diskon</th></tr></thead><tbody>
                @foreach($quote['lines'] as $line)<tr><td>{{ $line['name'] }}<small class="d-block text-muted">{{ $line['sku'] }}<br>{{ $line['location'] }}</small></td><td>{{ $line['quantity'] }} {{ $line['unit'] }}</td><td class="text-right">Rp {{ number_format($line['price']/100,2,',','.') }}</td><td class="text-right">Rp {{ number_format($line['total']/100,2,',','.') }}</td></tr>@endforeach
                </tbody></table></div>
            </div>
            <div class="col-lg-5"><div class="payment-detail border rounded p-3">
                <div id="paymentPlaceholder" class="text-muted py-4 text-center">Pilih metode pembayaran untuk menampilkan rincian pembayaran.</div>
                <div id="paymentDetails" hidden>
                    <div class="payment-total"><span>Total yang Harus Dibayar</span><strong>Rp {{ number_format($quote['total']/100,2,',','.') }}</strong><span id="paymentMethodName"></span></div>
                    <table class="table table-sm mt-3"><tbody><tr><th>Subtotal</th><td class="text-right">Rp {{ number_format($quote['subtotal']/100,2,',','.') }}</td></tr><tr><th>Diskon ({{ $draft['discount'] }}%)</th><td class="text-right">Rp {{ number_format(($quote['subtotal']-$quote['total'])/100,2,',','.') }}</td></tr></tbody></table>
                    <label for="paymentAmount">Nominal Dibayar</label><input id="paymentAmount" name="paid_amount" type="text" inputmode="decimal" pattern="[0-9]+([.][0-9]{1,2})?" placeholder="Contoh: 1200000" class="form-control" required value="{{ old('paid_amount') }}">
                    <small id="paymentHelp" class="form-text text-muted"></small>
                    <div class="d-flex justify-content-between my-3 font-weight-bold"><span>Kembalian</span><span id="paymentChange" role="status" aria-live="polite">Rp 0,00</span></div>
                </div>
                <p id="paymentMessage" class="text-danger" role="status"></p>
                <button id="finishPayment" type="submit" class="btn btn-success btn-block" disabled><i class="fas fa-check mr-1" aria-hidden="true"></i>Selesai</button>
                <small class="d-block text-muted mt-2">Klik Selesai setelah pembayaran diterima. Transaksi, stok, dan pemasukan akan dicatat saat itu.</small>
            </div></div>
        </div>
    </form>
</div></div>
@endsection
@push('scripts')
<script>window.cashierPaymentConfig = {total: @json($quote['total'])};</script>
<script src="{{ asset('resources/js/KasirPayment.js') }}" defer></script>
@endpush
