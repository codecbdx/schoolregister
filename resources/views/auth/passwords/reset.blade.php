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
                            <h5 class="text-muted font-weight-normal mb-4">{{ __('Welcome back! Please choose a new password for your account.') }}</h5>
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="form-group">
                                    <label for="email">{{ __('Email Address') }}</label>
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           placeholder="{{ __('Email Address') }}" value="{{ $email ?? old('email') }}"
                                           required
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
                                           class="form-control @error('password') is-invalid @enderror"
                                           name="password"
                                           placeholder="{{ __('Password') }}" required
                                           autocomplete="new-password" @error('password') autofocus @enderror>

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                    <input id="password-confirm" type="password"
                                           class="form-control"
                                           name="password_confirmation"
                                           placeholder="{{ __('Confirm Password') }}" required
                                           autocomplete="new-password">
                                </div>
                                <div class="mt-3">
                                    <button type="submit"
                                            class="btn btn-primary btn-icon-text mr-2 mb-2 mb-md-0 text-white">
                                        <i class="btn-icon-prepend" data-feather="lock"></i>
                                        {{ __('Reset Password') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
