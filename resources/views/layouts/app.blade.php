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
    <link rel="stylesheet" href="{{ asset('assets/vendors/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
    <!-- end plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets/css//style.css') }}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ env('AWS_URL') }}{{ $configuracion->system_icon }}"/>
</head>
<body class="sidebar-dark">
<div class="main-wrapper">
    <nav class="sidebar">
        <div class="sidebar-header">
            <a href="#" class="sidebar-brand">
                <div class="logo">
                    <img src="{{ env('AWS_URL') }}{{ $configuracion->system_logo }}">
                </div>
            </a>
            <div class="sidebar-toggler not-active">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="sidebar-body">
            <livewire:navigation/>
        </div>
    </nav>
    <div class="page-wrapper">
        <nav class="navbar">
            <a href="#" class="sidebar-toggler">
                <i data-feather="menu"></i>
            </a>
            <div class="navbar-content">
                <livewire:customer-title/>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown nav-profile">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ env('AWS_URL') }}{{ Auth::user()->user_image }}" alt="user">
                        </a>
                        <div class="dropdown-menu" aria-labelledby="profileDropdown">
                            <div class="dropdown-header d-flex flex-column align-items-center">
                                <div class="figure mb-3">
                                    <img src="{{ env('AWS_URL') }}{{ Auth::user()->user_image }}" alt="user">
                                </div>
                                <div class="info text-center">
                                    <p class="name font-weight-bold mb-0">{{ Str::limit(Auth::user()->name . ' ' . Auth::user()->paternal_lastname . ' ' . Auth::user()->maternal_lastname, 30) }}
                                    <p class="email text-muted mb-3">{{ Str::limit(Auth::user()->email, 30)  }}</p>
                                </div>
                            </div>
                            <div class="dropdown-body">
                                <ul class="profile-nav p-0 pt-3">
                                    <li class="nav-item">
                                        <a href="{{ route('edit_profile', ['id' => config('app.debug') ?  Auth::user()->id : encrypt( Auth::user()->id)]) }}"
                                           class="nav-link">
                                            <i data-feather="edit"></i>
                                            <span>{{ __('Edit Profile') }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('logout') }}" class="nav-link"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i data-feather="log-out"></i>
                                            <span>{{ __('Logout') }}</span>
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                              class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="page-content">
            {{ $slot }}
        </div>
        <footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between">
            <p class="text-muted text-center text-md-left">{{ __('Copyright') }} <a
                    href="#">{{ $configuracion->system_name }}</a>. {{ __('All rights reserved' )}}</p>
        </footer>
    </div>
</div>
<!-- core:js -->
<script src="{{ asset('assets/vendors/core/core.js') }}"></script>
<!-- endinject -->
<!-- plugin js for this page -->
<script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/vendors/promise-polyfill/polyfill.min.js') }}"></script>
<script src="{{ asset('assets/vendors/inputmask/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>
<!-- Optional:  polyfill for ES6 Promises for IE11 and Android browser -->
<!-- end plugin js for this page -->
<!-- inject:js -->
<script src="{{ asset('assets/vendors/feather-icons/feather.min.js') }}"></script>
<!-- endinject -->
<!-- custom js for this page -->
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/js/inputmask.js') }}"></script>
<!-- end custom js for this page -->
</body>
</html>
