<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Pengguna</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
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
                            <h1 class="m-0">Ubah Data Pengguna</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Data Pengguna</a></li>
                                <li class="breadcrumb-item active">Ubah</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">

                    <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Left Column: Personal & Account Info -->
                            <div class="col-md-8">
                                <div class="card card-warning card-outline">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-user-edit mr-1"></i> Informasi Pengguna
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        @if (session('error'))
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{ session('error') }}
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>NIP <small class="text-muted">(Tidak dapat
                                                            diubah)</small></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-id-badge"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control"
                                                            value="{{ $user->nip }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>NIK</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-id-card"></i></span>
                                                        </div>
                                                        <input type="text"
                                                            class="form-control @error('nik') is-invalid @enderror"
                                                            name="nik" value="{{ old('nik', $user->nik) }}"
                                                            placeholder="Nomor Induk Kependudukan">
                                                        @error('nik')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            name="name" value="{{ old('name', $user->name) }}"
                                                            required>
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Username <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-at"></i></span>
                                                        </div>
                                                        <input type="text"
                                                            class="form-control @error('username') is-invalid @enderror"
                                                            name="username"
                                                            value="{{ old('username', $user->username) }}" required>
                                                        @error('username')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-envelope"></i></span>
                                                        </div>
                                                        <input type="email"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            name="email" value="{{ old('email', $user->email) }}">
                                                        @error('email')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nomor Telepon</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-phone"></i></span>
                                                        </div>
                                                        <input type="text"
                                                            class="form-control @error('no_telepon') is-invalid @enderror"
                                                            name="no_telepon"
                                                            value="{{ old('no_telepon', $user->no_telepon) }}">
                                                        @error('no_telepon')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Password <small class="text-muted">(Kosongkan jika tidak
                                                            ubah)</small></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-lock"></i></span>
                                                        </div>
                                                        <input type="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            name="password" placeholder="Password baru">
                                                        @error('password')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Jenis Kelamin</label>
                                                    <select name="jenis_kelamin" class="form-control">
                                                        <option value="">-- Pilih --</option>
                                                        <option value="L"
                                                            {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                                            Laki-laki</option>
                                                        <option value="P"
                                                            {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                                            Perempuan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Alamat</label>
                                                    <textarea class="form-control" name="alamat" rows="1" placeholder="Alamat lengkap">{{ old('alamat', $user->alamat) }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Employment & Photo -->
                            <div class="col-md-4">
                                <div class="card card-info card-outline">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-briefcase mr-1"></i> Kepegawaian</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Role <span class="text-danger">*</span></label>
                                            <select name="role_id"
                                                class="form-control @error('role_id') is-invalid @enderror">
                                                <option value="">-- Pilih Role --</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                        {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                                        {{ $role->role_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Divisi</label>
                                            <select name="divisi_id" class="form-control">
                                                <option value="">-- Pilih Divisi --</option>
                                                @foreach ($divisi as $d)
                                                    <option value="{{ $d->id }}"
                                                        {{ old('divisi_id', $user->divisi_id) == $d->id ? 'selected' : '' }}>
                                                        {{ $d->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Jabatan</label>
                                            <select name="jabatan_id" class="form-control">
                                                <option value="">-- Pilih Jabatan --</option>
                                                @foreach ($jabatan as $j)
                                                    <option value="{{ $j->id }}"
                                                        {{ old('jabatan_id', $user->jabatan_id) == $j->id ? 'selected' : '' }}>
                                                        {{ $j->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Tanggal Bergabung</label>
                                            <input type="date"
                                                class="form-control @error('tanggal_bergabung') is-invalid @enderror"
                                                name="tanggal_bergabung"
                                                value="{{ old('tanggal_bergabung', $user->tanggal_bergabung ? $user->tanggal_bergabung->format('Y-m-d') : '') }}">
                                            @error('tanggal_bergabung')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Status Kepegawaian</label>
                                            <select name="status_kepegawaian" class="form-control">
                                                <option value="aktif"
                                                    {{ old('status_kepegawaian', $user->status_kepegawaian) == 'aktif' ? 'selected' : '' }}>
                                                    Aktif</option>
                                                <option value="nonaktif"
                                                    {{ old('status_kepegawaian', $user->status_kepegawaian) == 'nonaktif' ? 'selected' : '' }}>
                                                    Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="card card-warning card-outline">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-camera mr-1"></i> Foto Profil</h3>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="image-preview-container mb-3"
                                            style="width: 100%; height: 250px; background: #f4f6f9; border: 2px dashed #ddd; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 5px;">
                                            <img id="preview"
                                                src="{{ $user->profile_picture ? asset('uploads/profile/' . $user->profile_picture) : asset('image/default-user.png') }}"
                                                alt="Preview"
                                                style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                        </div>
                                        <div class="custom-file text-left">
                                            <input type="file" class="custom-file-input" id="profile_picture"
                                                name="profile_picture" accept="image/*">
                                            <label class="custom-file-label" for="profile_picture">Ganti
                                                foto...</label>
                                        </div>
                                        <input type="hidden" name="cropped_image" id="cropped_image">
                                        @error('profile_picture')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row pb-4">
                            <div class="col-12 text-right">
                                <a href="{{ route('user.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </section>

        </div>

        @include('include.footerSistem')

    </div>

    @include('services.LogoutModal')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>

    <script>
        $(document).ready(function() {
            bsCustomFileInput.init();
        });

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
                    movable: false,
                    scaler: false,
                    zoomable: false,
                    ready() {
                        // Auto crop on load
                        this.cropper.crop();
                    },
                    cropend() {
                        const canvas = cropper.getCroppedCanvas({
                            width: 300,
                            height: 300
                        });
                        canvas.toBlob((blob) => {
                            const reader = new FileReader();
                            reader.onloadend = function() {
                                document.getElementById('cropped_image').value = reader
                                    .result;
                            }
                            reader.readAsDataURL(blob);
                        });
                    }
                });

                // Trigger initial crop
                const canvas = cropper.getCroppedCanvas({
                    width: 300,
                    height: 300
                });
                if (canvas) {
                    canvas.toBlob((blob) => {
                        const reader = new FileReader();
                        reader.onloadend = function() {
                            document.getElementById('cropped_image').value = reader.result;
                        }
                        reader.readAsDataURL(blob);
                    });
                }
            };
            reader.readAsDataURL(file);
        });
    </script>

</body>

</html>
