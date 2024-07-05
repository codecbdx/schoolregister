@section('title', __('Edit Course'))

<div>
    <form wire:submit="save">
        @csrf
        <div class="row mb-4">
            <div class="col-lg-8 col-md-6 col-sm-6">
                <nav class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">{{ __('Management') }}</li>
                        <li class="breadcrumb-item"><a href="{{ route('courses') }}">{{ __('Courses') }}</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a class="text-facebook"
                                                                           href="#">{{ __('Edit Course') }}</a>
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
                </button>
                <button type="submit" class="d-none d-sm-block btn btn-linkedin btn-icon-text ml-2">
                    <i class="mdi mdi-school mr-2"></i>
                    {{ __('Update Course') }}
                </button>
                <button type="submit" class="d-block d-sm-none btn btn-linkedin btn-icon-text w-100">
                    <i class="mdi mdi-school mr-2"></i>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 stretch-card">
                <div class="card">
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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="control-label">{{ __('Course') }}</label>
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror" wire:model="name"
                                           placeholder="{{ __('Course') }}"
                                           autocomplete="name" autofocus>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="description">{{ __('Description') }}</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              wire:model="description" id="description" rows="5"></textarea>
                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="moodle_code" class="control-label">{{ __('Moodle Code') }}</label>
                                    <input id="moodle_code" type="text"
                                           class="form-control @error('moodle_code') is-invalid @enderror"
                                           wire:model="moodle_code"
                                           placeholder="{{ __('Moodle Code') }}"
                                           autocomplete="moodle_code">
                                    @error('moodle_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="image" class="control-label">{{ __('Course picture') }}</label>
                                    <input id="image" type="file" class="file-upload-default"
                                           accept="image/jpeg, image/png, image/jpg, image/webp" wire:model="image">
                                    <div class="input-group col-xs-12 mb-3">
                                        <input type="text" class="form-control file-upload-info" disabled
                                               placeholder="{{ __('Select your course image') }}">
                                        <span class="input-group-append">
												<button class="file-upload-browse btn btn-primary"
                                                        type="button">{{ __('Upload') }}</button>
											</span>
                                    </div>
                                    @if($image && !$errors->has('image'))
                                        <div class="d-flex justify-content-center align-items-center">
                                            <img height="246px" src="{{$image->temporaryUrl()}}"/>
                                        </div>
                                    @elseif($courseImage != null)
                                        @if(!$errors->has('image'))
                                            <div class="d-flex justify-content-center align-items-center">
                                                <img height="246px"
                                                     src="{{$courseSignedImage}}"/>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="mt-3 alert @error('image') alert-danger @else alert-primary @enderror"
                                         role="alert">
                                        {{ __('Type course picture') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script>
    document.querySelectorAll('.btn-back').forEach(button => {
        button.addEventListener('click', function() {
            window.history.back();
        });
    });
</script>
