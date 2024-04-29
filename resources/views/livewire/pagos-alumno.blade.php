@section('title', __('Account Status'))

<div>
    <div class="row mb-4">
        <div class="col-lg-8 col-md-6 col-sm-6">
            <nav class="page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">{{ __('Management') }}</li>
                    <li class="breadcrumb-item" aria-current="page"><a
                            href="{{ route('students') }}">{{ __('Students') }}</a>
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
            <button wire:click="$dispatch('open-create-concepto-pago-alumno-modal')" type="button"
                    class="d-none d-sm-block btn btn-success btn-icon-text ml-2">
                <i class="mdi mdi-counter mr-2"></i>
                {{ __('Add Payment Concept') }}
            </button>
            <button wire:click="$dispatch('open-create-concepto-pago-alumno-modal')" type="button"
                    class="d-block d-sm-none btn btn-success btn-icon-text w-100">
                <i class="mdi mdi-counter mr-2"></i>
                {{ __('Add Payment Concept') }}
            </button>
        </div>
    </div>
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-general-tab" data-toggle="tab" href="#nav-general"
               role="tab"
               aria-controls="nav-general" aria-selected="true">{{ __('Account Status') }}</a>
        </div>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-general" role="tabpanel"
                             aria-labelledby="nav-general-tab">
                            <div class="row mb-3">
                                <div class="col-md-6 d-flex flex-column justify-content-center align-items-start">
                                    <p class="mb-1">
                                        <strong>{{ __('CURP') }}</strong>: {{ $curpAlumno }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>{{ __('Name') }}</strong>: {{ $nombreAlumno }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>{{ __('Email Address') }}</strong>: {{ $correoAlumno }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>{{ __('Phone') }}</strong>: {{ $telefonoAlumno }}
                                    </p>
                                </div>
                                <div class="col-md-6 d-flex flex-column justify-content-center align-items-end">
                                    <h5 class="text-primary font-weight-bold mb-1">
                                        @php
                                            $total = 0;
                                        @endphp
                                        @foreach ($list_concepto_pago_alumno as $concepto_pago_alumno)
                                            @php
                                                $total += $concepto_pago_alumno->cargo;
                                            @endphp
                                        @endforeach
                                        {{ __('Total') }}: {{ number_format($total, 2) }}
                                    </h5>
                                    <h5 class="text-success font-weight-bold mb-1">
                                        @php
                                            $totalAbono = 0;
                                        @endphp
                                        @foreach ($list_concepto_pago_alumno as $concepto_pago_alumno)
                                            @php
                                                $totalAbono += $concepto_pago_alumno->abono;
                                            @endphp
                                        @endforeach
                                        {{ __('toPay') }}: {{ number_format($totalAbono, 2) }}
                                    </h5>
                                    <h5 class="text-danger font-weight-bold mb-1">
                                        @php
                                            $totalCargo = 0;
                                            $totalAbono = 0;
                                        @endphp
                                        @foreach ($list_concepto_pago_alumno as $concepto_pago_alumno)
                                            @php
                                                $totalCargo += $concepto_pago_alumno->cargo;
                                                $totalAbono += $concepto_pago_alumno->abono;
                                            @endphp
                                        @endforeach
                                        @php
                                            $totalSaldo = $totalCargo - $totalAbono;
                                        @endphp
                                        {{ __('Balance') }}: {{ number_format($totalSaldo, 2) }}
                                    </h5>
                                </div>
                            </div>
                            <h5 class="text-center text-uppercase mt-3 mb-3">{{ __('Payment Concepts') }}</h5>
                            <div class="row">
                                @if ($list_concepto_pago_alumno->count() == 0)
                                    <div class="alert alert-primary-muted" role="alert">
                                        <h4 class="alert-heading">{{ __('No records found!') }}</h4>
                                        <p>{{ __('No records have been added to the database yet. We suggest creating the first record.') }}</p>
                                        <hr>
                                        <div class="row justify-content-center">
                                            <img src="{{ asset('assets/images/404.svg') }}" class="w-75 mt-2 mb-2">
                                        </div>
                                    </div>
                                @else
                                    <div class="col">
                                        <p class="mb-3">
                                            @if($list_concepto_pago_alumno->total() >= 0)
                                                {{ __('Showing') }}
                                                @if(!$list_concepto_pago_alumno->firstItem())
                                                    {{ $list_concepto_pago_alumno->firstItem() }}
                                                @else
                                                    {{ __('of') }}
                                                @endif
                                                @if($list_concepto_pago_alumno->firstItem())
                                                    {{ $list_concepto_pago_alumno->firstItem() }}
                                                @else
                                                    0
                                                @endif
                                                {{ __('to') }}
                                                @if($list_concepto_pago_alumno->lastItem())
                                                    {{ $list_concepto_pago_alumno->lastItem() }}
                                                @else
                                                    0
                                                @endif
                                                {{ __('of') }} {{ $list_concepto_pago_alumno->total() }} {{ __('entries') }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col text-right">
                                        <button wire:click="printPaymentSchedule" type="button"
                                                class="btn btn-inverse-primary btn-icon mr-2 printPaymentSchedule"
                                                data-toggle="tooltip" data-placement="top"
                                                title="{{ __('Print payment schedule') }}">
                                            <i class="mdi mdi-calendar"></i>
                                        </button>
                                        <button wire:click="printPaymentSchedule" type="button"
                                                class="btn btn-inverse-success btn-icon printPaymentSchedule"
                                                data-toggle="tooltip" data-placement="top"
                                                title="{{ __('Print Account Status') }}">
                                            <i class="mdi mdi-account-box"></i>
                                        </button>
                                    </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                    <tr>
                                        <th>
                                            {{ __('Invoice')  }}
                                        </th>
                                        <th>
                                            {{ __('Due date')  }}
                                        </th>
                                        <th>
                                            {{ __('Concept')  }}
                                        </th>
                                        <th>
                                            {{ __('Charge')  }}
                                        </th>
                                        <th>
                                            {{ __('toPay')  }}
                                        </th>
                                        <th>
                                            {{ __('Balance')  }}
                                        </th>
                                        <th>
                                            {{ __('State')  }}
                                        </th>
                                        <th>
                                            {{ __('Edit')  }}
                                        </th>
                                        <th>
                                            {{ __('Delete')  }}
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($list_concepto_pago_alumno as $concepto_pago_alumno)
                                        <tr>
                                            <td>
                                                {{ Str::limit($concepto_pago_alumno->folio, 30) }}
                                            </td>
                                            <td>{{  date('d-m-Y', strtotime($concepto_pago_alumno->fecha_vencimiento)) }}</td>
                                            <td>
                                                {{ Str::limit($concepto_pago_alumno->concepto, 30) }}
                                            </td>
                                            <td>
                                                {{ number_format($concepto_pago_alumno->cargo, 2) }}
                                            </td>
                                            <td>
                                                {{ number_format( $concepto_pago_alumno->abono, 2) }}
                                            </td>
                                            <td>
                                                {{ number_format(($concepto_pago_alumno->cargo - $concepto_pago_alumno->abono), 2) }}
                                            </td>
                                            <td>
                                                @if ($concepto_pago_alumno->fecha_vencimiento < $fecha_actual)
                                                    <span
                                                        class="badge badge-danger text-white">{{ __('Defeated') }}</span>
                                                @elseif ($concepto_pago_alumno->estado_pago == 1)
                                                    <span class="badge badge-success">{{ __('Paid') }}</span>
                                                @elseif ($concepto_pago_alumno->estado_pago == 0)
                                                    <span
                                                        class="badge badge-warning text-white">{{ __('Outstanding balance') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button
                                                    wire:click="$dispatch('open-edit-concepto-pago-alumno-modal', '{{ config('app.debug') ? $concepto_pago_alumno->folio : encrypt($concepto_pago_alumno->folio) }}')"
                                                    type="button"
                                                    class="btn btn-linkedin btn-icon-text mb-1 mb-md-0"
                                                    data-toggle="tooltip"
                                                    data-placement="top" title="{{ __('Edit') }}">
                                                    <i class="mdi mdi-lead-pencil"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <button
                                                    wire:click="$dispatch('delete-prompt', '{{ config('app.debug') ? $concepto_pago_alumno->id : encrypt($concepto_pago_alumno->id) }}')"
                                                    type="button"
                                                    class="btn btn-danger btn-icon-text mb-1 mb-md-0"
                                                    data-toggle="tooltip"
                                                    data-placement="top" title="{{ __('Delete') }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col mt-3 mb-2">
                                {{ $list_concepto_pago_alumno->links() }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="createConceptoPagoAlumno" data-backdrop="static" tabindex="-1"
         role="dialog"
         aria-labelledby="createConceptoPagoAlumnoLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createConceptoPagoAlumnoLabel">{{ __('Add Payment Concept') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit="createConceptoPagoAlumno">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="folio_alumno" class="control-label">{{ __('Invoice') }}</label>
                                    <input id="folio_alumno" type="text"
                                           class="form-control @error('folio_alumno') is-invalid @enderror"
                                           wire:model="folio_alumno"
                                           placeholder="{{ __('Invoice') }}"
                                           autocomplete="folio_alumno" readonly autofocus>
                                    @error('folio_alumno')
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
                                    <label for="concepto_pago_alumno"
                                           class="control-label">{{ __('Payment Concept') }}</label>
                                    <div wire:ignore>
                                        <select
                                            class="form-control" id="concepto_pago_alumno" style="width: 100%;">
                                            <option value="" selected>{{ __('Select Payment Concept') }}</option>
                                            @foreach($list_conceptos_pago as $concepto_pago)
                                                <option
                                                    value="{{ $concepto_pago->nombre }}">{{ $concepto_pago->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden"
                                           class="form-control @error('concepto_pago_alumno') is-invalid @enderror"
                                           wire:model="concepto_pago_alumno"
                                           placeholder="{{ __('Payment Concept') }}"
                                           autocomplete="concepto_pago_alumno">
                                    @error('concepto_pago_alumno')
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
                                    <label for="cargo_alumno"
                                           class="control-label">{{ __('Charge') }}</label>
                                    <input id="cargo_alumno" type="text"
                                           class="form-control input-numerico @error('cargo_alumno') is-invalid @enderror"
                                           wire:model="cargo_alumno"
                                           placeholder="{{ __('Charge') }}"
                                           autocomplete="cargo_alumno">
                                    @error('cargo_alumno')
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
                                    <label for="fecha_vencimiento_alumno"
                                           class="control-label">{{ __('Due date') }}</label>
                                    <input id="fecha_vencimiento_alumno" type="date"
                                           class="form-control @error('fecha_vencimiento_alumno') is-invalid @enderror"
                                           wire:model="fecha_vencimiento_alumno"
                                           placeholder="{{ __('Due date') }}"
                                           autocomplete="fecha_vencimiento_alumno">
                                    @error('fecha_vencimiento_alumno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-icon-text">
                            <i class="mdi mdi-counter mr-2"></i>{{ __('Add') }}
                        </button>
                        <button type="button" class="btn btn-danger btn-icon-text" data-dismiss="modal">
                            <i class="mdi mdi-window-close mr-2"></i>{{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="editConceptoPagoAlumno" data-backdrop="static" tabindex="-1"
         role="dialog"
         aria-labelledby="editConceptoPagoAlumnoLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editConceptoPagoAlumnoLabel">{{ __('Update Payment Concept') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit="editConceptoPagoAlumno">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="folio_alumno_actual" class="control-label">{{ __('Invoice') }}</label>
                                    <input id="folio_alumno_actual" type="text"
                                           class="form-control @error('folio_alumno_actual') is-invalid @enderror"
                                           wire:model="folio_alumno_actual"
                                           placeholder="{{ __('Invoice') }}"
                                           autocomplete="folio_alumno_actual" readonly autofocus>
                                    @error('folio_alumno_actual')
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
                                    <label for="concepto_pago_alumno"
                                           class="control-label">{{ __('Payment Concept') }}</label>
                                    <div wire:ignore>
                                        <select
                                            class="form-control" id="concepto_pago_alumno_editar" style="width: 100%;">
                                            <option value="" selected>{{ __('Select Payment Concept') }}</option>
                                            @foreach($cursos as $curso)
                                                <option
                                                    value="{{ $curso->nombre }}">{{ $curso->nombre }}</option>
                                            @endforeach
                                            @foreach($list_conceptos_pago as $concepto_pago)
                                                <option
                                                    value="{{ $concepto_pago->nombre }}">{{ $concepto_pago->nombre }}</option>
                                            @endforeach
                                            @foreach($cursos as $curso)
                                                <option
                                                    value="{{ __('Inscription') }} - {{ $curso->nombre }}">{{ __('Inscription') }}
                                                    - {{ $curso->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden"
                                           class="form-control @error('concepto_pago_alumno') is-invalid @enderror"
                                           wire:model="concepto_pago_alumno"
                                           placeholder="{{ __('Payment Concept') }}"
                                           autocomplete="concepto_pago_alumno">
                                    @error('concepto_pago_alumno')
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
                                    <label for="cargo_alumno"
                                           class="control-label">{{ __('Charge') }}</label>
                                    <input id="cargo_alumno" type="text"
                                           class="form-control input-numerico @error('cargo_alumno') is-invalid @enderror"
                                           wire:model="cargo_alumno"
                                           placeholder="{{ __('Charge') }}"
                                           autocomplete="cargo_alumno">
                                    @error('cargo_alumno')
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
                                    <label for="fecha_vencimiento_alumno"
                                           class="control-label">{{ __('Due date') }}</label>
                                    <input id="fecha_vencimiento_alumno" type="date"
                                           class="form-control @error('fecha_vencimiento_alumno') is-invalid @enderror"
                                           wire:model="fecha_vencimiento_alumno"
                                           placeholder="{{ __('Due date') }}"
                                           autocomplete="fecha_vencimiento_alumno">
                                    @error('fecha_vencimiento_alumno')
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
                                    <label for="concepto_pago_alumno_seleccionado"
                                           class="control-label">{{ __('Transfer payment') }}</label>
                                    <div wire:ignore>
                                        <select
                                            class="form-control" id="concepto_pago_alumno_seleccionado"
                                            style="width: 100%;">
                                            <option value="" selected>{{ __('Select Student') }}</option>
                                            @foreach($list_alumnos_grupo as $alumno_grupo)
                                                <option
                                                    value="{{ $alumno_grupo->curp }}">{{ $alumno_grupo->nombre }} {{ $alumno_grupo->apellido_paterno }} {{ $alumno_grupo->apellido_materno }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden"
                                           class="form-control @error('concepto_pago_alumno_seleccionado') is-invalid @enderror"
                                           wire:model="concepto_pago_alumno_seleccionado"
                                           placeholder="{{ __('Student') }}"
                                           autocomplete="concepto_pago_alumno_seleccionado">
                                    @error('concepto_pago_alumno_seleccionado')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-linkedin btn-icon-text">
                            <i class="mdi mdi-counter mr-2"></i>{{ __('Update') }}
                        </button>
                        <button type="button" class="btn btn-danger btn-icon-text" data-dismiss="modal">
                            <i class="mdi mdi-window-close mr-2"></i>{{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputsNumericos = document.querySelectorAll('.input-numerico');

        inputsNumericos.forEach(function (input) {
            input.addEventListener('input', restringirEntradaNumerica);
        });

        function restringirEntradaNumerica(event) {
            // Remover todos los caracteres no numéricos excepto el punto decimal
            this.value = this.value.replace(/[^\d.]/g, '');

            // Si hay más de un punto decimal, eliminar los extras
            const puntos = this.value.match(/\./g);
            if (puntos && puntos.length > 1) {
                const lastIndex = this.value.lastIndexOf('.');
                this.value = this.value.substring(0, lastIndex) + this.value.substring(lastIndex + 1).replace(/\./g, '');
            }

            // Si el valor contiene más de dos decimales, recortar
            const partes = this.value.split('.');
            if (partes[1] && partes[1].length > 2) {
                partes[1] = partes[1].substring(0, 2);
                this.value = partes.join('.');
            }

            // Si el valor es mayor a 10 caracteres, recortar
            if (this.value.length > 10) {
                this.value = this.value.substring(0, 10);
            }
        }
    });

    $(document).ready(function () {
        $('#concepto_pago_alumno').select2();
        $('#concepto_pago_alumno_editar').select2();
        $('#concepto_pago_alumno_seleccionado').select2();

        $('#concepto_pago_alumno').select2().on('change', function (e) {
        @this.set('concepto_pago_alumno', e.target.value)
        });

        $('#concepto_pago_alumno_editar').select2().on('change', function (e) {
        @this.set('concepto_pago_alumno', e.target.value)
        });

        $('#concepto_pago_alumno_seleccionado').select2().on('change', function (e) {
        @this.set('concepto_pago_alumno_seleccionado', e.target.value)
        });
    });

    document.addEventListener("livewire:load", function (event) {
        window.livewire.hook('afterDomUpdate', () => {
            $('#concepto_pago_alumno').select2();
            $('#concepto_pago_alumno_editar').select2();
            $('#concepto_pago_alumno_seleccionado').select2();
        });
    });

    document.querySelectorAll('.btn-back').forEach(button => {
        button.addEventListener('click', function () {
            window.history.back();
        });
    });

    document.addEventListener('livewire:initialized', () => {
    @this.on('delete-prompt', conceptoPagoAlumnoID => {
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
            @this.dispatch('goOn-Delete', {conceptoPagoAlumnoID: conceptoPagoAlumnoID})

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

    @this.on('open-edit-concepto-pago-alumno-modal', conceptoPagoAlumnoFolio => {
        $('#editConceptoPagoAlumno').modal('show');
        $('#concepto_pago_alumno_seleccionado').val('').trigger('change');

    @this.set('folio_alumno_actual', conceptoPagoAlumnoFolio)
    })
    });

    window.addEventListener('open-create-concepto-pago-alumno-modal', event => {
        $('#createConceptoPagoAlumno').modal('show');
    @this.set('folio_alumno', 'Generando Folio...')

        $('#createConceptoPagoAlumno').on('hidden.bs.modal', function () {
            $('#concepto_pago_alumno').val('').trigger('change');
        });
    });

    window.addEventListener('close-create-concepto-pago-alumno-modal', event => {
        $('#createConceptoPagoAlumno').modal('hide');
    });

    window.addEventListener('pagoCreated', () => {
        Swal.fire({
            title: '{{ __('Created') }}',
            text: '{{ __('Register Created') }}',
            icon: 'success',
            confirmButtonText: '{{ __('Ok') }}'
        });
    });

    window.addEventListener('close-edit-concepto-pago-alumno-modal', event => {
        $('#editConceptoPagoAlumno').modal('hide');
    });

    window.addEventListener('selectConceptoPagoAlumnoModal', function (event) {
        var conceptoPagoAlumnoFolio = event.__livewire.params[0];
        $('#concepto_pago_alumno_editar').val(conceptoPagoAlumnoFolio).trigger('change');
    });

    window.addEventListener('pagoUpdated', () => {
        Swal.fire({
            title: '{{ __('Updated') }}',
            text: '{{ __('Register Updated') }}',
            icon: 'success',
            confirmButtonText: '{{ __('Ok') }}'
        });
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

    $('.printPaymentSchedule').on("click", function () {
        showDownloadToast();
    });
</script>
