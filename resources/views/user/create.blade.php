<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna</title>
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
                <h1 class="m-0">Tambah Pengguna Baru</h1>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-plus-circle"></i> Form Tambah Pengguna</h3>
                    </div>

                    <div class="card-body">

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <!-- KIRI -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Nama Lengkap</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name') }}" required>
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                               name="username" value="{{ old('username') }}" required>
                                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email Pengguna</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               name="email" value="{{ old('email') }}">
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="no_telepon">Nomor Telepon</label>
                                        <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                                               name="no_telepon" value="{{ old('no_telepon') }}">
                                        @error('no_telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="nik">NIK</label>
                                        <input type="text" class="form-control @error('nik') is-invalid @enderror"
                                            name="nik" value="{{ old('nik') }}">
                                        @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                </div>

                                <!-- TENGAH -->
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                               name="password" required>
                                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="role_id">Role</label>
                                        <select name="role_id" class="form-control @error('role_id') is-invalid @enderror">
                                            <option value="">-- Pilih Role --</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->role_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="divisi_id">Divisi</label>
                                        <select name="divisi_id" class="form-control @error('divisi_id') is-invalid @enderror">
                                            <option value="">-- Pilih Divisi --</option>
                                            @foreach($divisis as $divisi)
                                                <option value="{{ $divisi->id }}" {{ old('divisi_id') == $divisi->id ? 'selected' : '' }}>
                                                    {{ $divisi->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('divisi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="jabatan_id">Jabatan</label>
                                        <select name="jabatan_id" class="form-control @error('jabatan_id') is-invalid @enderror">
                                            <option value="">-- Pilih Jabatan --</option>
                                            @foreach($jabatans as $jabatan)
                                                <option value="{{ $jabatan->id }}" {{ old('jabatan_id') == $jabatan->id ? 'selected' : '' }}>
                                                    {{ $jabatan->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('jabatan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="tanggal_bergabung">Tanggal Bergabung</label>
                                        <input type="date" class="form-control @error('tanggal_bergabung') is-invalid @enderror"
                                               name="tanggal_bergabung" value="{{ old('tanggal_bergabung') }}">
                                        @error('tanggal_bergabung')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="jenis_kelamin">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="form-control">
                                            <option value="">-- Pilih --</option>
                                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="status_kepegawaian">Status Kepegawaian</label>
                                        <select name="status_kepegawaian" class="form-control">
                                            <option value="aktif" {{ old('status_kepegawaian') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="nonaktif" {{ old('status_kepegawaian') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="alamat">Alamat</label>
                                        <textarea class="form-control" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                                    </div>

                                </div>

                                <!-- KANAN: FOTO -->
                                <div class="col-md-4 text-center">

                                    <label>Foto Profil</label>
                                    <input type="file" name="profile_picture" id="profile_picture"
                                           class="form-control-file @error('profile_picture') is-invalid @enderror"
                                           accept="image/*">
                                    @error('profile_picture')<div class="text-danger">{{ $message }}</div>@enderror

                                    <div style="width: 300px; height: 300px; border: 2px dashed #ccc;
                                        margin: auto; display: flex; align-items: center; justify-content: center;">
                                        <img id="preview" src="https://via.placeholder.com/300?text=Preview"
                                             class="img-fluid rounded" style="object-fit: contain;">
                                    </div>

                                    <input type="hidden" name="cropped_image" id="cropped_image">

                                </div>

                            </div>

                            <button type="submit" class="btn btn-primary mt-4">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('user.index') }}" class="btn btn-secondary mt-4">Batal</a>

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
