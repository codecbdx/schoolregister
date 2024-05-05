@section('title', __('Groups'))

<div>
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">{{ __('Management') }}</li>
            <li class="breadcrumb-item" aria-current="page"><a class="text-facebook"
                                                               href="{{ route('groups') }}">{{ __('Groups') }}</a>
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
                           placeholder="{{ __('Search...') }}" autofocus autocomplete="search-grupos">
                </div>
            </div>
        </div>
        @foreach ($modulePermissions as $modulePermission)
            @if($modulePermission->route_name === 'create_group')
                <div class="col-lg-6 col-md-4 col-sm-5 d-flex flex-column justify-content-center align-items-end">
                    <button wire:click="$dispatch('open-create-grupo-modal')"
                            class="d-none d-sm-block btn btn-success btn-icon-text">
                        <i class="mdi mdi-clipboard-account mr-2"></i>
                        {{ __('Create Group') }}
                    </button>
                    <button wire:click="$dispatch('open-create-grupo-modal')"
                            class="d-block d-sm-none btn btn-success btn-icon-text w-100 mt-4">
                        <i class="mdi mdi-clipboard-account mr-2"></i>
                        {{ __('Create Group') }}
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
                            <button type="button" class="btn btn-success mt-1" wire:click="filterByStatus(0)">
                                {{ __('Active') }}
                            </button>
                            <button type="button" class="btn btn-danger mt-1" wire:click="filterByStatus(2)">
                                {{ __('Inactive') }}
                            </button>
                        </div>
                        <div class="col-sm-5 d-flex justify-content-end mt-2 mt-sm-0">
                            <select class="form-control @error('filtro_modalidad') is-invalid @enderror"
                                    id="filtro_modalidad" wire:model.live="filtro_modalidad">
                                <option value="" selected>{{ __('Modality Filter') }}</option>
                                @foreach($modalidades as $modalidad)
                                    <option
                                        value="{{ $modalidad->nombre }}">{{ $modalidad->nombre }}</option>
                                @endforeach
                            </select>
                            @error('filtro_modalidad')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    @if (empty($search) && $listGrupos->count() == 0)
                        <div class="alert alert-primary-muted" role="alert">
                            <h4 class="alert-heading">{{ __('No records found!') }}</h4>
                            <p>{{ __('No records have been added to the database yet. We suggest creating the first record.') }}</p>
                            <hr>
                            <div class="row justify-content-center">
                                <img src="{{ asset('assets/images/404.svg') }}" class="w-75 mt-2 mb-2">
                            </div>
                        </div>
                    @elseif ($listGrupos->count())
                        <div class="row">
                            <div class="col">
                                <p class="mb-3">
                                    @if($totalEntries > 0)
                                        {{ __('Showing') }}
                                        @if(!$listGrupos->firstItem())
                                            {{ $listGrupos->firstItem() }}
                                        @else
                                            {{ __('of') }}
                                        @endif
                                        {{ $listGrupos->firstItem() }} {{ __('to') }} {{ $listGrupos->lastItem() }} {{ __('of') }} {{ $listGrupos->total() }} {{ __('entries') }}
                                    @elseif($listGrupos->total() >= 0)
                                        {{ __('Showing') }}
                                        @if(!$listGrupos->firstItem())
                                            {{ $listGrupos->firstItem() }}
                                        @else
                                            {{ __('of') }}
                                        @endif
                                        @if($listGrupos->firstItem())
                                            {{ $listGrupos->firstItem() }}
                                        @else
                                            0
                                        @endif
                                        {{ __('to') }}
                                        @if($listGrupos->lastItem())
                                            {{ $listGrupos->lastItem() }}
                                        @else
                                            0
                                        @endif
                                        {{ __('of') }} {{ $listGrupos->total() }} {{ __('entries') }}
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
                                        <a wire:click.prevent="sortBy('grupo')" role="button"
                                           href="#">
                                            <i class="mdi mdi-sort ml-0"></i> {{ __('Group')  }}
                                        </a>
                                    </th>
                                    <th>
                                        <a wire:click.prevent="sortBy('ciclo')" role="button"
                                           href="#">
                                            <i class="mdi mdi-sort ml-0"></i> {{ __('Cycle')  }}
                                        </a>
                                    </th>
                                    <th>
                                        <a wire:click.prevent="sortBy('modalidad')" role="button"
                                           href="#">
                                            <i class="mdi mdi-sort ml-0"></i> {{ __('Modality')  }}
                                        </a>
                                    </th>
                                    <th>
                                        <a wire:click.prevent="sortBy('fecha_corte')" role="button"
                                           href="#">
                                            <i class="mdi mdi-sort ml-0"></i> {{ __('Cut Date')  }}
                                        </a>
                                    </th>
                                    <th>
                                        <a wire:click.prevent="sortBy('cancelled')" role="button"
                                           href="#">
                                            <i class="mdi mdi-sort ml-0"></i> {{ __('Status')  }}
                                        </a>
                                    </th>
                                    <th class="text-center">
                                        {{ __('Enrolled Students')  }}
                                    </th>
                                    @foreach ($modulePermissions as $modulePermission)
                                        @if($modulePermission->route_name === 'edit_group')
                                            <th>
                                                {{ __('Edit')  }}
                                            </th>
                                        @endif
                                        @if($modulePermission->route_name === 'delete_group')
                                            <th>
                                                {{ __('Delete')  }}
                                            </th>
                                        @endif
                                    @endforeach
                                    <th class="text-center">
                                        {{ __('Credentials') }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($listGrupos as $grupo)
                                    <tr>
                                        <td>
                                            {{ Str::limit($grupo->grupo, 30) }}
                                        </td>
                                        <td>
                                            {{ Str::limit($grupo->ciclo, 30) }}
                                        </td>
                                        <td>
                                            {{ Str::limit($grupo->modalidad, 20) }}
                                        </td>
                                        <td>{{  date('d-m-Y', strtotime($grupo->fecha_corte)) }}</td>
                                        <td class="text-center">
                                            @if ($grupo->cancelled == 2)
                                                <span
                                                    class="badge badge-danger text-white">{{ __('Inactive') }}</span>
                                            @elseif ($grupo->cancelled == 0)
                                                <span class="badge badge-success">{{ __('Active') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ $grupo->total_alumnos ? $grupo->total_alumnos : 0 }} {{ __('of') }} {{ $grupo->cantidad_max_alumnos }}
                                        </td>
                                        @foreach ($modulePermissions as $modulePermission)
                                            @if($modulePermission->route_name === 'edit_group')
                                                <td>
                                                    <a href="{{ route('edit_group', ['id' => config('app.debug') ? $grupo->id : encrypt($grupo->id)]) }}"
                                                       class="btn btn-linkedin btn-icon-text mb-1 mb-md-0">
                                                        <i class="mdi mdi-lead-pencil mr-2"></i>
                                                        {{ __('Edit') }}
                                                    </a>
                                                </td>
                                            @endif
                                            @if($modulePermission->route_name === 'delete_group')
                                                <td>
                                                    <button
                                                        wire:click="$dispatch('delete-prompt-grupo', '{{ config('app.debug') ? $grupo->id : encrypt($grupo->id) }}')"
                                                        type="button"
                                                        class="btn btn-danger btn-icon-text mb-1 mb-md-0">
                                                        <i class="mdi mdi-delete mr-2"></i>
                                                        {{ __('Delete') }}
                                                    </button>
                                                </td>
                                            @endif
                                        @endforeach
                                        @if ($grupo->total_alumnos > 0)
                                            <td class="text-center">
                                                <button
                                                    wire:click="$dispatch('print-credentials-group', '{{ config('app.debug') ? $grupo->id : encrypt($grupo->id) }}')"
                                                    type="button"
                                                    class="btn btn-inverse-primary btn-icon-text mb-1 mb-md-0">
                                                    <i class="mdi mdi-account-box mr-2"></i>
                                                    {{ __('Export credentials') }}
                                                </button>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <span
                                                    class="badge badge-warning text-white">{{ __('Incomplete information') }}</span>
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
                        {{ $listGrupos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @foreach ($modulePermissions as $modulePermission)
        @if($modulePermission->route_name === 'create_group')
            <div wire:ignore.self class="modal fade" id="createGrupo" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="createGrupoLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createGrupoLabel">{{ __('Create Group') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form wire:submit="createGrupo">
                            <div class="modal-body">
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
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success btn-icon-text">
                                    <i class="mdi mdi-clipboard-account mr-2"></i>{{ __('Create') }}
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

    window.addEventListener('open-create-grupo-modal', event => {
        $('#createGrupo').modal('show');
    });

    window.addEventListener('close-create-grupo-modal', event => {
        $('#createGrupo').modal('hide');
    });

    window.addEventListener('grupoCreated', () => {
        Swal.fire({
            title: '{{ __('Created') }}',
            text: '{{ __('Register Created') }}',
            icon: 'success',
            confirmButtonText: '{{ __('Ok') }}'
        });
    });

    document.addEventListener('livewire:initialized', () => {
    @this.on('delete-prompt-grupo', grupoId => {
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
            @this.dispatch('goOn-Delete-Grupo', {grupoId: grupoId})

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

    @this.on('print-credentials-group', grupoId => {
    @this.dispatch('goOn-Print-Credentials-Group', {grupoId: grupoId})
        showDownloadToast();
    })
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

    function showDownloadToast() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 16000,
            timerProgressBar: true,
        });

        Toast.fire({
            icon: 'success',
            title: '{{ __('Processing your download. Please wait...') }}',
        });
    }
</script>
