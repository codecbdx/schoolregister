@section('title', __('Means Interaction'))

<div>
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">{{ __('Configuration') }}</li>
            <li class="breadcrumb-item" aria-current="page"><a class="text-facebook"
                                                               href="{{ route('means_interaction') }}">{{ __('Means Interaction') }}</a>
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
                           placeholder="{{ __('Search...') }}" autofocus autocomplete="search-means">
                </div>
            </div>
        </div>
        @foreach ($modulePermissions as $modulePermission)
            @if($modulePermission->route_name === 'create_mean_interaction')
                <div class="col-lg-6 col-md-4 col-sm-5 d-flex flex-column justify-content-center align-items-end">
                    <button wire:click="$dispatch('open-create-mean-modal')"
                            class="d-none d-sm-block btn btn-success btn-icon-text">
                        <i class="mdi mdi-share-variant mr-2"></i>
                        {{ __('Create Mean Interaction') }}
                    </button>
                    <button wire:click="$dispatch('open-create-mean-modal')"
                            class="d-block d-sm-none btn btn-success btn-icon-text w-100 mt-4">
                        <i class="mdi mdi-share-variant mr-2"></i>
                        {{ __('Create Mean Interaction') }}
                    </button>
                </div>
            @endif
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if (empty($search) && $listMeans->count() == 0)
                        <div class="alert alert-primary-muted" role="alert">
                            <h4 class="alert-heading">{{ __('No records found!') }}</h4>
                            <p>{{ __('No records have been added to the database yet. We suggest creating the first record.') }}</p>
                            <hr>
                            <div class="row justify-content-center">
                                <img src="{{ asset('assets/images/404.svg') }}" class="w-75 mt-2 mb-2">
                            </div>
                        </div>
                    @elseif ($listMeans->count())
                        <div class="row">
                            <div class="col">
                                <p class="mb-3">
                                    @if($totalEntries > 0)
                                        {{ __('Showing') }}
                                        @if(!$listMeans->firstItem())
                                            {{ $listMeans->firstItem() }}
                                        @else
                                            {{ __('of') }}
                                        @endif
                                        {{ $listMeans->firstItem() }} {{ __('to') }} {{ $listMeans->lastItem() }} {{ __('of') }} {{ $listMeans->total() }} {{ __('entries') }}
                                    @elseif($listMeans->total() >= 0)
                                        {{ __('Showing') }}
                                        @if(!$listMeans->firstItem())
                                            {{ $listMeans->firstItem() }}
                                        @else
                                            {{ __('of') }}
                                        @endif
                                        @if($listMeans->firstItem())
                                            {{ $listMeans->firstItem() }}
                                        @else
                                            0
                                        @endif
                                        {{ __('to') }}
                                        @if($listMeans->lastItem())
                                            {{ $listMeans->lastItem() }}
                                        @else
                                            0
                                        @endif
                                        {{ __('of') }} {{ $listMeans->total() }} {{ __('entries') }}
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
                                        <a wire:click.prevent="sortBy('nombre')" role="button"
                                           href="#">
                                            <i class="mdi mdi-sort ml-0"></i> {{ __('Mean Interaction') }}
                                        </a>
                                    </th>
                                    <th>
                                        <a wire:click.prevent="sortBy('created_at')" role="button"
                                           href="#">
                                            <i class="mdi mdi-sort ml-0"></i> {{ __('Created at')  }}
                                        </a>
                                    </th>
                                    <th>
                                        <a wire:click.prevent="sortBy('updated_at')" role="button"
                                           href="#">
                                            <i class="mdi mdi-sort ml-0"></i> {{ __('Updated at')  }}
                                        </a>
                                    </th>
                                    @foreach ($modulePermissions as $modulePermission)
                                        @if($modulePermission->route_name === 'edit_mean_interaction')
                                            <th>
                                                {{ __('Edit')  }}
                                            </th>
                                        @endif
                                        @if($modulePermission->route_name === 'delete_mean_interaction')
                                            <th>
                                                {{ __('Delete')  }}
                                            </th>
                                        @endif
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($listMeans as $mean)
                                    <tr>
                                        <td>
                                            {{ Str::limit($mean->nombre, 30) }}
                                        </td>
                                        <td>{{  date('d-m-Y', strtotime($mean->created_at)) }}</td>
                                        <td>{{  date('d-m-Y', strtotime($mean->updated_at)) }}</td>
                                        @foreach ($modulePermissions as $modulePermission)
                                            @if($modulePermission->route_name === 'edit_mean_interaction')
                                                <td>
                                                    <button
                                                            wire:click="loadMean('{{ config('app.debug') ? $mean->id : encrypt($mean->id) }}')"
                                                            class="btn btn-linkedin btn-icon-text mb-1 mb-md-0">
                                                        <i class="mdi mdi-lead-pencil mr-2"></i>
                                                        {{ __('Edit') }}
                                                    </button>
                                                </td>
                                            @endif
                                            @if($modulePermission->route_name === 'delete_mean_interaction')
                                                <td>
                                                    <button
                                                            wire:click="$dispatch('delete-prompt-mean', '{{ config('app.debug') ? $mean->id : encrypt($mean->id) }}')"
                                                            type="button"
                                                            class="btn btn-danger btn-icon-text mb-1 mb-md-0">
                                                        <i class="mdi mdi-delete mr-2"></i>
                                                        {{ __('Delete') }}
                                                    </button>
                                                </td>
                                            @endif
                                        @endforeach
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
                        {{ $listMeans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @foreach ($modulePermissions as $modulePermission)
        @if($modulePermission->route_name === 'create_mean_interaction')
            <div wire:ignore.self class="modal fade" id="createMean" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="createMeanLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createMeanLabel">{{ __('Create Mean Interaction') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form wire:submit="createMean">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name" class="control-label">{{ ucfirst(strtolower(__('Mean Interaction'))) }}</label>
                                            <input id="name" type="text"
                                                   class="form-control @error('nombre_create') is-invalid @enderror"
                                                   wire:model="nombre_create"
                                                   placeholder="{{ ucfirst(strtolower(__('Mean Interaction'))) }}"
                                                   autocomplete="name" autofocus>
                                            @error('nombre_create')
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
                                    <i class="mdi mdi-share-variant mr-2"></i>{{ __('Create') }}
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
        @if($modulePermission->route_name === 'edit_mean_interaction')
            <div wire:ignore.self class="modal fade" id="editMean" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="editMeanLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editMeanLabel">{{ __('Update Mean Interaction') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form wire:submit="editMean">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name" class="control-label">{{ ucfirst(strtolower(__('Mean Interaction'))) }}</label>
                                            <input id="name" type="text"
                                                   class="form-control @error('nombre') is-invalid @enderror"
                                                   wire:model="nombre"
                                                   placeholder="{{ ucfirst(strtolower(__('Mean Interaction'))) }}"
                                                   autocomplete="name" autofocus>
                                            @error('nombre')
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
                                    <i class="mdi mdi-share-variant mr-2"></i>{{ __('Update') }}
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
    window.addEventListener('open-create-mean-modal', event => {
        $('#createMean').modal('show');
    });

    window.addEventListener('close-create-mean-modal', event => {
        $('#createMean').modal('hide');
    });

    window.addEventListener('open-edit-mean-modal', event => {
        $('#editMean').modal('show');
    });

    window.addEventListener('close-edit-mean-modal', event => {
        $('#editMean').modal('hide');
    });

    window.addEventListener('meanCreated', () => {
        Swal.fire({
            title: '{{ __('Created') }}',
            text: '{{ __('Register Created') }}',
            icon: 'success',
            confirmButtonText: '{{ __('Ok') }}'
        });
    });

    window.addEventListener('meanUpdated', () => {
        Swal.fire({
            title: '{{ __('Updated') }}',
            text: '{{ __('Register Updated') }}',
            icon: 'success',
            confirmButtonText: '{{ __('Ok') }}'
        });
    });

    document.addEventListener('livewire:initialized', () => {
    @this.on('delete-prompt-mean', meanId => {
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
            @this.dispatch('goOn-Delete-Mean', {meanId: meanId})

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
