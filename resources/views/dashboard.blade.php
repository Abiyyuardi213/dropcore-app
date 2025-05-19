<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropcore - Admin Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif !important;
        }
        .fc-daygrid-day-number {
            color: white !important;
        }
        .fc-daygrid-day {
            border: none !important;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('include.navbarSistem')
    @include('include.sidebar')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $totalPeran ?? 0 }}</h3>
                                <p>Total Peran</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-tag"></i>
                            </div>
                            <a href="{{ url('role') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $totalPengguna ?? 0 }}</h3>
                                <p>Total Pengguna</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-friends"></i>
                            </div>
                            <a href="{{ url('pengguna') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $totalProduk ?? 0 }}</h3>
                                <p>Total Produk</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <a href="{{ url('produk') }}" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $totalStok ?? 0 }}</h3>
                                <p>Total Stok</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <a href="{{ url('stok') }}" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $totalPenerimaan ?? 0 }}</h3>
                                <p>Penerimaan Barang</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-truck-loading"></i>
                            </div>
                            <a href="{{ url('penerimaan-barang') }}" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $totalPengeluaran ?? 0 }}</h3>
                                <p>Pengeluaran Barang</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-dolly"></i>
                            </div>
                            <a href="{{ url('pengeluaran-barang') }}" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-purple">
                            <div class="inner">
                                <h3>Fasilitas</h3>
                                <p>Fasilitas & Peminjaman</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <a href="{{ url('fasilitas/dashboard') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $totalLegalisir ?? 0 }}</h3>
                                <p>Manajemen Legalisir</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-file-signature"></i>
                            </div>
                            <a href="{{ url('legalisir') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <section class="col-lg-7 connectedSortable">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-bullhorn"></i> Pengumuman Terbaru</h3>
                            </div>
                            <div class="card-body">
                                {{-- @if (count($pengumumans) > 0)
                                    <ul class="list-group">
                                        @foreach ($pengumumans as $pengumuman)
                                            <li class="list-group-item">
                                                <h5 class="mb-1">{{ $pengumuman->judul }}</h5>
                                                <p class="mb-1 text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($pengumuman->isi), 100) }}</p>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($pengumuman->tanggal_dibuat)->format('d M Y') }}</small>
                                                <a href="{{ url('pengumuman/' . $pengumuman->pengumuman_id) }}" class="btn btn-sm btn-primary float-right">Read More</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-center text-muted">Tidak ada pengumuman terbaru.</p>
                                @endif --}}
                            </div>
                        </div>
                    </section>

                    <section class="col-lg-5 connectedSortable">
                        <div class="card bg-gradient-success">
                            <div class="card-header border-0">
                                <h3 class="card-title"><i class="far fa-calendar-alt"></i> Calendar</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" data-card-widget="remove"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div id="calendar" style="width: 100%"></div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>

    @include('include.footerSistem')
</div>

@include('services.ToastModal')
@include('services.LogoutModal')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="{{ asset('resources/js/ToastScript.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        if (calendarEl) {
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id'
            });
            calendar.render();
        }
    });

    $(document).ready(function () {
        $('[data-widget="treeview"]').each(function () {
            AdminLTE.Treeview._jQueryInterface.call($(this));
        });
    });
</script>
</body>
</html>
