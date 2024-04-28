@section('title', __('Edit User'))

<div>
    <form wire:submit="save">
        @csrf
        <div class="row mb-4">
            <div class="col-lg-8 col-md-6 col-sm-6">
                <nav class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">{{ __('Management') }}</li>
                        <li class="breadcrumb-item"><a href="{{ route('users') }}">{{ __('Users') }}</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a class="text-facebook"
                                                                           href="#">{{ __('Edit User') }}</a>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 d-flex justify-content-end align-items-center">
                <button type="button" class="d-none d-sm-block btn btn-light btn-icon-text btn-back">
                    <i class="mdi mdi-undo-variant mr-2"></i>
                    {{ __('Back') }}
                </button>
                <button type="button" class="d-block d-sm-none btn btn-light btn-icon-text w-100 btn-back">
                    <i class="mdi mdi-undo-variant mr-2"></i>
                    {{ __('Back') }}
                </button>
                <button type="submit" class="d-none d-sm-block btn btn-linkedin btn-icon-text ml-2">
                    <i class="mdi mdi-account-check mr-2"></i>
                    {{ __('Update User') }}
                </button>
                <button type="submit" class="d-block d-sm-none btn btn-linkedin btn-icon-text w-100">
                    <i class="mdi mdi-account-check mr-2"></i>
                    {{ __('Update User') }}
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card bg-transparent shadow-none border-0">
                    <h4 class="card-subtitle text-dark">{{ __('Profile Information') }}</h4>
                    <p class="card-description text-black">{{ __('Profile information and email address.') }}</p>
                </div>
            </div>
            <div class="col-lg-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label for="name" class="col-form-label">{{ __('Name') }}</label>
                            </div>
                            <div class="col-lg-9">
                                <input id="name" type="text"
                                       class="form-control @error('name') is-invalid @enderror" wire:model="name"
                                       placeholder="{{ __('Name') }}"
                                       autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label for="paternalLastName"
                                       class="col-form-label">{{ __('PaternalLastName') }}</label>
                            </div>
                            <div class="col-lg-9">
                                <input id="paternalLastName" type="text"
                                       class="form-control @error('paternal_lastname') is-invalid @enderror"
                                       wire:model="paternal_lastname"
                                       placeholder="{{ __('PaternalLastName') }}"
                                       autocomplete="paternal-lastname">

                                @error('paternal_lastname')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label for="maternalLastName"
                                       class="col-form-label">{{ __('MaternalLastName') }}</label>
                            </div>
                            <div class="col-lg-9">
                                <input id="maternalLastName" type="text"
                                       class="form-control @error('maternal_lastname') is-invalid @enderror"
                                       wire:model="maternal_lastname"
                                       placeholder="{{ __('MaternalLastName') }}"
                                       autocomplete="maternal-lastname">

                                @error('maternal_lastname')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label for="email" class="col-form-label">{{ __('Email Address') }}</label>
                            </div>
                            <div class="col-lg-9">
                                <input id="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror" wire:model="email"
                                       placeholder="{{ __('Email Address') }}"
                                       autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card bg-transparent shadow-none border-0">
                    <h4 class="card-subtitle text-dark">{{ __('Update Password') }}</h4>
                    <p class="card-description text-black">{{ __('Make sure your account is using a long, random password to stay secure.') }}</p>
                </div>
            </div>
            <div class="col-lg-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label for="password" class="col-form-label">{{ __('New Password') }}</label>
                            </div>
                            <div class="col-lg-9">
                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       wire:model="password"
                                       placeholder="{{ __('New Password') }}"
                                       autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label for="password-confirm"
                                       class="col-form-label">{{ __('Confirm Password') }}</label>
                            </div>
                            <div class="col-lg-9">
                                <input id="password-confirm" type="password"
                                       class="form-control"
                                       wire:model="password_confirmation"
                                       placeholder="{{ __('Confirm Password') }}"
                                       autocomplete="new-password">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card bg-transparent shadow-none border-0">
                    <h4 class="card-subtitle text-dark">{{ __('Upload photo') }}</h4>
                    <p class="card-description text-black">{{ __('For your user, consider adding a photo that is clear and centered on their face for easy identification.') }}</p>
                </div>
            </div>
            <div class="col-lg-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group m-0">
                            <p class="card-description text-justify text-dark">{{ __('Specifications image') }}</p>
                            <input type="file" class="file-upload-default"
                                   accept="image/jpeg, image/png, image/jpg, image/webp" wire:model="image">
                            <div class="input-group col-xs-12 mb-3">
                                <input type="text" class="form-control file-upload-info" disabled
                                       placeholder="{{ __('Select your profile image') }}">
                                <span class="input-group-append">
												<button class="file-upload-browse btn btn-primary"
                                                        type="button">{{ __('Upload') }}</button>
											</span>
                            </div>
                            @if($image && !$errors->has('image'))
                                <div class="d-flex justify-content-center align-items-center">
                                    <img height="180px" src="{{$image->temporaryUrl()}}"/>
                                </div>
                            @else
                                @if(!$errors->has('image'))
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img height="180px" src="{{ env('AWS_URL') }}{{$user_image}}"/>
                                    </div>
                                @endif
                            @endif
                            <div class="mt-3 alert @error('image') alert-danger @else alert-primary @enderror"
                                 role="alert">
                                {{ __('Type image user') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card bg-transparent shadow-none border-0">
                    <h4 class="card-subtitle text-dark">{{ __('Advanced configuration') }}</h4>
                    <p class="card-description text-black">{{ __('Here you can adjust essential details such as your role type and school.') }}</p>
                </div>
            </div>
            <div class="col-lg-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="select-user-role"
                                   class="col-form-label">{{ __('Rol Type') }}</label>
                            <select class="form-control @error('user_type') is-invalid @enderror" id="select-user-role"
                                    wire:model="user_type">
                                <option selected disabled>{{ __('Select your user role') }}</option>
                                @foreach($user_types as $index => $user_type)
                                    <option value="{{ $user_type->id }}">{{ $user_type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="select-user-school"
                                   class="col-form-label">{{ __('School') }}</label>
                            <select class="form-control @error('user_type') is-invalid @enderror"
                                    id="select-user-school"
                                    wire:model="user_customer">
                                <option selected disabled>{{ __('Select your school') }}</option>
                                @foreach($customers as $index => $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="select-user-status"
                                   class="col-form-label">{{ __('Status') }}</label>
                            <select class="form-control @error('user_type') is-invalid @enderror"
                                    id="select-user-status"
                                    wire:model="user_status">
                                <option disabled selected>{{ __('Select status') }}</option>
                                <option value="0">{{ __('Active') }}</option>
                                <option value="2">{{ __('Suspended') }}</option>
                            </select>
                            @error('user_status')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    document.querySelectorAll('.btn-back').forEach(button => {
        button.addEventListener('click', function () {
            window.history.back();
        });
    });
</script>
