@section('title', __('General configuration'))

<div>
    <form wire:submit="save">
        @csrf
        <div class="row mb-4">
            <div class="col-lg-8 col-md-6 col-sm-6">
                <nav class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">{{ __('Configuration') }}</li>
                        <li class="breadcrumb-item" aria-current="page"><a class="text-facebook"
                                                                           href="#">{{ __('System') }}</a>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 d-flex flex-column justify-content-center align-items-end">
                <button type="submit" class="d-none d-sm-block btn btn-linkedin btn-icon-text">
                    <i class="mdi mdi-tune mr-2"></i>
                    {{ __('Update Configuration') }}
                </button>
                <button type="submit" class="d-block d-sm-none btn btn-linkedin btn-icon-text w-100">
                    <i class="mdi mdi-tune mr-2"></i>
                    {{ __('Update Configuration') }}
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card bg-transparent shadow-none border-0">
                    <h4 class="card-subtitle text-dark">{{ __('System Information') }}</h4>
                    <p class="card-description text-black">{{ __('System name and logo') }}</p>
                </div>
            </div>
            <div class="col-lg-8 grid-margin stretch-card">
                <div class="card">
                    <div wire:loading wire:target="system_logo" class="loader-file">
                        <div
                            class="v-align">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div wire:loading wire:target="system_icon" class="loader-file">
                        <div
                            class="v-align">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="col-form-label">{{ __('Name System') }}</label>
                            <input id="name" type="text"
                                   class="form-control @error('name') is-invalid @enderror" wire:model="name"
                                   placeholder="{{ __('Name System') }}"
                                   autocomplete="name" autofocus>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="system_logo" class="col-form-label">{{ __('System Logo') }}</label>
                            <input id="system_logo" type="file" class="file-upload-default"
                                   accept="image/jpeg, image/png, image/jpg, image/webp" wire:model="system_logo">
                            <div class="input-group col-xs-12 mb-3">
                                <input type="text" class="form-control file-upload-info" disabled
                                       placeholder="{{ __('Select System Logo') }}">
                                <span class="input-group-append">
												<button class="file-upload-browse btn btn-primary"
                                                        type="button">{{ __('Upload') }}</button>
											</span>
                            </div>
                            @if($system_logo && !$errors->has('system_logo'))
                                <div class="d-flex justify-content-center align-items-center">
                                    <img width="65%" src="{{$system_logo->temporaryUrl()}}"/>
                                </div>
                            @else
                                @if(!$errors->has('system_logo'))
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img width="65%"
                                             src="{{ env('AWS_URL') }}{{ $current_system_logo }}"/>
                                    </div>
                                @endif
                            @endif
                            <div class="mt-3 alert @error('system_logo') alert-danger @else alert-primary @enderror"
                                 role="alert">
                                {{ __('Type system logo') }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="system_icon" class="col-form-label">{{ __('System Icon') }}</label>
                            <input id="system_icon" type="file" class="file-upload-default"
                                   accept="image/jpeg, image/png, image/jpg, image/webp" wire:model="system_icon">
                            <div class="input-group col-xs-12 mb-3">
                                <input type="text" class="form-control file-upload-info" disabled
                                       placeholder="{{ __('Select System Icon') }}">
                                <span class="input-group-append">
												<button class="file-upload-browse btn btn-primary"
                                                        type="button">{{ __('Upload') }}</button>
											</span>
                            </div>
                            @if($system_icon && !$errors->has('system_icon'))
                                <div class="d-flex justify-content-center align-items-center">
                                    <img width="20%" src="{{$system_icon->temporaryUrl()}}"/>
                                </div>
                            @else
                                @if(!$errors->has('system_icon'))
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img width="20%"
                                             src="{{ env('AWS_URL') }}{{ $current_system_icon }}"/>
                                    </div>
                                @endif
                            @endif
                            <div class="mt-3 alert @error('system_icon') alert-danger @else alert-primary @enderror"
                                 role="alert">
                                {{ __('Type system icon') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card bg-transparent shadow-none border-0">
                    <h4 class="card-subtitle text-dark">{{ __('Login Settings') }}</h4>
                    <p class="card-description text-black">{{ __('Login settings description') }}</p>
                </div>
            </div>
            <div class="col-lg-8 grid-margin stretch-card">
                <div class="card">
                    <div wire:loading wire:target="background_login" class="loader-file">
                        <div
                            class="v-align">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div wire:loading wire:target="form_image" class="loader-file">
                        <div
                            class="v-align">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="background_login" class="col-form-label">{{ __('Background Login') }}</label>
                            <input id="background_login" type="file" class="file-upload-default"
                                   accept="image/jpeg, image/png, image/jpg, image/webp" wire:model="background_login">
                            <div class="input-group col-xs-12 mb-3">
                                <input type="text" class="form-control file-upload-info" disabled
                                       placeholder="{{ __('Select background login system') }}">
                                <span class="input-group-append">
												<button class="file-upload-browse btn btn-primary"
                                                        type="button">{{ __('Upload') }}</button>
											</span>
                            </div>
                            @if($background_login && !$errors->has('background_login'))
                                <div class="d-flex justify-content-center align-items-center">
                                    <img width="65%" src="{{$background_login->temporaryUrl()}}"/>
                                </div>
                            @else
                                @if(!$errors->has('background_login'))
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img width="65%"
                                             src="{{ env('AWS_URL') }}{{ $current_background_login }}"/>
                                    </div>
                                @endif
                            @endif
                            <div
                                class="mt-3 alert @error('background_login') alert-danger @else alert-primary @enderror"
                                role="alert">
                                {{ __('Type background login') }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="form_image" class="col-form-label">{{ __('Picture Form Login') }}</label>
                            <input id="form_image" type="file" class="file-upload-default"
                                   accept="image/jpeg, image/png, image/jpg, image/webp" wire:model="form_image">
                            <div class="input-group col-xs-12 mb-3">
                                <input type="text" class="form-control file-upload-info" disabled
                                       placeholder="{{ __('Select picture form system') }}">
                                <span class="input-group-append">
												<button class="file-upload-browse btn btn-primary"
                                                        type="button">{{ __('Upload') }}</button>
											</span>
                            </div>
                            @if($form_image && !$errors->has('form_image'))
                                <div class="d-flex justify-content-center align-items-center">
                                    <img width="40%" src="{{$form_image->temporaryUrl()}}"/>
                                </div>
                            @else
                                @if(!$errors->has('form_image'))
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img width="40%"
                                             src="{{ env('AWS_URL') }}{{$current_form_image}}"/>
                                    </div>
                                @endif
                            @endif
                            <div class="mt-3 alert @error('image') alert-danger @else alert-primary @enderror"
                                 role="alert">
                                {{ __('Type form image') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
