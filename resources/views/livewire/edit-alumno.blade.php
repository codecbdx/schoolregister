@section('title', __('Edit Student'))

<div>
    <div class="row mb-4">
        <div class="col-lg-8 col-md-6 col-sm-6">
            <nav class="page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">{{ __('Management') }}</li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('students') }}">{{ __('Students') }}</a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 d-flex justify-content-end align-items-center">
            <button type="button" class="d-none d-sm-block btn btn-light btn-icon-text btn-back">
                <i class="mdi mdi-undo-variant mr-2"></i>
                {{ __('Back') }}
            </button>
            <button type="button" class="d-block d-sm-none btn btn-light btn-icon-text w-100 btn-back">
                <i class="mdi mdi-undo-variant mr-2"></i>
                {{ __('Back') }}
            </button>
        </div>
    </div>
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            @if($status == 0 or $status == 1)
                <a class="nav-item nav-link" id="nav-grupo-tab"
                   data-toggle="tab"
                   href="#nav-grupo" role="tab" aria-controls="nav-grupo" aria-selected="true">{{ __('Groups') }}</a>
            @endif
            <a class="nav-item nav-link active" id="nav-general-tab" data-toggle="tab" href="#nav-general"
               role="tab"
               aria-controls="nav-general" aria-selected="true">{{ __('Personal Information') }}</a>
            @if($status == 0 or $status == 1)
                <a class="nav-item nav-link" id="nav-user-info-tab"
                   data-toggle="tab" href="#nav-user-info"
                   role="tab"
                   aria-controls="nav-user-info" aria-selected="false">{{ __('User Information') }}</a>
            @endif
            <a class="nav-item nav-link" id="nav-address-tab" data-toggle="tab" href="#nav-address"
               role="tab"
               aria-controls="nav-address" aria-selected="false">{{ __('Address') }}</a>
            <a class="nav-item nav-link" id="nav-highschool-tab" data-toggle="tab" href="#nav-highschool"
               role="tab"
               aria-controls="nav-highschool" aria-selected="false">{{ __('High School') }}</a>
            <a class="nav-item nav-link" id="nav-university-tab" data-toggle="tab" href="#nav-university"
               role="tab"
               aria-controls="nav-university" aria-selected="false">{{ __('University') }}</a>
            <a class="nav-item nav-link" id="nav-documents-tab" data-toggle="tab" href="#nav-documents"
               role="tab"
               aria-controls="nav-documents" aria-selected="false">{{ __('Documents') }}</a>
        </div>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content" id="nav-tabContent">
                        @if($status == 0 or $status == 1)
                            <div class="tab-pane fade" id="nav-grupo"
                                 role="tabpanel"
                                 aria-labelledby="nav-grupo-tab">
                                @livewire('asignar-grupos')
                            </div>
                        @endif
                        <div class="tab-pane fade show active" id="nav-general" role="tabpanel"
                             aria-labelledby="nav-general-tab">
                            <form wire:submit="save">
                                <div class="row mb-4">
                                    <div class="col-lg-6 col-md-8 col-sm-7 d-flex flex-column justify-content-center">
                                        <div class="form-group">
                                            {{ __('Student Status') }}
                                            <div class="form-check">
                                                <label
                                                    class="form-check-label {{ $status == 1 ? 'text-success' : 'text-danger' }}">
                                                    <input type="checkbox" id="statusCheckbox" class="form-check-input"
                                                           onclick="updateStatus()" {{ $status == 1 ? 'checked' : '' }}
                                                           @if($status == 2) disabled @endif>
                                                    @if($status == 2)
                                                        {{ __('Incomplete information') }}
                                                    @elseif($status == 1)
                                                        {{   __('Active') }}
                                                    @elseif($status == 0)
                                                        {{ __('Low') }}
                                                    @endif
                                                    <i class="input-frame"></i>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="col-lg-6 col-md-4 col-sm-5 d-flex flex-column justify-content-center align-items-end">
                                        <a href="https://www.gob.mx/curp/" target="_blank"
                                           class="d-none d-sm-block btn btn-primary btn-icon-text mb-2">
                                            <i class="mdi mdi-qrcode-scan mr-2"></i>{{ __('Search CURP') }}
                                        </a>
                                        <a href="https://www.gob.mx/curp/" target="_blank"
                                           class="d-block d-sm-none btn btn-primary btn-icon-text mb-2 w-100">
                                            <i class="mdi mdi-qrcode-scan mr-2"></i>{{ __('Search CURP') }}
                                        </a>
                                        <button type="submit" class="d-none d-sm-block btn btn-linkedin btn-icon-text">
                                            <i class="mdi mdi-human-greeting mr-2"></i>
                                            {{ __('Update Student') }}
                                        </button>
                                        <button type="submit"
                                                class="d-block d-sm-none btn btn-linkedin btn-icon-text w-100">
                                            <i class="mdi mdi-human-greeting mr-2"></i>
                                            {{ __('Update Student') }}
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="nombre" class="control-label">{{ __('Name') }}</label>
                                            <input id="nombre" type="text"
                                                   class="form-control @error('nombre') is-invalid @enderror"
                                                   wire:model="nombre"
                                                   placeholder="{{ __('Name') }}"
                                                   autocomplete="nombre" autofocus>
                                            @error('nombre')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="apellido_paterno"
                                                   class="control-label">{{ __('PaternalLastName') }}</label>
                                            <input id="apellido_paterno" type="text"
                                                   class="form-control @error('apellido_paterno') is-invalid @enderror"
                                                   wire:model="apellido_paterno"
                                                   placeholder="{{ __('PaternalLastName') }}"
                                                   autocomplete="apellido_paterno">
                                            @error('apellido_paterno')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="apellido_materno"
                                                   class="control-label">{{ __('MaternalLastName') }}</label>
                                            <input id="apellido_materno" type="text"
                                                   class="form-control @error('apellido_materno') is-invalid @enderror"
                                                   wire:model="apellido_materno"
                                                   placeholder="{{ __('MaternalLastName') }}"
                                                   autocomplete="apellido_materno">
                                            @error('apellido_materno')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="curp" class="control-label">{{ __('CURP') }}</label>
                                            <input id="curp" type="text"
                                                   class="form-control @error('curp') is-invalid @enderror"
                                                   wire:model.defer="curp"
                                                   placeholder="{{ __('CURP') }}"
                                                   autocomplete="curp"
                                                   oninput="if(this.value.length === 18) @this.set('curp', this.value.toUpperCase()); else this.value = this.value.toUpperCase();"
                                                   maxlength="18">
                                            @error('curp')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="sexo" class="control-label">{{ __('Sex') }}</label>
                                            <select id="sexo" class="form-control @error('sexo') is-invalid @enderror"
                                                    wire:model="sexo">
                                                <option value="">{{ __('Select') }}</option>
                                                <option value="Hombre">{{ __('Hombre') }}</option>
                                                <option value="Mujer">{{ __('Mujer') }}</option>
                                            </select>
                                            @error('sexo')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="fecha_nacimiento"
                                                   class="control-label">{{ __('Birth Day') }}</label>
                                            <input id="fecha_nacimiento" type="date"
                                                   class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                                   wire:model="fecha_nacimiento"
                                                   placeholder="{{ __('Birth Day') }}"
                                                   autocomplete="fecha_nacimiento">
                                            @error('fecha_nacimiento')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="correo" class="control-label">{{ __('Email') }}</label>
                                            <input id="correo" type="text"
                                                   class="form-control @error('correo') is-invalid @enderror"
                                                   wire:model="correo"
                                                   placeholder="{{ __('Email') }}">
                                            @error('correo')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="telefono_emergencia"
                                                   class="control-label">{{ __('Emergency Phone') }}</label>
                                            <input id="telefono_emergencia" type="text"
                                                   class="form-control @error('telefono_emergencia') is-invalid @enderror"
                                                   wire:model="telefono_emergencia"
                                                   placeholder="{{ __('Emergency Phone') }}"
                                                   autocomplete="telefono_emergencia">
                                            @error('telefono_emergencia')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="telefono_alumno"
                                                   class="control-label">{{ __('WhatsApp Phone') }}</label>
                                            <input id="telefono_alumno" type="text"
                                                   class="form-control @error('telefono_alumno') is-invalid @enderror"
                                                   wire:model="telefono_alumno"
                                                   placeholder="{{ __('WhatsApp Phone') }}"
                                                   autocomplete="telefono_alumno">
                                            @error('telefono_alumno')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="facebook" class="control-label">{{ __('Facebook') }}</label>
                                            <input id="facebook" type="text"
                                                   class="form-control @error('facebook') is-invalid @enderror"
                                                   wire:model="facebook"
                                                   placeholder="{{ __('Facebook') }}"
                                                   autocomplete="facebook">
                                            @error('facebook')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="instagram" class="control-label">{{ __('Instagram') }}</label>
                                            <input id="instagram" type="text"
                                                   class="form-control @error('instagram') is-invalid @enderror"
                                                   wire:model="instagram"
                                                   placeholder="{{ __('Instagram') }}"
                                                   autocomplete="instagram">
                                            @error('instagram')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="nombre_tutor"
                                                   class="control-label">{{ __('Name') }} {{ strtolower(__('Tutor')) }}</label>
                                            <input id="nombre_tutor" type="text"
                                                   class="form-control @error('nombre_tutor') is-invalid @enderror"
                                                   wire:model="nombre_tutor"
                                                   placeholder="{{ __('Name') }} {{ strtolower(__('Tutor')) }}"
                                                   autocomplete="nombre_tutor">
                                            @error('nombre_tutor')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="apellido_paterno_tutor"
                                                   class="control-label">{{ __('PaternalLastName') }} {{ strtolower(__('Tutor')) }}</label>
                                            <input id="apellido_paterno_tutor" type="text"
                                                   class="form-control @error('apellido_paterno_tutor') is-invalid @enderror"
                                                   wire:model="apellido_paterno_tutor"
                                                   placeholder="{{ __('PaternalLastName') }} {{ strtolower(__('Tutor')) }}"
                                                   autocomplete="apellido_paterno_tutor">
                                            @error('apellido_paterno_tutor')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="apellido_materno_tutor"
                                                   class="control-label">{{ __('MaternalLastName') }} {{ strtolower(__('Tutor')) }}</label>
                                            <input id="apellido_materno_tutor" type="text"
                                                   class="form-control @error('apellido_materno_tutor') is-invalid @enderror"
                                                   wire:model="apellido_materno_tutor"
                                                   placeholder="{{ __('MaternalLastName') }} {{ strtolower(__('Tutor')) }}"
                                                   autocomplete="apellido_materno_tutor">
                                            @error('apellido_materno_tutor')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="telefono_tutor"
                                                   class="control-label">{{ __('Phone') }} {{ __('of') }} {{ strtolower(__('Tutor')) }}</label>
                                            <input id="telefono_tutor" type="text"
                                                   class="form-control @error('telefono_tutor') is-invalid @enderror"
                                                   wire:model="telefono_tutor"
                                                   placeholder="{{ __('Phone') }} {{ __('of') }} {{ strtolower(__('Tutor')) }}"
                                                   autocomplete="telefono_tutor">
                                            @error('telefono_tutor')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="parentesco_tutor"
                                                   class="control-label">{{ __('Relationship') }} {{ strtolower(__('Tutor')) }}</label>
                                            <select class="form-control @error('parentesco_tutor') is-invalid @enderror"
                                                    id="parentesco_tutor" wire:model="parentesco_tutor">
                                                <option value="" selected>{{ __('Select a relationship') }}</option>
                                                @foreach($parentescos as $parentesco)
                                                    <option
                                                        value="{{ $parentesco->nombre }}">{{ $parentesco->nombre }}</option>
                                                @endforeach
                                            </select>
                                            @error('parentesco_tutor')
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
                                            <label for="medio_interaccion"
                                                   class="control-label">{{ __('How did you know us?') }}</label>
                                            <select
                                                class="form-control @error('medio_interaccion') is-invalid @enderror"
                                                id="medio_interaccion" wire:model="medio_interaccion">
                                                <option value=""
                                                        selected>{{ __('Select How did you know us?') }}</option>
                                                @foreach($medios as $medio)
                                                    <option value="{{ $medio->nombre }}">{{ $medio->nombre }}</option>
                                                @endforeach
                                            </select>
                                            @error('medio_interaccion')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @if($status == 0 or $status == 1)
                            <div class="tab-pane fade" id="nav-user-info" role="tabpanel"
                                 aria-labelledby="nav-user-info-tab">
                                <form wire:submit="save">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <h6 class="card-title text-center">{{ __('Access Data to the Moodle') }}</h6>
                                                <label for="user_moodle"
                                                       class="control-label">{{ __('User Moodle') }}</label>
                                                <input id="user_moodle" type="text"
                                                       class="form-control"
                                                       wire:model="user_moodle"
                                                       placeholder="{{ __('User Moodle') }}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="password_moodle"
                                                       class="control-label">{{ __('Password Moodle') }}</label>
                                                <input id="password_moodle" type="text"
                                                       class="form-control"
                                                       wire:model="password_moodle"
                                                       placeholder="{{ __('Password Moodle') }}"
                                                       autocomplete="password_moodle" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <h6 class="card-title text-center">{{ __('System Access Data') }}</h6>
                                                <label for="correo_sistema"
                                                       class="control-label">{{ __('Email Address') }}</label>
                                                <input id="correo_sistema" type="text"
                                                       class="form-control"
                                                       wire:model="correo_sistema"
                                                       placeholder="{{ __('Email Address') }}"
                                                       autocomplete="correo_sistema" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">{{ __('Edit Profile & Image') }}</label>
                                                <a href="{{ route('edit_profile_student', ['id' => config('app.debug') ? $correo : encrypt($correo)]) }}"
                                                   class="btn btn-linkedin btn-icon-text w-100">
                                                    <i class="mdi mdi-account-key mr-2"></i>
                                                    {{ __('Update User Data') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                        <div class="tab-pane fade" id="nav-address" role="tabpanel"
                             aria-labelledby="nav-address-tab">
                            @livewire('direcciones')
                        </div>
                        <div class="tab-pane fade" id="nav-highschool" role="tabpanel"
                             aria-labelledby="nav-highschool-tab">
                            @livewire('media-superior-alumno')
                        </div>
                        <div class="tab-pane fade" id="nav-university" role="tabpanel"
                             aria-labelledby="nav-university-tab">
                            @livewire('universidad')
                        </div>
                        <div class="tab-pane fade" id="nav-documents" role="tabpanel"
                             aria-labelledby="nav-documents-tab">
                            @livewire('documentos')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function updateStatus() {
        var checkbox = document.getElementById('statusCheckbox');
    @this.set('status', checkbox.checked)
    }

    window.addEventListener('alumnoUpdated', () => {
        Swal.fire({
            title: '{{ __('Updated') }}',
            text: '{{ __('Register Updated') }}',
            icon: 'success',
            confirmButtonText: '{{ __('Ok') }}'
        });
    });

    document.querySelectorAll('.btn-back').forEach(button => {
        button.addEventListener('click', function () {
            window.history.back();
        });
    });
</script>
