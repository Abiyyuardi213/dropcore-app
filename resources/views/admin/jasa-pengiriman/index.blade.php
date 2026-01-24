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
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-warning btn-sm mr-1 edit-btn"
                                                data-id="{{ $item->id }}" data-nama="{{ $item->nama }}"
                                                data-kode="{{ $item->kode }}" data-biaya="{{ $item->biaya_dasar }}"
                                                data-toggle="modal" data-target="#editModal">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <form action="{{ route('jasa-pengiriman.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i
                                                        class="fas fa-trash"></i> Hapus</button>
                                            </form>
                                        </div>
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Jasa Pengiriman</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Jasa</label>
                            <input type="text" name="nama" id="edit_nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Kode</label>
                            <input type="text" name="kode" id="edit_kode" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Harga per Kg</label>
                            <input type="number" name="biaya_dasar" id="edit_biaya" class="form-control" value="0">
                            <small class="text-muted">Harga ongkos kirim per kilogram.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editBtns = document.querySelectorAll('.edit-btn');
            const editForm = document.getElementById('editForm');
            const editNama = document.getElementById('edit_nama');
            const editKode = document.getElementById('edit_kode');
            const editBiaya = document.getElementById('edit_biaya');

            editBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const nama = this.dataset.nama;
                    const kode = this.dataset.kode;
                    const biaya = this.dataset.biaya;

                    editNama.value = nama;
                    editKode.value = kode;
                    editBiaya.value = biaya;

                    // Update form action
                    editForm.action = "{{ url('admin/jasa-pengiriman') }}/" + id;
                });
            });
        });
    </script>
@endsection
