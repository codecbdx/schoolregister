@extends('layouts.login-app')

@section('title', __('Register'))

@section('content')
    <div class="row w-100 mx-0 auth-page">
        <div class="col-md-8 col-xl-6 mx-auto">
            <div class="card">
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
                            <h5 class="text-muted font-weight-normal mb-4">{{ __('Create account.') }}</h5>
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="name">{{ __('Name') }}</label>
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror" name="name"
                                           placeholder="{{ __('Name') }}" value="{{ old('name') }}" required
                                           autocomplete="name"
                                           @if(! $errors->has('paternal_lastname') && ! $errors->has('maternal_lastname') && ! $errors->has('email') && ! $errors->has('password')) autofocus @endif>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="paternalLastName">{{ __('PaternalLastName') }}</label>
                                            <input id="paternalLastName" type="text"
                                                   class="form-control @error('paternal_lastname') is-invalid @enderror"
                                                   name="paternal_lastname"
                                                   placeholder="{{ __('PaternalLastName') }}"
                                                   value="{{ old('paternal_lastname') }}" required
                                                   autocomplete="paternal-lastname"
                                                   @error('paternal_lastname') autofocus @enderror>

                                            @error('paternal_lastname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div><!-- Col -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="maternalLastName">{{ __('MaternalLastName') }}</label>
                                            <input id="maternalLastName" type="text"
                                                   class="form-control @error('maternal_lastname') is-invalid @enderror"
                                                   name="maternal_lastname"
                                                   placeholder="{{ __('MaternalLastName') }}"
                                                   value="{{ old('maternal_lastname') }}" required
                                                   autocomplete="maternal-lastname"
                                                   @error('maternal_lastname') autofocus @enderror>

                                            @error('maternal_lastname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div><!-- Col -->
                                </div>
                                <div class="form-group">
                                    <label for="email">{{ __('Email Address') }}</label>
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           placeholder="{{ __('Email Address') }}" value="{{ old('email') }}" required
                                           autocomplete="email" @error('email') autofocus @enderror>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
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
                                    </div><!-- Col -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                            <input id="password-confirm" type="password"
                                                   class="form-control"
                                                   name="password_confirmation"
                                                   placeholder="{{ __('Confirm Password') }}" required
                                                   autocomplete="new-password">
                                        </div>
                                    </div><!-- Col -->
                                </div>
                                <div class="mt-3">
                                    <button type="submit"
                                            class="btn btn-primary btn-icon-text mr-2 mb-2 mb-md-0 text-white">
                                        <i class="btn-icon-prepend" data-feather="log-in"></i>
                                        {{ __('Register') }}
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
