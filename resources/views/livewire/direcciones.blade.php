<div>
    <form wire:submit="saveDireccionAlumno">
        <div class="row">
            <div class="col-sm-6">
                <label for="codigo_postal" class="control-label">{{ __('Zip Code') }}</label>
                <div class="search-form">
                    <div class="input-group border border-primary rounded-sm  @error('search') is-invalid @enderror">
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
                        <input id="codigo_postal" wire:model.live="search" class="form-control"
                               type="text"
                               placeholder="{{ __('Search Zip Code...') }}" autofocus
                               autocomplete="search-direccion-alumno">
                    </div>
                    @error('search')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>
            <div
                class="@if($btn_edit == true) d-sm-none @endif col-sm-6 d-flex flex-column justify-content-center align-items-end">
                <button wire:click="$dispatch('set-curp')"
                        class="d-none d-sm-block btn btn-success btn-icon-text">
                    <i class="mdi mdi-google-maps mr-2"></i>
                    {{ __('Add') }}
                </button>
                <button wire:click="$dispatch('set-curp')"
                        class="d-block d-sm-none btn btn-success btn-icon-text w-100 mt-3">
                    <i class="mdi mdi-google-maps mr-2"></i>
                    {{ __('Add') }}
                </button>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="list_asentamiento"
                           class="control-label">{{ __('Search Result') }}</label>
                    <select id="list_asentamiento" wire:model="list_asentamiento"
                            class="form-control @error('list_asentamiento') is-invalid @enderror">
                        @if($select_asentamiento == true)
                            <option value=""
                                    selected>{{ __('Select Settlement') }}</option>
                        @else
                            @if(count($options) == 0)
                                <option value=""
                                        selected>{{ __('No results for your search') }}
                                </option>
                            @else
                                <option value=""
                                        selected>{{ __('Select Settlement By Zip Code') }}
                                </option>
                            @endif
                        @endif
                        @foreach ($options as $option)
                            <option value="{{ $option->asentamiento }}">{{ $option->asentamiento }}</option>
                        @endforeach
                    </select>
                    @error('list_asentamiento')
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
                    <label for="asentamiento"
                           class="control-label">{{ __('Settlement') }}</label>
                    <input id="asentamiento" type="text"
                           class="form-control @error('asentamiento') is-invalid @enderror"
                           wire:model="asentamiento"
                           placeholder="{{ __('Settlement') }}"
                           autocomplete="off">
                    @error('asentamiento')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="tipo_asentamiento"
                           class="control-label">{{ __('Settlement Type') }}</label>
                    <input id="tipo_asentamiento" type="text"
                           class="form-control @error('tipo_asentamiento') is-invalid @enderror"
                           wire:model="tipo_asentamiento"
                           placeholder="{{ __('Settlement Type') }}"
                           autocomplete="off">
                    @error('tipo_asentamiento')
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
                    <label for="municipio"
                           class="control-label">{{ __('Municipality') }}</label>
                    <input id="municipio" type="text"
                           class="form-control @error('municipio') is-invalid @enderror"
                           wire:model="municipio"
                           placeholder="{{ __('Municipality') }}"
                           autocomplete="off">
                    @error('municipio')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="estado"
                           class="control-label">{{ __('State') }}</label>
                    <input id="estado" type="text"
                           class="form-control @error('estado') is-invalid @enderror"
                           wire:model="estado"
                           placeholder="{{ __('State') }}"
                           autocomplete="off">
                    @error('estado')
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
                    <label for="calle"
                           class="control-label">{{ __('Street and Number') }}</label>
                    <input id="calle" type="text"
                           class="form-control @error('calle') is-invalid @enderror"
                           wire:model="calle"
                           placeholder="{{ __('Street and Number') }}"
                           autocomplete="off">
                    @error('calle')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="table-responsive">
            @if ($list_direcciones_alumno->count() == 0)
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
                            {{ __('Zip Code')  }}
                        </th>
                        <th>
                            {{ __('Street and Number')  }}
                        </th>
                        <th>
                            {{ __('Settlement')  }}
                        </th>
                        <th>
                            {{ __('Settlement Type')  }}
                        </th>
                        <th>
                            {{ __('Municipality')  }}
                        </th>
                        <th>
                            {{ __('State')  }}
                        </th>
                        <th>
                            {{ __('Delete')  }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($list_direcciones_alumno as $direccion_alumno)
                        <tr>
                            <td>
                                {{ Str::limit($direccion_alumno->codigo_postal, 30) }}
                            </td>
                            <td>
                                {{ Str::limit($direccion_alumno->calle, 30) }}
                            </td>
                            <td>
                                {{ $direccion_alumno->asentamiento }}
                            </td>
                            <td>
                                {{ $direccion_alumno->tipo_asentamiento }}
                            </td>
                            <td>
                                {{ $direccion_alumno->municipio }}
                            </td>
                            <td>
                                {{ $direccion_alumno->estado }}
                            </td>
                            <td>
                                <button
                                    wire:click="$dispatch('delete-prompt-direccion-alumno', '{{ config('app.debug') ? $direccion_alumno->id : encrypt($direccion_alumno->id) }}')"
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
    </form>
</div>
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectElementAsentamiento = document.getElementById("list_asentamiento");
        if (selectElementAsentamiento) {
            selectElementAsentamiento.addEventListener('change', function () {
                // Aquí actualizas el estado de Livewire solo cuando el usuario cambia el select
            @this.dispatch('goOn-Changed-List-Asentamiento')
            });
        }
    });

    document.addEventListener('livewire:initialized', () => {
    @this.on('delete-prompt-direccion-alumno', direccionAlumnoId => {
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
            @this.dispatch('goOn-Delete-Direccion-Alumno', {direccionAlumnoId: direccionAlumnoId})

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
    })
    });
</script>
