<!doctype html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="{{ asset('/materialize') }}/assets/"
    data-template="vertical-menu-template-no-customizer" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login | FUMA - Football Tournament Management</title>

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

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/css/rtl/core.css" />
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/css/rtl/theme-default.css" />
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/libs/@form-validation/form-validation.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="{{ asset('/materialize') }}/assets/vendor/js/helpers.js"></script>
    <script src="{{ asset('/materialize') }}/assets/js/config.js"></script>
</head>

<body>
    <!-- Content -->
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Login -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <a href="{{ route('fuma.login') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <i class="ri-football-line ri-3x text-primary"></i>
                                </span>
                                <span class="app-brand-text demo text-heading fw-bold">FUMA</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-1 pt-2">Welcome to FUMA Backoffice! 👋</h4>
                        <p class="mb-4">Please sign-in to your account</p>

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form id="formAuthentication1" class="mb-3" action="{{ route('fuma.login.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email or Username</label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" placeholder="Enter your email or username"
                                       value="{{ old('email') }}" autofocus />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                           name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                           aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                            </div>
                        </form>

                        <p class="text-center">
                            <span>New on our platform?</span>
                            <a href="{{ route('register') }}">
                                <span>Create an account</span>
                            </a>
                        </p>

                        <div class="divider my-4">
                            <div class="divider-text">or</div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <a href="javascript:;" class="btn btn-icon btn-label-facebook me-3">
                                <i class="tf-icons ri-facebook-fill fs-5"></i>
                            </a>

                            <a href="javascript:;" class="btn btn-icon btn-label-google-plus me-3">
                                <i class="tf-icons ri-google-fill fs-5"></i>
                            </a>

                            <a href="javascript:;" class="btn btn-icon btn-label-twitter">
                                <i class="tf-icons ri-twitter-fill fs-5"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>
    <!-- / Content -->

    <!-- Core JS -->
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/js/menu.js"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/@form-validation/popular.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/@form-validation/auto-focus.js"></script>

    <!-- Main JS -->
    <script src="{{ asset('/materialize') }}/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="{{ asset('/materialize') }}/assets/js/pages-auth.js"></script>
</body>
</html>
