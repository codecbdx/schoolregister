@section('title', __('Edit School'))

<div>
    <form wire:submit="save">
        @csrf
        <div class="row mb-4">
            <div class="col-lg-8 col-md-6 col-sm-6">
                <nav class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">{{ __('Management') }}</li>
                        <li class="breadcrumb-item"><a href="{{ route('customers') }}">{{ __('Schools') }}</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a class="text-facebook"
                                                                           href="#">{{ __('Edit School') }}</a>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 d-flex justify-content-end align-items-center">
                <button type="button" class="d-none d-sm-block btn btn-light btn-icon-text btn-back">
                    <i class="mdi mdi-undo-variant mr-2"></i>
                    {{ __('Back') }}
                </button>
                <button type="button" class="d-block d-sm-none btn btn-light btn-icon-text w-100 btn-back">
                    <i class="mdi mdi-undo-variant mr-2"></i>
                </button>
                <button type="submit" class="d-none d-sm-block btn btn-linkedin btn-icon-text ml-2">
                    <i class="mdi mdi-school mr-2"></i>
                    {{ __('Update School') }}
                </button>
                <button type="submit" class="d-block d-sm-none btn btn-linkedin btn-icon-text w-100">
                    <i class="mdi mdi-school mr-2"></i>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="control-label">{{ __('School') }}</label>
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror" wire:model="name"
                                           placeholder="{{ __('School') }}"
                                           autocomplete="name" autofocus>
                                    @error('name')
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
                                    <label for="description">{{ __('Address') }}</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              wire:model="description" id="description" rows="5"></textarea>
                                    @error('description')
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
                                    <label for="responsible" class="control-label">{{ __('Responsible') }}</label>
                                    <input id="responsible" type="text"
                                           class="form-control @error('responsible') is-invalid @enderror"
                                           wire:model="responsible"
                                           placeholder="{{ __('Responsible') }}"
                                           autocomplete="responsible">
                                    @error('responsible')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="email" class="control-label">{{ __('Email Address') }}</label>
                                    <input id="email"
                                           class="form-control @error('email') is-invalid @enderror" wire:model="email"
                                           placeholder="{{ __('Email Address') }}" autocomplete="email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="phone" class="control-label">{{ __('Phone') }}</label>
                                    <input id="phone"
                                           class="input-numerico form-control @error('phone') is-invalid @enderror"
                                           wire:model="phone"
                                           placeholder="{{ __('Phone') }}"
                                           autocomplete="phone">
                                    @error('phone')
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
                                    <label for="bank"
                                           class="control-label">{{ __('Bank') }}</label>
                                    <input id="bank" type="text"
                                           class="form-control @error('bank') is-invalid @enderror"
                                           wire:model="bank"
                                           placeholder="{{ __('Bank') }}"
                                           autocomplete="bank">
                                    @error('bank')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="bank_account_holder"
                                           class="control-label">{{ __('Bank account holder') }}</label>
                                    <input id="bank_account_holder" type="text"
                                           class="form-control @error('bank_account_holder') is-invalid @enderror"
                                           wire:model="bank_account_holder"
                                           placeholder="{{ __('Bank account holder') }}"
                                           autocomplete="bank_account_holder">
                                    @error('bank_account_holder')
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
                                    <label for="clabe"
                                           class="control-label">{{ __('CLABE') }}</label>
                                    <input id="clabe" type="text"
                                           class="form-control @error('clabe') is-invalid @enderror"
                                           wire:model="clabe"
                                           placeholder="{{ __('CLABE') }}"
                                           autocomplete="clabe">
                                    @error('clabe')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="bank_account_number"
                                           class="control-label">{{ __('Bank account number') }}</label>
                                    <input id="bank_account_number" type="text"
                                           class="form-control @error('bank_account_number') is-invalid @enderror"
                                           wire:model="bank_account_number"
                                           placeholder="{{ __('Bank account number') }}"
                                           autocomplete="bank_account_number">
                                    @error('bank_account_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    document.querySelectorAll('.btn-back').forEach(button => {
        button.addEventListener('click', function() {
            window.history.back();
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const inputsNumericos = document.querySelectorAll('.input-numerico');

        inputsNumericos.forEach(function (input) {
            input.addEventListener('input', restringirEntradaNumerica);
        });

        function restringirEntradaNumerica(event) {
            this.value = this.value.replace(/\D/g, '');

            if (this.value.length > 10) {
                this.value = this.value.substring(0, 10);
            }
        }
    });
</script>
