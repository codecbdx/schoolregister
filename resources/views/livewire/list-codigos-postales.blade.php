@section('title', __('Zip Codes'))

<div>
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">{{ __('Management') }}</li>
            <li class="breadcrumb-item" aria-current="page"><a class="text-facebook"
                                                               href="{{ route('zip_codes') }}">{{ __('Zip Codes') }}</a>
            </li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-6 grid-margin">
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
                           placeholder="{{ __('Search...') }}" autofocus autocomplete="search-zip-codes">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if (empty($search) && $zipCodes->count() == 0)
                        <div class="alert alert-primary-muted" role="alert">
                            <h4 class="alert-heading">{{ __('No records found!') }}</h4>
                            <p>{{ __('No records have been added to the database yet. We suggest creating the first record.') }}</p>
                            <hr>
                            <div class="row justify-content-center">
                                <img src="{{ asset('assets/images/404.svg') }}" class="w-75 mt-2 mb-2">
                            </div>
                        </div>
                    @elseif ($zipCodes->count())
                        <p class="mb-3">
                            @if($totalEntries > 0)
                                {{ __('Showing') }}
                                @if(!$zipCodes->firstItem())
                                    {{ $zipCodes->firstItem() }}
                                @else
                                    {{ __('of') }}
                                @endif
                                {{ $zipCodes->firstItem() }} {{ __('to') }} {{ $zipCodes->lastItem() }} {{ __('of') }} {{ $zipCodes->total() }} {{ __('entries') }}
                            @elseif($zipCodes->total() >= 0)
                                {{ __('Showing') }}
                                @if(!$zipCodes->firstItem())
                                    {{ $zipCodes->firstItem() }}
                                @else
                                    {{ __('of') }}
                                @endif
                                @if($zipCodes->firstItem())
                                    {{ $zipCodes->firstItem() }}
                                @else
                                    0
                                @endif
                                {{ __('to') }}
                                @if($zipCodes->lastItem())
                                    {{ $zipCodes->lastItem() }}
                                @else
                                    0
                                @endif
                                {{ __('of') }} {{ $zipCodes->total() }} {{ __('entries') }}
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
                                        <a wire:click.prevent="sortBy('codigo')" role="button" href="#">
                                            <i class="mdi mdi-sort ml-0"></i> {{ __('Code')  }}
                                        </a>
                                    </th>
                                    <th>
                                        <a wire:click.prevent="sortBy('asentamiento')" role="button"
                                           href="#">
                                            <i class="mdi mdi-sort ml-0"></i> {{ __('Settlement')  }}
                                        </a>
                                    </th>
                                    <th>
                                        <a wire:click.prevent="sortBy('tipo_asentamiento')" role="button"
                                           href="#">
                                            <i class="mdi mdi-sort ml-0"></i> {{ __('Settlement Type')  }}
                                        </a>
                                    </th>
                                    <th>
                                        <a wire:click.prevent="sortBy('municipio')" role="button"
                                           href="#">
                                            <i class="mdi mdi-sort ml-0"></i> {{ __('Municipality')  }}
                                        </a>
                                    </th>
                                    <th>
                                        <a wire:click.prevent="sortBy('estado')" role="button"
                                           href="#">
                                            <i class="mdi mdi-sort ml-0"></i> {{ __('State')  }}
                                        </a>
                                    </th>
                                    <th>
                                        <a wire:click.prevent="sortBy('zona')" role="button"
                                           href="#">
                                            <i class="mdi mdi-sort ml-0"></i> {{ __('Zone')  }}
                                        </a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($zipCodes as $zipCode)
                                    <tr>
                                        <td>{{ $zipCode->codigo }}</td>
                                        <td>{{ $zipCode->asentamiento }}</td>
                                        <td>{{ $zipCode->tipo_asentamiento }}</td>
                                        <td>{{ $zipCode->municipio }}</td>
                                        <td>{{ $zipCode->estado }}</td>
                                        <td>{{ $zipCode->zona }}</td>
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
                        {{ $zipCodes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
