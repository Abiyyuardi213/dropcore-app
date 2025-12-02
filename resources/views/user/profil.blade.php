<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif !important;
            background-color: #f4f6f9;
        }
        .profile-img {
            width: 150px; /* Sedikit dikecilkan */
            height: 150px;
            object-fit: cover;
            border-radius: 50%; /* Dibuat bulat */
            border: 5px solid #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            cursor: pointer; /* Menandakan bisa diklik */
            transition: transform 0.3s ease;
        }
        .profile-img:hover {
            transform: scale(1.03);
        }
        #profileInput {
            display: none; /* Sembunyikan input file standar */
        }
        .card-custom {
            border-radius: 12px !important;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1); /* Shadow yang lebih dalam */
        }
        .card-header-custom {
            padding: 20px !important;
            background: linear-gradient(to right, #007bff, #0056b3); /* Gradien modern */
            color: white;
            border-bottom: none;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .form-group label {
            font-weight: 600;
            color: #555;
        }
        .form-control {
            border-radius: 8px;
            box-shadow: none !important;
        }
        .modal-content {
            border-radius: 10px;
        }
        /* Style untuk area kiri */
        .profile-sidebar {
            padding: 30px;
            border-right: 1px solid #eee;
            background-color: #fcfcfc;
            text-align: center;
        }
        @media (max-width: 767px) {
            .profile-sidebar {
                border-right: none;
                border-bottom: 1px solid #eee;
            }
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    @include('include.navbarSistem')
    @include('include.sidebar')

    <div class="content-wrapper">
        <div class="content-header pb-1">
            <div class="container-fluid">
                <h4 class="m-0 text-dark font-weight-bold">
                    <i class="fas fa-user-cog mr-2"></i>Edit Profil
                </h4>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid d-flex justify-content-center">
                <div class="card shadow card-custom" style="max-width: 1000px; width:100%;">
                    <div class="card-header card-header-custom text-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-user-edit mr-2"></i>Perbarui Informasi Profil Pengguna
                        </h3>
                    </div>

                    <form action="{{ route('user.profil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body p-0">
                            <div class="row m-0">
                                <div class="col-md-4 profile-sidebar">
                                    <h5 class="mb-4 text-center text-muted font-weight-bold">FOTO PROFIL</h5>
                                    <div class="profile-box">
                                        <img src="{{ Auth::user()->profile_picture
                                            ? asset('uploads/profile/' . Auth::user()->profile_picture)
                                            : asset('image/default-avatar.png') }}"
                                            id="previewImage"
                                            class="profile-img mb-3"
                                            onclick="document.getElementById('profileInput').click();">

                                        <input type="file" name="profile_picture" id="profileInput" accept="image/*">

                                        <p class="text-info font-weight-bold mt-2">
                                            {{ Auth::user()->name }}
                                        </p>
                                        <small class="text-muted d-block">*Klik pada foto untuk mengganti</small>

                                        <div class="mt-4 pt-2 border-top">
                                            <div class="form-group mb-0 text-left">
                                                <label class="mb-1"><i class="fas fa-id-badge mr-2"></i>NIP Pegawai</label>
                                                <p class="form-control-static font-weight-bold">{{ Auth::user()->nip }}</p>
                                            </div>
                                            <div class="form-group mb-0 text-left">
                                                <label class="mb-1"><i class="fas fa-user-tag mr-2"></i>Peran</label>
                                                <p class="form-control-static text-primary font-weight-bold">{{ Auth::user()->role->role_name ?? '-' }}</p>
                                            </div>
                                            <div class="form-group mb-0 text-left">
                                                <label class="mb-1"><i class="fas fa-briefcase mr-2"></i>Divisi/Jabatan</label>
                                                <p class="form-control-static">{{ Auth::user()->divisi->name ?? '-' }} / {{ Auth::user()->jabatan->name ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8 p-4">
                                    <h5 class="mb-4 text-center text-muted font-weight-bold">INFORMASI AKUN & PRIBADI</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name"><i class="fas fa-user mr-2"></i>Nama Lengkap <span class="text-danger">*</span></label>
                                                <input type="text" name="name" id="name" class="form-control"
                                                    value="{{ Auth::user()->name }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="username"><i class="fas fa-at mr-2"></i>Username <span class="text-danger">*</span></label>
                                                <input type="text" name="username" id="username" class="form-control"
                                                    value="{{ Auth::user()->username }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email"><i class="fas fa-envelope mr-2"></i>Email</label>
                                                <input type="email" name="email" id="email" class="form-control"
                                                    value="{{ Auth::user()->email }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="password"><i class="fas fa-lock mr-2"></i>Password (Kosongkan jika tidak diubah)</label>
                                                <input type="password" name="password" id="password" class="form-control"
                                                    placeholder="********">
                                            </div>
                                            <div class="form-group">
                                                <label for="no_telepon"><i class="fas fa-phone-alt mr-2"></i>No. Telepon</label>
                                                <input type="text" name="no_telepon" id="no_telepon" class="form-control"
                                                    value="{{ Auth::user()->no_telepon }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tempat_lahir"><i class="fas fa-map-marker-alt mr-2"></i>Tempat Lahir</label>
                                                <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control"
                                                    value="{{ Auth::user()->tempat_lahir }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="tanggal_lahir"><i class="fas fa-calendar-alt mr-2"></i>Tanggal Lahir</label>
                                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control"
                                                    value="{{ Auth::user()->tanggal_lahir }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="jenis_kelamin"><i class="fas fa-venus-mars mr-2"></i>Jenis Kelamin</label>
                                                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                                                    <option value="">- pilih -</option>
                                                    <option value="L" {{ Auth::user()->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                    <option value="P" {{ Auth::user()->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="status_kepegawaian"><i class="fas fa-handshake mr-2"></i>Status Kepegawaian</label>
                                                <select name="status_kepegawaian" id="status_kepegawaian" class="form-control">
                                                    <option value="">- pilih -</option>
                                                    <option value="aktif" {{ Auth::user()->status_kepegawaian == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                    <option value="nonaktif" {{ Auth::user()->status_kepegawaian == 'nonaktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="alamat"><i class="fas fa-home mr-2"></i>Alamat</label>
                                                <textarea name="alamat" id="alamat" class="form-control" rows="2">{{ Auth::user()->alamat }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right bg-white border-top">
                            <button type="submit" class="btn btn-success px-4 elevation-2">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    @include('include.footerSistem')
</div>

<div class="modal fade" id="cropModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Crop Foto Profil</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="cropImage">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="cropBtn">
                    <i class="fas fa-crop mr-1"></i> Crop & Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
let cropper;
let selectedFile;

document.getElementById("previewImage").addEventListener("click", function() {
    document.getElementById('profileInput').click();
});

document.getElementById("profileInput").addEventListener("change", function(e) {
    if (e.target.files && e.target.files[0]) {
        selectedFile = e.target.files[0];

        let reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById("cropImage").src = ev.target.result;
            $('#cropModal').modal('show');
        };

        reader.readAsDataURL(selectedFile);
    }
});

$('#cropModal').on('shown.bs.modal', function () {
    if (cropper) {
        cropper.destroy();
    }
    cropper = new Cropper(document.getElementById("cropImage"), {
        aspectRatio: 1,
        viewMode: 1,
    });
});

$('#cropModal').on('hidden.bs.modal', function () {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
});

document.getElementById("cropBtn").addEventListener("click", function () {
    let canvas = cropper.getCroppedCanvas({
        width: 400,
        height: 400
    });

    canvas.toBlob(function(blob) {
        let croppedFile = new File([blob], selectedFile.name, { type: "image/jpeg" });

        let dt = new DataTransfer();
        dt.items.add(croppedFile);
        document.getElementById("profileInput").files = dt.files;

        document.getElementById("previewImage").src = canvas.toDataURL();

        $('#cropModal').modal('hide');
    }, 'image/jpeg', 0.8); // Kualitas 80%
});
</script>

</body>
</html>
