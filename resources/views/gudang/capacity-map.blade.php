<div class="card card-primary card-outline warehouse-capacity-map" id="warehouseCapacityMap">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-warehouse mr-2" aria-hidden="true"></i>Visualisasi Kapasitas Gudang</h3><div class="card-tools"><button type="button" class="btn btn-tool" onclick="window.location.reload()" aria-label="Perbarui data kapasitas"><i class="fas fa-sync-alt" aria-hidden="true"></i></button></div></div>
    <div class="card-body">
        <div class="row align-items-end mb-3">
            <div class="col-md-6"><label for="warehouseMapSelect">Pilih Gudang</label><select id="warehouseMapSelect" class="form-control"><option value="">Semua Gudang</option>@foreach($warehouseMap as $warehouse)<option value="{{ $warehouse['id'] }}">{{ $warehouse['code'] }} — {{ $warehouse['name'] }}</option>@endforeach</select></div>
            <div class="col-md-6 mt-3 mt-md-0"><label for="warehouseMapSearch">Cari Area / Rak</label><input id="warehouseMapSearch" class="form-control" type="search" placeholder="Ketik nama area atau kode rak" autocomplete="off"></div>
        </div>
        <div class="d-flex flex-wrap mb-3" style="gap:.5rem" aria-label="Keterangan warna kapasitas"><span class="badge badge-success">Lega &lt;60%</span><span class="badge badge-info">Terisi 60–&lt;80%</span><span class="badge badge-warning">Hampir penuh 80–&lt;100%</span><span class="badge badge-danger">Penuh ≥100%</span><span class="badge badge-secondary">Belum terukur</span></div>
        <div class="row mb-2">
            @foreach([['free','success','Rak Lega'],['near','warning','Rak Hampir Penuh'],['full','danger','Rak Penuh / Lebih'],['unknown','secondary','Rak Belum Terukur']] as [$key,$color,$label])
                <div class="col-sm-6 col-xl-3"><div class="info-box shadow-none border"><span class="info-box-icon bg-{{ $color }}"><i class="fas fa-th-large" aria-hidden="true"></i></span><div class="info-box-content"><span class="info-box-text">{{ $label }}</span><span class="info-box-number">{{ $capacitySummary[$key] }}</span></div></div></div>
            @endforeach
        </div>
        <p class="text-muted text-sm">Skema hubungan gudang → area → rak berdasarkan data lokasi, bukan denah fisik. Pemakaian dihitung dari stok saat halaman dimuat ({{ now('Asia/Jakarta')->format('d/m/Y H:i') }}). Kapasitas tiap tingkat dihitung tersendiri; persentase gudang tidak dijumlahkan dari persentase rak.</p>
        @forelse($warehouseMap as $warehouse)
            <section class="warehouse-map-node border rounded mb-3" data-warehouse-id="{{ $warehouse['id'] }}" aria-label="{{ $warehouse['name'] }}">
                <div class="bg-light p-3 border-bottom"><div class="row">
                    <div class="col-lg-7"><h4 class="h5 mb-1"><i class="fas fa-warehouse mr-2" aria-hidden="true"></i>{{ $warehouse['name'] }} @if(!$warehouse['active'])<span class="badge badge-secondary">Nonaktif</span>@endif</h4><div class="text-muted">{{ $warehouse['code'] }} &middot; {{ $warehouse['location'] }}</div><div class="text-sm mt-2">{{ $warehouse['areas']->count() }} area &middot; {{ $warehouse['areas']->sum(fn ($area) => $area['racks']->count()) }} rak</div>
                        @if($warehouse['unassigned'] > 0)<div class="text-warning text-sm mt-2"><i class="fas fa-exclamation-triangle mr-1" aria-hidden="true"></i>{{ number_format($warehouse['unassigned']) }} kuantitas stok belum memiliki area yang sesuai; tetap termasuk total gudang.</div>@endif
                    </div>
                    <div class="col-lg-5 mt-3 mt-lg-0">@include('gudang.capacity-meter', ['capacity'=>$warehouse['capacity'], 'locationName'=>$warehouse['name']])</div>
                </div></div>
                <div class="p-3 warehouse-map-areas">
                    @forelse($warehouse['areas'] as $area)
                        <details class="warehouse-area-node border rounded mb-3" data-area-name="{{ mb_strtolower($area['code'].' '.$area['name']) }}" @if($loop->first) open @endif>
                            <summary class="p-3"><span class="font-weight-bold"><i class="fas fa-vector-square mr-1" aria-hidden="true"></i>{{ $area['code'] }} — {{ $area['name'] }}</span> <span class="badge badge-{{ $area['capacity']['color'] }}">{{ $area['capacity']['label'] }}</span> <span class="text-muted text-sm">{{ $area['racks']->count() }} rak</span> @if(!$area['active'])<span class="badge badge-secondary">Nonaktif</span>@endif</summary>
                            <div class="px-3 pb-3"><div class="row mb-3"><div class="col-lg-6">@include('gudang.capacity-meter', ['capacity'=>$area['capacity'], 'locationName'=>$area['name']])</div><div class="col-lg-6 text-muted text-sm mt-2">Klik kartu rak untuk membuka detail lokasi dan kapasitasnya.
                                @if($area['unassigned'] > 0)<p class="text-warning mt-2 mb-0">{{ number_format($area['unassigned']) }} kuantitas stok belum memiliki rak yang sesuai; tetap termasuk total area.</p>@endif
                            </div></div>
                            <div class="warehouse-rack-grid">
                                @forelse($area['racks'] as $rack)
                                    <a href="{{ route('rak-gudang.show', $rack['id']) }}" class="warehouse-rack-node text-body border rounded p-3" data-rack-name="{{ mb_strtolower($rack['code'].' '.$rack['position']) }}" style="border-top:3px solid var(--{{ $rack['capacity']['color'] }})!important">
                                        <div class="d-flex align-items-start mb-2"><i class="fas fa-pallet text-{{ $rack['capacity']['color'] }} fa-2x mr-2" aria-hidden="true"></i><div class="min-width-0"><strong class="d-block">{{ $rack['code'] }}</strong><small class="text-muted">{{ $rack['position'] ?: 'Posisi belum diisi' }}</small></div></div>
                                        @if(!$rack['active'])<span class="badge badge-secondary mb-2">Nonaktif</span>@endif
                                        @include('gudang.capacity-meter', ['capacity'=>$rack['capacity'], 'locationName'=>$rack['code']])
                                    </a>
                                @empty<div class="text-muted p-3 border rounded">Area ini belum memiliki rak.</div>@endforelse
                            </div></div>
                        </details>
                    @empty<div class="text-muted text-center py-3">Gudang ini belum memiliki area.</div>@endforelse
                </div>
            </section>
        @empty<div class="text-center text-muted py-4"><i class="fas fa-warehouse fa-2x mb-3" aria-hidden="true"></i><p class="mb-0">Belum ada data gudang. Tambahkan gudang, area, dan rak untuk menampilkan visualisasi.</p></div>@endforelse
        <p id="warehouseMapEmpty" class="text-center text-muted py-4 mb-0" role="status" hidden>Tidak ada lokasi yang sesuai dengan pencarian.</p>
    </div>
</div>
