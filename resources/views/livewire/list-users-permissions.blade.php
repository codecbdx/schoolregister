@section('title', __('User Permissions'))

<div>
    <div class="row mb-4">
        <div class="col-lg-8 col-md-6 col-sm-6">
            <nav class="page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">{{ __('Configuration') }}</li>
                    <li class="breadcrumb-item" aria-current="page"><a class="text-facebook"
                                                                       href="{{ route('permissions') }}">{{ __('User Permissions') }}</a>
                    </li>
                </ol>
            </nav>
        </div>
        @foreach ($modulePermissions as $modulePermission)
            @if($modulePermission->route_name === 'create_permission')
                <div class="col-lg-4 col-md-6 col-sm-6 d-flex flex-column justify-content-center align-items-end">
                    <button wire:click="$dispatch('open-create-permission-modal')"
                            class="d-none d-sm-block btn btn-success btn-icon-text">
                        <i class="mdi mdi-folder-lock mr-2"></i>
                        {{ __('Create User Permission') }}
                    </button>
                    <button wire:click="$dispatch('open-create-permission-modal')"
                            class="d-block d-sm-none btn btn-success btn-icon-text w-100">
                        <i class="mdi mdi-folder-lock mr-2"></i>
                        {{ __('Create User Permission') }}
                    </button>
                </div>
            @endif
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if (empty($search) && $permissions->count() == 0)
                        <div class="alert alert-primary-muted" role="alert">
                            <h4 class="alert-heading">{{ __('No records found!') }}</h4>
                            <p>{{ __('No records have been added to the database yet. We suggest creating the first record.') }}</p>
                            <hr>
                            <div class="row justify-content-center">
                                <img src="{{ asset('assets/images/404.svg') }}" class="w-75 mt-2 mb-2">
                            </div>
                        </div>
                    @elseif ($permissions->count())
                        <p class="mb-3">
                            @if($totalEntries > 0)
                                {{ __('Showing') }}
                                @if(!$permissions->firstItem())
                                    {{ $permissions->firstItem() }}
                                @else
                                    {{ __('of') }}
                                @endif
                                {{ $permissions->firstItem() }} {{ __('to') }} {{ $permissions->lastItem() }} {{ __('of') }} {{ $permissions->total() }} {{ __('entries') }}
                            @elseif($permissions->total() >= 0)
                                {{ __('Showing') }}
                                @if(!$permissions->firstItem())
                                    {{ $permissions->firstItem() }}
                                @else
                                    {{ __('of') }}
                                @endif
                                @if($permissions->firstItem())
                                    {{ $permissions->firstItem() }}
                                @else
                                    0
                                @endif
                                {{ __('to') }}
                                @if($permissions->lastItem())
                                    {{ $permissions->lastItem() }}
                                @else
                                    0
                                @endif
                                {{ __('of') }} {{ $permissions->total() }} {{ __('entries') }}
                                @if($totalEntries > 0)
                                    ({{ __('filtered from') }} {{ $totalEntries }} {{ __('entries') }})
                                @endif
                            @endif
                        </p>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                <tr>
                                    <th>
                                        <a wire:click.prevent="sortBy('user_type_id')" role="button"
                                           href="#">
                                            <i class="mdi mdi-sort ml-0"></i> {{ __('Rol')  }}
                                        </a>
                                    </th>
                                    <th>
                                        <a wire:click.prevent="sortBy('route_name')" role="button"
                                           href="#">
                                            <i class="mdi mdi-sort ml-0"></i> {{ __('Route')  }}
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
                                        @if($modulePermission->route_name === 'edit_permission')
                                            <th>
                                                {{ __('Edit')  }}
                                            </th>
                                        @endif
                                        @if($modulePermission->route_name === 'delete_permission')
                                            <th>
                                                {{ __('Delete')  }}
                                            </th>
                                        @endif
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td>
                                            @foreach($this->user_types as $user_type)
                                                @if($user_type->id == $permission->user_type_id)
                                                    {{ $user_type->name }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($routes as $route)
                                                @if($route['route'] == $permission->route_name)
                                                    {{ $route['name'] }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>{{  date('d-m-Y', strtotime($permission->created_at)) }}</td>
                                        <td>{{  date('d-m-Y', strtotime($permission->updated_at)) }}</td>
                                        @foreach ($modulePermissions as $modulePermission)
                                            @if($modulePermission->route_name === 'edit_permission')
                                                <td>
                                                    <button
                                                        wire:click="loadPermission('{{ config('app.debug') ? $permission->id : encrypt($permission->id) }}')"
                                                        class="btn btn-linkedin btn-icon-text mb-1 mb-md-0">
                                                        <i class="mdi mdi-lead-pencil mr-2"></i>
                                                        {{ __('Edit') }}
                                                    </button>
                                                </td>
                                            @endif
                                            @if($modulePermission->route_name === 'delete_permission')
                                                <td>
                                                    <button
                                                        wire:click="$dispatch('delete-prompt-permission', '{{ config('app.debug') ? $permission->id : encrypt($permission->id) }}')"
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
                        {{ $permissions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @foreach ($modulePermissions as $modulePermission)
        @if($modulePermission->route_name === 'create_permission')
            <div wire:ignore.self class="modal fade" id="createPermission" data-backdrop="static" tabindex="-1"
                 role="dialog"
                 aria-labelledby="createPermissionLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createPermissionLabel">{{ __('Create User Permission') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form wire:submit="createPermission">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="select-user-role"
                                           class="col-form-label">{{ __('Rol Type') }}</label>
                                    <select
                                        class="form-control @error('select_type_permission_create') is-invalid @enderror"
                                        id="select-user-role" wire:model="select_type_permission_create">
                                        <option selected>{{ __('Select your user role') }}</option>
                                        @foreach($user_types as $user_type)
                                            <option value="{{ $user_type->id }}">{{ $user_type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('select_type_permission_create')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="select-route"
                                           class="col-form-label">{{ __('Route') }}</label>
                                    <select
                                        class="form-control @error('select_route_permission_create') is-invalid @enderror"
                                        id="select-route" wire:model="select_route_permission_create">
                                        <option selected>{{ __('Select route') }}</option>
                                        @foreach($routes as $route)
                                            <option value="{{ $route['route'] }}">
                                                {{ $route['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('select_route_permission_create')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success btn-icon-text">
                                    <i class="mdi mdi-folder-lock mr-2"></i>{{ __('Create') }}
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
        @if($modulePermission->route_name === 'edit_permission')
            <div wire:ignore.self class="modal fade" id="editPermission" data-backdrop="static" tabindex="-1"
                 role="dialog"
                 aria-labelledby="editPermissionLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPermissionLabel">{{ __('Update User Permission') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form wire:submit="editPermission">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="select-user-role"
                                           class="col-form-label">{{ __('Rol Type') }}</label>
                                    <select class="form-control @error('select_type_permission') is-invalid @enderror"
                                            id="select-user-role"
                                            wire:model="select_type_permission">
                                        <option selected disabled>{{ __('Select your user role') }}</option>
                                        @foreach($user_types as $user_type)
                                            <option value="{{ $user_type->id }}">{{ $user_type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('select_type_permission')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="select-route"
                                           class="col-form-label">{{ __('Route') }}</label>
                                    <select class="form-control @error('select_route_permission') is-invalid @enderror"
                                            id="select-route" wire:model="select_route_permission">
                                        <option selected disabled>{{ __('Select route') }}</option>
                                        @foreach($routes as $route)
                                            <option value="{{ $route['route'] }}">
                                                {{ $route['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('select_route_permission')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-linkedin btn-icon-text">
                                    <i class="mdi mdi-folder-lock mr-2"></i>{{ __('Update') }}
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
<script>
    window.addEventListener('open-create-permission-modal', event => {
        $('#createPermission').modal('show');
    });

    window.addEventListener('close-create-permission-modal', event => {
        $('#createPermission').modal('hide');
    });

    window.addEventListener('open-edit-permission-modal', event => {
        $('#editPermission').modal('show');
    });

    window.addEventListener('close-edit-permission-modal', event => {
        $('#editPermission').modal('hide');
    });

    window.addEventListener('permissionCreated', () => {
        Swal.fire({
            title: '{{ __('Created') }}',
            text: '{{ __('Register Created') }}',
            icon: 'success',
            confirmButtonText: '{{ __('Ok') }}'
        });
    });

    window.addEventListener('permissionUpdated', () => {
        Swal.fire({
            title: '{{ __('Updated') }}',
            text: '{{ __('Register Updated') }}',
            icon: 'success',
            confirmButtonText: '{{ __('Ok') }}'
        });
    });

    document.addEventListener('livewire:initialized', () => {
    @this.on('delete-prompt-permission', permissionId => {
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
            @this.dispatch('goOn-Delete-UserPermission', {permissionId: permissionId})

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
</script>
