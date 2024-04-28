@extends('layouts.login-app')

@section('title', __('SignIn'))

@section('content')
    <div class="row w-100 mx-0 auth-page">
        <div class="col-md-8 col-xl-6 mx-auto">
            <div class="card card-shadow">
                <div class="row">
                    <div class="col-md-4 pr-md-0">
                        <div class="auth-left-wrapper"
                             style="background-image: url({{ env('AWS_URL') }}{{ $configuracion->form_image }});">
                        </div>
                    </div>
                    <div class="col-md-8 pl-md-0">
                        <div class="auth-form-wrapper px-4 py-5">
                            <a href="#" class="noble-ui-logo d-block mb-2">
                                <div class="logo">
                                    <img src="{{ env('AWS_URL') }}{{ $configuracion->system_logo }}">
                                </div>
                            </a>
                            <h5 class="text-muted font-weight-normal mb-4 text-center">{{ __('Welcome back! Log in to your account.') }}</h5>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="email">{{ __('Email Address') }}</label>
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           placeholder="{{ __('Email Address') }}" value="{{ old('email') }}" required
                                           autocomplete="email" @if(!$errors->has('password')) autofocus @endif>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password">{{ __('Password') }}</label>
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           placeholder="{{ __('Password') }}" required autocomplete="current-password"
                                           @error('password') autofocus @enderror>


                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="remember"
                                               id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                                <div class="mt-3">
                                    <button type="submit"
                                            class="btn btn-primary btn-icon-text mt-2 mr-2 mb-2 mb-md-0 text-white">
                                        <i class="btn-icon-prepend" data-feather="log-in"></i>
                                        {{ __('Login') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}"
                                           class="btn btn-outline-primary btn-icon-text mt-2 mb-2 mb-md-0">
                                            <i class="btn-icon-prepend" data-feather="key"></i>
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                       class="btn-link d-block mt-3 text-muted">
                                        {{ __('Not a user? Sign up') }}
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
