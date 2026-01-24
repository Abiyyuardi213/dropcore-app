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
                                            <td>{{ $order->user->name }}</td>
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
