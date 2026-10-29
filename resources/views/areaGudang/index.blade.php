<!DOCTYPE html>
<html lang="id"><head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Garuda Fiber - Area Gudang</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('resources/css/WarehouseCards.css') }}">
</head><body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('include.navbarSistem')
    @include('include.sidebar')
    <div class="content-wrapper">
        <section class="content-header"><div class="container-fluid"><div class="row mb-2"><div class="col-sm-6"><h1>Manajemen Area Gudang</h1></div><div class="col-sm-6"><ol class="breadcrumb float-sm-right"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li><li class="breadcrumb-item active">Area Gudang</li></ol></div></div></div></section>
        <section class="content"><div class="container-fluid">
            <div class="card"><div class="card-header d-flex align-items-center"><h3 class="card-title">Daftar Area &amp; Kapasitas</h3><a class="btn btn-primary btn-sm ml-auto" href="{{ route('area-gudang.create') }}"><i class="fas fa-plus mr-1" aria-hidden="true"></i>Tambah Area Gudang</a></div>
                <div class="card-body">
                    <div class="row align-items-end mb-3">
                        <div class="col-md-6"><label for="warehouseCardSearch">Cari Area Gudang</label><input id="warehouseCardSearch" class="form-control" type="search" placeholder="Cari kode, nama area, gudang, atau penanggung jawab" autocomplete="off"></div>
                        <div class="col-md-3 mt-3 mt-md-0"><label for="areaWarehouseFilter">Gudang</label><select id="areaWarehouseFilter" class="form-control"><option value="">Semua Gudang</option>@foreach($gudangs as $gudang)<option value="{{ $gudang['id'] }}">{{ $gudang['name'] }}</option>@endforeach</select></div>
                        <div class="col-md-3 mt-3 mt-md-0"><label for="warehouseCardStatus">Status Area</label><select id="warehouseCardStatus" class="form-control"><option value="">Semua Status</option><option value="1">Aktif</option><option value="0">Nonaktif</option></select></div>
                    </div>
                    <div class="d-flex flex-wrap mb-2" style="gap:.5rem"><span class="badge badge-success">Lega &lt;60%</span><span class="badge badge-info">Terisi 60–&lt;80%</span><span class="badge badge-warning">Hampir penuh 80–&lt;100%</span><span class="badge badge-danger">Penuh ≥100%</span><span class="badge badge-secondary">Belum terukur</span></div>
                    <p class="text-muted text-sm mb-0">Kapasitas dihitung dari seluruh stok area saat halaman dimuat, termasuk stok yang belum ditempatkan pada rak. Perhitungan mengikuti satuan kapasitas dan data barang yang tersedia.</p>
                    <small class="text-muted" id="warehouseCardCount" role="status">Menampilkan {{ $areas->count() }} area gudang</small>
                </div>
            </div>
            <div class="warehouse-card-grid" id="warehouseCards" data-entity="area gudang">
                @foreach($areas as $area)
                    <article class="card card-outline card-{{ $area['capacity']['color'] }} warehouse-card" data-search="{{ mb_strtolower($area['code'].' '.$area['name'].' '.$area['warehouse_name'].' '.$area['warehouse_code'].' '.$area['pic']) }}" data-active="{{ $area['active'] ? '1' : '0' }}" data-warehouse-id="{{ $area['warehouse_id'] }}" aria-label="{{ $area['name'] }}">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3"><div class="warehouse-card-icon bg-{{ $area['capacity']['color'] }} mr-3"><i class="fas fa-vector-square fa-2x" aria-hidden="true"></i></div><div><span class="text-muted text-sm">{{ $area['code'] }}</span><h2 class="h5 mb-1">{{ $area['name'] }}</h2><span class="badge badge-{{ $area['active'] ? 'success' : 'secondary' }} warehouse-status-badge">{{ $area['active'] ? 'Aktif' : 'Nonaktif' }}</span> @if($area['type'])<span class="badge badge-light border">{{ $area['type'] }}</span>@endif</div></div>
                            <div class="mb-2"><i class="fas fa-warehouse text-muted mr-1" aria-hidden="true"></i><a href="{{ route('gudang.show',$area['warehouse_id']) }}">{{ $area['warehouse_name'] }}</a> <small class="text-muted">{{ $area['warehouse_code'] }}</small> @if(!$area['warehouse_active'])<span class="badge badge-secondary">Gudang nonaktif</span>@endif</div>
                            @if($area['pic'])<p class="text-sm mb-2"><strong>Penanggung jawab:</strong> {{ $area['pic'] }}</p>@endif
                            @if($area['description'])<p class="text-muted text-sm">{{ $area['description'] }}</p>@endif
                            @if($area['temperature'] || $area['humidity'])<p class="text-muted text-sm">@if($area['temperature'])<i class="fas fa-thermometer-half mr-1" aria-hidden="true"></i>{{ $area['temperature'] }}@endif @if($area['humidity'])<span class="ml-2"><i class="fas fa-tint mr-1" aria-hidden="true"></i>{{ $area['humidity'] }}</span>@endif</p>@endif
                            <div class="border rounded p-3 mb-3 bg-light">@include('gudang.capacity-meter', ['capacity'=>$area['capacity'], 'locationName'=>$area['name']])</div>
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2"><span><i class="fas fa-pallet mr-1" aria-hidden="true"></i>{{ $area['racks']->count() }} rak</span><span class="text-danger">{{ $area['racks']->filter(fn ($rack) => in_array($rack['capacity']['status'],['near','full']))->count() }} rak perlu perhatian</span></div>
                            @if($area['unassigned'] > 0)<p class="text-warning text-sm">{{ number_format($area['unassigned']) }} kuantitas stok belum memiliki rak yang sesuai; tetap termasuk total area.</p>@endif
                            <details><summary class="text-primary font-weight-bold">Lihat Visualisasi Rak</summary><div class="warehouse-rack-links">
                                @forelse($area['racks'] as $rack)<a class="warehouse-rack-link" href="{{ route('rak-gudang.show',$rack['id']) }}"><strong class="d-block mb-2"><i class="fas fa-pallet text-{{ $rack['capacity']['color'] }} mr-1" aria-hidden="true"></i>{{ $rack['code'] }}</strong><small class="d-block text-muted mb-2">{{ $rack['position'] ?: 'Posisi belum diisi' }}</small>@if(!$rack['active'])<span class="badge badge-secondary mb-2">Nonaktif</span>@endif @include('gudang.capacity-meter', ['capacity'=>$rack['capacity'], 'locationName'=>$rack['code']])</a>@empty<span class="text-muted text-sm">Area ini belum memiliki rak.</span>@endforelse
                            </div></details>
                            <div class="custom-control custom-switch mt-3"><input type="checkbox" class="custom-control-input warehouse-status-toggle" id="area-status-{{ $area['id'] }}" data-url="{{ route('area-gudang.toggleStatus',$area['id']) }}" @checked($area['active'])><label class="custom-control-label" for="area-status-{{ $area['id'] }}">Area aktif</label></div>
                        </div>
                        <div class="card-footer"><a class="btn btn-primary btn-sm" href="{{ route('area-gudang.show',$area['id']) }}"><i class="fas fa-eye mr-1" aria-hidden="true"></i>Detail</a><a class="btn btn-info btn-sm" href="{{ route('area-gudang.edit',$area['id']) }}"><i class="fas fa-edit mr-1" aria-hidden="true"></i>Edit</a><button type="button" class="btn btn-danger btn-sm delete-gudang-btn" data-url="{{ route('area-gudang.destroy',$area['id']) }}"><i class="fas fa-trash mr-1" aria-hidden="true"></i>Hapus</button></div>
                    </article>
                @endforeach
            </div>
            <div id="warehouseCardsEmpty" class="card" @if($areas->isNotEmpty()) hidden @endif><div class="card-body text-center text-muted py-5"><i class="fas fa-vector-square fa-3x mb-3" aria-hidden="true"></i><p class="mb-0">{{ $areas->isEmpty() ? 'Belum ada data area gudang. Tambahkan area untuk menampilkan visualisasi.' : 'Tidak ada area yang sesuai dengan pencarian atau filter.' }}</p></div></div>
        </div></section>
    </div>
    @include('include.footerSistem')
</div>
<form id="deleteWarehouseForm" method="POST" hidden>@csrf @method('DELETE')</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
@include('services.ToastModal')
@include('services.LogoutModal')
<script src="{{ asset('resources/js/WarehouseCards.js') }}" defer></script>
</body></html>
