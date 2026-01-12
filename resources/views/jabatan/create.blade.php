@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Jabatan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('jabatan.index') }}">Jabatan</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Jabatan</h3>
                </div>
                <form action="{{ route('jabatan.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Kode Jabatan</label>
                            <input type="text" name="kode_jabatan"
                                class="form-control @error('kode_jabatan') is-invalid @enderror"
                                value="{{ old('kode_jabatan') }}" placeholder="Contoh: JAB-MGR" required>
                            @error('kode_jabatan')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Nama Jabatan</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="Contoh: Manager Operasional" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Divisi</label>
                            <select name="divisi_id" class="form-control @error('divisi_id') is-invalid @enderror">
                                <option value="">-- Pilih Divisi --</option>
                                @foreach ($divisis as $div)
                                    <option value="{{ $div->id }}"
                                        {{ old('divisi_id') == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                                @endforeach
                            </select>
                            @error('divisi_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gaji Pokok</label>
                                    <input type="number" name="gaji_pokok" class="form-control"
                                        value="{{ old('gaji_pokok') }}" placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tunjangan</label>
                                    <input type="number" name="tunjangan" class="form-control"
                                        value="{{ old('tunjangan') }}" placeholder="0">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tanggung Jawab</label>
                            <textarea name="tanggung_jawab" class="form-control" rows="3" placeholder="Rincian tanggung jawab...">{{ old('tanggung_jawab') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Kualifikasi</label>
                            <textarea name="kualifikasi" class="form-control" rows="3" placeholder="Kualifikasi yang dibutuhkan...">{{ old('kualifikasi') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Deskripsi Tambahan</label>
                            <textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('jabatan.index') }}" class="btn btn-default">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
