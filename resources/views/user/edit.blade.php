<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Pengguna</title>
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
                    <h1 class="m-0">Ubah Pengguna</h1>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-edit"></i> Form Ubah Pengguna</h3>
                        </div>

                        <div class="card-body">
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">

                                    <!-- KIRI -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>NIP</label>
                                            <input type="text" class="form-control" value="{{ $user->nip }}" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label>Nama Pengguna</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                name="name" value="{{ old('name', $user->name) }}">
                                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                                name="username" value="{{ old('username', $user->username) }}">
                                            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email', $user->email) }}">
                                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Nomor Telepon</label>
                                            <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                                                name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}">
                                            @error('no_telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Peran</label>
                                            <select name="role_id" class="form-control @error('role_id') is-invalid @enderror">
                                                <option value="">-- Pilih Peran --</option>
                                                @foreach($roles as $r)
                                                    <option value="{{ $r->id }}" {{ old('role_id', $user->role_id) == $r->id ? 'selected' : '' }}>
                                                        {{ $r->role_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <!-- TENGAH -->
                                    <div class="col-md-4">

                                        <div class="form-group">
                                            <label>Password <small class="text-muted">(opsional)</small></label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                name="password">
                                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="jenis_kelamin">Jenis Kelamin</label>
                                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                                                <option value="">-- Pilih Jenis Kelamin --</option>
                                                <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $user->alamat) }}</textarea>
                                            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Tanggal Bergabung</label>
                                            <input type="date" name="tanggal_bergabung"
                                                class="form-control @error('tanggal_bergabung') is-invalid @enderror"
                                                value="{{ old('tanggal_bergabung', $user->tanggal_bergabung) }}">
                                            @error('tanggal_bergabung')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Status Pegawai</label>
                                            <select name="status" class="form-control @error('status') is-invalid @enderror">
                                                <option value="">-- Pilih --</option>
                                                <option value="Aktif" {{ old('status', $user->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                                <option value="Nonaktif" {{ old('status', $user->status) == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                            </select>
                                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Divisi</label>
                                            <select name="divisi_id" class="form-control">
                                                <option value="">-- Pilih Divisi --</option>
                                                @foreach($divisi as $d)
                                                    <option value="{{ $d->id }}" {{ old('divisi_id', $user->divisi_id) == $d->id ? 'selected' : '' }}>
                                                        {{ $d->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Jabatan</label>
                                            <select name="jabatan_id" class="form-control">
                                                <option value="">-- Pilih Jabatan --</option>
                                                @foreach($jabatan as $j)
                                                    <option value="{{ $j->id }}" {{ old('jabatan_id', $user->jabatan_id) == $j->id ? 'selected' : '' }}>
                                                        {{ $j->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>

                                    <!-- KANAN (FOTO) -->
                                    <div class="col-md-4 text-center">
                                        <label>Foto Profil</label>
                                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="form-control">

                                        <div style="width: 300px; height: 300px; margin-top: 10px; border: 2px dashed #ccc;">
                                            <img id="preview" src="{{ $user->profile_picture ? asset('uploads/profile/' . $user->profile_picture) : 'https://via.placeholder.com/300' }}" class="img-fluid rounded">
                                        </div>
                                        <input type="hidden" name="cropped_image" id="cropped_image">
                                    </div>

                                </div>

                                <button type="submit" class="btn btn-warning mt-4"><i class="fas fa-save"></i> Simpan Perubahan</button>
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
        const img = document.getElementById('preview');
        const input = document.getElementById('profile_picture');

        input.addEventListener('change', e => {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = () => {
                img.src = reader.result;

                if (cropper) cropper.destroy();
                cropper = new Cropper(img, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 1,
                });
            };
            reader.readAsDataURL(file);
        });
    </script>

</body>
</html>
