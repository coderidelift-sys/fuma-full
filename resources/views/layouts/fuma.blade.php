<!doctype html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="{{ asset('/materialize') }}/assets/"
    data-template="vertical-menu-template-no-customizer" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title') | FUMA - Football Tournament Management</title>

    <meta name="description" content="FUMA - Football Tournament Management System Backoffice" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('/materialize') }}/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/fonts/remixicon/remixicon.css" />
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/fonts/flag-icons.css" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/libs/node-waves/node-waves.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/css/rtl/core.css" />
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/css/rtl/theme-default.css" />
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet"
        href="{{ asset('/materialize') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet"
        href="{{ asset('/materialize') }}/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('/materialize') }}/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('/materialize') }}/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('/materialize') }}/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/libs/@form-validation/form-validation.css" />
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/libs/toastr/toastr.css" />
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/libs/sweetalert2/sweetalert2.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/css/pages/app-logistics-dashboard.css" />

    <!-- Helpers -->
    <script src="{{ asset('/materialize') }}/assets/vendor/js/helpers.js"></script>
    <script src="{{ asset('/materialize') }}/assets/js/config.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/jquery/jquery.js"></script>

    @stack('styles')
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="{{ route('fuma.dashboard') }}" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <i class="ri-football-line ri-2x text-primary"></i>
                        </span>
                        <span class="app-brand-text demo menu-text fw-semibold ms-2">FUMA</span>
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                        <i class="ri-menu-line"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                @include('fuma._partials.sidebar_menu')
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                            <i class="ri-menu-fill ri-22px"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ auth()->user()->avatarUrl ?? asset('/materialize/assets/img/avatars/1.png') }}"
                                             alt="User Avatar" class="rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('fuma.profile') }}">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ auth()->user()->avatarUrl ?? asset('/materialize/assets/img/avatars/1.png') }}"
                                                             alt="User Avatar" class="rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-medium d-block small">{{ auth()->user()->name }}</span>
                                                    <small class="text-muted">
                                                        {{ auth()->user()->roles->first()->display_name ?? 'User' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('fuma.profile') }}">
                                            <i class="ri-user-3-line ri-22px me-3"></i>
                                            <span class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    <div class="dropdown-divider"></div>
                                    <li>
                                        <form action="{{ route('fuma.logout') }}" method="post" class="d-none" id="form-logout">
                                            @csrf
                                        </form>
                                        <div class="d-grid px-4 pt-2 pb-1">
                                            <a class="btn btn-sm btn-danger d-flex" href="javascript:void(0)"
                                                onclick="event.preventDefault(); $('#form-logout').submit();">
                                                <small class="align-middle">Logout</small>
                                                <i class="ri-logout-box-r-line ms-2 ri-16px"></i>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    @yield('content')

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                                <div class="text-body mb-2 mb-md-0">
                                    © {{ date('Y') }} FUMA - Football Tournament Management System
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/js/menu.js"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/@form-validation/popular.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/@form-validation/auto-focus.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/sweetalert2/sweetalert2.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/toastr/toastr.js"></script>

    <!-- FUMA API Configuration -->
    <script>
        window.FUMA_CONFIG = {
            API_BASE_URL: '{{ url('/api') }}',
            CSRF_TOKEN: '{{ csrf_token() }}',
            USER_ROLE: '{{ auth()->user()->roles->first()->name ?? "" }}',
            USER_ID: {{ auth()->id() }},
            ASSETS_URL: '{{ asset('/materialize') }}'
        };
    </script>

    <!-- Main JS -->
    @vite(['resources/js/fuma-backoffice.js'])

    <!-- Toastr Notifications -->
    <script>
        @if (session('success'))
            toastr.success('{{ session('success') }}', 'Success!');
        @elseif (session('error'))
            toastr.error('{{ session('error') }}', 'Error!');
        @elseif (session('info'))
            toastr.info('{{ session('info') }}', 'Info!');
        @elseif (session('warning'))
            toastr.warning('{{ session('warning') }}', 'Warning!');
        @endif
    </script>

    @stack('scripts')
</body>
</html>
