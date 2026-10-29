@php
    $capacityNumber = fn ($value) => rtrim(rtrim(number_format($value, $capacity['unit'] === 'm³' ? 4 : 2, ',', '.'), '0'), ',');
@endphp
<div class="d-flex justify-content-between align-items-center mb-2" style="gap:.5rem">
    <span class="badge badge-{{ $capacity['color'] }}">{{ $capacity['label'] }}</span>
    <strong>{{ $capacity['known'] ? number_format($capacity['percent'], 1, ',', '.').'%' : '—' }}</strong>
</div>
@if($capacity['known'])
    <div class="progress progress-sm mb-2" role="progressbar" aria-label="Pemakaian kapasitas {{ $locationName }}" aria-valuenow="{{ $capacity['bar'] }}" aria-valuemin="0" aria-valuemax="100" aria-valuetext="{{ $capacity['percent'] }} persen">
        <div class="progress-bar bg-{{ $capacity['color'] }}" style="width:{{ $capacity['bar'] }}%"></div>
    </div>
    <div class="text-sm">{{ $capacityNumber($capacity['used']) }} / {{ $capacityNumber($capacity['limit']) }} {{ $capacity['unit'] }}</div>
    <small class="d-block text-muted">@if($capacity['excess'] > 0)Melebihi {{ $capacityNumber($capacity['excess']) }}@else Sisa {{ $capacityNumber($capacity['remaining']) }}@endif {{ $capacity['unit'] }}</small>
@else
    <div class="progress progress-sm mb-2" aria-hidden="true"><div class="progress-bar bg-secondary progress-bar-striped" style="width:100%;opacity:.25"></div></div>
    <div class="text-sm">Kapasitas: {{ $capacity['raw'] ?: 'Belum diatur' }}</div>
@endif
<small class="d-block text-muted mt-1">{{ $capacity['message'] }}</small>
<div class="text-muted text-sm mt-2"><i class="fas fa-boxes mr-1" aria-hidden="true"></i>{{ number_format($capacity['quantity'], 0, ',', '.') }} kuantitas stok &middot; {{ $capacity['products'] }} produk</div>
