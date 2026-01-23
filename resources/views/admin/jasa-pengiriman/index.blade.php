@extends('layouts.dashboard-master')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tambah Jasa Pengiriman</h3>
                </div>
                <form action="{{ route('jasa-pengiriman.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Jasa</label>
                            <input type="text" name="nama" class="form-control" placeholder="Contoh: JNE, J&T"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Kode</label>
                            <input type="text" name="kode" class="form-control" placeholder="Optional. Contoh: jne">
                        </div>
                        <div class="form-group">
                            <label>Harga per Kg</label>
                            <input type="number" name="biaya_dasar" class="form-control" value="0">
                            <small class="text-muted">Harga ongkos kirim per kilogram.</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Jasa Pengiriman</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kode</th>
                                <th>Harga / Kg</th>
                                <th>Status</th>
                                <th style="width: 150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $item)
                                <tr>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->kode ?? '-' }}</td>
                                    <td>Rp {{ number_format($item->biaya_dasar, 0, ',', '.') }}</td>
                                    <td>
                                        <form action="{{ route('jasa-pengiriman.toggleStatus', $item->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-sm btn-{{ $item->status ? 'success' : 'danger' }}">
                                                {{ $item->status ? 'Aktif' : 'Nonaktif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('jasa-pengiriman.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"><i
                                                    class="fas fa-trash"></i> Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
