@section('title', __('Students'))

<div>
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">{{ __('Management') }}</li>
            <li class="breadcrumb-item" aria-current="page"><a class="text-facebook"
                                                               href="{{ route('students') }}">{{ __('Students') }}</a>
            </li>
        </ol>
    </nav>
    <div class="row mb-4">
        <div class="col-lg-6 col-md-8 col-sm-7">
            <div class="search-form">
                <div class="input-group border border-primary rounded-sm">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary border-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="white" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-search icon-md">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </div>
                    </div>
                    <input wire:model.live="search" class="form-control" type="text"
                           placeholder="{{ __('Search...') }}" autofocus autocomplete="search-alumnos">
                </div>
            </div>
        </div>
        @foreach ($modulePermissions as $modulePermission)
            @if($modulePermission->route_name === 'create_student')
                <div class="col-lg-6 col-md-4 col-sm-5 d-flex flex-column justify-content-center align-items-end">
                    <button wire:click="$dispatch('open-create-alumno-modal')"
                            class="d-none d-sm-block btn btn-success btn-icon-text">
                        <i class="mdi mdi-human-greeting mr-2"></i>
                        {{ __('Create Student') }}
                    </button>
                    <button wire:click="$dispatch('open-create-alumno-modal')"
                            class="d-block d-sm-none btn btn-success btn-icon-text w-100 mt-4">
                        <i class="mdi mdi-human-greeting mr-2"></i>
                        {{ __('Create Student') }}
                    </button>
                </div>
            @endif
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-7">
                            <button type="button" class="btn btn-primary mt-1" wire:click="filterByStatus">
                                {{ __('All') }}
                            </button>
                            <button type="button" class="btn btn-success mt-1" wire:click="filterByStatus(1)">
                                {{ __('Active') }}
                            </button>
                            <button type="button" class="btn btn-danger mt-1" wire:click="filterByStatus(0)">
                                {{ __('Low') }}
                            </button>
                            <button type="button" class="btn btn-warning text-white mt-1"
                                    wire:click="filterByStatus(2)">
                                {{ __('Incomplete information') }}
                            </button>
                        </div>
                        <div class="col-sm-5 d-flex justify-content-end mt-2 mt-sm-0">
                            <input id="filtro_fecha_inscripcion" type="date"
                                   class="form-control @error('filtro_fecha_inscripcion') is-invalid @enderror"
                                   wire:model.live="filtro_fecha_inscripcion"
                                   placeholder="{{ __('Birth Day') }}"
                                   autocomplete="filtro_fecha_inscripcion">
                            @error('filtro_fecha_inscripcion')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    @if (empty($search) && $listAlumnos->count() == 0)
                        <div class="alert alert-primary-muted" role="alert">
                            <h4 class="alert-heading">{{ __('No records found!') }}</h4>
                            <p>{{ __('No records have been added to the database yet. We suggest creating the first record.') }}</p>
                            <hr>
                            <div class="row justify-content-center">
                                <img src="{{ asset('assets/images/404.svg') }}" class="w-75 mt-2 mb-2">
                            </div>
                        </div>
                    @elseif ($listAlumnos->count())
                        <div class="row">
                            <div class="col">
                                <p class="mb-3">
                                    @if($totalEntries > 0)
                                        {{ __('Showing') }}
                                        @if(!$listAlumnos->firstItem())
                                            {{ $listAlumnos->firstItem() }}
                                        @else
                                            {{ __('of') }}
                                        @endif
                                        {{ $listAlumnos->firstItem() }} {{ __('to') }} {{ $listAlumnos->lastItem() }} {{ __('of') }} {{ $listAlumnos->total() }} {{ __('entries') }}
                                    @elseif($listAlumnos->total() >= 0)
                                        {{ __('Showing') }}
                                        @if(!$listAlumnos->firstItem())
                                            {{ $listAlumnos->firstItem() }}
                                        @else
                                            {{ __('of') }}
                                        @endif
                                        @if($listAlumnos->firstItem())
                                            {{ $listAlumnos->firstItem() }}
                                        @else
                                            0
                                        @endif
                                        {{ __('to') }}
                                        @if($listAlumnos->lastItem())
                                            {{ $listAlumnos->lastItem() }}
                                        @else
                                            0
                                        @endif
                                        {{ __('of') }} {{ $listAlumnos->total() }} {{ __('entries') }}
                                        @if($totalEntries > 0)
                                            ({{ __('filtered from') }} {{ $totalEntries }} {{ __('entries') }})
                                        @endif
                                    @endif
                                </p>
                            </div>
                            <div class="col text-right">
                                <button id="downloadFile" wire:click="export" type="button"
                                        class="btn btn-success btn-icon"
                                        data-toggle="tooltip" data-placement="top" title="{{ __('Export') }}">
                                    <i class="mdi mdi-download"></i>
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                <tr>
                                    <th>
                                        <a wire:click.prevent="sortBy('curp')" role="button"
                                           href="#">
                                            <i class="mdi mdi-filter ml-0"></i> {{ __('curp')  }}
                                        </a>
                                    </th>
                                    <th>
                                        <a wire:click.prevent="sortBy('nombre')" role="button"
                                           href="#">
                                            <i class="mdi mdi-filter ml-0"></i> {{ __('Name')  }}
                                        </a>
                                    </th>
                                    <th>
                                        <a wire:click.prevent="sortBy('correo')" role="button"
                                           href="#">
                                            <i class="mdi mdi-filter ml-0"></i> {{ __('Email Address')  }}
                                        </a>
                                    </th>
                                    <th>
                                        <a wire:click.prevent="sortBy('created_at')" role="button"
                                           href="#">
                                            <i class="mdi mdi-filter ml-0"></i> {{ __('Inscription Date')  }}
                                        </a>
                                    </th>
                                    <th>
                                        <a wire:click.prevent="sortBy('status')" role="button"
                                           href="#">
                                            <i class="mdi mdi-filter ml-0"></i> {{ __('Status')  }}
                                        </a>
                                    </th>
                                    @foreach ($modulePermissions as $modulePermission)
                                        @if($modulePermission->route_name === 'edit_payment_student')
                                            <th class="text-center">
                                                {{ __('Payments')  }}
                                            </th>
                                        @endif
                                    @endforeach
                                    @foreach ($modulePermissions as $modulePermission)
                                        @if($modulePermission->route_name === 'edit_student')
                                            <th class="text-center">
                                                {{ __('Edit')  }}
                                            </th>
                                        @endif
                                        @if($modulePermission->route_name === 'delete_student')
                                            <th class="text-center">
                                                {{ __('Delete')  }}
                                            </th>
                                        @endif
                                    @endforeach
                                    <th class="text-center">
                                        {{ __('Credential') }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($listAlumnos as $alumno)
                                    <tr>
                                        <td>
                                            {{ $alumno->curp }}
                                        </td>
                                        <td>
                                            @php
                                                $nameStudent = $alumno->nombre .' ' . $alumno->apellido_paterno . ' ' . $alumno->apellido_materno;
                                            @endphp
                                            {{ Str::limit($nameStudent, 40) }}
                                        </td>
                                        <td>{{ Str::limit($alumno->correo, 25) }}</td>
                                        <td>{{  date('d-m-Y', strtotime($alumno->created_at)) }}</td>
                                        <td class="text-center">
                                            @if ($alumno->status == 2)
                                                <span
                                                    class="badge badge-warning text-white">{{ __('Incomplete information') }}</span>
                                            @elseif ($alumno->status == 1)
                                                <span class="badge badge-success">{{ __('Active') }}</span>
                                            @elseif ($alumno->status == 0)
                                                <span
                                                    class="badge badge-danger text-white">{{ __('Low') }}</span>
                                            @endif
                                        </td>
                                        @foreach ($modulePermissions as $modulePermission)
                                            @if($modulePermission->route_name === 'edit_payment_student')
                                                <td class="text-center">
                                                    <a href="{{ route('edit_payment_student', ['id' => config('app.debug') ? $alumno->id : encrypt($alumno->id)]) }}"
                                                       class="btn btn-inverse-success btn-icon-text mb-1 mb-md-0"
                                                       data-toggle="tooltip" data-placement="top"
                                                       title="{{ __('Payments') }}">
                                                        <i class="mdi mdi-cash-usd"></i>
                                                    </a>
                                                </td>
                                            @endif
                                        @endforeach
                                        @foreach ($modulePermissions as $modulePermission)
                                            @if($modulePermission->route_name === 'edit_student')
                                                <td class="text-center">
                                                    <a href="{{ route('edit_student', ['id' => config('app.debug') ? $alumno->id : encrypt($alumno->id)]) }}"
                                                       class="btn btn-linkedin btn-icon-text mb-1 mb-md-0"
                                                       data-toggle="tooltip"
                                                       data-placement="top" title="{{ __('Edit') }}">
                                                        <i class="mdi mdi-lead-pencil"></i>
                                                    </a>
                                                </td>
                                            @endif
                                            @if($modulePermission->route_name === 'delete_student')
                                                <td class="text-center">
                                                    <button
                                                        wire:click="$dispatch('delete-prompt-alumno', '{{ config('app.debug') ? $alumno->id : encrypt($alumno->id) }}')"
                                                        type="button"
                                                        class="btn btn-danger btn-icon-text mb-1 mb-md-0"
                                                        data-toggle="tooltip"
                                                        data-placement="top" title="{{ __('Delete') }}">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </td>
                                            @endif
                                        @endforeach
                                        @if ($alumno->status == 1)
                                            <td class="text-center">
                                                <button
                                                    wire:click="$dispatch('print-credential-student', '{{ config('app.debug') ? $alumno->id : encrypt($alumno->id) }}')"
                                                    type="button"
                                                    class="btn btn-inverse-primary btn-icon-text mb-1 mb-md-0"
                                                    data-toggle="tooltip"
                                                    data-placement="top" title="{{ __('Export credential') }}">
                                                    <i class="mdi mdi-account-box"></i>
                                                </button>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                @if ($alumno->status == 2)
                                                    <span
                                                        class="badge badge-warning text-white">{{ __('Incomplete information') }}</span>
                                                @elseif ($alumno->status == 1)
                                                    <span class="badge badge-success">{{ __('Active') }}</span>
                                                @elseif ($alumno->status == 0)
                                                    <span
                                                        class="badge badge-danger text-white">{{ __('Low') }}</span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-primary-muted" role="alert">
                            <h4 class="alert-heading">{{ __('No results found') }}</h4>
                            <p>{{ __('It seems that there are no results matching your current search. We recommend trying different search criteria or creating a new record.') }}</p>
                            <hr>
                            <div class="row justify-content-center">
                                <img src="{{ asset('assets/images/404.svg') }}" class="w-75 mt-2 mb-2">
                            </div>
                        </div>
                    @endif
                </div>
                @if ($totalEntries > 0)
                    <div class="col mb-2">
                        {{ $listAlumnos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @foreach ($modulePermissions as $modulePermission)
        @if($modulePermission->route_name === 'create_student')
            <div wire:ignore.self class="modal fade" id="createAlumno" data-backdrop="static" tabindex="-1"
                 role="dialog"
                 aria-labelledby="createAlumnoLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createAlumnoLabel">{{ __('Create Student') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form wire:submit="createAlumno">
                            <div class="modal-body">
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
                                                   autocomplete="fecha_nacimiento"
                                            >
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
                                                   placeholder="{{ __('Email') }}"
                                                   autocomplete="correo">
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
                                                   class="form-control input-numerico @error('telefono_emergencia') is-invalid @enderror"
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
                                                   class="form-control input-numerico @error('telefono_alumno') is-invalid @enderror"
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
                                                   class="form-control input-numerico @error('telefono_tutor') is-invalid @enderror"
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
                            </div>
                            <div class="modal-footer">
                                <a href="https://www.gob.mx/curp/" target="_blank"
                                   class="btn btn-primary btn-icon-text">
                                    <i class="mdi mdi-qrcode-scan mr-2"></i>{{ __('Search CURP') }}
                                </a>
                                <button type="submit" class="btn btn-success btn-icon-text">
                                    <i class="mdi mdi-human-greeting mr-2"></i>{{ __('Create') }}
                                </button>
                                <button type="button" class="btn btn-danger btn-icon-text" data-dismiss="modal">
                                    <i class="mdi mdi-window-close mr-2"></i>{{ __('Cancel') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputsNumericos = document.querySelectorAll('.input-numerico');

        inputsNumericos.forEach(function (input) {
            input.addEventListener('input', restringirEntradaNumerica);
        });

        function restringirEntradaNumerica(event) {
            this.value = this.value.replace(/\D/g, '');

            if (this.value.length > 10) {
                this.value = this.value.substring(0, 10);
            }
        }
    });

    window.addEventListener('open-create-alumno-modal', event => {
        $('#createAlumno').modal('show');
    });

    window.addEventListener('close-create-alumno-modal', event => {
        $('#createAlumno').modal('hide');
    });

    window.addEventListener('alumnoCreated', () => {
        Swal.fire({
            title: '{{ __('Created') }}',
            text: '{{ __('Register Created') }}',
            icon: 'success',
            confirmButtonText: '{{ __('Ok') }}'
        });
    });

    document.addEventListener('livewire:initialized', () => {
    @this.on('delete-prompt-alumno', alumnoId => {
        Swal.fire({
            title: '{{ __('Are you sure?') }}',
            text: '{{ __("You won't be able to revert this!") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __('Yes, delete it!') }}',
            cancelButtonText: '{{ __('No, cancel!') }}',
            reverseButtons: false,
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
            @this.dispatch('goOn-Delete-Alumno', {alumnoId: alumnoId})

            @this.on('deleted', (event) => {
                Swal.fire({
                    title: '{{ __('Deleted') }}',
                    text: '{{ __("Your record has been deleted.") }}',
                    icon: 'success',
                });
            })
            }
        });
    })

    @this.on('print-credential-student', alumnoId => {
    @this.dispatch('goOn-Print-Credential-Alumno', {alumnoId: alumnoId})
        showDownloadToast();
    })
    });

    function showDownloadToast() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 6000,
            timerProgressBar: true,
        });

        Toast.fire({
            icon: 'success',
            title: '{{ __('Processing your download. Please wait...') }}',
        });
    }

    $('#downloadFile').on("click", function () {
        showDownloadToast();
    });
</script>
