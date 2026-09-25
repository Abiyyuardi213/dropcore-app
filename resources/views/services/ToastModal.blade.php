<!-- SweetAlert2 Toast Notification -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Legacy Support: Hidden Bootstrap Toast Structure for AJAX calls -->
<div aria-live="polite" aria-atomic="true"
    style="position: absolute; opacity: 0; z-index: -1; height: 0; overflow: hidden;">
    <div id="toastNotification" class="toast">
        <div class="toast-body"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Define Global Toast Mixin
        window.Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // 1. Session Flash Messages (PHP)
        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: {!! json_encode(session('success')) !!}
            });
        @endif

        @if (session('error'))
            Toast.fire({
                icon: 'error',
                title: {!! json_encode(session('error')) !!}
            });
        @endif

        @if (session('warning'))
            Toast.fire({
                icon: 'warning',
                title: {!! json_encode(session('warning')) !!}
            });
        @endif

        @if (session('info'))
            Toast.fire({
                icon: 'info',
                title: {!! json_encode(session('info')) !!}
            });
        @endif

        @if ($errors->any())
            Toast.fire({
                icon: 'error',
                title: {!! json_encode($errors->first()) !!}
            });
        @endif

        // 2. Legacy AJAX Support (jQuery)
        // Intercept Bootstrap toast show event to trigger SweetAlert
        if (typeof $ !== 'undefined') {
            $('#toastNotification').on('show.bs.toast', function() {
                var message = $(this).find('.toast-body').text();
                // Simple heuristic to guess type if not explicit
                var icon = 'success';
                if (message.toLowerCase().includes('gagal') || message.toLowerCase().includes(
                        'error')) {
                    icon = 'error';
                }

                Toast.fire({
                    icon: icon,
                    title: message
                });
            });
        }
    });

    //perbaiki toast modal
</script>
