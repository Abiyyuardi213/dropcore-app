@extends('layouts.kasir')
@section('title', 'Produk Kasir | Garuda Fiber')
@section('heading', 'Produk Kasir')

@section('content')
<div class="card card-outline card-primary shadow-sm" style="border-radius:0">
    <div class="card-header d-flex align-items-center">
        <h2 class="card-title font-weight-bold"><i class="fas fa-box mr-2" aria-hidden="true"></i>Daftar Produk</h2>
        <span class="badge badge-light border ml-auto">{{ number_format($products->total(), 0, ',', '.') }} produk</span>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-3">Lihat SKU, harga jual, dan stok produk di seluruh lokasi gudang.</p>
        @if($errors->any())<div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>@endif
        <form action="{{ route('kasir.produk') }}" method="GET" class="mb-3">
            <div class="row align-items-end">
                <div class="col-md-5 form-group"><label for="search">Cari Produk / SKU</label><div class="input-group"><div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search" aria-hidden="true"></i></span></div><input id="search" name="search" type="search" class="form-control" placeholder="Nama produk, SKU, atau merek" value="{{ request('search') }}" maxlength="100"></div></div>
                <div class="col-md-3 form-group"><label for="category">Kategori</label><select id="category" name="category" class="form-control"><option value="">Semua Kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->category_name }}</option>@endforeach</select></div>
                <div class="col-md-2 form-group"><label for="stock">Stok</label><select id="stock" name="stock" class="form-control"><option value="">Semua Stok</option><option value="available" @selected(request('stock') == 'available')>Tersedia</option><option value="empty" @selected(request('stock') == 'empty')>Habis</option></select></div>
                <div class="col-md-2 form-group d-flex"><button class="btn btn-primary mr-2" type="submit">Cari</button><a href="{{ route('kasir.produk') }}" class="btn btn-default">Reset</a></div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead><tr><th>No.</th><th>Produk</th><th>SKU</th><th>Kategori</th><th class="text-right">Harga Jual</th><th>Satuan</th><th class="text-right">Stok</th><th>Status</th></tr></thead>
                <tbody>@forelse($products as $product)
                    <tr>
                        <td>{{ $products->firstItem() + $loop->index }}</td>
                        <td><div class="d-flex align-items-center">
                            @if($product->image)<img src="{{ asset('uploads/product/'.$product->image) }}" alt="{{ $product->name }}" loading="lazy" class="mr-3 rounded border" style="width:48px;height:48px;object-fit:contain">@else<span class="mr-3 rounded border bg-light d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px"><i class="fas fa-box text-muted" aria-hidden="true"></i></span>@endif
                            <div><span class="font-weight-bold">{{ $product->name }}</span>@if($product->merk)<small class="d-block text-muted">{{ $product->merk }}</small>@endif</div>
                        </div></td>
                        <td class="text-nowrap">{{ $product->sku ?: '-' }}</td><td>{{ $product->category->category_name ?? '-' }}</td>
                        <td class="text-right text-nowrap">Rp {{ number_format($product->price, 2, ',', '.') }}</td><td>{{ $product->uom->name ?? '-' }}</td>
                        <td class="text-right">{{ number_format($product->stok_sum_quantity ?? 0, 0, ',', '.') }}</td>
                        <td><span class="badge badge-{{ ($product->stok_sum_quantity ?? 0) > 0 ? 'success' : 'secondary' }}">{{ ($product->stok_sum_quantity ?? 0) > 0 ? 'Tersedia' : 'Habis' }}</span></td>
                    </tr>
                @empty<tr><td colspan="8" class="text-center text-muted py-4">Tidak ada produk yang sesuai.</td></tr>@endforelse</tbody>
            </table>
        </div>
        <div class="d-flex flex-wrap justify-content-between align-items-center mt-2"><span class="text-muted small mb-2">Menampilkan {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk</span>{{ $products->links('pagination::bootstrap-4') }}</div>
    </div>
</div>
@endsection
