<!-- Toast Container di bawah navbar -->
<div aria-live="polite" aria-atomic="true"
    style="position: fixed; top: 70px; right: 20px; z-index: 9999; pointer-events: none;">
    <div id="toastNotification" class="toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true"
        data-bs-delay="3000" data-delay="3000" data-bs-autohide="true" data-autohide="true"
        style="pointer-events: auto; width: 350px; max-width: none;">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check-circle mr-2 me-2"></i>
            <strong class="mr-auto me-auto">Notifikasi</strong>
            <!-- Bootstrap 4 Close -->
            <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close"
                style="display: none;">
                <span aria-hidden="true">&times;</span>
            </button>
            <!-- Bootstrap 5 Close -->
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"
                style="display: none;"></button>
        </div>
        <div class="toast-body">
            @if (session('success'))
                {{ session('success') }}
            @elseif (session('error'))
                {{ session('error') }}
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var toastEl = document.getElementById('toastNotification');
        if (!toastEl) return;

        var toastBody = toastEl.querySelector('.toast-body');
        var hasContent = toastBody && toastBody.textContent.trim().length > 0;

        if (hasContent) {
            // Determine if using Bootstrap 5 or 4
            if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                // Bootstrap 5
                // Show BS5 close button, hide BS4
                if (toastEl.querySelector('.btn-close')) toastEl.querySelector('.btn-close').style.display =
                    'block';

                var toast = new bootstrap.Toast(toastEl);
                toast.show();
            } else if (typeof $ !== 'undefined' && $.fn.toast) {
                // Bootstrap 4 (jQuery)
                // Show BS4 close button, hide BS5
                if (toastEl.querySelector('.close')) toastEl.querySelector('.close').style.display = 'block';

                $(toastEl).toast({
                    delay: 3000
                });
                $(toastEl).toast('show');
            }
        }
    });
</script>
