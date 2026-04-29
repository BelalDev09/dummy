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

<!-- SweetAlert -->
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

            dr.on('change', function(e) {
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

        const setSidebarSize = (size) => {
            html.setAttribute("data-sidebar-size", size);
            sessionStorage.setItem("data-sidebar-size", size);
            if (icon) {
                icon.classList.toggle("open", size === "sm");
            }
        };

        let current = html.getAttribute("data-sidebar-size") || "lg";
        if (current === "sm-hover" || current === "sm-hover-active") {
            current = "sm";
            setSidebarSize(current);
        } else if (icon) {
            icon.classList.toggle("open", current === "sm");
        }

        if (btn) {
            btn.addEventListener("click", function(event) {
                event.stopImmediatePropagation();
                const currentSize = html.getAttribute("data-sidebar-size") || "lg";
                const nextSize = currentSize === "lg" ? "sm" : "lg";
                setSidebarSize(nextSize);
            }, true);
        }
    });
</script>
{{-- light or dark mode --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const themeBtn = document.querySelector(".light-dark-mode");
        const updateThemeIcon = (theme) => {
            if (!themeBtn) return;
            const icon = themeBtn.querySelector("i");
            if (!icon) return;
            if (theme === "dark") {
                icon.classList.remove("bx-moon");
                icon.classList.add("bx-sun");
            } else {
                icon.classList.remove("bx-sun");
                icon.classList.add("bx-moon");
            }
        };

        const initTheme = () => {
            const html = document.documentElement;
            const savedTheme = sessionStorage.getItem("data-bs-theme") || html.getAttribute(
                "data-bs-theme") || "light";
            html.setAttribute("data-bs-theme", savedTheme);
            sessionStorage.setItem("data-bs-theme", savedTheme);
            updateThemeIcon(savedTheme);
        };

        if (themeBtn) {
            themeBtn.addEventListener("click", function() {
                const html = document.documentElement;
                const current = html.getAttribute("data-bs-theme") || "light";
                const next = current === "dark" ? "light" : "dark";
                html.setAttribute("data-bs-theme", next);
                sessionStorage.setItem("data-bs-theme", next);
                updateThemeIcon(next);
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

        let time = h + ":" + m + ":" + s + " " + ampm;

        let el = document.getElementById("timer");
        if (el) el.innerHTML = time;

    }, 1000);
</script>

<!-- ===============================
    SELECT ALL / BULK
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
    SWEET ALERT (VELZON STYLE)
================================ -->
<script>
    const SwalConfirm = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-primary w-xs me-2",
            cancelButton: "btn btn-danger w-xs"
        },
        buttonsStyling: false
    });

    const SwalToast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
</script>

<!-- ===============================
    BULK DELETE
================================ -->
<script>
    function bulk_delete(ids, url, rows, table) {

        SwalConfirm.fire({
            title: "Delete selected items?",
            text: "This action cannot be undone!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete all!",
            cancelButtonText: "Cancel"
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        ids: ids,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        if (response.success) {

                            SwalToast.fire({
                                icon: "success",
                                title: response.message
                            });

                            table.ajax.reload();

                            $('#select_all').prop('checked', false);
                            $('.delete_btn').addClass('d-none');

                        } else {
                            SwalToast.fire({
                                icon: "error",
                                title: response.message
                            });
                        }

                    },
                    error: function() {
                        SwalToast.fire({
                            icon: "error",
                            title: "Something went wrong!"
                        });
                    }
                });

            }
        });
    }
</script>

<!-- ===============================
    SINGLE DELETE
================================ -->
<script>
    function deleteData(url) {

        SwalConfirm.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
            reverseButtons: true
        }).then((result) => {

            if (result.isConfirmed) {
                window.location.href = url;
            }

        });
    }
</script>

<!-- ===============================
    SESSION TOAST
================================ -->
<script>
    @if (session('success'))
        SwalToast.fire({
            icon: "success",
            title: "{{ session('success') }}"
        });
    @endif

    @if (session('error'))
        SwalToast.fire({
            icon: "error",
            title: "{{ session('error') }}"
        });
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
