<!DOCTYPE html>
<html lang="id"><head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garuda Fiber - Dashboard Produk</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        .product-dashboard-chart { position:relative; height:280px; }
        .product-dashboard-value { font-size:1.6rem; font-weight:700; overflow-wrap:anywhere; }
        .product-dashboard-table td,.product-dashboard-table th { vertical-align:middle; }
        .product-dashboard-empty { text-align:center; color:#6c757d; padding:1.5rem; }
    </style>
</head><body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">
    @include('include.navbarSistem')
    @include('include.sidebar')
    <div class="content-wrapper">
        <section class="content-header"><div class="container-fluid"><div class="row mb-2"><div class="col-sm-6"><h1>Dashboard Produk</h1><p class="text-muted text-sm mb-0">Kondisi stok saat ini &middot; {{ now('Asia/Jakarta')->format('d/m/Y H:i') }}</p></div><div class="col-sm-6"><ol class="breadcrumb float-sm-right"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li><li class="breadcrumb-item active">Dashboard Produk</li></ol></div></div></div></section>
        <section class="content"><div class="container-fluid">
            @if($errors->any())<div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>@endif
            <div class="row">
                @foreach([['products','info','fas fa-boxes','Total Produk'],['empty','danger','fas fa-box-open','Stok Habis / Minus'],['low','warning','fas fa-exclamation-triangle','Stok Menipis'],['over','primary','fas fa-layer-group','Stok Berlebih']] as [$key,$color,$icon,$label])
                    <div class="col-lg-3 col-6"><div class="small-box bg-{{ $color }}"><div class="inner"><h3>{{ number_format($summary[$key]) }}</h3><p>{{ $label }}</p></div><div class="icon"><i class="{{ $icon }}" aria-hidden="true"></i></div><a class="small-box-footer" href="{{ $key === 'products' ? route('product.index') : ($key === 'over' ? '#productOverstock' : '#productStockAlerts') }}">{{ $key === 'products' ? 'Lihat Master Produk' : 'Lihat Rincian' }} <i class="fas fa-arrow-circle-right" aria-hidden="true"></i></a></div></div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-lg-4"><div class="card card-outline card-success"><div class="card-header"><h3 class="card-title">Estimasi Nilai Stok (Harga Beli)</h3></div><div class="card-body"><div class="product-dashboard-value">{{ $summary['valued_products'] ? 'Rp '.number_format($summary['purchase_value'],2,',','.') : 'Belum tersedia' }}</div><p class="text-muted text-sm mt-2 mb-0">Harga beli master saat ini × stok tersisa. Terhitung {{ $summary['valued_products'] }} dari {{ $summary['stocked'] }} produk yang memiliki stok; {{ $summary['missing_cost'] }} produk belum memiliki harga beli valid. Estimasi ini bukan HPP historis.</p></div></div></div>
                <div class="col-lg-4"><div class="card card-outline card-info"><div class="card-header"><h3 class="card-title">Kuantitas Stok per Satuan</h3></div><div class="card-body py-2">
                    @forelse($stockByUnit as $unit)<div class="d-flex justify-content-between py-2 border-bottom"><span>{{ $unit['unit'] }} <small class="text-muted">({{ $unit['products'] }} produk)</small></span><strong>{{ number_format($unit['quantity'],0,',','.') }}</strong></div>@empty<p class="text-muted my-2">Belum ada stok tersedia.</p>@endforelse
                    <small class="d-block text-muted mt-2">{{ $summary['stocked'] }} produk memiliki stok. Satuan berbeda ditampilkan terpisah.</small>
                </div></div></div>
                <div class="col-lg-4"><div class="card card-outline card-warning"><div class="card-header"><h3 class="card-title">Kelengkapan &amp; Kualitas Data</h3></div><div class="card-body py-2"><ul class="list-group list-group-flush text-sm">
                    @foreach([['missing_min','Produk tanpa batas stok minimum'],['missing_cost','Produk berstok tanpa harga beli valid'],['missing_sku','Produk tanpa SKU'],['negative','Produk dengan total stok negatif'],['invalid_limits','Produk dengan minimum melebihi maksimum'],['unlocated','Baris stok tanpa lokasi lengkap']] as [$key,$label])<li class="list-group-item px-0 d-flex justify-content-between"><span>{{ $label }}</span><span class="badge badge-{{ $summary[$key] ? 'warning' : 'light' }} ml-2">{{ $summary[$key] }}</span></li>@endforeach
                </ul></div><div class="card-footer text-right"><a href="{{ route('product.index') }}" class="text-sm">Periksa Data Produk <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i></a></div></div></div>
            </div>
            <div class="row">
                <div class="col-lg-8"><div class="card card-danger card-outline" id="productStockAlerts"><div class="card-header"><h3 class="card-title">Prioritas Pengisian Stok</h3><span class="badge badge-danger float-right">{{ $summary['empty'] + $summary['low'] }} produk</span></div><div class="card-body p-0"><div class="table-responsive"><table class="table table-striped product-dashboard-table mb-0"><thead><tr><th>Produk / SKU</th><th>Status</th><th class="text-right">Stok</th><th class="text-right">Minimum</th><th class="text-right">Kekurangan ke Minimum</th></tr></thead><tbody>
                    @forelse($stockAlerts as $product)<tr><td><a href="{{ route('product.show',$product->id) }}">{{ $product->name }}</a><small class="d-block text-muted">{{ $product->sku ?: 'SKU belum diisi' }}</small></td><td><span class="badge badge-{{ $product->stock_status === 'empty' ? 'danger' : 'warning' }}">{{ $product->stock_quantity < 0 ? 'Stok minus' : ($product->stock_status === 'empty' ? 'Habis' : 'Menipis') }}</span></td><td class="text-right">{{ number_format($product->stock_quantity) }} {{ $product->uom->name ?? '' }}</td><td class="text-right">{{ (float)$product->min_stock > 0 ? number_format($product->min_stock) : 'Belum diatur' }}</td><td class="text-right">{{ (float)$product->min_stock > 0 ? number_format(max(0,$product->min_stock-$product->stock_quantity)) : '—' }}</td></tr>@empty<tr><td colspan="5" class="product-dashboard-empty">Tidak ada produk habis atau stok menipis.</td></tr>@endforelse
                </tbody></table></div></div><div class="card-footer text-muted text-sm">Menampilkan maksimal 10 prioritas. Stok menipis: stok positif ≤ minimum yang sudah diatur. Kekurangan dihitung untuk mencapai minimum, bukan rekomendasi jumlah pembelian.</div></div></div>
                <div class="col-lg-4"><div class="card"><div class="card-header"><h3 class="card-title">Komposisi Kondisi Produk</h3></div><div class="card-body">
                    @if($summary['products'])<div class="product-dashboard-chart"><canvas id="productStatusChart" role="img" aria-label="Komposisi produk tersedia, menipis, habis, dan berlebih"></canvas></div>@else<p class="product-dashboard-empty">Belum ada data produk.</p>@endif
                    <ul class="list-unstyled text-sm mt-3 mb-0"><li>Tersedia: <strong>{{ $summary['normal'] }}</strong></li><li>Menipis: <strong>{{ $summary['low'] }}</strong></li><li>Habis / minus: <strong>{{ $summary['empty'] }}</strong></li><li>Berlebih: <strong>{{ $summary['over'] }}</strong></li></ul>
                </div></div></div>
            </div>
            <div class="card card-primary card-outline"><div class="card-header"><div class="d-flex flex-wrap justify-content-between align-items-center" style="gap:.75rem"><h3 class="card-title">Pergerakan Barang Masuk &amp; Keluar</h3><form method="GET" action="{{ route('dashboard-produk') }}" class="form-inline"><label for="productMovementDays" class="mr-2">Periode</label><select name="days" id="productMovementDays" class="form-control form-control-sm mr-2">@foreach([7,30,90] as $days)<option value="{{ $days }}" @selected($movements['days']===$days)>{{ $days }} hari terakhir</option>@endforeach</select><button type="submit" class="btn btn-primary btn-sm">Tampilkan</button></form></div></div>
                <div class="card-body"><div class="row mb-3"><div class="col-sm-6"><div class="info-box bg-light shadow-none border"><span class="info-box-icon text-success"><i class="fas fa-arrow-down" aria-hidden="true"></i></span><div class="info-box-content"><span class="info-box-text">Barang Masuk</span><span class="info-box-number">{{ number_format($movements['incoming_quantity']) }} kuantitas</span><span class="text-muted text-sm">{{ $movements['incoming_count'] }} dokumen penerimaan selesai</span></div></div></div><div class="col-sm-6"><div class="info-box bg-light shadow-none border"><span class="info-box-icon text-primary"><i class="fas fa-arrow-up" aria-hidden="true"></i></span><div class="info-box-content"><span class="info-box-text">Barang Keluar</span><span class="info-box-number">{{ number_format($movements['outgoing_quantity']) }} kuantitas</span><span class="text-muted text-sm">{{ $movements['outgoing_count'] }} dokumen pengeluaran selesai</span></div></div></div></div>
                    <div class="product-dashboard-chart"><canvas id="productMovementChart" role="img" aria-label="Grafik kuantitas barang masuk dan keluar per hari"></canvas></div>
                    <p class="text-muted text-sm mt-3 mb-0">{{ $movements['start']->format('d/m/Y') }} – {{ $movements['end']->format('d/m/Y') }}. Berdasarkan dokumen penerimaan/pengeluaran berstatus selesai, termasuk pengeluaran dari kasir. Draft dan pembatalan tidak dihitung. Grafik menjumlahkan kuantitas berbagai satuan untuk menunjukkan aktivitas, bukan kapasitas fisik.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4"><div class="card"><div class="card-header"><h3 class="card-title">Sebaran Produk per Kategori</h3></div><div class="card-body">
                    @forelse($categories as $category)<div class="mb-3"><div class="d-flex justify-content-between"><strong class="text-sm">{{ $category['name'] }}</strong><span class="text-sm">{{ $category['products'] }} produk</span></div><div class="progress progress-xs mt-2"><div class="progress-bar bg-info" style="width:{{ $category['products']/max(1,$summary['products'])*100 }}%"></div></div><small class="text-muted">{{ $category['stocked'] }} produk memiliki stok</small></div>@empty<p class="text-muted mb-0">Belum ada kategori produk.</p>@endforelse
                </div></div></div>
                <div class="col-lg-4"><div class="card card-primary card-outline" id="productOverstock"><div class="card-header"><h3 class="card-title">Pantauan Stok Berlebih</h3></div><div class="card-body p-0"><ul class="list-group list-group-flush">
                    @forelse($overstock as $product)<li class="list-group-item"><a href="{{ route('product.show',$product->id) }}">{{ $product->name }}</a><small class="d-block text-muted">{{ $product->sku ?: '-' }}</small><div class="text-sm mt-1">Stok {{ number_format($product->stock_quantity) }} / maks. {{ number_format($product->max_stock) }} {{ $product->uom->name ?? '' }} <span class="badge badge-primary">+{{ number_format($product->stock_quantity-$product->max_stock) }}</span></div></li>@empty<li class="list-group-item text-muted">Tidak ada stok yang melebihi maksimum terdata.</li>@endforelse
                </ul></div><div class="card-footer text-muted text-sm">Maksimal 5 produk. Berlaku jika batas stok maksimum sudah diatur.</div></div></div>
                <div class="col-lg-4"><div class="card"><div class="card-header"><h3 class="card-title">Dokumen Pergerakan Terbaru</h3></div><div class="card-body p-0"><ul class="list-group list-group-flush">
                    @forelse($recentDocuments as $document)<li class="list-group-item"><span class="badge badge-{{ $document['type']==='penerimaan' ? 'success' : 'primary' }}">{{ $document['type']==='penerimaan' ? 'Masuk' : 'Keluar' }}</span>
                        @if(auth()->user()->role?->hasPermission('transaksi'))<a class="ml-1" href="{{ route($document['type']==='penerimaan' ? 'penerimaan-barang.show' : 'pengeluaran-barang.show',$document['id']) }}">{{ $document['number'] }}</a>@else<strong class="ml-1 text-sm">{{ $document['number'] }}</strong>@endif
                        <small class="d-block text-muted mt-1">{{ \Carbon\Carbon::parse($document['date'])->format('d/m/Y') }} &middot; {{ number_format($document['quantity']) }} kuantitas</small></li>@empty<li class="list-group-item text-muted">Belum ada dokumen selesai pada periode ini.</li>@endforelse
                </ul></div></div></div>
            </div>
        </div></section>
    </div>
    @include('include.footerSistem')
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>
@include('services.ToastModal')
@include('services.LogoutModal')
<script>window.productDashboardData = {movements: {{ Illuminate\Support\Js::from($chart) }}, statuses: {{ Illuminate\Support\Js::from([$summary['normal'],$summary['low'],$summary['empty'],$summary['over']]) }}};</script>
<script src="{{ asset('resources/js/ProductDashboard.js') }}" defer></script>
</body></html>
