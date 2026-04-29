<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Plugins -->
<script src="https://cdn.jsdelivr.net/npm/dropify/dist/js/dropify.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Bootstrap -->
<script src="{{ asset('Backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Other libs -->
<script src="{{ asset('Backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('Backend/assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('Backend/assets/libs/feather-icons/feather.min.js') }}"></script>

<!-- App -->
<script src="{{ asset('Backend/assets/js/plugins.js') }}"></script>
<script src="{{ asset('Backend/assets/js/app.js') }}"></script>
<script src="{{ asset('Backend/assets/js/layout.js') }}"></script>

<!-- SweetAlert2 (ONLY ONCE) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {

        /* ===============================
           DROPIFY
        =============================== */
        if ($.fn.dropify) {
            const dr = $('.dropify').dropify();

            dr.on('dropify.afterClear', function(e, el) {
                let flag = $(el.element).closest('.form-field-wrapper').find('[data-remove-flag]');
                if (flag.length) flag.val(1);
            });

            dr.on('change', function() {
                let flag = $(this).closest('.form-field-wrapper').find('[data-remove-flag]');
                if (flag.length) flag.val(0);
            });
        }

        /* ===============================
           FEATHER ICON
        =============================== */
        if (typeof feather !== "undefined") {
            feather.replace({
                width: 14,
                height: 14
            });
        }

    });
</script>

<!-- ===============================
    SIDEBAR TOGGLE
================================ -->
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const html = document.documentElement;
        const btn = document.getElementById("topnav-hamburger-icon");
        const icon = document.querySelector(".hamburger-icon");

        const getSize = () => {
            let size = html.getAttribute("data-sidebar-size") || "lg";
            return (size === "sm-hover" || size === "sm-hover-active") ? "sm" : size;
        };

        const setSize = (size) => {
            html.setAttribute("data-sidebar-size", size);
            sessionStorage.setItem("data-sidebar-size", size);

            if (icon) {
                icon.classList.toggle("open", size === "sm");
            }
        };

        if (btn) {
            btn.addEventListener("click", function() {

                let current = getSize();
                let next = (current === "lg") ? "sm" : "lg";

                setSize(next);

            });
        }

        // restore state on load
        let saved = sessionStorage.getItem("data-sidebar-size") || "lg";
        setSize(saved);

    });
</script>


<!-- ===============================
    THEME TOGGLE
================================ -->
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const themeBtn = document.querySelector(".light-dark-mode");

        const updateIcon = (theme) => {
            if (!themeBtn) return;
            const icon = themeBtn.querySelector("i");
            if (!icon) return;

            icon.classList.toggle("bx-sun", theme === "dark");
            icon.classList.toggle("bx-moon", theme !== "dark");
        };

        const initTheme = () => {
            const html = document.documentElement;
            const saved = sessionStorage.getItem("theme") || "light";
            html.setAttribute("data-bs-theme", saved);
            updateIcon(saved);
        };

        if (themeBtn) {
            themeBtn.addEventListener("click", function() {
                const html = document.documentElement;
                const current = html.getAttribute("data-bs-theme") || "light";
                const next = current === "dark" ? "light" : "dark";

                html.setAttribute("data-bs-theme", next);
                sessionStorage.setItem("theme", next);
                updateIcon(next);
            });
        }

        initTheme();
    });
</script>

<!-- ===============================
    CLOCK
================================ -->
<script>
    setInterval(function() {
        let now = new Date();
        let h = now.getHours();
        let m = now.getMinutes();
        let s = now.getSeconds();

        m = m < 10 ? "0" + m : m;
        s = s < 10 ? "0" + s : s;

        let ampm = h < 12 ? "AM" : "PM";
        h = h % 12 || 12;

        let el = document.getElementById("timer");
        if (el) el.innerHTML = `${h}:${m}:${s} ${ampm}`;

    }, 1000);
</script>

<!-- ===============================
    SELECT ALL
================================ -->
<script>
    function select_all() {
        let checked = $('#select_all').is(':checked');
        $('.select_data').prop('checked', checked);
        $('.delete_btn').toggleClass('d-none', !checked);
    }

    function select_single_item() {
        let total = $('.select_data').length;
        let checked = $('.select_data:checked').length;

        $('#select_all').prop('checked', total === checked);
        $('.delete_btn').toggleClass('d-none', checked === 0);
    }
</script>

<!-- ===============================
    SWEET ALERT HELPERS
================================ -->
<script>
    const SwalConfirm = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-primary w-xs me-2",
            cancelButton: "btn btn-danger w-xs"
        },
        buttonsStyling: false
    });

    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    window.showToast = function(type, message) {

        const colors = {
            success: '#10b981',
            error: '#ef4444',
            warning: '#f59e0b',
            info: '#3b82f6'
        };

        Toast.fire({
            icon: type,
            title: message,
            background: '#fff',
            color: '#111',
            didOpen: (toast) => {
                toast.style.borderLeft = `5px solid ${colors[type] || '#3b82f6'}`;
                toast.style.borderRadius = '10px';
                toast.style.boxShadow = '0 10px 25px rgba(0,0,0,0.08)';
            }
        });
    };
</script>

<!-- ===============================
    SESSION TOAST (FIXED)
================================ -->
<script>
    @if (session('success'))
        showToast('success', @json(session('success')));
    @endif

    @if (session('error'))
        showToast('error', @json(session('error')));
    @endif

    @if (session('warning'))
        showToast('warning', @json(session('warning')));
    @endif

    @if (session('info'))
        showToast('info', @json(session('info')));
    @endif
</script>

<!-- ===============================
    AJAX SETUP
================================ -->
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
