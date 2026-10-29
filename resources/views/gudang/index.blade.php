<!DOCTYPE html>
<html lang="id"><head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Garuda Fiber - Manajemen Gudang</title>
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
        <section class="content-header"><div class="container-fluid"><div class="row mb-2"><div class="col-sm-6"><h1>Manajemen Gudang</h1></div><div class="col-sm-6"><ol class="breadcrumb float-sm-right"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li><li class="breadcrumb-item active">Gudang</li></ol></div></div></div></section>
        <section class="content"><div class="container-fluid">
            <div class="card"><div class="card-header d-flex align-items-center"><h3 class="card-title">Daftar Gudang &amp; Kapasitas</h3><a class="btn btn-primary btn-sm ml-auto" href="{{ route('gudang.create') }}"><i class="fas fa-plus mr-1" aria-hidden="true"></i>Tambah Gudang</a></div>
                <div class="card-body">
                    <div class="row align-items-end mb-3"><div class="col-md-8"><label for="warehouseCardSearch">Cari Gudang</label><input type="search" id="warehouseCardSearch" class="form-control" placeholder="Cari kode, nama, lokasi, atau penanggung jawab" autocomplete="off"></div><div class="col-md-4 mt-3 mt-md-0"><label for="warehouseCardStatus">Status Gudang</label><select id="warehouseCardStatus" class="form-control"><option value="">Semua Status</option><option value="1">Aktif</option><option value="0">Nonaktif</option></select></div></div>
                    <div class="d-flex flex-wrap mb-2" style="gap:.5rem"><span class="badge badge-success">Lega &lt;60%</span><span class="badge badge-info">Terisi 60–&lt;80%</span><span class="badge badge-warning">Hampir penuh 80–&lt;100%</span><span class="badge badge-danger">Penuh ≥100%</span><span class="badge badge-secondary">Belum terukur</span></div>
                    <p class="text-muted text-sm mb-0">Kapasitas mengikuti stok saat halaman dimuat. Satuan yang belum sesuai atau data yang belum lengkap ditandai Belum terukur.</p>
                    <small class="text-muted" id="warehouseCardCount" role="status">Menampilkan {{ $gudangs->count() }} gudang</small>
                </div>
            </div>
            <div class="warehouse-card-grid" id="warehouseCards">
                @foreach($gudangs as $gudang)
                @php $rackList = $gudang['areas']->flatMap(fn ($area) => $area['racks']); @endphp
                <article class="card card-outline card-{{ $gudang['capacity']['color'] }} warehouse-card" data-search="{{ mb_strtolower($gudang['code'].' '.$gudang['name'].' '.$gudang['location'].' '.$gudang['pic']) }}" data-active="{{ $gudang['active'] ? '1' : '0' }}" aria-label="{{ $gudang['name'] }}">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3"><div class="warehouse-card-icon bg-{{ $gudang['capacity']['color'] }} mr-3"><i class="fas fa-warehouse fa-2x" aria-hidden="true"></i></div><div><span class="text-muted text-sm">{{ $gudang['code'] }}</span><h2 class="h5 mb-1">{{ $gudang['name'] }}</h2><span class="badge badge-{{ $gudang['active'] ? 'success' : 'secondary' }} warehouse-status-badge">{{ $gudang['active'] ? 'Aktif' : 'Nonaktif' }}</span> @if($gudang['type'])<span class="badge badge-light border">{{ $gudang['type'] }}</span>@endif</div></div>
                        <div class="text-muted mb-2"><i class="fas fa-map-marker-alt mr-1" aria-hidden="true"></i>{{ $gudang['location'] ?: 'Lokasi belum diisi' }}</div>
                        @if($gudang['pic'])<div class="text-sm mb-2"><strong>Penanggung jawab:</strong> {{ $gudang['pic'] }}</div>@endif
                        @if($gudang['description'])<p class="text-muted text-sm">{{ $gudang['description'] }}</p>@endif
                        <div class="border rounded p-3 mb-3 bg-light">@include('gudang.capacity-meter', ['capacity'=>$gudang['capacity'], 'locationName'=>$gudang['name']])</div>
                        <div class="d-flex justify-content-between border-bottom pb-2 mb-2"><span><i class="fas fa-vector-square mr-1" aria-hidden="true"></i>{{ $gudang['areas']->count() }} area</span><span><i class="fas fa-pallet mr-1" aria-hidden="true"></i>{{ $rackList->count() }} rak</span><span class="text-danger">{{ $rackList->filter(fn ($rack) => in_array($rack['capacity']['status'], ['near','full']))->count() }} rak perlu perhatian</span></div>
                        @if($gudang['unassigned'] > 0)<p class="text-warning text-sm">{{ number_format($gudang['unassigned']) }} kuantitas stok belum memiliki area yang sesuai.</p>@endif
                        <details><summary class="text-primary font-weight-bold">Lihat Visualisasi Area &amp; Rak</summary>
                            @forelse($gudang['areas'] as $area)
                                <div class="warehouse-area-card"><h3 class="h6 mb-2">{{ $area['code'] }} — {{ $area['name'] }} @if(!$area['active'])<span class="badge badge-secondary">Nonaktif</span>@endif</h3>
                                    @include('gudang.capacity-meter', ['capacity'=>$area['capacity'], 'locationName'=>$area['name']])
                                    <div class="warehouse-rack-links">@forelse($area['racks'] as $rack)<a class="warehouse-rack-link" href="{{ route('rak-gudang.show',$rack['id']) }}"><strong class="d-block mb-2"><i class="fas fa-pallet text-{{ $rack['capacity']['color'] }} mr-1" aria-hidden="true"></i>{{ $rack['code'] }}</strong>@if(!$rack['active'])<span class="badge badge-secondary mb-2">Nonaktif</span>@endif @include('gudang.capacity-meter', ['capacity'=>$rack['capacity'], 'locationName'=>$rack['code']])</a>@empty<span class="text-muted text-sm">Belum ada rak.</span>@endforelse</div>
                                </div>
                            @empty<p class="text-muted text-sm mt-2 mb-0">Belum ada area pada gudang ini.</p>@endforelse
                        </details>
                        <div class="custom-control custom-switch mt-3"><input type="checkbox" class="custom-control-input warehouse-status-toggle" id="warehouse-status-{{ $gudang['id'] }}" data-url="{{ route('gudang.toggleStatus',$gudang['id']) }}" @checked($gudang['active'])><label class="custom-control-label" for="warehouse-status-{{ $gudang['id'] }}">Gudang aktif</label></div>
                    </div>
                    <div class="card-footer"><a class="btn btn-primary btn-sm" href="{{ route('gudang.show',$gudang['id']) }}"><i class="fas fa-eye mr-1" aria-hidden="true"></i>Detail</a><a class="btn btn-info btn-sm" href="{{ route('gudang.edit',$gudang['id']) }}"><i class="fas fa-edit mr-1" aria-hidden="true"></i>Edit</a><button type="button" class="btn btn-danger btn-sm delete-gudang-btn" data-url="{{ route('gudang.destroy',$gudang['id']) }}"><i class="fas fa-trash mr-1" aria-hidden="true"></i>Hapus</button></div>
                </article>
                @endforeach
            </div>
            <div id="warehouseCardsEmpty" class="card" @if($gudangs->isNotEmpty()) hidden @endif><div class="card-body text-center text-muted py-5"><i class="fas fa-warehouse fa-3x mb-3" aria-hidden="true"></i><p class="mb-0">{{ $gudangs->isEmpty() ? 'Belum ada data gudang. Tambahkan gudang untuk menampilkan visualisasi.' : 'Tidak ada gudang yang sesuai dengan pencarian atau status.' }}</p></div></div>
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
