<div>
    <div class="row">
        <div class="col-sm-6">
            <div class="search-form">
                <div class="input-group border border-primary rounded-sm">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-primary border-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                 height="24"
                                 viewBox="0 0 24 24"
                                 fill="none" stroke="white" stroke-width="2"
                                 stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="feather feather-search icon-md">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </div>
                    </div>
                    <input wire:model.live="search" class="form-control"
                           type="text"
                           placeholder="{{ __('Search Student...') }}" autofocus
                           autocomplete="search-alumnos">
                </div>
            </div>
        </div>
        <div
            class="col-lg-6 col-md-4 col-sm-5 d-flex flex-column justify-content-center align-items-end">
            <button wire:click="$dispatch('open-create-concepto-pago-alumno-modal')" type="button"
                    class="d-none d-sm-block btn btn-primary btn-icon-text mb-2">
                <i class="mdi mdi-counter mr-2"></i>{{ __('Add Payment Concept') }}
            </button>
            <button wire:click="$dispatch('open-create-concepto-pago-alumno-modal')" type="button"
                    class="d-block d-sm-none btn btn-primary btn-icon-text mb-2 w-100">
                <i class="mdi mdi-counter mr-2"></i>{{ __('Add Payment Concept') }}
            </button>
            <button wire:click="$dispatch('set-curp')" class="d-none d-sm-block btn btn-success btn-icon-text">
                <i class="mdi mdi-human-greeting mr-2"></i>
                {{ __('Add') }}
            </button>
            <button wire:click="$dispatch('set-curp')"
                    class="d-block d-sm-none btn btn-success btn-icon-text w-100">
                <i class="mdi mdi-human-greeting mr-2"></i>
                {{ __('Add') }}
            </button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="fecha_inscripcion" class="control-label">{{ __('Inscription Date') }}</label>
                <input id="fecha_inscripcion" type="date"
                       class="form-control @error('fecha_inscripcion') is-invalid @enderror"
                       wire:model.live="fecha_inscripcion"
                       placeholder="{{ __('Birth Day') }}"
                       autocomplete="fecha_inscripcion">
                @error('fecha_inscripcion')
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
                <label for="alumno" class="control-label">{{ __('Students') }}</label>
                <select id="alumno" wire:model="alumno" class="form-control @error('alumno') is-invalid @enderror">
                    @if($select_alumno == true)
                        <option value="" selected>{{ __('Select Student') }}</option>
                    @else
                        @if(count($options) == 0)
                            <option value="" selected>{{ __('No results for your search') }}</option>
                        @else
                            <option value="" selected>{{ __('Select Student By Search') }}</option>
                        @endif
                    @endif
                    @foreach ($options as $option)
                        @php
                            $contains = false;
                            foreach ($list_alumnos as $alumno) {
                                if (str_contains($alumno->curp, $option->curp)) {
                                    $contains = true;
                                    break;
                                }
                            }
                        @endphp
                        @if (!$contains)
                            <option
                                value="{{ $option->curp }}">{{ $option->nombre }} {{ $option->apellido_paterno }} {{ $option->apellido_materno }}
                                ({{ $option->curp }})
                            </option>
                        @endif
                    @endforeach
                </select>
                @error('alumno')
                <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                 </span>
                @enderror
            </div>
        </div>
    </div>
    @if ($list_alumnos->count() == 0)
        <div class="alert alert-primary-muted" role="alert">
            <h4 class="alert-heading">{{ __('No records found!') }}</h4>
            <p>{{ __('No records have been added to the database yet. We suggest creating the first record.') }}</p>
            <hr>
            <div class="row justify-content-center">
                <img src="{{ asset('assets/images/404.svg') }}" class="w-75 mt-2 mb-2">
            </div>
        </div>
    @else
        <div class="row">
            <div class="col">
                <p class="mb-3">
                    @if($list_alumnos->total() >= 0)
                        {{ __('Showing') }}
                        @if(!$list_alumnos->firstItem())
                            {{ $list_alumnos->firstItem() }}
                        @else
                            {{ __('of') }}
                        @endif
                        @if($list_alumnos->firstItem())
                            {{ $list_alumnos->firstItem() }}
                        @else
                            0
                        @endif
                        {{ __('to') }}
                        @if($list_alumnos->lastItem())
                            {{ $list_alumnos->lastItem() }}
                        @else
                            0
                        @endif
                        {{ __('of') }} {{ $list_alumnos->total() }} {{ __('entries') }}
                    @endif
                </p>
            </div>
            <div class="col text-right">
                <button id="downloadFile" wire:click="export" type="button" class="btn btn-success btn-icon" data-toggle="tooltip"
                        data-placement="top" title="{{ __('Export') }}">
                    <i class="mdi mdi-download"></i>
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                <tr>
                    <th>
                        {{ __('Course')  }}
                    </th>
                    <th>
                        {{ __('Moodle Code')  }}
                    </th>
                    <th>
                        {{ __('CURP')  }} {{ __('Student')  }}
                    </th>
                    <th>
                        {{ __('Name')  }} {{ __('Student')  }}
                    </th>
                    <th>
                        {{ __('User Moodle')  }}
                    </th>
                    <th>
                        {{ __('Password Moodle')  }}
                    </th>
                    <th>
                        {{ __('Inscription Date')  }}
                    </th>
                    <th>
                        {{ __('Delete')  }}
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach ($list_alumnos as $alumno)
                    @if(trim($alumno['nombre']) !== '' || trim($alumno['apellido_paterno']) !== '' || trim($alumno['apellido_materno']) !== '' || trim($alumno['curso']) !== '' || trim($alumno['codigo_moodle']) !== '')
                        <tr>
                            <td>
                                {{ Str::limit($alumno['curso'], 20) }}
                            </td>
                            <td>
                                {{ Str::limit($alumno['codigo_moodle'], 20) }}
                            </td>
                            <td>
                                {{ Str::limit($alumno['curp'], 18) }}
                            </td>
                            <td>
                                {{ Str::limit($alumno['nombre'] . ' ' . $alumno['apellido_paterno'] . ' ' . $alumno['apellido_materno'], 40) }}
                            </td>
                            <td>
                                {{ Str::limit($alumno['usuario_moodle'], 15) }}
                            </td>
                            <td>
                                {{ Str::limit($alumno['contrasena_moodle'], 15) }}
                            </td>
                            <td>{{  date('d-m-Y', strtotime($alumno['created_at'])) }}</td>
                            <td>
                                <button
                                    wire:click="$dispatch('delete-prompt-asignar-alumno', '{{ config('app.debug') ? $alumno['id'] : encrypt($alumno['id']) }}')"
                                    type="button"
                                    class="btn btn-danger btn-icon-text mb-1 mb-md-0">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col mb-2">
            {{ $list_alumnos->links() }}
        </div>
    @endif
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
                                    <label for="concepto_pago_alumno"
                                           class="control-label">{{ __('Payment Concept') }}</label>
                                    <div wire:ignore>
                                        <select
                                            class="form-control" id="concepto_pago_alumno" style="width: 100%;">
                                            <option value="" selected>{{ __('Select Payment Concept') }}</option>
                                            {{ $conceptos_pago_grupo->precio_total }}
                                            @if($conceptos_pago_grupo->inscripcion > 0)
                                                <option
                                                    value="{{ __('Inscription') }} - {{ $curso_grupo->nombre }}">{{ __('Inscription') }}
                                                    - {{ $curso_grupo->nombre }}</option>
                                            @endif
                                            @if($conceptos_pago_grupo->precio_total > 0)
                                                <option
                                                    value="{{ __('Course') }} - {{ $curso_grupo->nombre }}">{{ __('Course') }} - {{ $curso_grupo->nombre }}</option>
                                            @endif
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
</div>
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script>
    // Crear un observador de mutación
    const observer = new MutationObserver(function (mutationsList, observer) {
        // Verificar si hay cambios en los hijos del elemento select
        mutationsList.forEach(mutation => {
            if (mutation.type === 'childList') {
                const selectElement = document.getElementById('alumno');
                selectElement.dispatchEvent(new Event('change'));
            }
        });
    });

    // Configurar las opciones para observar cambios en los nodos hijos
    const config = {childList: true};

    // Observar el elemento select con la configuración dada
    const selectElement = document.getElementById("alumno");
    observer.observe(selectElement, config);

    document.addEventListener('livewire:initialized', () => {
    @this.on('delete-prompt-asignar-alumno', alumnoId => {
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
            @this.dispatch('goOn-Delete-Asignar-Alumno', {alumnoId: alumnoId})

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

    @this.on('set-curp', customerId => {
        var elemento = document.getElementById('alumno');

    @this.set('curp', elemento.value)

    @this.dispatch('goOn-Save-Asignar-Alumno')
    })
    });

    $(document).ready(function () {
        $('#concepto_pago_alumno').select2();

        $('#concepto_pago_alumno').select2().on('change', function (e) {
        @this.set('concepto_pago_alumno', e.target.value)
        });
    });

    document.addEventListener("livewire:load", function (event) {
        window.livewire.hook('afterDomUpdate', () => {
            $('#concepto_pago_alumno').select2();
        });
    });

    window.addEventListener('open-create-concepto-pago-alumno-modal', event => {
        $('#createConceptoPagoAlumno').modal('show');

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

    window.addEventListener('pagoAlumnosCreated', () => {
        Swal.fire({
            title: '{{ __('Invoices created') }}',
            text: '{{ __('Invoices Created Successfully') }}',
            icon: 'success',
            confirmButtonText: '{{ __('Ok') }}'
        });
    });

    window.addEventListener('pagoAlumnosWithErrosCreated', function (event) {
        var count_errors = event.__livewire.params[0];

        Swal.fire({
            title: '{{ __('Invoices created') }}',
            html: '<div>{{ __('Invoices Created Successfully') }}</div><br/><div>{{ __('The number of records that could not be added is') }}: ' + count_errors + '</div>',
            icon: 'success',
            confirmButtonText: '{{ __('Ok') }}'
        });
    });

    $('#downloadFile').on("click", function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        Toast.fire({
            icon: 'success',
            title: '{{ __('Processing your download. Please wait...') }}',
        });
    });
</script>
