@extends('layouts.kasir')
@section('title', 'Pelanggan Kasir | Garuda Fiber')
@section('heading', 'Pelanggan Kasir')

@section('content')
<div class="card card-outline card-primary shadow-sm" style="border-radius:0">
    <div class="card-header d-flex align-items-center">
        <h2 class="card-title font-weight-bold"><i class="fas fa-users mr-2" aria-hidden="true"></i>Daftar Pelanggan</h2>
        <span class="badge badge-light border ml-auto">{{ number_format($customers->total(), 0, ',', '.') }} pelanggan</span>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-3">Distributor terdaftar dan kontak konsumen dari transaksi pengeluaran barang yang sudah selesai.</p>
        @if($errors->any())<div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>@endif
        <form method="GET" action="{{ route('kasir.pelanggan') }}" class="mb-3">
            <div class="row align-items-end">
                <div class="col-md-7 form-group"><label for="customerSearch">Cari Pelanggan</label>
                    <div class="input-group"><div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search" aria-hidden="true"></i></span></div><input id="customerSearch" type="search" name="search" class="form-control" placeholder="Nama, kode distributor, telepon, atau email" value="{{ request('search') }}" maxlength="100"></div>
                </div>
                <div class="col-md-3 form-group"><label for="customerType">Jenis Pelanggan</label><select id="customerType" name="type" class="form-control"><option value="">Semua Pelanggan</option><option value="distributor" @selected(request('type') == 'distributor')>Distributor</option><option value="konsumen" @selected(request('type') == 'konsumen')>Konsumen</option></select></div>
                <div class="col-md-2 form-group d-flex"><button type="submit" class="btn btn-primary mr-2">Cari</button><a href="{{ route('kasir.pelanggan') }}" class="btn btn-default">Reset</a></div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead><tr><th>No.</th><th>Nama Pelanggan</th><th>Jenis</th><th>Kode Distributor</th><th>Telepon</th><th>Email</th><th>Alamat</th><th>Status</th></tr></thead>
                <tbody>@forelse($customers as $customer)<tr>
                    <td>{{ $customers->firstItem() + $loop->index }}</td>
                    <td class="font-weight-bold">{{ $customer->nama }}</td>
                    <td><span class="badge badge-{{ $customer->jenis === 'distributor' ? 'primary' : 'info' }}">{{ ucfirst($customer->jenis) }}</span></td>
                    <td class="text-nowrap">{{ $customer->kode ?: '-' }}</td>
                    <td class="text-nowrap">{{ $customer->telepon ?: '-' }}</td>
                    <td>{{ $customer->email ?: '-' }}</td>
                    <td style="min-width:180px;max-width:320px;overflow-wrap:anywhere">{{ $customer->alamat ?: '-' }}</td>
                    <td><span class="badge badge-{{ $customer->status === 'active' ? 'success' : 'secondary' }}">{{ ['active' => 'Aktif', 'inactive' => 'Nonaktif', 'blacklisted' => 'Diblokir', 'recorded' => 'Tercatat'][$customer->status] ?? $customer->status }}</span></td>
                </tr>@empty<tr><td colspan="8" class="text-center text-muted py-4">Tidak ada pelanggan yang sesuai.</td></tr>@endforelse</tbody>
            </table>
        </div>
        <div class="d-flex flex-wrap justify-content-between align-items-center mt-2"><span class="text-muted small mb-2">Menampilkan {{ $customers->firstItem() ?? 0 }}–{{ $customers->lastItem() ?? 0 }} dari {{ $customers->total() }} pelanggan</span>{{ $customers->links('pagination::bootstrap-4') }}</div>
    </div>
</div>
@endsection
