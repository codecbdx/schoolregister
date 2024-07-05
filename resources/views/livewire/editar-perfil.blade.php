@section('title', __('Edit Profile'))

<div>
    <form wire:submit="save">
        @csrf
        <div class="row mb-4">
            <div class="col-lg-12 d-flex justify-content-end align-items-center">
                <button type="button" class="d-none d-sm-block btn btn-light btn-icon-text btn-back">
                    <i class="mdi mdi-undo-variant mr-2"></i>
                    {{ __('Back') }}
                </button>
                <button type="button" class="d-block d-sm-none btn btn-light btn-icon-text w-100 btn-back">
                    <i class="mdi mdi-undo-variant mr-2"></i>
                </button>
                <button type="submit" class="d-none d-sm-block btn btn-linkedin btn-icon-text ml-2">
                    <i class="mdi mdi-account-check mr-2"></i>
                    {{ __('Update Profile') }}
                </button>
                <button type="submit" class="d-block d-sm-none btn btn-linkedin btn-icon-text w-100">
                    <i class="mdi mdi-account-check mr-2"></i>
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
                                <input id="nombre" type="text"
                                       class="form-control @error('nombre') is-invalid @enderror" wire:model="nombre"
                                       placeholder="{{ __('Name') }}"
                                       autocomplete="off" autofocus>

                                @error('nombre')
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
                                       autocomplete="off">

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
                                       autocomplete="off">

                                @error('maternal_lastname')
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
                                       autocomplete="off">

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
                                       autocomplete="off">
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
                            <div wire:loading wire:target="image" class="loader-file">
                                <div
                                    class="v-align">
                                    <div class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="card-description text-justify text-dark">{{ __('Specifications image') }}</p>
                            <input type="file" class="file-upload-default"
                                   accept="image/jpeg, image/png, image/jpg, image/webp" wire:model="image">
                            <div class="input-group col-xs-12 mb-3">
                                <input type="text" class="form-control file-upload-info" disabled
                                       placeholder="{{ __('Select your profile image') }}">
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-primary"
                                            type="button">
                                        {{ __('Upload') }}
                                    </button>
                                </span>
                            </div>
                            @if($image && !$errors->has('image'))
                                <div class="d-flex justify-content-center align-items-center">
                                    <img height="180px" src="{{$image->temporaryUrl()}}"/>
                                </div>
                            @else
                                @if(!$errors->has('image'))
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img height="180px" src="{{$userSignedImage}}"/>
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
    </form>
</div>
<script>
    document.querySelectorAll('.btn-back').forEach(button => {
        button.addEventListener('click', function () {
            window.history.back();
        });
    });

    window.addEventListener('userUpdated', () => {
        Swal.fire({
            title: '{{ __('Updated') }}',
            text: '{{ __('Register Updated') }}',
            icon: 'success',
            confirmButtonText: '{{ __('Ok') }}',
            allowOutsideClick: false
        }).then((result) => {
            if (result.value) {
                window.location.reload();
            }
        });
    });
</script>
