@section('title', __('Edit Group'))

<div>
    <div class="row mb-4">
        <div class="col-lg-8 col-md-6 col-sm-6">
            <nav class="page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">{{ __('Management') }}</li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('groups') }}">{{ __('Groups') }}</a>
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
            <a class="nav-item nav-link active" id="nav-general-tab" data-toggle="tab" href="#nav-general"
               role="tab"
               aria-controls="nav-general" aria-selected="true">{{ __('General Data') }}</a>
            <a class="@if($status != 0) d-none @endif nav-item nav-link" id="nav-alumno-grupo-tab" data-toggle="tab"
               href="#nav-alumno-grupo"
               role="tab"
               aria-controls="nav-alumno-grupo" aria-selected="false">{{ __('Students') }}</a>
        </div>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-general" role="tabpanel"
                             aria-labelledby="nav-general-tab">
                            <form wire:submit="save">
                                <div class="row mb-4">
                                    <div class="col-lg-6 col-md-8 col-sm-7 d-flex flex-column justify-content-center">
                                        <div class="form-group">
                                            {{ __('Group Status') }}
                                            <div class="form-check">
                                                <label
                                                    class="form-check-label {{ $status == 0 ? 'text-success' : 'text-danger' }}">
                                                    <input type="checkbox" id="statusCheckbox" class="form-check-input"
                                                           onclick="updateStatus()" {{ $status == 0 ? 'checked' : '' }}>
                                                    @if($status == 2)
                                                        {{ __('Inactive') }}
                                                    @elseif($status == 0)
                                                        {{ __('Active') }}
                                                    @endif
                                                    <i class="input-frame"></i>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="col-lg-6 col-md-4 col-sm-5 d-flex flex-column justify-content-center align-items-end">
                                        <button type="submit" class="d-none d-sm-block btn btn-linkedin btn-icon-text">
                                            <i class="mdi mdi-clipboard-account mr-2"></i>
                                            {{ __('Update Group') }}
                                        </button>
                                        <button type="submit"
                                                class="d-block d-sm-none btn btn-linkedin btn-icon-text w-100">
                                            <i class="mdi mdi-clipboard-account mr-2"></i>
                                            {{ __('Update Group') }}
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="grupo" class="control-label">{{ __('Group') }}</label>
                                            <input id="grupo" type="text"
                                                   class="form-control @error('grupo') is-invalid @enderror"
                                                   wire:model="grupo"
                                                   placeholder="{{ __('Group') }}"
                                                   autocomplete="grupo" autofocus>
                                            @error('grupo')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="ciclo"
                                                   class="control-label">{{ __('Cycle') }}</label>
                                            <input id="ciclo" type="text"
                                                   class="form-control @error('ciclo') is-invalid @enderror"
                                                   wire:model="ciclo"
                                                   placeholder="{{ __('Cycle') }}"
                                                   autocomplete="ciclo">
                                            @error('ciclo')
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
                                            <label for="inicio_periodo"
                                                   class="control-label">{{ __('Start Period') }}</label>
                                            <input id="inicio_periodo" type="date"
                                                   class="form-control @error('inicio_periodo') is-invalid @enderror"
                                                   wire:model="inicio_periodo"
                                                   placeholder="{{ __('Start Period') }}"
                                                   autocomplete="inicio_periodo"
                                            >
                                            @error('inicio_periodo')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="fin_periodo"
                                                   class="control-label">{{ __('End Period') }}</label>
                                            <input id="fin_periodo" type="date"
                                                   class="form-control @error('fin_periodo') is-invalid @enderror"
                                                   wire:model="fin_periodo"
                                                   placeholder="{{ __('End Period') }}"
                                                   autocomplete="fin_periodo"
                                            >
                                            @error('fin_periodo')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="fecha_corte" class="control-label">{{ __('Cut Date') }}</label>
                                            <input id="fecha_corte" type="date"
                                                   class="form-control @error('fecha_corte') is-invalid @enderror"
                                                   wire:model="fecha_corte"
                                                   placeholder="{{ __('Cut Date') }}"
                                                   autocomplete="fecha_corte"
                                            >
                                            @error('fecha_corte')
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
                                            <label for="curso"
                                                   class="control-label">{{ __('Course') }}</label>
                                            <select
                                                class="form-control @error('curso') is-invalid @enderror"
                                                id="curso" wire:model="curso">
                                                <option value="" selected>{{ __('Select a course') }}</option>
                                                @foreach($cursos as $curso)
                                                    <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                                @endforeach
                                            </select>
                                            @error('curso')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="modalidad"
                                                   class="control-label">{{ __('Modality') }}</label>
                                            <select class="form-control @error('modalidad') is-invalid @enderror"
                                                    id="modalidad" wire:model="modalidad">
                                                <option value="" selected>{{ __('Select a modality') }}</option>
                                                @foreach($modalidades as $modalidad)
                                                    <option
                                                        value="{{ $modalidad->nombre }}">{{ $modalidad->nombre }}</option>
                                                @endforeach
                                            </select>
                                            @error('modalidad')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="cantidad_maxima_alumnos"
                                                   class="control-label">{{ __('Maximum number of students') }}</label>
                                            <input id="cantidad_maxima_alumnos" type="text"
                                                   class="form-control @error('cantidad_maxima_alumnos') is-invalid @enderror input-numerico-entero"
                                                   wire:model="cantidad_maxima_alumnos"
                                                   placeholder="{{ __('Maximum number of students') }}"
                                                   autocomplete="cantidad_maxima_alumnos">
                                            @error('cantidad_maxima_alumnos')
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
                                            <label for="precio_total"
                                                   class="control-label">{{ __('Total Price') }}</label>
                                            <input id="precio_total" type="text"
                                                   class="form-control @error('precio_total') is-invalid @enderror input-numerico"
                                                   wire:model="precio_total"
                                                   placeholder="{{ __('Total Price') }}"
                                                   autocomplete="precio_total">
                                            @error('precio_total')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="precio_mensualidad"
                                                   class="control-label">{{ __('Monthly Price') }}</label>
                                            <input id="precio_mensualidad" type="text"
                                                   class="form-control @error('precio_mensualidad') is-invalid @enderror input-numerico"
                                                   wire:model="precio_mensualidad"
                                                   placeholder="{{ __('Monthly Price') }}"
                                                   autocomplete="precio_mensualidad">
                                            @error('precio_mensualidad')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="inscripcion"
                                                   class="control-label">{{ __('Inscription Price') }}</label>
                                            <input id="inscripcion" type="text"
                                                   class="form-control @error('inscripcion') is-invalid @enderror input-numerico"
                                                   wire:model="inscripcion"
                                                   placeholder="{{ __('Inscription Price') }}"
                                                   autocomplete="inscripcion">
                                            @error('inscripcion')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="@if($status != 0) d-none @endif tab-pane fade" id="nav-alumno-grupo"
                             role="tabpanel"
                             aria-labelledby="nav-alumno-grupo-tab">
                            @livewire('asignar-alumnos')
                        </div>
                    </div>
                </div>
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

    document.addEventListener('DOMContentLoaded', function () {
        const inputsNumericosEnteros = document.querySelectorAll('.input-numerico-entero');

        inputsNumericosEnteros.forEach(function (input) {
            input.addEventListener('input', restringirEntradaNumericaEntera);
        });

        function restringirEntradaNumericaEntera(event) {
            // Remover todos los caracteres no numéricos
            this.value = this.value.replace(/\D/g, '');

            // Si el valor es mayor a 10 caracteres, recortar
            if (this.value.length > 3) {
                this.value = this.value.substring(0, 3);
            }
        }
    });

    function updateStatus() {
        var checkbox = document.getElementById('statusCheckbox');
    @this.set('status', checkbox.checked)
    }

    window.addEventListener('grupoUpdated', () => {
        Swal.fire({
            title: '{{ __('Updated') }}',
            text: '{{ __('Register Updated') }}',
            icon: 'success',
            confirmButtonText: '{{ __('Ok') }}',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.reload();
            }
        });
    });

    $(document).ready(function () {
        $('#inicio_periodo').on('change', function () {
            var startDate = new Date($(this).val());
            var endDate = new Date($('#fin_periodo').val());
            var cutDate = new Date($('#fecha_corte').val());

            // Verifica si la fecha de inicio es mayor que la fecha final
            if (endDate && startDate > endDate) {
                Swal.fire({
                    title: 'Error',
                    text: '{{ __('The start date cannot be after the end date.') }}',
                    icon: 'error'
                }).then(function () {
                    $('#inicio_periodo').val('');
                });
            }
                // Verifica si la fecha de inicio es mayor que la fecha de corte
            // Solo verifica si cutDate es válida para asegurar que la comparación sea significativa
            else if (cutDate && startDate > cutDate) {
                Swal.fire({
                    title: 'Error',
                    text: '{{ __('The start date cannot be after the cut-off date.') }}',
                    icon: 'error'
                }).then(function () {
                    $('#inicio_periodo').val('');
                });
            }
        });

        $('#fin_periodo').on('change', function () {
            var startDate = new Date($('#inicio_periodo').val());
            var endDate = new Date($(this).val());
            var cutDate = new Date($('#fecha_corte').val());

            // Verifica si la fecha final es menor o igual a la fecha de inicio
            if (endDate <= startDate) {
                Swal.fire({
                    title: 'Error',
                    text: '{{ __('The period end date cannot be less than or equal to the period start date.') }}',
                    icon: 'error'
                }).then(function () {
                    $('#fin_periodo').val('');
                });
            }
            // Verifica si la fecha final es mayor que la fecha de corte
            else if (cutDate && endDate > cutDate) { // Asegura que cutDate sea válida antes de comparar
                Swal.fire({
                    title: 'Error',
                    text: '{{ __('The period end date cannot be after the cut-off date.') }}',
                    icon: 'error'
                }).then(function () {
                    $('#fin_periodo').val('');
                });
            }
        });

        $('#fecha_corte').on('change', function () {
            var endDate = new Date($('#fin_periodo').val());
            var cutDate = new Date($(this).val());

            // Asegura que la fecha de corte no sea menor o igual a la fecha final del período,
            // pero solo si endDate es válida (en caso de que el usuario establezca la fecha de corte primero)
            if (endDate && cutDate <= endDate) {
                Swal.fire({
                    title: 'Error',
                    text: '{{ __('The cut-off date cannot be less than or equal to the period end date.') }}',
                    icon: 'error'
                }).then(function () {
                    $('#fecha_corte').val('');
                });
            }
        });

        $('#precio_total').on('input', function () {
            var totalPrice = parseFloat($(this).val());
            var startDate = new Date($('#inicio_periodo').val());
            var endDate = new Date($('#fin_periodo').val());
            var monthsDifference = getMonthsDifference(startDate, endDate);
            if (!isNaN(totalPrice) && monthsDifference > 0) {
                var monthlyPrice = totalPrice / monthsDifference;
                $('#precio_mensualidad').val(monthlyPrice.toFixed(2));
            @this.set('precio_mensualidad', monthlyPrice.toFixed(2))
            } else {
                $('#precio_mensualidad').val('');
            }
        });
    });

    function getMonthsDifference(startDate, endDate) {
        var months;
        months = (endDate.getFullYear() - startDate.getFullYear()) * 12;
        months -= startDate.getMonth();
        months += endDate.getMonth();
        if (startDate.getDate() > endDate.getDate()) {
            months -= 1; // Si el día de inicio es mayor al día de fin, restamos un mes
        }
        return months <= 0 ? 0 : months;
    }

    document.querySelectorAll('.btn-back').forEach(button => {
        button.addEventListener('click', function () {
            window.history.back();
        });
    });
</script>
