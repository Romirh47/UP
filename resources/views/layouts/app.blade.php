<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IOT PANEL</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">

                    <img src="../assets/images/logos/logo.svg" alt="" />
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <!-- Body Wrapper -->
                <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
                    data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

                    <!-- Sidebar Start -->
                    <aside class="left-sidebar">
                        <!-- Sidebar scroll -->
                        <div>
                            <!-- Brand Logo -->
                            <div class="brand-logo d-flex align-items-center justify-content-between">


                                </a>
                                <!-- Close Button for Mobile View -->
                                <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer"
                                    id="sidebarCollapse">
                                    <i class="ti ti-x fs-8"></i>
                                </div>
                            </div>

                            <!-- Sidebar navigation -->
                            <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                                <ul id="sidebarnav">
                                    <!-- Home Section -->
                                    <li class="nav-small-cap">
                                        <iconify-icon icon="solar:menu-dots-linear"
                                            class="nav-small-cap-icon fs-4"></iconify-icon>
                                        <span class="hide-menu">Home</span>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
                                            <iconify-icon icon="solar:widget-add-line-duotone"></iconify-icon>
                                            <span class="hide-menu">Dashboard</span>
                                        </a>
                                    </li>
                                    <!-- Divider -->
                                    <li>
                                        <span class="sidebar-divider lg"></span>
                                    </li>
                                    <!-- Sensors Section -->
                                    <li class="nav-small-cap">
                                        <iconify-icon icon="solar:menu-dots-linear"
                                            class="nav-small-cap-icon fs-4"></iconify-icon>
                                        <span class="hide-menu">MANAGEMENT SETTING</span>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('sensors.index') }}"
                                            aria-expanded="false">
                                            <iconify-icon icon="solar:layers-minimalistic-bold-duotone"></iconify-icon>
                                            <span class="hide-menu">LIST SENSORS</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('sensordata.index') }}"
                                            aria-expanded="false">
                                            <iconify-icon icon="solar:layers-minimalistic-bold-duotone"></iconify-icon>
                                            <span class="hide-menu">SENSORS DATA</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('actuators.index') }}"
                                            aria-expanded="false">
                                            <iconify-icon icon="solar:login-3-line-duotone"></iconify-icon>
                                            <span class="hide-menu">AKTUATOR</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('temperatures.index') }}"
                                            aria-expanded="false">
                                            <iconify-icon icon="solar:layers-minimalistic-bold-duotone"></iconify-icon>
                                            <span class="hide-menu">SETTING</span>
                                        </a>
                                    </li>
                                    {{-- <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('humidities.index') }}"
                                            aria-expanded="false">
                                            <iconify-icon icon="solar:danger-circle-line-duotone"></iconify-icon>
                                            <span class="hide-menu">HUMIDITY</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('intensities.index') }}"
                                            aria-expanded="false">
                                            <iconify-icon
                                                icon="solar:bookmark-square-minimalistic-line-duotone"></iconify-icon>
                                            <span class="hide-menu">INTENSITY</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('moistures.index') }}"
                                            aria-expanded="false">
                                            <iconify-icon icon="solar:file-text-line-duotone"></iconify-icon>
                                            <span class="hide-menu">MOISTURES</span>
                                        </a>
                                    </li> --}}
                                    {{-- <!-- Divider -->
                                    <li>
                                        <span class="sidebar-divider lg"></span>
                                    </li>
                                    <!-- Actuator Section -->
                                    <li class="nav-small-cap">
                                        <iconify-icon icon="solar:menu-dots-linear"
                                            class="nav-small-cap-icon fs-4"></iconify-icon>
                                        <span class="hide-menu">AKTUATOR</span>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('actuators.index') }}"
                                            aria-expanded="false">
                                            <iconify-icon icon="solar:login-3-line-duotone"></iconify-icon>
                                            <span class="hide-menu">AKTUATOR</span>
                                        </a>
                                    </li>
                                    <!-- Divider --> --}}
                                    <li>
                                        <span class="sidebar-divider lg"></span>
                                    </li>
                                    <!-- Extra Section -->
                                    <li class="nav-small-cap">
                                        <iconify-icon icon="solar:menu-dots-linear"
                                            class="nav-small-cap-icon fs-4"></iconify-icon>
                                        <span class="hide-menu">USERS</span>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('users.index') }}" aria-expanded="false">
                                            <iconify-icon icon="solar:user-plus-rounded-line-duotone"></iconify-icon>
                                            <span class="hide-menu">USER LISTS</span>
                                        </a>
                                    </li>
                                </ul>
                                <!-- End Sidebar navigation -->
                        </div>
                        <!-- End Sidebar scroll -->
                    </aside>
                    <!-- Sidebar End -->
                </div>

                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav">
                        <li class="nav-item d-block d-xl-none">
                            <a class="nav-link sidebartoggler " id="headerCollapse" href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                            <a href="https://adminmart.com/product/matdash-free-bootstrap-5-admin-dashboard-template/"
                                target="_blank" class="btn btn-primary">hello {{ Auth::user()->name }}</a>
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if(Auth::user()->photo)
                                        <img src="{{ Storage::url(Auth::user()->photo) }}" alt="" width="35" height="35" class="rounded-circle">
                                    @else
                                        <img src="../assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
                                    aria-labelledby="drop2">
                                    <div class="message-body">
                                        <a href="javascript:void(0)"
                                            class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-user fs-6"></i>
                                            <p class="mb-0 fs-3">My Profile</p>
                                        </a>
                                        <a href="{{ route('logout') }}"
                                            class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!--  Header End -->
            <div class="body-wrapper-inner">
                <div class="container-fluid">
                    @yield('content')
                    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
                    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
                    <script src="../assets/js/sidebarmenu.js"></script>
                    <script src="../assets/js/app.min.js"></script>
                    <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
                    <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
                    <script src="../assets/js/dashboard.js"></script>
                    <!-- solar icons -->
                    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
                    @stack('scripts')
</body>

</html>
