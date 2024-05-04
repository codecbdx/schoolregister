@section('title', __('Courses'))

<div>
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">{{ __('Management') }}</li>
            <li class="breadcrumb-item" aria-current="page"><a class="text-facebook"
                                                               href="{{ route('courses') }}">{{ __('Courses') }}</a>
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
                           placeholder="{{ __('Search...') }}" autofocus autocomplete="search-courses">
                </div>
            </div>
        </div>
        @foreach ($modulePermissions as $modulePermission)
            @if($modulePermission->route_name === 'create_course')
                <div class="col-lg-6 col-md-4 col-sm-5 d-flex flex-column justify-content-center align-items-end">
                    <a href="{{ route('create_course') }}" type="submit"
                       class="d-none d-sm-block btn btn-success btn-icon-text">
                        <i class="mdi mdi-book-plus mr-2"></i>
                        {{ __('Create new course') }}
                    </a>
                    <a href="{{ route('create_course') }}" type="submit"
                       class="d-block d-sm-none btn btn-success btn-icon-text w-100 mt-4">
                        <i class="mdi mdi-book-plus mr-2"></i>
                        {{ __('Create new course') }}
                    </a>
                </div>
            @endif
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            @if (empty($search) && $courses->count() == 0)
                                <div class="alert alert-primary-muted" role="alert">
                                    <h4 class="alert-heading">{{ __('No records found!') }}</h4>
                                    <p>{{ __('No records have been added to the database yet. We suggest creating the first record.') }}</p>
                                    <hr>
                                    <div class="row justify-content-center">
                                        <img src="{{ asset('assets/images/404.svg') }}" class="w-75 mt-2 mb-2">
                                    </div>
                                </div>
                            @elseif ($courses->count())
                                <p class="mb-3">
                                    @if($totalEntries > 0)
                                        {{ __('Showing') }}
                                        @if(!$courses->firstItem())
                                            {{ $courses->firstItem() }}
                                        @else
                                            {{ __('of') }}
                                        @endif
                                        {{ $courses->firstItem() }} {{ __('to') }} {{ $courses->lastItem() }} {{ __('of') }} {{ $courses->total() }} {{ __('entries') }}
                                    @elseif($courses->total() >= 0)
                                        {{ __('Showing') }}
                                        @if(!$courses->firstItem())
                                            {{ $courses->firstItem() }}
                                        @else
                                            {{ __('of') }}
                                        @endif
                                        @if($courses->firstItem())
                                            {{ $courses->firstItem() }}
                                        @else
                                            0
                                        @endif
                                        {{ __('to') }}
                                        @if($courses->lastItem())
                                            {{ $courses->lastItem() }}
                                        @else
                                            0
                                        @endif
                                        {{ __('of') }} {{ $courses->total() }} {{ __('entries') }}
                                        @if($totalEntries > 0)
                                            ({{ __('filtered from') }} {{ $totalEntries }} {{ __('entries') }})
                                        @endif
                                    @endif
                                </p>
                        </div>
                        <div class="col text-right">
                            <button id="downloadFile" wire:click="export" type="button" class="btn btn-success btn-icon"
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
                                        <i class="mdi mdi-sort ml-0"></i> {{ __('Name')  }}
                                    </a>
                                </th>
                                <th>
                                    <a wire:click.prevent="sortBy('descripcion')" role="button"
                                       href="#">
                                        <i class="mdi mdi-sort ml-0"></i> {{ __('Description')  }}
                                    </a>
                                </th>
                                <th>
                                    <a wire:click.prevent="sortBy('codigo_moodle')" role="button"
                                       href="#">
                                        <i class="mdi mdi-sort ml-0"></i> {{ __('Moodle Code')  }}
                                    </a>
                                </th>
                                @foreach ($modulePermissions as $modulePermission)
                                    @if($modulePermission->route_name === 'edit_course')
                                        <th>
                                            {{ __('Edit')  }}
                                        </th>
                                    @endif
                                    @if($modulePermission->route_name === 'delete_course')
                                        <th>
                                            {{ __('Delete')  }}
                                        </th>
                                    @endif
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($courses as $course)
                                <tr>
                                    <td>{{ Str::limit($course->nombre, 30) }}</td>
                                    <td>{{ Str::limit($course->descripcion, 60) }}</td>
                                    <td>{{ $course->codigo_moodle }}</td>
                                    @foreach ($modulePermissions as $modulePermission)
                                        @if($modulePermission->route_name === 'edit_course')
                                            <td>
                                                <a href="{{ route('edit_course', ['id' => config('app.debug') ? $course->id : encrypt($course->id)]) }}"
                                                   class="btn btn-linkedin btn-icon-text mb-1 mb-md-0">
                                                    <i class="mdi mdi-lead-pencil mr-2"></i>
                                                    {{ __('Edit') }}
                                                </a>
                                            </td>
                                        @endif
                                        @if($modulePermission->route_name === 'delete_course')
                                            <td>
                                                <button
                                                    wire:click="$dispatch('delete-prompt-course', '{{ config('app.debug') ? $course->id : encrypt($course->id) }}')"
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
                        {{ $courses->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
    @this.on('delete-prompt-course', courseId => {
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
            @this.dispatch('goOn-Delete-Course', {courseId: courseId})

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
