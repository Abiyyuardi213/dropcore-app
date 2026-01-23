<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profil - Garuda Fiber</title>

    <!-- Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background font-sans text-foreground antialiased min-h-screen flex flex-col">
    @include('include.navbar-client')

    <main class="flex-1">
        <!-- Header -->
        <div class="border-b bg-muted/30">
            <div class="container mx-auto px-4 py-12 md:py-16">
                <h1 class="text-3xl font-bold tracking-tight">Pengaturan Profil</h1>
                <p class="text-muted-foreground mt-2">Kelola informasi pribadi dan keamanan akun Anda.</p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Left Sidebar: Profile Picture -->
                <div class="lg:col-span-4">
                    <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
                        <div class="p-6 text-center space-y-6">
                            <h3 class="font-semibold text-lg">Foto Profil</h3>

                            <div class="relative inline-block group">
                                <div
                                    class="h-40 w-40 rounded-full border-4 border-muted overflow-hidden bg-muted group-hover:opacity-90 transition-opacity">
                                    @if ($user->profile_picture)
                                        <img id="profilePreview"
                                            src="{{ asset('uploads/profile/' . $user->profile_picture) }}"
                                            class="h-full w-full object-cover" alt="Foto Profil">
                                    @else
                                        <div
                                            class="h-full w-full flex items-center justify-center text-muted-foreground">
                                            <i class="bi bi-person text-6xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" onclick="document.getElementById('profileInputFile').click()"
                                    class="absolute bottom-0 right-0 h-10 w-10 bg-primary text-primary-foreground rounded-full flex items-center justify-center shadow-lg border-2 border-background hover:scale-110 transition-transform">
                                    <i class="bi bi-camera-fill"></i>
                                </button>
                            </div>

                            <div class="space-y-2">
                                <p class="text-xs text-muted-foreground leading-relaxed px-4">
                                    Format JPG atau PNG. Maksimum 5MB.
                                </p>
                            </div>

                            <input type="file" id="profileInputFile" name="profile_picture_hidden"
                                style="display: none;" accept="image/*">
                        </div>
                    </div>
                </div>

                <!-- Right Side: Account Info Form -->
                <div class="lg:col-span-8">
                    <div class="rounded-xl border bg-card text-card-foreground shadow-sm overflow-hidden">
                        <div class="border-b p-6">
                            <h3 class="font-semibold text-xl">Informasi Akun</h3>
                            <p class="text-sm text-muted-foreground">Detail profil Anda akan muncul di seluruh platform.
                            </p>
                        </div>
                        <div class="p-6">
                            @if (session('success'))
                                <div
                                    class="mb-6 rounded-lg border border-green-500/50 bg-green-500/10 p-4 text-sm text-green-600 flex items-center gap-3">
                                    <i class="bi bi-check-circle text-base"></i>
                                    <p>{{ session('success') }}</p>
                                </div>
                            @endif

                            <form action="{{ route('distributor.profil.update') }}" method="POST" class="space-y-8">
                                @csrf
                                <input type="hidden" name="cropped_image" id="croppedImage">

                                <!-- Basic Info -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium leading-none" for="name">Nama
                                            Lengkap</label>
                                        <input type="text" name="name" id="name"
                                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                            value="{{ old('name', $user->name) }}" required>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium leading-none" for="username">Username</label>
                                        <input type="text" name="username" id="username"
                                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                            value="{{ old('username', $user->username) }}" required>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium leading-none" for="email">Alamat
                                            Email</label>
                                        <input type="email" name="email" id="email"
                                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                            value="{{ old('email', $user->email) }}">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium leading-none" for="no_telepon">No.
                                            Telepon</label>
                                        <input type="text" name="no_telepon" id="no_telepon"
                                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                            value="{{ old('no_telepon', $user->no_telepon) }}">
                                    </div>

                                    <div class="space-y-2 md:col-span-2">
                                        <label class="text-sm font-medium leading-none" for="alamat">Alamat
                                            Lengkap</label>
                                        <textarea name="alamat" id="alamat" rows="3"
                                            class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">{{ old('alamat', $user->alamat) }}</textarea>
                                        <p class="text-[0.8rem] text-muted-foreground">Alamat ini akan digunakan sebagai
                                            alamat pengiriman default.</p>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm font-medium leading-none" for="nik">NIK / No.
                                            KTP</label>
                                        <input type="text" name="nik" id="nik"
                                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                            value="{{ old('nik', $user->nik) }}"
                                            placeholder="Nomor Induk Kependudukan">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm font-medium leading-none" for="npwp">NPWP
                                            (Opsional)</label>
                                        <input type="text" name="npwp" id="npwp"
                                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                            value="{{ old('npwp', $user->npwp) }}"
                                            placeholder="Nomor Pokok Wajib Pajak">
                                    </div>
                                </div>

                                <div class="border-t pt-8">
                                    <h3 class="font-semibold text-lg mb-6">Keamanan Akun</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium leading-none" for="password">Password
                                                Baru</label>
                                            <input type="password" name="password" id="password"
                                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                                placeholder="Biarkan kosong jika tetap">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium leading-none"
                                                for="password_confirmation">Konfirmasi Password</label>
                                            <input type="password" name="password_confirmation"
                                                id="password_confirmation"
                                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end pt-4">
                                    <button type="submit"
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-8">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Crop Modal Styled as Drawer/Centered Shadcn Modal -->
    <div id="cropDialog"
        class="fixed inset-0 z-[100] hidden items-center justify-center bg-background/80 backdrop-blur-sm animate-in fade-in duration-200">
        <div
            class="bg-card w-full max-w-sm rounded-xl border shadow-lg overflow-hidden animate-in zoom-in-95 duration-200">
            <div class="p-4 border-b">
                <h3 class="font-semibold">Sesuaikan Foto</h3>
                <p class="text-xs text-muted-foreground">Atur posisi foto profil Anda.</p>
            </div>
            <div class="p-4">
                <div class="aspect-square bg-muted rounded-md overflow-hidden flex items-center justify-center">
                    <img id="cropImage" class="max-w-full block">
                </div>
            </div>
            <div class="p-4 flex gap-2 justify-end border-t bg-muted/30">
                <button type="button" onclick="closeCrop()"
                    class="h-9 px-4 rounded-md border border-input bg-background text-sm font-medium hover:bg-accent hover:text-accent-foreground">Batal</button>
                <button type="button" id="cropBtn"
                    class="h-9 px-4 rounded-md bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90">Simpan
                    Foto</button>
            </div>
        </div>
    </div>

    @include('include.footer-client')

    <script>
        let cropper;
        const cropDialog = document.getElementById('cropDialog');

        function closeCrop() {
            if (cropper) cropper.destroy();
            cropDialog.classList.add('hidden');
            cropDialog.classList.remove('flex');
        }

        document.getElementById('profileInputFile').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('cropImage').src = e.target.result;
                cropDialog.classList.remove('hidden');
                cropDialog.classList.add('flex');

                if (cropper) cropper.destroy();
                cropper = new Cropper(document.getElementById('cropImage'), {
                    aspectRatio: 1,
                    viewMode: 1,
                    movable: true,
                    zoomable: true,
                });
            };
            reader.readAsDataURL(file);
        });

        document.getElementById('cropBtn').addEventListener('click', function() {
            if (!cropper) return;
            const canvas = cropper.getCroppedCanvas({
                width: 400,
                height: 400
            });
            const dataUrl = canvas.toDataURL();

            document.getElementById('profilePreview').src = dataUrl;
            document.getElementById('croppedImage').value = dataUrl;

            closeCrop();
        });
    </script>
</body>

</html>
