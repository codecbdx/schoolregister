<div>
    <div class="row">
        <div wire:loading wire:target="ine_pdf" class="loader-file">
            <div
                class="v-align">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div wire:loading wire:target="ine_tutor_pdf" class="loader-file">
            <div
                class="v-align">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div wire:loading wire:target="curp_pdf" class="loader-file">
            <div
                class="v-align">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div wire:loading wire:target="curp_tutor_pdf" class="loader-file">
            <div
                class="v-align">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div wire:loading wire:target="address_pdf" class="loader-file">
            <div
                class="v-align">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            @if($mayor_edad)
                <div class="form-group">
                    <label for="ine_pdf"
                           class="col-form-label">{{ __('INE of the Student') }}</label>
                    <input id="ine_pdf" type="file" class="file-upload-default"
                           accept="application/pdf"
                           wire:model="ine_pdf">
                    <div class="input-group col-xs-12 mb-3">
                        <input type="text" class="form-control file-upload-info" disabled
                               placeholder="{{ __('Select INE') }}">
                        <span class="input-group-append">
												<button class="file-upload-browse btn btn-primary"
                                                        type="button">{{ __('Load') }}</button>
											</span>
                    </div>
                    @if($ine_pdf && !$errors->has('ine_pdf'))
                        <div class="d-flex justify-content-center align-items-center mt-3">
                            <!-- Botón para abrir el PDF -->
                            <a href="{{$ine_pdf->temporaryUrl()}}" target="_blank" class="btn btn-light mr-2">
                                {{ __('Preview PDF') }}
                            </a>
                            <button wire:click="loadINE" class="btn btn-primary">
                                {{ __('Load PDF') }}
                            </button>
                        </div>
                    @else
                        @if(!$errors->has('ine_pdf') and $current_ine_pdf !== '')
                            <div class="d-flex justify-content-center align-items-center">
                                <!-- Botón para abrir el PDF -->
                                <a href="{{ env('AWS_URL') }}{{$current_ine_pdf}}" target="_blank"
                                   class="btn btn-primary mr-2">
                                    {{ __('Open PDF') }}
                                </a>
                                <button wire:click="deleteINE" class="btn btn-danger">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        @endif
                    @endif
                    <div
                        class=" mt-3 alert @error('ine_pdf') alert-danger @else alert-primary @enderror"
                        role="alert">
                        {{ __('Type PDF') }}
                    </div>
                </div>
            @else
                <div class="form-group">
                    <label for="ine_tutor_pdf"
                           class="col-form-label">{{ __("Tutor's INE") }}</label>
                    <input id="ine_tutor_pdf" type="file" class="file-upload-default"
                           accept="application/pdf"
                           wire:model="ine_tutor_pdf">
                    <div class="input-group col-xs-12 mb-3">
                        <input type="text" class="form-control file-upload-info" disabled
                               placeholder="{{ __("Select Tutor's INE") }}">
                        <span class="input-group-append">
												<button class="file-upload-browse btn btn-primary"
                                                        type="button">{{ __('Load') }}</button>
											</span>
                    </div>
                    @if($ine_tutor_pdf && !$errors->has('ine_tutor_pdf'))
                        <div class="d-flex justify-content-center align-items-center">
                            <!-- Botón para abrir el PDF -->
                            <a href="{{$ine_tutor_pdf->temporaryUrl()}}" target="_blank" class="btn btn-light mr-2">
                                {{ __('Preview PDF') }}
                            </a>
                            <button wire:click="loadINETutor" class="btn btn-primary">
                                {{ __('Load PDF') }}
                            </button>
                        </div>
                    @else
                        @if(!$errors->has('ine_tutor_pdf') and $current_ine_tutor_pdf !== '')
                            <div class="d-flex justify-content-center align-items-center">
                                <!-- Botón para abrir el PDF -->
                                <a href="{{ env('AWS_URL') }}{{$current_ine_tutor_pdf}}" target="_blank"
                                   class="btn btn-primary mr-2">
                                    {{ __('Open PDF') }}
                                </a>
                                <button wire:click="deleteINETutor" class="btn btn-danger">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        @endif
                    @endif
                    <div
                        class="mt-3 alert @error('ine_tutor_pdf') alert-danger @else alert-primary @enderror"
                        role="alert">
                        {{ __('Type PDF') }}
                    </div>
                </div>
            @endif
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label for="curp_pdf"
                       class="col-form-label">{{ __('Student CURP') }}</label>
                <input id="curp_pdf" type="file" class="file-upload-default"
                       accept="application/pdf"
                       wire:model="curp_pdf">
                <div class="input-group col-xs-12 mb-3">
                    <input type="text" class="form-control file-upload-info" disabled
                           placeholder="{{ __('Select CURP') }}">
                    <span class="input-group-append">
												<button class="file-upload-browse btn btn-primary"
                                                        type="button">{{ __('Load') }}</button>
											</span>
                </div>
                @if($curp_pdf && !$errors->has('curp_pdf'))
                    <div class="d-flex justify-content-center align-items-center">
                        <!-- Botón para abrir el PDF -->
                        <a href="{{$curp_pdf->temporaryUrl()}}" target="_blank" class="btn btn-light mr-2">
                            {{ __('Preview PDF') }}
                        </a>
                        <button wire:click="loadCURP" class="btn btn-primary">
                            {{ __('Load PDF') }}
                        </button>
                    </div>
                @else
                    @if(!$errors->has('curp_pdf') and $current_curp_pdf !== '')
                        <div class="d-flex justify-content-center align-items-center">
                            <!-- Botón para abrir el PDF -->
                            <a href="{{ env('AWS_URL') }}{{$current_curp_pdf}}" target="_blank"
                               class="btn btn-primary mr-2">
                                {{ __('Open PDF') }}
                            </a>
                            <button wire:click="deleteCURP" class="btn btn-danger">
                                {{ __('Delete') }}
                            </button>
                        </div>
                    @endif
                @endif
                <div
                    class="mt-3 alert @error('curp_pdf') alert-danger @else alert-primary @enderror"
                    role="alert">
                    {{ __('Type PDF') }}
                </div>
            </div>
        </div>
        @if(!$mayor_edad)
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="curp_tutor_pdf"
                           class="col-form-label">{{ __("Tutor's CURP") }}</label>
                    <input id="curp_tutor_pdf" type="file" class="file-upload-default"
                           accept="application/pdf"
                           wire:model="curp_tutor_pdf">
                    <div class="input-group col-xs-12 mb-3">
                        <input type="text" class="form-control file-upload-info" disabled
                               placeholder="{{ __("Select Tutor's CURP") }}">
                        <span class="input-group-append">
												<button class="file-upload-browse btn btn-primary"
                                                        type="button">{{ __('Load') }}</button>
											</span>
                    </div>
                    @if($curp_tutor_pdf && !$errors->has('curp_tutor_pdf'))
                        <div class="d-flex justify-content-center align-items-center">
                            <!-- Botón para abrir el PDF -->
                            <a href="{{$curp_tutor_pdf->temporaryUrl()}}" target="_blank" class="btn btn-light mr-2">
                                {{ __('Preview PDF') }}
                            </a>
                            <button wire:click="loadCURPTutor" class="btn btn-primary">
                                {{ __('Load PDF') }}
                            </button>
                        </div>
                    @else
                        @if(!$errors->has('curp_tutor_pdf') and $current_curp_tutor_pdf !== '')
                            <div class="d-flex justify-content-center align-items-center">
                                <!-- Botón para abrir el PDF -->
                                <a href="{{ env('AWS_URL') }}{{$current_curp_tutor_pdf}}" target="_blank"
                                   class="btn btn-primary mr-2">
                                    {{ __('Open PDF') }}
                                </a>
                                <button wire:click="deleteCURPTutor" class="btn btn-danger">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        @endif
                    @endif
                    <div
                        class="mt-3 alert @error('curp_tutor_pdf') alert-danger @else alert-primary @enderror"
                        role="alert">
                        {{ __('Type PDF') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="address_pdf"
                       class="col-form-label">{{ __('Proof of Address') }}</label>
                <input id="address_pdf" type="file" class="file-upload-default"
                       accept="application/pdf"
                       wire:model="address_pdf">
                <div class="input-group col-xs-12 mb-3">
                    <input type="text" class="form-control file-upload-info"
                           disabled
                           placeholder="{{ __('Select Proof of Address') }}">
                    <span class="input-group-append">
												<button class="file-upload-browse btn btn-primary"
                                                        type="button">{{ __('Load') }}</button>
											</span>
                </div>
                @if($address_pdf && !$errors->has('address_pdf'))
                    <div class="d-flex justify-content-center align-items-center">
                        <!-- Botón para abrir el PDF -->
                        <a href="{{$address_pdf->temporaryUrl()}}" target="_blank" class="btn btn-light mr-2">
                            {{ __('Preview PDF') }}
                        </a>
                        <button wire:click="loadComprobante" class="btn btn-primary">
                            {{ __('Load PDF') }}
                        </button>
                    </div>
                @else
                    @if(!$errors->has('address_pdf') and $current_address_pdf !== '')
                        <div class="d-flex justify-content-center align-items-center">
                            <!-- Botón para abrir el PDF -->
                            <a href="{{ env('AWS_URL') }}{{$current_address_pdf}}" target="_blank"
                               class="btn btn-primary mr-2">
                                {{ __('Open PDF') }}
                            </a>
                            <button wire:click="deleteAddress" class="btn btn-danger">
                                {{ __('Delete') }}
                            </button>
                        </div>
                    @endif
                @endif
                <div
                    class="mt-3 alert @error('address_pdf') alert-danger @else alert-primary @enderror"
                    role="alert">
                    {{ __('Type PDF') }}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navDocumentsTab = document.getElementById('nav-documents-tab');
        if (navDocumentsTab) {
            navDocumentsTab.addEventListener('click', function () {
            @this.dispatch('set-curp')
            });
        }
    });

    document.addEventListener('livewire:initialized', () => {
    @this.on('set-curp', () => {
        var elemento = document.getElementById('curp');

    @this.set('curp', elemento.value)
    @this.dispatch('goOn-Load-PDF')
    })
    });
</script>
