@extends('layouts.kasir')
@section('title', 'Dashboard Kasir | Garuda Fiber')
@section('heading', 'Dashboard Kasir')

@push('styles')
<style>
    .cashier-dashboard-container { border-radius: 0; }
    .cashier-dashboard-container > .card-body { padding: 1.25rem; }
    .cashier-shortcuts { display: flex; flex-wrap: wrap; gap: 16px; }
    .cashier-shortcut { display: flex; flex-direction: column; align-items: center; justify-content: center; width: 105px; min-height: 96px; padding: 12px 8px; background: #457fb3; color: #fff; border: 0; border-radius: 0; text-align: center; gap: 7px; }
    a.cashier-shortcut:hover, a.cashier-shortcut:focus { background: #326694; color: #fff; }
    .cashier-shortcut > i { font-size: 30px; }
    .cashier-shortcut[disabled] { opacity: .65; cursor: not-allowed; }
    .cashier-shortcut small { font-size: 10px; line-height: 1; }
    .cashier-metric { border-radius: 2px; box-shadow: none; }
    .cashier-metric .inner { padding: 18px 14px; min-height: 110px; }
    .cashier-metric h3 { font-size: 1.3rem; position: relative; z-index: 1; }
    .cashier-metric p { margin-bottom: 0; position: relative; z-index: 1; }
    .cashier-metric .icon > i { font-size: 62px; top: 27px; }
    .cashier-red { background: #cc553e; color: white; }
    .cashier-blue { background: #2e77b8; color: white; }
    .cashier-orange { background: #eca035; color: white; }
    .cashier-black { background: #191919; color: white; }
    .cashier-green { background: #48a367; color: white; }
    .cashier-chart { height: 280px; position: relative; }
    .cashier-chart-card { border-radius: 2px; }
    .cashier-chart-card .card-header { background: white; }
    @media (max-width: 575.98px) { .cashier-shortcuts { gap: 10px; } .cashier-shortcut { width: calc((100% - 20px) / 3); min-height: 90px; } }
</style>
@endpush



@section('content')
<div class="card card-outline card-primary shadow-sm cashier-dashboard-container">
    <div class="card-body">
<nav class="cashier-shortcuts mb-4 pb-4 border-bottom" aria-label="Pintasan kasir">
    <a class="cashier-shortcut" href="{{ route('kasir.index') }}" aria-current="page"><i class="fas fa-house" aria-hidden="true"></i><span>Dashboard</span></a>
    <a class="cashier-shortcut" href="{{ route('kasir.penjualan') }}"><i class="fas fa-list" aria-hidden="true"></i><span>Penjualan</span></a>
    <a class="cashier-shortcut" href="{{ route('kasir.produk') }}"><i class="fas fa-box" aria-hidden="true"></i><span>Produk</span></a>
    <a class="cashier-shortcut" href="{{ route('kasir.pelanggan') }}"><i class="fas fa-users" aria-hidden="true"></i><span>Pelanggan</span></a>
    <a class="cashier-shortcut" href="{{ route('kasir.laporan') }}"><i class="fas fa-file-lines" aria-hidden="true"></i><span>Laporan</span></a>
    <a class="cashier-shortcut" href="{{ route('kasir.password') }}"><i class="fas fa-lock" aria-hidden="true"></i><span>Password</span></a>
</nav>
<p class="text-muted small mb-3">Ringkasan penjualan selesai milik akun kasir ini. Laba kotor dihitung dari transaksi yang harga modalnya lengkap. Transaksi tanpa modal belum termasuk dalam nominal laba terhitung.</p>
<div class="row">
    @foreach(['Hari Ini', 'Bulan Ini', 'Tahun Ini', 'Seluruh Periode'] as $index => $period)
        <div class="col-sm-6 col-xl-3"><div class="small-box cashier-metric {{ ['cashier-red', 'cashier-blue', 'cashier-orange', 'cashier-black'][$index] }}">
            <div class="inner"><h3>Rp {{ number_format($summary[$period]['total'], 0, ',', '.') }}</h3><p>{{ $index === 3 ? 'Total Seluruh Penjualan' : 'Penjualan '.$period }}</p></div>
            <div class="icon"><i class="fas fa-chart-column" aria-hidden="true"></i></div>
        </div></div>
    @endforeach
</div>
<div class="row">
    @foreach(['Hari Ini', 'Bulan Ini', 'Tahun Ini', 'Seluruh Periode'] as $index => $period)
        <div class="col-sm-6 col-xl-3"><div class="small-box cashier-metric {{ ['cashier-red', 'cashier-blue', 'cashier-orange', 'cashier-black'][$index] }}">
            <div class="inner">
                <h3 @if($summary[$period]['missing_count'] && !$summary[$period]['known_count']) style="font-size:1.5rem;" @endif>{{ $summary[$period]['missing_count'] && !$summary[$period]['known_count'] ? 'Belum tersedia' : 'Rp '.number_format($summary[$period]['known_profit'], 0, ',', '.') }}</h3>
                <p>{{ $index === 3 ? 'Laba Kotor Seluruh Periode' : 'Laba Kotor '.$period }}{{ $summary[$period]['missing_count'] ? ' (Terhitung)' : '' }}</p>
                <small class="d-block">{{ $summary[$period]['known_count'] }} dari {{ $summary[$period]['sales_count'] }} transaksi terhitung</small>
                @if($summary[$period]['missing_count'])<small class="d-block font-weight-bold">{{ $summary[$period]['missing_count'] }} transaksi belum memiliki modal lengkap</small>@endif
            </div>
            <div class="icon"><i class="fas fa-chart-column" aria-hidden="true"></i></div>
        </div></div>
    @endforeach
</div>
<div class="row">
    @foreach([['Produk Terjual', 'fa-box'], ['Kategori Terjual', 'fa-folder'], ['Pelanggan', 'fa-users'], ['Transaksi Hari Ini', 'fa-cash-register'], ['Unit Terjual', 'fa-cubes'], ['Jumlah Invoice', 'fa-file-invoice']] as [$label, $icon])
        <div class="col-sm-6 col-md-4 col-xl-2"><div class="small-box cashier-metric cashier-green">
            <div class="inner"><h3>{{ number_format($stats[$label], 0, ',', '.') }}</h3><p>{{ $label }}</p></div><div class="icon"><i class="fas {{ $icon }}" aria-hidden="true"></i></div>
        </div></div>
    @endforeach
</div>
<div class="row">
    <div class="col-lg-6"><section class="card cashier-chart-card" aria-labelledby="dailyChartTitle">
        <div class="card-header d-flex align-items-center"><h2 class="card-title" id="dailyChartTitle">Grafik Penjualan Harian</h2><span class="badge badge-light border ml-auto">7 Hari Terakhir</span></div>
        <div class="card-body"><div class="cashier-chart"><canvas id="cashierDailyChart" role="img" aria-label="Grafik penjualan kasir harian"></canvas></div><p class="text-center text-muted small mt-3 mb-0">Transaksi selesai, tidak termasuk pembatalan.</p></div>
    </section></div>
    <div class="col-lg-6"><section class="card cashier-chart-card" aria-labelledby="monthlyChartTitle">
        <div class="card-header d-flex align-items-center"><h2 class="card-title" id="monthlyChartTitle">Grafik Penjualan Bulanan</h2><span class="badge badge-light border ml-auto">{{ now('Asia/Jakarta')->year }}</span></div>
        <div class="card-body"><div class="cashier-chart"><canvas id="cashierMonthlyChart" role="img" aria-label="Grafik penjualan kasir bulanan"></canvas></div><p class="text-center text-muted small mt-3 mb-0">Transaksi selesai, tidak termasuk pembatalan.</p></div>
    </section></div>
</div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>
<script>
    (() => {
        const dailyLabels = @json($dailyLabels);
        const monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        function createChart(id, labels, amounts) {
            new Chart(document.getElementById(id), {
                type: 'bar',
                data: { labels, datasets: [{ label: 'Penjualan Kasir', data: amounts, backgroundColor: 'rgba(92, 214, 112, .45)', borderColor: '#63c878', borderWidth: 1, maxBarThickness: 42 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { callbacks: { label: context => 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y) } } }, scales: { x: { grid: { display: false } }, y: { beginAtZero: true, suggestedMax: 100000, grid: { color: '#eef0f2' }, ticks: { callback: value => new Intl.NumberFormat('id-ID').format(value) } } } }
            });
        }
        createChart('cashierDailyChart', dailyLabels, @json($dailyData));
        createChart('cashierMonthlyChart', monthlyLabels, @json($monthlyData));
    })();
</script>
@endpush
