@extends('layouts.dashboard-master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Daftar Pesanan Distributor</h4>
                    </div>
                    <div class="card-body">
                        @if($errors->any())<div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>@endif
                        <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex flex-wrap align-items-end mb-3" style="gap:.75rem;">
                            <div class="form-group mb-0"><label for="orderDateFrom">Dari Tanggal</label><input type="date" id="orderDateFrom" name="date_from" class="form-control form-control-sm" value="{{ $filters['date_from'] ?? '' }}"></div>
                            <div class="form-group mb-0"><label for="orderDateTo">Sampai Tanggal</label><input type="date" id="orderDateTo" name="date_to" class="form-control form-control-sm" value="{{ $filters['date_to'] ?? '' }}"></div>
                            <div class="form-group mb-0"><label for="orderDistributor">Distributor</label><select id="orderDistributor" name="distributor" class="form-control form-control-sm" style="max-width:240px;"><option value="">Semua Distributor</option>@foreach($distributors as $distributor)<option value="{{ $distributor->id }}" @selected(($filters['distributor'] ?? '') === (string)$distributor->id)>{{ $distributor->name }}</option>@endforeach</select></div>
                            <div class="form-group mb-0"><label for="orderStatus">Status Pesanan</label><select id="orderStatus" name="status" class="form-control form-control-sm"><option value="">Semua Status</option>@foreach($statuses as $value=>$label)<option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>@endforeach</select></div>
                            <div class="form-group mb-0"><label for="orderProof">Bukti Pembayaran</label><select id="orderProof" name="proof" class="form-control form-control-sm"><option value="">Semua</option><option value="uploaded" @selected(($filters['proof'] ?? '') === 'uploaded')>Sudah Diunggah</option><option value="missing" @selected(($filters['proof'] ?? '') === 'missing')>Belum Diunggah</option></select></div>
                            <button type="submit" class="btn btn-primary btn-sm">Terapkan Filter</button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-default btn-sm">Reset</a>
                        </form>
                        <p class="text-muted text-sm">{{ $orders->count() }} pesanan sesuai filter. Rentang tanggal berdasarkan tanggal pesanan dibuat; bukti yang diunggah belum tentu sudah diverifikasi.</p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="example1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Order Number</th>
                                        <th>Distributor</th>
                                        <th>Total Harga</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ $order->user->name ?? 'User Tidak Ditemukan' }}</td>
                                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $order->status == 'completed'
                                                        ? 'success'
                                                        : ($order->status == 'processing'
                                                            ? 'primary'
                                                            : ($order->status == 'shipped'
                                                                ? 'info'
                                                                : ($order->status == 'cancelled'
                                                                    ? 'danger'
                                                                    : ($order->status == 'waiting_confirmation'
                                                                        ? 'warning'
                                                                        : ($order->status == 'waiting_payment'
                                                                            ? 'secondary'
                                                                            : ($order->status == 'cancel_requested'
                                                                                ? 'orange'
                                                                                : 'dark')))))) }}">
                                                    {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                                    class="btn btn-sm btn-info">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
