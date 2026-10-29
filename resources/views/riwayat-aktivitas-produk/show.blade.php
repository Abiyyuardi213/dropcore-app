@extends('layouts.dashboard-master')

@section('content-header')
<div class="row mb-2"><div class="col-sm-7"><h1 class="m-0">Detail Riwayat Aktivitas</h1></div><div class="col-sm-5"><ol class="breadcrumb float-sm-right"><li class="breadcrumb-item"><a href="{{ route('riwayat-aktivitas-produk.index') }}">Riwayat Aktivitas Produk</a></li><li class="breadcrumb-item active">Detail</li></ol></div></div>
@endsection

@section('content')
@php
    $type = strtolower($riwayat->tipe_aktivitas ?? '');
    $color = 'secondary'; $icon = 'info-circle';
    if (str_contains($type,'stok')) { $color='info'; $icon='boxes'; }
    elseif (str_contains($type,'tambah') || str_contains($type,'create')) { $color='success'; $icon='plus-circle'; }
    elseif (str_contains($type,'edit') || str_contains($type,'update')) { $color='warning'; $icon='edit'; }
    elseif (str_contains($type,'hapus') || str_contains($type,'delete')) { $color='danger'; $icon='trash'; }
    $activityLabel = ucwords(str_replace('_',' ',$riwayat->tipe_aktivitas ?? 'Aktivitas'));
@endphp
<div class="container-fluid" style="overflow-wrap:anywhere;">
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-outline card-{{ $color }}">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-{{ $icon }} mr-2" aria-hidden="true"></i>{{ $activityLabel }}</h3><span class="badge badge-light float-right">Catatan Aktivitas</span></div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3">ID Riwayat</dt><dd class="col-sm-9"><code>{{ $riwayat->id }}</code></dd>
                        <dt class="col-sm-3">Waktu Dicatat</dt><dd class="col-sm-9">{{ $riwayat->created_at?->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') ?? '-' }} WIB</dd>
                        <dt class="col-sm-3">Kode Aktivitas</dt><dd class="col-sm-9"><span class="badge badge-{{ $color }}">{{ $riwayat->tipe_aktivitas ?? '-' }}</span></dd>
                        <dt class="col-sm-3">Pelaku</dt><dd class="col-sm-9">{{ $riwayat->user->name ?? ($riwayat->user_id ? 'Pengguna tidak tersedia' : 'System / Tidak tercatat') }}</dd>
                        @if($riwayat->user_id)<dt class="col-sm-3">ID Pengguna</dt><dd class="col-sm-9"><small>{{ $riwayat->user_id }}</small></dd>@endif
                    </dl>
                    <hr><h5><i class="fas fa-align-left mr-2 text-muted" aria-hidden="true"></i>Deskripsi Lengkap</h5>
                    <div class="callout callout-{{ $color }} mb-0" style="white-space:pre-wrap;">{{ filled($riwayat->deskripsi) ? $riwayat->deskripsi : 'Tidak ada deskripsi tambahan yang tercatat.' }}</div>
                </div>
                <div class="card-footer text-muted text-sm"><i class="fas fa-info-circle mr-1" aria-hidden="true"></i>Riwayat ini menyimpan jenis aktivitas, deskripsi, dan pelaku. Rincian nilai sebelum dan sesudah perubahan tidak tersimpan pada catatan ini.</div>
            </div>
            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-history mr-2" aria-hidden="true"></i>Aktivitas Terbaru Produk yang Sama</h3></div>
                <div class="card-body p-0"><ul class="list-group list-group-flush">
                    @forelse($relatedActivities as $activity)
                        <li class="list-group-item {{ $activity->id === $riwayat->id ? 'bg-light' : '' }}">
                            <div class="d-flex flex-wrap justify-content-between" style="gap:.5rem;"><a href="{{ route('riwayat-aktivitas-produk.show',$activity->id) }}">{{ ucwords(str_replace('_',' ',$activity->tipe_aktivitas)) }}</a><small class="text-muted">{{ $activity->created_at?->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') }} WIB</small></div>
                            <div class="text-sm mt-1">{{ \Illuminate\Support\Str::limit($activity->deskripsi ?? 'Tanpa deskripsi',160) }}</div>
                            <small class="text-muted">{{ $activity->user->name ?? ($activity->user_id ? 'Pengguna tidak tersedia' : 'System / Tidak tercatat') }}</small>
                            @if($activity->id === $riwayat->id)<span class="badge badge-secondary ml-2">Sedang dilihat</span>@endif
                        </li>
                    @empty<li class="list-group-item text-muted">Tidak ada riwayat terkait yang dapat ditampilkan.</li>@endforelse
                </ul></div>
                <div class="card-footer text-muted text-sm">Maksimal 5 aktivitas terbaru berdasarkan ID produk yang sama.</div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-box mr-2" aria-hidden="true"></i>Produk Terkait</h3></div>
                <div class="card-body">
                    @if($riwayat->produk)
                        <h5 class="font-weight-bold">{{ $riwayat->produk->name }}</h5>
                        <dl class="mb-0"><dt>SKU</dt><dd>{{ $riwayat->produk->sku ?: 'Belum diisi' }}</dd><dt>Merk</dt><dd>{{ $riwayat->produk->merk ?: 'Belum diisi' }}</dd><dt>ID Produk</dt><dd><small>{{ $riwayat->produk_id }}</small></dd></dl>
                        <p class="text-muted text-sm">Identitas produk di atas mengikuti data master saat ini, bukan salinan saat aktivitas terjadi.</p>
                        @if(auth()->user()->role?->hasPermission('produk'))<a href="{{ route('product.show',$riwayat->produk_id) }}" class="btn btn-outline-primary btn-block"><i class="fas fa-external-link-alt mr-1" aria-hidden="true"></i>Lihat Detail Produk</a>@endif
                    @else
                        <div class="callout callout-warning"><h5>Produk Tidak Tersedia</h5><p class="mb-0">Data produk sudah dihapus atau tidak lagi tersedia. Catatan aktivitas tetap dapat dilihat.</p></div>
                        <strong>ID Produk Tercatat</strong><p class="text-muted text-sm">{{ $riwayat->produk_id ?: 'Tidak tercatat' }}</p>
                    @endif
                    @if($riwayat->produk_id)<a href="{{ route('riwayat-aktivitas-produk.index',['produk_id'=>$riwayat->produk_id]) }}" class="btn btn-default btn-block mt-2"><i class="fas fa-history mr-1" aria-hidden="true"></i>Semua Riwayat Produk Ini</a>@endif
                </div>
            </div>
            <a href="{{ route('riwayat-aktivitas-produk.index') }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left mr-1" aria-hidden="true"></i>Kembali ke Daftar</a>
        </div>
    </div>
</div>
@endsection
