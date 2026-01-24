@extends('layouts.dashboard-master')

@section('content')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>
                        Kembali</a>
                    <h2 class="m-0 text-dark">Detail Pesanan #{{ $order->order_number }}</h2>
                    <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-info">
                        <i class="fas fa-file-invoice"></i> Cetak Faktur Pajak
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="row">
                    <!-- Left Column: Order Details and Items -->
                    <div class="col-lg-8">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Rincian Item</h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 40%">Produk</th>
                                                <th style="width: 20%">Harga Satuan</th>
                                                <th style="width: 10%">Qty</th>
                                                <th style="width: 30%" class="text-right">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                // Fallback calculation for legacy orders
                                                $dpp =
                                                    $order->tax_base > 0
                                                        ? $order->tax_base
                                                        : $order->total_price / 1.11;
                                                $ppn = $order->tax_amount > 0 ? $order->tax_amount : $dpp * 0.11;
                                            @endphp
                                            @foreach ($order->items as $item)
                                                <tr>
                                                    <td>{{ $item->product->name }}</td>
                                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td class="text-right">Rp
                                                        {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Totals Section -->
                            <div class="card-footer p-0">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold">
                                            Total DPP (Dasar Pengenaan Pajak) :
                                        </td>
                                        <td class="text-right" style="width: 30%">Rp {{ number_format($dpp, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold">
                                            PPN (11%) :
                                        </td>
                                        <td class="text-right">Rp {{ number_format($ppn, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="bg-gray-light">
                                        <td colspan="3" class="text-right font-weight-bold" style="font-size: 1.2em;">
                                            Total Bayar :
                                        </td>
                                        <td class="text-right font-weight-bold" style="font-size: 1.2em; color: #007bff;">
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">Informasi Distributor & Pembayaran</h3>
                            </div>
                            <div class="card-body">
                                <dl class="row mb-0">
                                    <dt class="col-sm-4">Metode Pembayaran</dt>
                                    <dd class="col-sm-8"><span
                                            class="badge badge-success">{{ $order->payment_method ?? 'Tidak ditemukan' }}</span>
                                    </dd>

                                    <div class="col-12 mt-2 mb-2">
                                        <hr>
                                    </div>

                                    <dt class="col-sm-4">Nama Distributor</dt>
                                    <dd class="col-sm-8">{{ $order->user->name }}</dd>

                                    <dt class="col-sm-4">Email</dt>
                                    <dd class="col-sm-8">{{ $order->user->email }}</dd>

                                    <dt class="col-sm-4">Telepon</dt>
                                    <dd class="col-sm-8">{{ $order->user->no_telepon ?? '-' }}</dd>

                                    <dt class="col-sm-4">Alamat Kirim</dt>
                                    <dd class="col-sm-8">
                                        @if ($order->shipping_address == 'Alamat belum diatur' && $order->user->alamat)
                                            {{ $order->user->alamat }}
                                            <br><small class="text-info"><i class="fas fa-info-circle"></i> Menggunakan
                                                alamat profil saat ini</small>
                                        @else
                                            {{ $order->shipping_address }}
                                        @endif
                                    </dd>

                                    <dt class="col-sm-4">Catatan</dt>
                                    <dd class="col-sm-8 text-muted">{{ $order->notes ?? '-' }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Status and Actions -->
                    <div class="col-lg-4">
                        <div class="card card-warning card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Proses Pesanan</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <label class="d-block mb-2">Status Saat Ini:</label>
                                    <div
                                        class="text-center p-3 rounded 
                                        {{ $order->status == 'completed'
                                            ? 'bg-success'
                                            : ($order->status == 'processing'
                                                ? 'bg-primary'
                                                : ($order->status == 'shipped'
                                                    ? 'bg-info'
                                                    : ($order->status == 'cancelled'
                                                        ? 'bg-danger'
                                                        : ($order->status == 'waiting_confirmation'
                                                            ? 'bg-warning'
                                                            : ($order->status == 'waiting_payment'
                                                                ? 'bg-secondary'
                                                                : 'bg-dark'))))) }}">
                                        <h4 class="m-0 text-white text-uppercase font-weight-bold">
                                            {{ str_replace('_', ' ', $order->status == 'cancel_requested' ? 'Permintaan Batal' : $order->status) }}
                                        </h4>
                                    </div>
                                </div>

                                @if ($order->proof_of_payment)
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">Bukti Pembayaran (Dari Distributor)</h3>
                                        </div>
                                        <div class="card-body text-center">
                                            <img src="{{ asset('uploads/payment_proofs/' . $order->proof_of_payment) }}"
                                                alt="Bukti Pembayaran" class="img-fluid rounded mb-2"
                                                style="max-height: 300px;">
                                            <a href="{{ asset('uploads/payment_proofs/' . $order->proof_of_payment) }}"
                                                target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-search-plus"></i> Perbesar
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if ($order->status == 'cancel_requested')
                                    <div class="alert alert-warning">
                                        <h5><i class="icon fas fa-exclamation-triangle"></i> Permintaan Pembatalan</h5>
                                        <p><strong>Alasan:</strong> {{ $order->cancellation_reason }}</p>
                                        @if ($order->cancellation_note)
                                            <p><strong>Catatan:</strong> {{ $order->cancellation_note }}</p>
                                        @endif
                                        <hr>
                                        <p class="mb-2">Tindakan:</p>
                                        <div class="d-flex" style="gap: 10px;">
                                            <form id="formApproveCancel"
                                                action="{{ route('admin.orders.update-status', $order->id) }}"
                                                method="POST" style="flex:1;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="button" class="btn btn-danger btn-block"
                                                    onclick="confirmCancel()">
                                                    Setujui Batal
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.orders.update-status', $order->id) }}"
                                                method="POST" style="flex:1;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="processing">
                                                <button type="submit" class="btn btn-outline-primary btn-block">
                                                    Tolak (Proses)
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif

                                @if ($order->shipping_provider)
                                    <div class="callout callout-info">
                                        <h5><i class="fas fa-truck"></i> Info Pengiriman</h5>
                                        <p class="mb-1"><strong>Jasa:</strong> {{ $order->shipping_provider }}</p>
                                        <p class="mb-1"><strong>Resi:</strong> {{ $order->tracking_number }}</p>
                                        <p class="mb-0 text-sm text-muted"> <i class="far fa-clock"></i>
                                            {{ $order->shipped_at ? $order->shipped_at->format('d M Y H:i') : '-' }}</p>
                                    </div>
                                @endif

                                <div class="bg-light p-3 rounded border">
                                    <h5 class="mb-3 border-bottom pb-2">Update Status</h5>
                                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <div class="form-group">
                                            <label>Pilih Status Baru</label>
                                            <select name="status" id="statusSelect" class="form-control"
                                                onchange="toggleShippingFields()">
                                                <option value="waiting_payment"
                                                    {{ $order->status == 'waiting_payment' ? 'selected' : '' }}>Waiting
                                                    Payment</option>
                                                <option value="waiting_confirmation"
                                                    {{ $order->status == 'waiting_confirmation' ? 'selected' : '' }}>
                                                    Waiting Confirmation</option>
                                                <option value="processing"
                                                    {{ $order->status == 'processing' ? 'selected' : '' }}>Processing
                                                </option>
                                                <option value="shipped"
                                                    {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped (Dikirim)
                                                </option>
                                                <option value="completed"
                                                    {{ $order->status == 'completed' ? 'selected' : '' }}>Completed
                                                </option>
                                                <option value="cancelled"
                                                    {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                                </option>
                                                <option value="cancel_requested"
                                                    {{ $order->status == 'cancel_requested' ? 'selected' : '' }}>
                                                    Cancellation Requested
                                                </option>
                                            </select>
                                        </div>

                                        <div id="shippingFields" style="display: none;" class="animate-fade-in">
                                            <div class="form-group">
                                                <label>Jasa Ekspedisi <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-shipping-fast"></i></span>
                                                    </div>
                                                    <input type="text" name="shipping_provider" class="form-control"
                                                        placeholder="Contoh: JNE, J&T"
                                                        value="{{ $order->shipping_provider }}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Nomor Resi <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-barcode"></i></span>
                                                    </div>
                                                    <input type="text" name="tracking_number" class="form-control"
                                                        placeholder="Masukkan nomor resi"
                                                        value="{{ $order->tracking_number }}">
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-block btn-lg mt-4">
                                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmCancel() {
            Swal.fire({
                title: 'Konfirmasi Pembatalan',
                text: "Apakah Anda yakin ingin menyetujui pembatalan pesanan ini? Stok akan dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Setujui Batal!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formApproveCancel').submit();
                }
            })
        }

        function toggleShippingFields() {
            var status = document.getElementById('statusSelect').value;
            var shippingFields = document.getElementById('shippingFields');
            if (status === 'shipped') {
                shippingFields.style.display = 'block';
            } else {
                shippingFields.style.display = 'none';
            }
        }
        // Run on load
        document.addEventListener('DOMContentLoaded', function() {
            toggleShippingFields();
        });
    </script>
@endsection
