(() => {
    'use strict';
    const input = document.getElementById('profileInput');
    if (!input) return;
    const preview = document.getElementById('previewImage');
    const image = document.getElementById('cropImage');
    const error = document.getElementById('profilePhotoError');
    const status = document.getElementById('profilePhotoStatus');
    const modal = window.jQuery('#cropModal');
    let cropper, accepted = false, previousFiles = input.files;
    const editTab = () => window.jQuery('#profile-edit-tab').tab('show');
    document.getElementById('chooseProfilePhoto').addEventListener('click', () => { editTab(); input.click(); });
    input.addEventListener('change', () => {
        const file = input.files[0];
        if (!file) return;
        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type) || file.size > 2048 * 1024) {
            error.textContent = 'Pilih foto JPG, PNG, atau WebP dengan ukuran maksimal 2 MB.'; error.hidden = false; input.files = previousFiles; return;
        }
        error.hidden = true; accepted = false;
        const reader = new FileReader();
        reader.onload = () => {
            image.src = reader.result;
            if (typeof window.Cropper === 'function') modal.modal('show');
            else { preview.src = reader.result; previousFiles = input.files; status.textContent = 'Foto dipilih. Klik Simpan Perubahan untuk menyimpan.'; }
        };
        reader.readAsDataURL(file);
    });
    modal.on('shown.bs.modal', () => { cropper?.destroy(); cropper = new window.Cropper(image, {aspectRatio: 1, viewMode: 1, autoCropArea: .9}); });
    modal.on('hidden.bs.modal', () => { cropper?.destroy(); cropper = null; if (!accepted) input.files = previousFiles; });
    document.getElementById('cropBtn').addEventListener('click', () => {
        if (!cropper) return;
        const canvas = cropper.getCroppedCanvas({width: 400, height: 400, imageSmoothingEnabled: true, imageSmoothingQuality: 'high'});
        canvas.toBlob(blob => {
            if (!blob) return;
            const transfer = new DataTransfer();
            transfer.items.add(new File([blob], 'profile.jpg', {type: 'image/jpeg', lastModified: Date.now()}));
            input.files = transfer.files; previousFiles = input.files; accepted = true;
            preview.src = canvas.toDataURL('image/jpeg'); status.textContent = 'Foto siap disimpan. Klik Simpan Perubahan.';
            editTab(); modal.modal('hide');
        }, 'image/jpeg', .9);
    });
})();
