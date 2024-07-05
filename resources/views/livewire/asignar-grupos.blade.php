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
                           placeholder="{{ __('Search Group...') }}" autofocus
                           autocomplete="search-grupos">
                </div>
            </div>
        </div>
        <div
            class="col-sm-6 d-flex flex-column justify-content-center align-items-end">
            <button wire:click="$dispatch('set-group')"
                    class="d-none d-sm-block btn btn-success btn-icon-text">
                <i class="mdi mdi-clipboard-account mr-2"></i>
                {{ __('Add') }}
            </button>
            <button wire:click="$dispatch('set-group')"
                    class="d-block d-sm-none btn btn-success btn-icon-text w-100 mt-3">
                <i class="mdi mdi-clipboard-account mr-2"></i>
                {{ __('Add') }}
            </button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="modalidad" class="control-label">{{ __('Modality') }}</label>
                <select class="form-control @error('modalidad') is-invalid @enderror"
                        id="modalidad" wire:model.live="modalidad">
                    <option value="" selected>{{ __('Select a modality') }}</option>
                    @foreach($modalidades as $modalidad)
                        <option value="{{ $modalidad->nombre }}">{{ $modalidad->nombre }}</option>
                    @endforeach
                </select>
                @error('modalidad')
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
                <label for="grupo" class="control-label">{{ __('Groups') }}</label>
                <select id="grupo" wire:model="grupo" class="form-control @error('grupo') is-invalid @enderror">
                    @if($select_grupo == true)
                        <option value="" selected>{{ __('Select Group') }}</option>
                    @else
                        @if(count($options) == 0)
                            <option value="" selected>{{ __('No results for your search') }}</option>
                        @else
                            <option value="" selected>{{ __('Select Group By Search') }}</option>
                        @endif
                    @endif
                    @foreach ($options as $option)
                        @php
                            $contains = false;
                            foreach ($list_grupos as $grupo) {
                                if (str_contains($grupo->grupo_id, $option->id)) {
                                    $contains = true;
                                    break;
                                }
                            }
                        @endphp
                        @if (!$contains)
                            <option
                                value="{{ $option->id }}">{{ $option->grupo }} - {{ $option->curso_nombre }} ({{ $option->modalidad }})
                            </option>
                        @endif
                    @endforeach
                </select>
                @error('grupo')
                <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                 </span>
                @enderror
            </div>
        </div>
    </div>
    <div class="table-responsive">
        @if ($list_grupos->count() == 0)
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
                    @if($list_grupos->total() >= 0)
                        {{ __('Showing') }}
                        @if(!$list_grupos->firstItem())
                            {{ $list_grupos->firstItem() }}
                        @else
                            {{ __('of') }}
                        @endif
                        @if($list_grupos->firstItem())
                            {{ $list_grupos->firstItem() }}
                        @else
                            0
                        @endif
                        {{ __('to') }}
                        @if($list_grupos->lastItem())
                            {{ $list_grupos->lastItem() }}
                        @else
                            0
                        @endif
                        {{ __('of') }} {{ $list_grupos->total() }} {{ __('entries') }}
                    @endif
                </p>
            </div>
            <table class="table table-hover mb-0">
                <thead>
                <tr>
                    <th>
                        {{ __('Group')  }}
                    </th>
                    <th>
                        {{ __('Cycle')  }}
                    </th>
                    <th>
                        {{ __('Modality')  }}
                    </th>
                    <th>
                        {{ __('Course')  }}
                    </th>
                    <th>
                        {{ __('Moodle Code')  }}
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
                @foreach ($list_grupos as $grupo)
                    @if(trim($grupo['grupo']) !== '' || trim($grupo['ciclo']) !== '' || trim($grupo['modalidad']) !== '' || trim($grupo['curso']) !== '' || trim($grupo['codigo_moodle']) !== '')
                        <tr>
                            <td>
                                {{ Str::limit($grupo['grupo'], 30) }}
                            </td>
                            <td>
                                {{ Str::limit($grupo['ciclo'], 30) }}
                            </td>
                            <td>
                                {{ Str::limit($grupo['modalidad'], 20) }}
                            </td>
                            <td>
                                {{ Str::limit($grupo['curso'], 20) }}
                            </td>
                            <td>
                                {{ Str::limit($grupo['codigo_moodle'], 20) }}
                            </td>
                            <td>{{  date('d-m-Y', strtotime($grupo['created_at'])) }}</td>
                            <td>
                                <button
                                    wire:click="$dispatch('delete-prompt-asignar-grupo', '{{ config('app.debug') ? $grupo['id'] : encrypt($grupo['id']) }}')"
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
            <div class="col mb-2">
                {{ $list_grupos->links() }}
            </div>
        @endif
    </div>
</div>
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script>
    // Crear un observador de mutación
    const observerGrupo = new MutationObserver(function (mutationsList, observer) {
        // Verificar si hay cambios en los hijos del elemento select
        mutationsList.forEach(mutation => {
            if (mutation.type === 'childList') {
                const selectElementGrupo = document.getElementById('grupo');
                selectElementGrupo.dispatchEvent(new Event('change'));
            }
        });
    });

    // Configurar las opciones para observar cambios en los nodos hijos
    const configGrupo = {childList: true};

    // Observar el elemento select con la configuración dada
    const selectElementGrupo = document.getElementById("grupo");
    observerGrupo.observe(selectElementGrupo, configGrupo);

    document.addEventListener('livewire:initialized', () => {
    @this.on('delete-prompt-asignar-grupo', grupoId => {
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
            @this.dispatch('goOn-Delete-Asignar-Grupo', {grupoId: grupoId})

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

    @this.on('set-group', customerId => {
        var elemento = document.getElementById('grupo');

    @this.dispatch('goOn-Save-Asignar-Grupo')
    })
    });
</script>
