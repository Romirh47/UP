<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HRtrack</title>
    <link rel="shortcut icon" type="image/png" href="/assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="/assets/css/styles.min.css" />

    <style>
        /* Background Body */
        body {
            background-color: #153448; /* Warna latar belakang utama */
            color: #DFD0B8; /* Warna teks */
        }

        /* Button Primary */
        .btn-primary {
            --bs-btn-color: #DFD0B8 !important; /* Warna teks tombol */
            --bs-btn-bg: #3C5B6F !important; /* Warna latar belakang tombol */
            --bs-btn-border-color: #3C5B6F !important; /* Warna tepi tombol */
            --bs-btn-hover-color: #DFD0B8 !important; /* Warna teks saat hover */
            --bs-btn-hover-bg: #948979 !important; /* Warna latar belakang saat hover */
            --bs-btn-hover-border-color: #948979 !important; /* Warna tepi saat hover */
            --bs-btn-focus-shadow-rgb: 122, 116, 255 !important; /* Bayangan fokus */
            --bs-btn-active-color: #DFD0B8 !important; /* Warna teks saat aktif */
            --bs-btn-active-bg: #948979 !important; /* Warna latar belakang saat aktif */
            --bs-btn-active-border-color: #948979 !important; /* Warna tepi saat aktif */
            --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125) !important; /* Bayangan saat aktif */
            --bs-btn-disabled-color: #DFD0B8 !important; /* Warna teks saat dinonaktifkan */
            --bs-btn-disabled-bg: #3C5B6F !important; /* Warna latar belakang saat dinonaktifkan */
            --bs-btn-disabled-border-color: #3C5B6F !important; /* Warna tepi saat dinonaktifkan */
        }

        /* Sidebar Navigation */
        .sidebar-nav ul .sidebar-item.selected > .sidebar-link,
        .sidebar-nav ul .sidebar-item.selected > .sidebar-link.active,
        .sidebar-nav ul .sidebar-item > .sidebar-link.active {
            background-color: #3C5B6F; /* Warna latar belakang item sidebar yang dipilih */
            color: #DFD0B8; /* Warna teks item sidebar yang dipilih */
            -webkit-box-shadow: 0px 17px 20px -8px rgba(77, 91, 236, 0.231372549); /* Bayangan */
            box-shadow: 0px 17px 20px -8px rgba(77, 91, 236, 0.231372549); /* Bayangan */
        }

        /* Modal Content */
        .modal-content,
        .swal2-modal {
            background-color: #2554ef; /* Warna latar belakang konten modal */
            color: #f7fcff; /* Warna teks konten modal */
        }

        /* Modal Content - Titles, Labels, Inputs, etc. */
        .modal-content .modal-title,
        .modal-content .modal-body .form-label,
        .modal-content .modal-body .form-control,
        .swal2-modal .swal2-title,
        .swal2-modal .swal2-html-container {
            color: #153448; /* Warna teks untuk judul, label, dan input di dalam modal */
        }

        /* Placeholder Text */
        .modal-content .modal-body .form-control::placeholder {
            color: #bbbbbb; /* Warna teks placeholder di dalam input */
        }
    </style>

</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div
            class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="/" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="/assets/images/logos/logo.svg" alt="">
                                </a>
                                <p class="text-center">IOT PANEL</p>
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- solar icons -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    @stack('script')
</body>

</html>
