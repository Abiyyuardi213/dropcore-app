<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil | DropCore</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif !important;
            background-color: #f4f6f9;
        }

        .profile-img {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .profile-img:hover {
            transform: scale(1.05);
            border-color: #007bff;
        }

        #profileInput {
            display: none;
        }

        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .profile-header-bg {
            height: 120px;
            background: linear-gradient(135deg, #007bff, #6610f2);
            position: relative;
        }

        .profile-card-body {
            position: relative;
            padding-top: 0;
            margin-top: -70px;
            text-align: center;
        }

        .left-panel {
            background: #fff;
            border-right: 1px solid #f0f0f0;
        }

        .form-control {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 15px;
            height: auto;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        label {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .input-group-text {
            border-radius: 8px 0 0 8px;
            border: 1px solid #e9ecef;
            background-color: #fff;
            color: #6c757d;
        }

        .input-group>.form-control {
            border-radius: 0 8px 8px 0;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('include.navbarSistem')
        @include('include.sidebar')

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Profil Saya</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Profil</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-10">

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="mb-0 pl-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="card card-custom">
                                <form action="{{ route('user.profil.update') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row no-gutters">
                                        <!-- Left Column: Profile Picture & Core Info -->
                                        <div class="col-md-4 left-panel">
                                            <div class="profile-header-bg"></div>
                                            <div class="profile-card-body p-4">
                                                <div class="avatar-container mb-3">
                                                    <img src="{{ Auth::user()->profile_picture ? asset('uploads/profile/' . Auth::user()->profile_picture) : asset('image/default-avatar.png') }}"
                                                        id="previewImage" class="profile-img" alt="User Image"
                                                        title="Klik untuk mengubah foto profil">
                                                    <div class="mt-2">
                                                        <span class="badge badge-secondary" style="cursor: pointer"
                                                            onclick="document.getElementById('profileInput').click();">
                                                            <i class="fas fa-camera"></i> Ubah Foto
                                                        </span>
                                                    </div>
                                                </div>

                                                <input type="file" name="profile_picture" id="profileInput"
                                                    accept="image/*">

                                                <h5 class="font-weight-bold text-dark mb-0">{{ Auth::user()->name }}
                                                </h5>
                                                <p class="text-muted small mb-3">
                                                    {{ Auth::user()->role->role_name ?? 'User' }}</p>

                                                <div class="text-left mt-4 px-2">
                                                    <div class="mb-3">
                                                        <small class="text-uppercase text-muted font-weight-bold"
                                                            style="font-size: 10px; letter-spacing: 1px;">INFORMASI
                                                            PEGAWAI</small>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-muted small">NIP</span>
                                                        <span
                                                            class="font-weight-bold small text-dark">{{ Auth::user()->nip ?? '-' }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-muted small">Divisi</span>
                                                        <span
                                                            class="font-weight-bold small text-dark">{{ Auth::user()->divisi->name ?? '-' }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-muted small">Jabatan</span>
                                                        <span
                                                            class="font-weight-bold small text-dark">{{ Auth::user()->jabatan->name ?? '-' }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-muted small">Gabung Sejak</span>
                                                        <span class="font-weight-bold small text-dark">
                                                            {{ Auth::user()->tanggal_bergabung ? \Carbon\Carbon::parse(Auth::user()->tanggal_bergabung)->format('d M Y') : '-' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Column: Edit Form -->
                                        <div class="col-md-8 bg-white">
                                            <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                                                <h5 class="card-title text-primary"><i class="fas fa-edit mr-2"></i>
                                                    Edit Informasi Pribadi</h5>
                                            </div>
                                            <div class="card-body px-4 pb-4">

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="name">Nama Lengkap</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-user"></i></span>
                                                            </div>
                                                            <input type="text" name="name" id="name"
                                                                class="form-control" value="{{ Auth::user()->name }}"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="username">Username</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-at"></i></span>
                                                            </div>
                                                            <input type="text" name="username" id="username"
                                                                class="form-control"
                                                                value="{{ Auth::user()->username }}" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="email">Alamat Email</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-envelope"></i></span>
                                                            </div>
                                                            <input type="email" name="email" id="email"
                                                                class="form-control"
                                                                value="{{ Auth::user()->email }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="no_telepon">No. Telepon / WA</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-phone"></i></span>
                                                            </div>
                                                            <input type="text" name="no_telepon" id="no_telepon"
                                                                class="form-control"
                                                                value="{{ Auth::user()->no_telepon }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="jenis_kelamin">Jenis Kelamin</label>
                                                        <select name="jenis_kelamin" id="jenis_kelamin"
                                                            class="form-control">
                                                            <option value="">- Pilih Jenis Kelamin -</option>
                                                            <option value="L"
                                                                {{ Auth::user()->jenis_kelamin == 'L' ? 'selected' : '' }}>
                                                                Laki-laki</option>
                                                            <option value="P"
                                                                {{ Auth::user()->jenis_kelamin == 'P' ? 'selected' : '' }}>
                                                                Perempuan</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="alamat">Alamat Lengkap</label>
                                                    <textarea name="alamat" id="alamat" class="form-control" rows="3"
                                                        placeholder="Masukkan alamat lengkap...">{{ Auth::user()->alamat }}</textarea>
                                                </div>

                                                <hr class="my-4">

                                                <h6 class="text-danger mb-3"><i class="fas fa-lock mr-2"></i> Ganti
                                                    Password</h6>
                                                <div class="form-group">
                                                    <label for="password"
                                                        class="text-muted font-weight-normal small">Biarkan kosong jika
                                                        tidak ingin mengubah password.</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-key"></i></span>
                                                        </div>
                                                        <input type="password" name="password" id="password"
                                                            class="form-control" placeholder="Password Baru">
                                                    </div>
                                                </div>

                                                <div class="text-right mt-4">
                                                    <button type="submit" class="btn btn-primary px-5 py-2">
                                                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @include('include.footerSistem')
    </div>

    <!-- Modal Crop -->
    <div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-hidden="true"
        data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-crop-alt mr-2"></i> Sesuaikan Foto</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0 text-center bg-dark">
                    <div style="max-height: 400px; display: flex; justify-content: center; align-items: center;">
                        <img id="cropImage" style="max-width: 100%; max-height: 400px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="cropBtn">
                        <i class="fas fa-check mr-1"></i> Potong & Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            let cropper;
            let selectedFile;
            const profileInput = document.getElementById('profileInput');
            const previewImage = document.getElementById('previewImage');
            const cropImage = document.getElementById('cropImage');
            const cropModal = $('#cropModal');

            profileInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    selectedFile = e.target.files[0];
                    const reader = new FileReader();

                    reader.onload = function(ev) {
                        cropImage.src = ev.target.result;
                        cropModal.modal('show');
                    }

                    reader.readAsDataURL(selectedFile);
                }
            });

            cropModal.on('shown.bs.modal', function() {
                if (cropper) cropper.destroy();
                cropper = new Cropper(cropImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 0.8,
                });
            });

            cropModal.on('hidden.bs.modal', function() {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                // Reset input if cancelled so same file can be selected again
                if (!selectedFile) profileInput.value = '';
            });

            document.getElementById('cropBtn').addEventListener('click', function() {
                if (!cropper) return;

                const canvas = cropper.getCroppedCanvas({
                    width: 400,
                    height: 400,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });

                canvas.toBlob(function(blob) {
                    // Create a new file from the blob
                    const croppedFile = new File([blob], selectedFile.name, {
                        type: "image/jpeg",
                        lastModified: new Date().getTime()
                    });

                    // Create a DataTransfer to update the file input
                    const dt = new DataTransfer();
                    dt.items.add(croppedFile);
                    profileInput.files = dt.files;

                    // Update preview
                    previewImage.src = canvas.toDataURL('image/jpeg');

                    cropModal.modal('hide');
                }, 'image/jpeg', 0.9);
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
</body>

</html>
