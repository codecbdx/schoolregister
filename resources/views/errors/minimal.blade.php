<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $configuracion->system_name }} - @yield('title')</title>

    <!-- core:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/core/core.css') }}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- end plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets/css//style.css') }}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('storage/') }}/{{ $configuracion->system_icon }}"/>
</head>
<body class="sidebar-dark">
<div class="main-wrapper">
    <div class="page-wrapper full-page">
        <div class="page-content d-flex align-items-center justify-content-center">

            <div class="row w-100 mx-0 auth-page">
                <div class="col-md-8 col-xl-6 mx-auto d-flex flex-column align-items-center">
                    <img src="{{ asset('assets/images/404.svg') }}" class="img-fluid mb-2" alt="404">
                    <h1 class="font-weight-bold mb-22 mt-2 tx-80 text-muted">@yield('code')</h1>
                    <h4 class="mb-2">@yield('titleError')</h4>
                    <h6 class="text-muted mb-3 text-center">@yield('message')</h6>
                    <a href="{{ route('home') }}" class="btn btn-primary">{{ __('Back to home') }}</a>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- core:js -->
<script src="{{ asset('assets/vendors/core/core.js') }}"></script>
<!-- endinject -->
<!-- plugin js for this page -->
<!-- end plugin js for this page -->
<!-- inject:js -->
<script src="{{ asset('assets/vendors/feather-icons/feather.min.js') }}"></script>
<!-- endinject -->
<!-- custom js for this page -->
<script src="{{ asset('assets/js/main.js') }}"></script>
<!-- end custom js for this page -->
</body>
</html>
