    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />

    @yield('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropify/dist/css/dropify.min.css">
    <link rel="icon" href="{{ $setting?->favicon ? asset($setting->favicon) : '' }}">

    <!-- CSS Libraries -->
    <link href="{{ asset('Backend/assets/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('Backend/assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" />

    <!-- Core CSS -->
    <link href="{{ asset('Backend/assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('Backend/assets/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('Backend/assets/css/app.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('Backend/assets/css/custom.min.css') }}" rel="stylesheet" />

    <style>
        .dropify-wrapper .dropify-preview .dropify-render img {
            width: 100%;
            height: auto;
            max-height: 220px;
            object-fit: contain;
        }

        .dropify-wrapper {
            border-radius: 10px;
            border: 1px dashed rgba(0, 0, 0, 0.15);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .dropify-wrapper:hover {
            border-color: rgba(0, 0, 0, 0.35);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        .dropify-wrapper .dropify-clear {
            border-radius: 999px;
        }

        @media (min-width:768px) {

            :is([data-layout=vertical], [data-layout=semibox])[data-sidebar-size=sm-hover] .topnav-hamburger,
            :is([data-layout=vertical], [data-layout=semibox])[data-sidebar-size=sm-hover-active] .topnav-hamburger {
                display: inline-flex !important;
            }

            :is([data-layout=vertical], [data-layout=semibox])[data-sidebar-size=sm-hover] .navbar-brand-box .logo-lg,
            :is([data-layout=vertical], [data-layout=semibox])[data-sidebar-size=sm-hover-active] .navbar-brand-box .logo-lg {
                display: none !important;
            }

            :is([data-layout=vertical], [data-layout=semibox])[data-sidebar-size=sm-hover] .navbar-brand-box .logo-sm,
            :is([data-layout=vertical], [data-layout=semibox])[data-sidebar-size=sm-hover-active] .navbar-brand-box .logo-sm {
                display: inline-block !important;
            }
        }
    </style>

    <script>
        const sidebarSize = sessionStorage.getItem('data-sidebar-size') || document.documentElement.getAttribute(
            'data-sidebar-size');
        if (sidebarSize === 'sm-hover' || sidebarSize === 'sm-hover-active') {
            document.documentElement.setAttribute('data-sidebar-size', 'sm');
            sessionStorage.setItem('data-sidebar-size', 'sm');
        }
    </script>

    <!-- Layout JS -->
    {{-- <script src="{{ asset('Backend/assets/js/layout.js') }}"></script> --}}
