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
        .profile-box {
            text-align: center;
            padding: 20px 0;
        }
        .profile-img {
            width: 170px;
            height: 170px;
            object-fit: cover;
            border-radius: 14px;
            border: 4px solid #fff;
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        }
        #profileInput {
            max-width: 260px;
            margin-left: auto;
            margin-right: auto;
        }
        .card-custom {
            border-radius: 14px !important;
            overflow: hidden;
        }
        .card-header-custom {
            padding: 18px !important;
            background: #007bff;
            color: white;
        }
        #cropImage {
            width: 100%;
            max-height: 450px;
            display: block;
        }
        .modal-content {
            border-radius: 10px;
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
                <div class="card shadow card-custom" style="max-width: 900px; width:100%;">
                    <div class="card-header card-header-custom text-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-user-edit mr-2"></i>Perbarui Informasi Profil
                        </h3>
                    </div>

                    <form action="{{ route('user.profil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="profile-box">
                                <img src="{{ Auth::user()->profile_picture
                                    ? asset('uploads/profile/' . Auth::user()->profile_picture)
                                    : asset('image/default-avatar.png') }}"
                                     id="previewImage"
                                     class="profile-img mb-3">
                                <input type="file" name="profile_picture" class="form-control" id="profileInput">
                                <small class="text-muted d-block mt-1">*Klik foto untuk mengubah</small>
                            </div>
                            <hr>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control"
                                               value="{{ Auth::user()->name }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" name="username" class="form-control"
                                               value="{{ Auth::user()->username }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control"
                                               value="{{ Auth::user()->email }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>No. Telepon</label>
                                        <input type="text" name="no_telepon" class="form-control"
                                               value="{{ Auth::user()->no_telepon }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Password (opsional)</label>
                                        <input type="password" name="password" class="form-control"
                                               placeholder="********">
                                    </div>
                                    <div class="form-group">
                                        <label>Peran</label>
                                        <input type="text" class="form-control"
                                               value="{{ Auth::user()->role->role_name ?? '-' }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right bg-white">
                            <button type="submit" class="btn btn-success px-4">
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
            <div class="modal-header">
                <h5 class="modal-title">Crop Foto Profil</h5>
                <button type="button" class="close" data-dismiss="modal">
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
    });
});
</script>

</body>
</html>
