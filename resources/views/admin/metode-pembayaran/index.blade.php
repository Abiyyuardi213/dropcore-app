@extends('layouts.dashboard-master')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tambah Metode Pembayaran</h3>
                </div>
                <form action="{{ route('metode-pembayaran.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Bank/Metode</label>
                            <input type="text" name="nama_bank" class="form-control" placeholder="Contoh: BCA, Mandiri"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Nomor Rekening</label>
                            <input type="text" name="nomor_rekening" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Atas Nama</label>
                            <input type="text" name="atas_nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi/Instruksi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
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
                    <h3 class="card-title">Daftar Metode Pembayaran</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Bank</th>
                                <th>No. Rekening</th>
                                <th>Atas Nama</th>
                                <th>Status</th>
                                <th style="width: 150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($methods as $item)
                                <tr>
                                    <td>{{ $item->nama_bank }}</td>
                                    <td>{{ $item->nomor_rekening }}</td>
                                    <td>{{ $item->atas_nama }}</td>
                                    <td>
                                        <form action="{{ route('metode-pembayaran.toggleStatus', $item->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-sm btn-{{ $item->status ? 'success' : 'danger' }}">
                                                {{ $item->status ? 'Aktif' : 'Nonaktif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('metode-pembayaran.destroy', $item->id) }}" method="POST"
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
