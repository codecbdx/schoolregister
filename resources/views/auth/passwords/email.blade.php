@extends('layouts.login-app')

@section('title', __('Reset Password'))

@section('content')
    <div class="row w-100 mx-0 auth-page">
        <div class="col-md-8 col-xl-6 mx-auto">
            <div class="card">
                <div class="row">
                    <div class="col-md-4 pr-md-0">
                        <div class="auth-left-wrapper"
                             style="background-image: url({{ asset('storage/') }}/{{ $configuracion->form_image }});">

                        </div>
                    </div>
                    <div class="col-md-8 pl-md-0">
                        <div class="auth-form-wrapper px-4 py-5">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <a href="#" class="noble-ui-logo d-block mb-2">
                                <div class="logo">
                                    <img src="{{ asset('storage/') }}/{{ $configuracion->system_logo }}">
                                </div>
                            </a>
                            <h5 class="text-muted font-weight-normal mb-4">{{ __('Need to regain access? Reset your password here.') }}</h5>
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="email">{{ __('Email Address') }}</label>
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           placeholder="{{ __('Email Address') }}" value="{{ old('email') }}" required
                                           autocomplete="off" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="mt-3">
                                    <button type="submit"
                                            class="btn btn-primary btn-icon-text mr-2 mb-2 mb-md-0 text-white">
                                        <i class="btn-icon-prepend" data-feather="mail"></i>
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                </div>
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}"
                                       class="btn-link d-block mt-3 text-muted">{{ __('Already a user? Sign in') }}</a>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
