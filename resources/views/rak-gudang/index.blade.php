<!DOCTYPE html>
<html lang="id"><head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Garuda Fiber - Rak Gudang</title>
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
        <section class="content-header"><div class="container-fluid"><div class="row mb-2"><div class="col-sm-6"><h1>Manajemen Rak Gudang</h1></div><div class="col-sm-6"><ol class="breadcrumb float-sm-right"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li><li class="breadcrumb-item active">Rak Gudang</li></ol></div></div></div></section>
        <section class="content"><div class="container-fluid">
            <div class="card"><div class="card-header d-flex align-items-center"><h3 class="card-title">Daftar Rak &amp; Kapasitas</h3><a class="btn btn-primary btn-sm ml-auto" href="{{ route('rak-gudang.create') }}"><i class="fas fa-plus mr-1" aria-hidden="true"></i>Tambah Rak Gudang</a></div>
                <div class="card-body">
                    <div class="row align-items-end mb-3">
                        <div class="col-lg-4 col-md-6"><label for="warehouseCardSearch">Cari Rak Gudang</label><input id="warehouseCardSearch" class="form-control" type="search" placeholder="Cari kode rak, posisi, gudang, atau area" autocomplete="off"></div>
                        <div class="col-lg-4 col-md-6 mt-3 mt-md-0"><label for="areaWarehouseFilter">Gudang</label><select id="areaWarehouseFilter" class="form-control"><option value="">Semua Gudang</option>@foreach($gudangs as $gudang)<option value="{{ $gudang['id'] }}">{{ $gudang['name'] }}</option>@endforeach</select></div>
                        <div class="col-lg-4 mt-3 mt-lg-0"><label for="rackAreaFilter">Area Gudang</label><select id="rackAreaFilter" class="form-control"><option value="">Semua Area</option>@foreach($areas as $area)<option value="{{ $area['id'] }}" data-warehouse-id="{{ $area['warehouse_id'] }}">{{ $area['code'] }} — {{ $area['name'] }}</option>@endforeach</select></div>
                        <div class="col-md-6 mt-3"><label for="warehouseCardStatus">Status Rak</label><select id="warehouseCardStatus" class="form-control"><option value="">Semua Status</option><option value="1">Aktif</option><option value="0">Nonaktif</option></select></div>
                        <div class="col-md-6 mt-3"><label for="rackCapacityFilter">Kondisi Kapasitas</label><select id="rackCapacityFilter" class="form-control"><option value="">Semua Kondisi</option><option value="free">Lega (&lt;60%)</option><option value="moderate">Terisi (60–&lt;80%)</option><option value="near">Hampir Penuh (80–&lt;100%)</option><option value="full">Penuh / Melebihi (≥100%)</option><option value="unknown">Belum Terukur</option></select></div>
                    </div>
                    <div class="d-flex flex-wrap mb-2" style="gap:.5rem"><span class="badge badge-success">Lega &lt;60%</span><span class="badge badge-info">Terisi 60–&lt;80%</span><span class="badge badge-warning">Hampir penuh 80–&lt;100%</span><span class="badge badge-danger">Penuh ≥100%</span><span class="badge badge-secondary">Belum terukur</span></div>
                    <p class="text-muted text-sm mb-0">Pemakaian kapasitas dihitung dari stok pada masing-masing rak saat halaman dimuat. Satuan yang belum sesuai atau data barang yang belum lengkap ditandai Belum terukur.</p>
                    <small class="text-muted" id="warehouseCardCount" role="status">Menampilkan {{ $raks->count() }} rak gudang</small>
                </div>
            </div>
            <div class="warehouse-card-grid" id="warehouseCards" data-entity="rak gudang">
                @foreach($raks as $rak)
                    <article class="card card-outline card-{{ $rak['capacity']['color'] }} warehouse-card" data-search="{{ mb_strtolower($rak['code'].' '.$rak['position'].' '.$rak['warehouse_name'].' '.$rak['area_name'].' '.$rak['area_code'].' '.$rak['type']) }}" data-active="{{ $rak['active'] ? '1' : '0' }}" data-warehouse-id="{{ $rak['warehouse_id'] }}" data-area-id="{{ $rak['area_id'] }}" data-capacity-status="{{ $rak['capacity']['status'] }}" aria-label="{{ $rak['code'] }}">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3"><div class="warehouse-card-icon bg-{{ $rak['capacity']['color'] }} mr-3"><i class="fas fa-pallet fa-2x" aria-hidden="true"></i></div><div><h2 class="h5 mb-1">{{ $rak['code'] }}</h2><span class="badge badge-{{ $rak['active'] ? 'success' : 'secondary' }} warehouse-status-badge">{{ $rak['active'] ? 'Aktif' : 'Nonaktif' }}</span> @if($rak['type'])<span class="badge badge-light border">{{ $rak['type'] }}</span>@endif</div></div>
                            <div class="mb-2"><i class="fas fa-warehouse text-muted mr-1" aria-hidden="true"></i><a href="{{ route('gudang.show',$rak['warehouse_id']) }}">{{ $rak['warehouse_name'] }}</a> @if(!$rak['warehouse_active'])<span class="badge badge-secondary">Gudang nonaktif</span>@endif</div>
                            <div class="mb-3"><i class="fas fa-vector-square text-muted mr-1" aria-hidden="true"></i><a href="{{ route('area-gudang.show',$rak['area_id']) }}">{{ $rak['area_code'] }} — {{ $rak['area_name'] }}</a> @if(!$rak['area_active'])<span class="badge badge-secondary">Area nonaktif</span>@endif</div>
                            <div class="border rounded p-3 mb-3 bg-light">@include('gudang.capacity-meter', ['capacity'=>$rak['capacity'], 'locationName'=>$rak['code']])</div>
                            <dl class="row text-sm mb-0"><dt class="col-4">Posisi</dt><dd class="col-8">{{ $rak['position'] ?: 'Belum diisi' }}</dd><dt class="col-4">Dimensi</dt><dd class="col-8">{{ $rak['dimensions'] ?: 'Belum diisi' }}</dd><dt class="col-4">Bahan Rak</dt><dd class="col-8">{{ $rak['material'] ?: 'Belum diisi' }}</dd></dl>
                            @if($rak['description'])<p class="text-muted text-sm">{{ $rak['description'] }}</p>@endif
                            <div class="custom-control custom-switch mt-3"><input type="checkbox" class="custom-control-input warehouse-status-toggle" id="rack-status-{{ $rak['id'] }}" data-url="{{ route('rak-gudang.toggleStatus',$rak['id']) }}" @checked($rak['active'])><label class="custom-control-label" for="rack-status-{{ $rak['id'] }}">Rak aktif</label></div>
                        </div>
                        <div class="card-footer"><a class="btn btn-primary btn-sm" href="{{ route('rak-gudang.show',$rak['id']) }}"><i class="fas fa-eye mr-1" aria-hidden="true"></i>Detail</a><a class="btn btn-info btn-sm" href="{{ route('rak-gudang.edit',$rak['id']) }}"><i class="fas fa-edit mr-1" aria-hidden="true"></i>Edit</a><button type="button" class="btn btn-danger btn-sm delete-gudang-btn" data-url="{{ route('rak-gudang.destroy',$rak['id']) }}"><i class="fas fa-trash mr-1" aria-hidden="true"></i>Hapus</button></div>
                    </article>
                @endforeach
            </div>
            <div id="warehouseCardsEmpty" class="card" @if($raks->isNotEmpty()) hidden @endif><div class="card-body text-center text-muted py-5"><i class="fas fa-pallet fa-3x mb-3" aria-hidden="true"></i><p class="mb-0">{{ $raks->isEmpty() ? 'Belum ada data rak gudang. Tambahkan rak untuk menampilkan visualisasi.' : 'Tidak ada rak yang sesuai dengan pencarian atau filter.' }}</p></div></div>
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
