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
                           autocomplete="search-media-superior">
                </div>
            </div>
        </div>
        <div
            class="col-sm-6 d-flex flex-column justify-content-center align-items-end"
            @if($btn_edit == true) style="display: none!important;" @endif>
            <button wire:click="$dispatch('set-curp')"
                    class="d-none d-sm-block btn btn-success btn-icon-text">
                <i class="mdi mdi-clipboard-text mr-2"></i>
                {{ __('Add') }}
            </button>
            <button wire:click="$dispatch('set-curp')"
                    class="d-block d-sm-none btn btn-success btn-icon-text w-100 mt-3">
                <i class="mdi mdi-clipboard-text mr-2"></i>
                {{ __('Add') }}
            </button>
        </div>
        <div
            class="col-sm-6 d-flex flex-column justify-content-center align-items-end"
            @if($btn_edit == false) style="display: none!important;" @endif>
            <button wire:click="$dispatch('save-update-prompt-educacion-media-superior')"
                    class="d-none d-sm-block btn btn-linkedin btn-icon-text">
                <i class="mdi mdi-clipboard-text mr-2"></i>
                {{ __('Update') }}
            </button>
            <button wire:click="$dispatch('save-update-prompt-educacion-media-superior')"
                    class="d-block d-sm-none btn btn-linkedin btn-icon-text w-100 mt-3">
                <i class="mdi mdi-clipboard-text mr-2"></i>
                {{ __('Update') }}
            </button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="media_superior"
                       class="control-label">{{ __('High School') }}</label>
                <select id="media_superior" wire:model="media_superior"
                        class="form-control @error('media_superior') is-invalid @enderror">
                    @if($select_media_superior == true)
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
                @error('media_superior')
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
                <label for="area" class="control-label">{{ __('Terminal Area') }}</label>
                <select
                    class="form-control @error('area') is-invalid @enderror"
                    id="area" wire:model="area">
                    <option value=""
                            selected>{{ __('Select') }}</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->nombre }}">{{ $area->nombre }}</option>
                    @endforeach
                </select>
                @error('area')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="status_media_superior" class="control-label">{{ __('Status') }}</label>
                <select
                    class="form-control @error('status_media_superior') is-invalid @enderror"
                    id="status_media_superior" wire:model.live="status_media_superior">
                    <option value=""
                            selected>{{ __('Select') }}</option>
                    @foreach($lista_estatus as $estatus)
                        <option value="{{ $estatus->nombre }}">{{ $estatus->nombre }}</option>
                    @endforeach
                </select>
                @error('status_media_superior')
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
                <label for="grado" class="control-label">{{ __('Grade') }}</label>
                <select
                    class="form-control @error('grado') is-invalid @enderror"
                    id="grado" wire:model="grado">
                    <option value=""
                            selected>{{ __('Select') }}</option>
                    @foreach($grados as $grado)
                        <option value="{{ $grado->grado }}">{{ $grado->grado }}</option>
                    @endforeach
                </select>
                @error('grado')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="promedio" class="control-label">{{ __('Average') }}</label>
                <input id="promedio" type="text"
                       class="form-control @error('promedio') is-invalid @enderror"
                       wire:model="promedio"
                       placeholder="{{ __('Average') }}"
                       autocomplete="promedio"
                       oninput="validarPromedio(this)">
                @error('promedio')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
        </div>
    </div>
    <div class="table-responsive">
        @if ($list_educacion_media_superior->count() == 0)
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
                        {{ __('High School')  }}
                    </th>
                    <th>
                        {{ __('Terminal Area')  }}
                    </th>
                    <th>
                        {{ __('Status')  }}
                    </th>
                    <th>
                        {{ __('Grade')  }}
                    </th>
                    <th>
                        {{ __('Average')  }}
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
                @foreach ($list_educacion_media_superior as $educacion_media_superior)
                    <tr>
                        <td>
                            {{ Str::limit($educacion_media_superior->escuela, 30) }}
                        </td>
                        <td>
                            {{ Str::limit($educacion_media_superior->area_terminal, 30) }}
                        </td>
                        <td>
                            {{ $educacion_media_superior->estatus }}
                        </td>
                        <td>
                            {{ $educacion_media_superior->grado }}
                        </td>
                        <td>
                            @php
                                // Obtener el valor del promedio final aproximado
                                $promedio = $educacion_media_superior->promedio_final_aproximado;

                                // Convertir a número flotante para manipulación
                                $valorNumerico = floatval($promedio);

                                // Redondear adecuadamente
                                if (floor($valorNumerico) == $valorNumerico) {
                                    // Si el número es entero, mostrar sin decimales
                                    echo number_format($valorNumerico, 0);
                                } else if (floor($valorNumerico * 10) / 10 == $valorNumerico) {
                                    // Si tiene un decimal no cero, mostrar con un decimal
                                    echo number_format($valorNumerico, 1);
                                } else {
                                    // De lo contrario, mostrar con dos decimales
                                    echo number_format($valorNumerico, 2);
                                }
                            @endphp

                        </td>
                        <td>
                            <button
                                wire:click="$dispatch('update-prompt-educacion-media-superior', '{{ config('app.debug') ? $educacion_media_superior->id : encrypt($educacion_media_superior->id) }}')"
                                type="button"
                                class="btn btn-linkedin btn-icon-text mb-1 mb-md-0">
                                <i class="mdi mdi-lead-pencil"></i>
                            </button>
                        </td>
                        <td>
                            <button
                                wire:click="$dispatch('delete-prompt-educacion-media-superior', '{{ config('app.debug') ? $educacion_media_superior->id : encrypt($educacion_media_superior->id) }}')"
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
    function validarPromedio(input) {
        let valor = input.value.trim();
        valor = valor.replace(/[^0-9.]/g, '');

        if (valor < 0 || valor > 10) {
            valor = Math.max(0, Math.min(10, parseFloat(valor)));
        }

        input.value = valor;
    }

    // Crear un observador de mutación
    const observer = new MutationObserver(function (mutationsList, observer) {
        // Verificar si hay cambios en los hijos del elemento select
        mutationsList.forEach(mutation => {
            if (mutation.type === 'childList') {
                const selectElement = document.getElementById('media_superior');
                selectElement.dispatchEvent(new Event('change'));
            }
        });
    });

    // Configurar las opciones para observar cambios en los nodos hijos
    const config = {childList: true};

    // Observar el elemento select con la configuración dada
    const selectElement = document.getElementById("media_superior");
    observer.observe(selectElement, config);

    document.addEventListener('livewire:initialized', () => {
    @this.on('update-prompt-educacion-media-superior', educacionId => {

    @this.dispatch('goOn-Update-Educacion-Media-Superior', {educacionId: educacionId})

    })

    @this.on('save-update-prompt-educacion-media-superior', () => {
    @this.set('curp', null)
    @this.dispatch('goOn-Save-Update-Educacion-Media-Superior')
        $("#media_superior").val("");
        $("#area").val("");
        $("#status_media_superior").val("");
        $("#grado").val("");
        $("#promedio").val("");
    })

    @this.on('delete-prompt-educacion-media-superior', educacionId => {
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
            @this.dispatch('goOn-Delete-Educacion-Media-Superior', {educacionId: educacionId})

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

    @this.dispatch('goOn-Save-Educacion-Media-Superior')
    })
    });
</script>
