<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pegawai</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css"/>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('include.navbarSistem')
    @include('include.sidebar')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="m-0">Edit Pegawai</h1>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit"></i> Form Edit Pegawai
                        </h3>
                    </div>

                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- KOLOM KIRI -->
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control"
                                               value="{{ old('name', $pegawai->name) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label>NIK</label>
                                        <input type="text" name="nik" class="form-control"
                                               value="{{ old('nik', $pegawai->nik) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control"
                                               value="{{ old('email', $pegawai->email) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Nomor Telepon</label>
                                        <input type="text" name="no_telepon" class="form-control"
                                               value="{{ old('no_telepon', $pegawai->no_telepon) }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="form-control">
                                            <option value="">-- Pilih --</option>
                                            <option value="L" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Tanggal Bergabung</label>
                                        <input type="date"
                                            name="tanggal_bergabung"
                                            class="form-control"
                                            value="{{ old('tanggal_bergabung', optional($pegawai->tanggal_bergabung)->format('Y-m-d')) }}">
                                    </div>
                                </div>

                                <!-- KOLOM TENGAH -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" name="username" class="form-control"
                                               value="{{ old('username', $pegawai->username) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Password (kosongkan jika tidak diganti)</label>
                                        <input type="password" name="password" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Divisi</label>
                                        <select name="divisi_id" class="form-control">
                                            <option value="">-- Pilih Divisi --</option>
                                            @foreach($divisis as $d)
                                                <option value="{{ $d->id }}"
                                                    {{ old('divisi_id', $pegawai->divisi_id) == $d->id ? 'selected' : '' }}>
                                                    {{ $d->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Jabatan</label>
                                        <select name="jabatan_id" class="form-control">
                                            <option value="">-- Pilih Jabatan --</option>
                                            @foreach($jabatans as $j)
                                                <option value="{{ $j->id }}"
                                                    {{ old('jabatan_id', $pegawai->jabatan_id) == $j->id ? 'selected' : '' }}>
                                                    {{ $j->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Status Kepegawaian</label>
                                        <select name="status_kepegawaian" class="form-control">
                                            <option value="aktif" {{ old('status_kepegawaian', $pegawai->status_kepegawaian) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="nonaktif" {{ old('status_kepegawaian', $pegawai->status_kepegawaian) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $pegawai->alamat) }}</textarea>
                                    </div>
                                </div>

                                <!-- KOLOM KANAN: FOTO -->
                                <div class="col-md-4">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-warning">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-image"></i> Foto Profil
                                            </h5>
                                        </div>
                                        <div class="card-body text-center">
                                            <!-- Preview -->
                                            <div class="mb-3">
                                                <div style="
                                                    width: 260px;
                                                    height: 260px;
                                                    margin: auto;
                                                    border-radius: 15px;
                                                    overflow: hidden;
                                                    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
                                                    border: 1px solid #ddd;
                                                ">
                                                    <img id="preview"
                                                         src="{{ $pegawai->profile_picture ? asset('storage/profile/'.$pegawai->profile_picture) : 'https://via.placeholder.com/260?text=Preview' }}"
                                                         style="width:100%; height:100%; object-fit: cover;">
                                                </div>
                                            </div>

                                            <!-- Input File -->
                                            <label class="btn btn-outline-warning">
                                                <i class="fas fa-upload"></i> Ganti Foto
                                                <input type="file" name="profile_picture" id="profile_picture" accept="image/*" hidden>
                                            </label>

                                            <input type="hidden" name="cropped_image" id="cropped_image">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-warning mt-4">
                                <i class="fas fa-save"></i> Update
                            </button>

                            <a href="{{ route('pegawai.index') }}" class="btn btn-secondary mt-4">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('include.footerSistem')

</div>

@include('services.logoutModal')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
    let cropper;
    const image = document.getElementById('preview');
    const input = document.getElementById('profile_picture');

    input.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = () => {
            image.src = reader.result;

            if (cropper) cropper.destroy();

            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 1,
                crop() {
                    const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
                    canvas.toBlob((blob) => {
                        const reader = new FileReader();
                        reader.onloadend = function () {
                            document.getElementById('cropped_image').value = reader.result;
                        }
                        reader.readAsDataURL(blob);
                    });
                }
            });
        };
        reader.readAsDataURL(file);
    });
</script>

</body>
</html>
