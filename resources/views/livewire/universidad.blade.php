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
                           placeholder="{{ __('Search...') }}" autofocus
                           autocomplete="search-universidad-alumno">
                </div>
            </div>
        </div>
        <div
            class="col-sm-6 d-flex flex-column justify-content-center align-items-end"
            @if($btn_edit == true) style="display: none!important;" @endif>
            <button wire:click="$dispatch('set-curp')"
                    class="d-none d-sm-block btn btn-success btn-icon-text">
                <i class="mdi mdi-bank mr-2"></i>
                {{ __('Add') }}
            </button>
            <button wire:click="$dispatch('set-curp')"
                    class="d-block d-sm-none btn btn-success btn-icon-text w-100 mt-3">
                <i class="mdi mdi-bank mr-2"></i>
            </button>
        </div>
        <div
            class="col-sm-6 d-flex flex-column justify-content-center align-items-end"
            @if($btn_edit == false) style="display: none!important;" @endif>
            <button wire:click="$dispatch('save-update-prompt-universidad-alumno')"
                    class="d-none d-sm-block btn btn-linkedin btn-icon-text">
                <i class="mdi mdi-bank mr-2"></i>
                {{ __('Update') }}
            </button>
            <button wire:click="$dispatch('save-update-prompt-universidad-alumno')"
                    class="d-block d-sm-none btn btn-linkedin btn-icon-text w-100 mt-3">
                <i class="mdi mdi-bank mr-2"></i>
            </button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="universidad"
                       class="control-label">{{ __('University') }}</label>
                <select id="universidad" wire:model="universidad"
                        class="form-control @error('universidad') is-invalid @enderror">
                    @if($select_universidad == true)
                        <option value=""
                                selected>{{ __('Select') }}</option>
                    @else
                        @if(count($options) == 0)
                            <option value=""
                                    selected>{{ __('No results for your search') }}
                            </option>
                        @endif
                    @endif
                    @foreach ($options as $option)
                        <option value="{{ $option->nombre }}">{{ $option->nombre }}</option>
                    @endforeach
                </select>
                @error('universidad')
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
                <label for="licenciatura" class="control-label">{{ __('Degree') }}</label>
                <select
                    class="form-control @error('licenciatura') is-invalid @enderror"
                    id="licenciatura" wire:model="licenciatura">
                    <option value=""
                            selected>{{ __('Select') }}</option>
                    @foreach($carreras as $carrera)
                        <option value="{{ $carrera->nombre }}">{{ $carrera->nombre }}</option>
                    @endforeach
                </select>
                @error('licenciatura')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="fecha_examen" class="control-label">{{ __('Exam Date') }}</label>
                <input id="fecha_examen" type="date"
                       class="form-control @error('fecha_examen') is-invalid @enderror"
                       wire:model="fecha_examen"
                       placeholder="{{ __('Exam Date') }}"
                       autocomplete="fecha_examen">
                @error('fecha_examen')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
        </div>
    </div>
    <div class="table-responsive">
        @if ($list_universidades_alumno->count() == 0)
            <div class="alert alert-primary-muted" role="alert">
                <h4 class="alert-heading">{{ __('No records found!') }}</h4>
                <p>{{ __('No records have been added to the database yet. We suggest creating the first record.') }}</p>
                <hr>
                <div class="row justify-content-center">
                    <img src="{{ asset('assets/images/404.svg') }}" class="w-75 mt-2 mb-2">
                </div>
            </div>
        @else
            <table class="table table-hover mb-0">
                <thead>
                <tr>
                    <th>
                        {{ __('University')  }}
                    </th>
                    <th>
                        {{ __('Degree')  }}
                    </th>
                    <th>
                        {{ __('Exam Date')  }}
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
                @foreach ($list_universidades_alumno as $universidad_alumno)
                    <tr>
                        <td>
                            {{ Str::limit($universidad_alumno->universidad, 30) }}
                        </td>
                        <td>
                            {{ Str::limit($universidad_alumno->licenciatura, 30) }}
                        </td>
                        <td>
                            {{  date('d-m-Y', strtotime($universidad_alumno->fecha_examen)) }}
                        </td>
                        <td>
                            <button
                                wire:click="$dispatch('update-prompt-universidad-alumno', '{{ config('app.debug') ? $universidad_alumno->id : encrypt($universidad_alumno->id) }}')"
                                type="button"
                                class="btn btn-linkedin btn-icon-text mb-1 mb-md-0">
                                <i class="mdi mdi-lead-pencil"></i>
                            </button>
                        </td>
                        <td>
                            <button
                                wire:click="$dispatch('delete-prompt-universidad-alumno', '{{ config('app.debug') ? $universidad_alumno->id : encrypt($universidad_alumno->id) }}')"
                                type="button"
                                class="btn btn-danger btn-icon-text mb-1 mb-md-0">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script>
    // Crear un observador de mutación
    const observerUniversidad = new MutationObserver(function (mutationsList, observerUniversidad) {
        // Verificar si hay cambios en los hijos del elemento select
        mutationsList.forEach(mutation => {
            if (mutation.type === 'childList') {
                const selectElementUniversidad = document.getElementById('universidad');
                selectElementUniversidad.dispatchEvent(new Event('change'));
            }
        });
    });

    // Configurar las opciones para observar cambios en los nodos hijos
    const configUniversidad = {childList: true};

    // Observar el elemento select con la configuración dada
    const selectElementUniversidad = document.getElementById("universidad");
    observerUniversidad.observe(selectElementUniversidad, configUniversidad);

    document.addEventListener('livewire:initialized', () => {
    @this.on('update-prompt-universidad-alumno', universidadAlumnoId => {

    @this.dispatch('goOn-Update-Universidad-Alumno', {universidadAlumnoId: universidadAlumnoId})

    })

    @this.on('save-update-prompt-universidad-alumno', () => {
    @this.set('curp', null)
    @this.dispatch('goOn-Save-Update-Universidad-Alumno')
    })

    @this.on('clear-save-update-prompt-universidad-alumno', () => {
        $("#universidad").val("");
        $("#licenciatura").val("");
        $("#fecha_examen").val("");
    })

    @this.on('delete-prompt-universidad-alumno', universidadAlumnoId => {
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
            @this.dispatch('goOn-Delete-Universidad-Alumno', {universidadAlumnoId: universidadAlumnoId})

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
        var elemento = document.getElementById('curp');

    @this.set('curp', elemento.value)

    @this.dispatch('goOn-Save-Universidad-Alumno')
    })
    });
</script>
